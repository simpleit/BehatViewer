<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use Symfony\Component\Console\Output\ConsoleOutput,
    PhpAmqpLib\Message\AMQPMessage;
use BehatViewer\BehatViewerWorkerBundle\Console\Output\CompositeOutput;

class BuildConsumer extends Consumer
{
    public function execute(AMQPMessage $msg)
    {
        $opts = $this->getOptions($msg);

        $repository = $this->container->get('doctrine')->getRepository('BehatViewerCoreBundle:Project');
        $project = $repository->findOneBySlug(
            $opts['project']
        );

        $pusherOutput = $this->container->get('behat_viewer.pusher.output');
        $output = new CompositeOutput(array(
            new ConsoleOutput(ConsoleOutput::VERBOSITY_VERBOSE, true, new \Symfony\Component\Console\Formatter\OutputFormatter()),
            $pusherOutput
        ));

        $job = new \BehatViewer\BehatViewerWorkerBundle\Entity\Job();
        $job->setDate(new \DateTime());
        $job->setProject($project);
        $job->setStatus(\BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_RUNNING);
        $this->container->get('doctrine')->getManager()->persist($job);
        $this->container->get('doctrine')->getManager()->flush();

        $jobOutput = new \BehatViewer\BehatViewerWorkerBundle\Console\Output\PersistentOutput($job);
        $jobOutput->setContainer($this->container);

        $output->addOutput($jobOutput);

        $pusherOutput->setChannel('job-' . $job->getId());

        try {
            $status = $this->container->get('behat_viewer.builder')->build(
                $project->getStrategy(),
                $output
            );

            $result = $status === 0
                ? \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_SUCCESS
                : \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_FAILED;

        } catch (\Exception $exception) {
            $result = \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_FAILED;
        }

        $job->setStatus($result);
        $this->container->get('doctrine')->getManager()->persist($job);
        $this->container->get('doctrine')->getManager()->flush();

        return true;
    }
}
