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

		if(null !== $project) {
			$cmd = $project->getTestCommand();
			$cmd = str_replace("\r\n", PHP_EOL, $cmd);

			if (file_exists('build.sh')) {
				unlink('build.sh');
			}
			$fp = fopen('build.sh', 'w+');
			$script = '#!/bin/sh' . PHP_EOL . $cmd;
			fwrite($fp, $script, strlen($script));
			fclose($fp);

			$output = new ConsoleOutput();

			$process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess('sh -e build.sh', $project->getRootPath());
			$process->setTimeout(600);
			$process->run(function ($type, $buffer) use ($output) {
				if ('err' === $type) {
					$output->writeln('<error>' . $buffer . '</error>');
				} else {
					$output->write($buffer);
				}
			});

			if (file_exists('build.sh')) {
				unlink('build.sh');
			}
		}

		return true;
	}
}
