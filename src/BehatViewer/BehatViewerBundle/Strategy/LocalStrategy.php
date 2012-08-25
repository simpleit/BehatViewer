<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\LocalStrategyType
;

class LocalStrategy extends Strategy
{
	public function getId() {
		return 'local';
	}

	public function getLabel() {
		return 'Local directory';
	}

	public function getForm() {
		return new LocalStrategyType();
	}
	
	public function build() {

	}
}
