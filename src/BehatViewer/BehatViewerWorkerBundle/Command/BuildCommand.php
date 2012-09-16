<?php
namespace BehatViewer\BehatViewerWorkerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    BehatViewer\BehatViewerBundle\Command\ProjectCommand,
	BehatViewer\BehatViewerWorkerBundle\Console\Output\CompositeOutput;

/**
 *
 */
class BuildCommand extends ProjectCommand
{
    /**
     *
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('behat-viewer:build')
            ->setDescription('Builds a project\'s report file')
            ->addOption('definitions', null, InputOption::VALUE_NONE, 'Updates definition list')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$pusherOutput = $this->getContainer()->get('behat_viewer.pusher.output');
		$output = new CompositeOutput(array(
			$output,
			$pusherOutput
		));

        parent::execute($input, $output);

		$job = new \BehatViewer\BehatViewerWorkerBundle\Entity\Job();
		$job->setDate(new \DateTime());
		$job->setProject($this->getProject());
		$job->setStatus(\BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_RUNNING);
		$this->getDoctrine()->getManager()->persist($job);
		$this->getDoctrine()->getManager()->flush();

		$jobOutput = new \BehatViewer\BehatViewerWorkerBundle\Console\Output\PersistentOutput($job);
		$jobOutput->setContainer($this->getContainer());

		$output->addOutput($jobOutput);

		$pusherOutput->setChannel('job-' . $job->getId());

		try {
			$status = $this->getContainer()->get('behat_viewer.builder')->build(
				$this->getProject()->getStrategy(),
				$output
			);

			$result = $status === 0
				? \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_SUCCESS
				: \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_FAILED;

		} catch(\Exception $exception) {
			$result = \BehatViewer\BehatViewerWorkerBundle\DBAL\Type\EnumJobStatusType::TYPE_FAILED;
			$status = $exception->getCode() ?: 1;
		}

		$job->setStatus($result);
		$this->getDoctrine()->getManager()->persist($job);
		$this->getDoctrine()->getManager()->flush();

		return $status;
    }
}
