<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GitStrategyType
;

class GitStrategy extends Strategy
{
	public function getId() {
		return 'git';
	}

	public function getLabel() {
		return 'Git repository';
	}

	public function getForm() {
		return new GitStrategyType();
	}

	public function build() {

	}
}
