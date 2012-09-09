<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer\Consumer,
    PhpAmqpLib\Message\AMQPMessage;

class AnalyzeConsumer extends Consumer
{
    public function execute(AMQPMessage $msg)
    {
        $options = $this->getOptions($msg);

		$repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Project');
		$project = $repository->findOneBySlug($options['project']);

		$data = json_decode($options['payload'], true);
		$analyzer = $this->getContainer()->get('behat_viewer.analyzer');
		$analyzer->analyze($project, $data);

        return true;
    }
}
