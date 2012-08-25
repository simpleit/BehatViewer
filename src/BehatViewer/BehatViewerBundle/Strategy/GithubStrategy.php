<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GithubStrategyType
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

	}
}
