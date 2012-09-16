<?php
namespace BehatViewer\BehatViewerPusherBundle\Console\Output;

use Symfony\Component\Console\Output\OutputInterface,
	Symfony\Component\Console\Formatter\OutputFormatterInterface,
	BehatViewer\BehatViewerPusherBundle\Console\Formatter\PusherFormater;

require_once __DIR__ . '/../../../../../vendor/thunderpush/php-thunderclient/thunderclient/Thunder.php';

class PusherOutput implements OutputInterface
{
	private $pusher;
	private $channel;
	private $verbosity;
	private $decorated = true;
	private $formatter;

	public function __construct($pusher, $channel) {
		$this->setPusher($pusher);
		$this->setChannel($channel);
	}

	public function setPusher($pusher) {
		$this->pusher = $pusher;

		return $this;
	}

	public function getPusher() {
		return $this->pusher;
	}

	public function setChannel($channel) {
		$this->channel = $channel;

		return $this;
	}

	public function getChannel() {
		return $this->channel;
	}

	public function write($messages, $newline = false, $type = 0)
	{
		$this->getPusher()->send_message_to_channel(
			$this->getChannel(),
			array(
				'msg' => $this->getFormatter()->format($messages)
			)
		);
	}

	public function writeln($messages, $type = 0)
	{
		$this->write($messages, $type);
	}

	public function setVerbosity($level)
	{
		$this->verbosity = $level;
	}

	public function getVerbosity()
	{
		return $this->verbosity;
	}

	public function setDecorated($decorated)
	{
		$this->decorated = $decorated;

		return $this;
	}

	public function isDecorated()
	{
		return $this->decorated;
	}

	public function setFormatter(OutputFormatterInterface $formatter)
	{
		$this->formatter = $formatter;

		return $this;
	}

	public function getFormatter()
	{
		if(null === $this->formatter) {
			$this->setFormatter(new PusherFormater());
		}

		return $this->formatter;
	}
}
