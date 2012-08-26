<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GithubStrategyType,
	Symfony\Component\Console\Output\ConsoleOutput
;

class GithubStrategy extends Strategy
{
	public function getId() {
		return 'github';
	}

	public function getLabel() {
		return 'Github repository';
	}

	public function getForm() {
		return new GithubStrategyType();
	}

	public function build() {
		$project = $this->getProject();
		$configuration = $project->getConfiguration();

		$dir = sprintf(
			$this->container->get('kernel')->getRootDir() . '/data/repos/%s',
			$project->getUser()->getUsername(),
			$project->getSlug()
		);

		if(false === is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		$url = sprintf(
			'git://github.com/%s/%s',
			$configuration->username,
			$configuration->repository
		);

		$status = 0;
		$output = new ConsoleOutput();
		$process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
			'git clone --depth=50 ' . $url,
			$dir
		);
		$process->setTimeout(600);
		$status = $process->run(function ($type, $buffer) use (&$output) {
			if ('err' === $type) {
				$output->writeln('<error>' . $buffer . '</error>');
			} else {
				$output->write($buffer);
			}
		});

		$dir = $dir . DIRECTORY_SEPARATOR . $project->getSlug();
		if($status !== 0) {
			$output = new ConsoleOutput();
			$process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
				'git pull origin master',
				$dir
			);
			$status = $process->run(function ($type, $buffer) use (&$output) {
				if ('err' === $type) {
					$output->writeln('<error>' . $buffer . '</error>');
				} else {
					$output->write($buffer);
				}
			});
		}

		$cmd = $project->getTestCommand();
		$cmd = str_replace("\r\n", PHP_EOL, $cmd);

		if (file_exists($dir . DIRECTORY_SEPARATOR . 'build.sh')) {
			unlink($dir . DIRECTORY_SEPARATOR . 'build.sh');
		}
		$fp = fopen($dir . DIRECTORY_SEPARATOR . 'build.sh', 'w+');
		$script = '#!/bin/sh' . PHP_EOL . $cmd;
		fwrite($fp, $script, strlen($script));
		fclose($fp);

		$output = new ConsoleOutput();
		$process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
			'sh -e build.sh',
			$dir
		);
		$process->setTimeout(600);
		$status = $process->run(function ($type, $buffer) use (&$output) {
			if ('err' === $type) {
				$output->writeln('<error>' . $buffer . '</error>');
			} else {
				$output->write($buffer);
			}
		});

		if (file_exists('build.sh')) {
			unlink('build.sh');
		}

		return $status;
	}
}
