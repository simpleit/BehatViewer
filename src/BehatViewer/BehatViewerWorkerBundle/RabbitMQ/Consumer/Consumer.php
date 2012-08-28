<?php
namespace BehatViewer\BehatViewerWorkerBundle\RabbitMQ\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface,
    Symfony\Component\DependencyInjection\ContainerAware,
    PhpAmqpLib\Message\AMQPMessage;

abstract class Consumer extends ContainerAware implements ConsumerInterface
{
    public function getOptions(AMQPMessage $msg)
    {
        return unserialize($msg->body);
    }
}
