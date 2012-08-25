<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	BehatViewer\BehatViewerBundle\Entity
;

abstract class Strategy extends ContainerAware
{
	/** @var BehatViewer\BehatViewerBundle\Entity\Project */
	private $project;

	/**
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getId();

	/**
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getLabel();

	/**
	 * @abstract
	 *
	 * @return int
	 */
	abstract public function build();

	/**
	 * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
	 *
	 * @return Strategy
	 */
	public function setProject(Entity\Project $project) {
		$this->project = $project;

		return $this;
	}

	/**
	 * @return \BehatViewer\BehatViewerBundle\Entity\Project
	 */
	public function getProject() {
		return $this->project;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getId();
	}
}
