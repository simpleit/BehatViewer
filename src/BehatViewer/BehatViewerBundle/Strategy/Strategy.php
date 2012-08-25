<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	Symfony\Component\DependencyInjection\ContainerAware
;

abstract class Strategy extends ContainerAware
{
	abstract public function getId();
	abstract public function getLabel();

	abstract public function build();

	public function __toString() {
		return $this->getId();
	}
}
