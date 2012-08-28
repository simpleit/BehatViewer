<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

class DefinitionConsumer extends Consumer
{
    public function execute(AMQPMessage $msg)
    {
        $options = $this->getOptions($msg);

        return true;
    }
}
