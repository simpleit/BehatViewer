<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use Symfony\Component\Console\Output\ConsoleOutput,
    PhpAmqpLib\Message\AMQPMessage;

class BuildConsumer extends Consumer
{
    public function execute(AMQPMessage $msg)
    {
        $options = $this->getOptions($msg);
        $repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Project');
        $project = $repository->findOneBySlug($options['project']);

        if ($project->getStrategy()->build() === 0) {
            //$this->getApplication()->find('behat-viewer:analyze')->run($input, $output);
        }

        return true;
    }
}
