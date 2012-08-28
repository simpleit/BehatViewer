<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

use
	BehatViewer\BehatViewerWorkerBundle\Strategy\Form\Type\GitLocalStrategyType
;

class GitLocalStrategy extends GitStrategy
{
	public static function getId() {
		return 'git_local';
	}

	public static function getLabel() {
		return 'Local git repository';
	}

	public static function getForm() {
		return new GitLocalStrategyType();
	}

	public static function getNewConfiguration() {
		return new Configuration\GitLocalStrategyConfiguration();
	}

	protected function getRepositoryUrl() {
		return $this->getConfiguration()->getRepositoryPath();
	}

	protected function getCloneCommand() {
		return 'git clone --shared ' . $this->getRepositoryUrl();
	}
}
