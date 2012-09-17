<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

class CleanConsumer extends Consumer
{
    public function execute(AMQPMessage $msg)
    {
        $options = $this->getOptions($msg);

        $repository = $this->getContainer()->get('doctrine')->getRepository('BehatViewerCoreBundle:Build');
        $project = $repository->findOneBySlug($options['project']);
        $repository->removeWeekBuildsForProject($project);

        return true;
    }
}
