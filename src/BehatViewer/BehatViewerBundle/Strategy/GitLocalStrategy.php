<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GitLocalStrategyType
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

	}
}
