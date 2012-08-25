<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer\Consumer,
	PhpAmqpLib\Message\AMQPMessage;

class AnalyzeConsumer extends Consumer
{
	public function execute(AMQPMessage $msg)
	{
		$options = $this->getOptions($msg);

		return true;
	}
}
