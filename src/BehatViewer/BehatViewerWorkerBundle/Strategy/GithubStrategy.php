<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

use
	BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type\GithubStrategyType
;

class GithubStrategy extends GitStrategy
{
	public static function getId() {
		return 'github';
	}

	public static function getLabel() {
		return 'Github repository';
	}

	public static function getForm() {
		return new GithubStrategyType();
	}

	public static function getNewConfiguration() {
		return new Configuration\GithubStrategyConfiguration();
	}

	protected function getRepositoryUrl() {
		return sprintf(
			'git://github.com/%s/%s',
			$this->getConfiguration()->getUsername(),
			$this->getConfiguration()->getRepository()
		);
	}
}
