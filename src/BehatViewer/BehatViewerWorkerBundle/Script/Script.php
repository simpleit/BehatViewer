<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class Script
{
	private $commands = array();

	public function addCommand($command) {
		$this->commands[] = $command;

		return $this;
	}

	public function addCommands(array $commands) {
		$this->commands += $commands;

		return $this;
	}

	public function getCommands() {
		return $this->commands;
	}

	public function append(Script $script) {
		$this->addCommands($script->getCommands());
	}

	public function __toString() {
		return join(PHP_EOL, $this->commands);
	}
}
