<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GitLocalStrategyType,
	Symfony\Component\Console\Output\ConsoleOutput
;

class GitLocalStrategy extends Strategy
{
	public function getId() {
		return 'git_local';
	}

	public function getLabel() {
		return 'Local git repository';
	}

	public function getForm() {
		return new GitLocalStrategyType();
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

		$status = 0;
		$output = new ConsoleOutput();
		$process = new \BehatViewer\BehatViewerBundle\Process\UnbefferedProcess(
			'git clone --shared --depth=50 ' . $configuration->repository_path,
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
			'vagrant up' . PHP_EOL . 'vagrant ssh -c "cd /vagrant; sh -e ./build.sh"' . PHP_EOL . 'vagrant halt',
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
