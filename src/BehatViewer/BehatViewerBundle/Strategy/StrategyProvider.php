<?php
namespace BehatViewer\BehatViewerBundle\Strategy;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	BehatViewer\BehatViewerBundle\Strategy\Form\Type\GithubStrategyType,
	BehatViewer\BehatViewerBundle\Entity
;

class StrategyProvider extends ContainerAware
{
	private $strategies = array();

	public function addStrategy($id, $strategy) {
		$this->strategies[$id] = $strategy;

		return $this;
	}

	public function getStrategies() {
		return $this->strategies;
	}

	public function getStrategy($id) {
		return $this->strategies[$id];
	}

	public function getStrategyForProject(Entity\Project $project) {
		$class = $this->container->getParameter(sprintf('behat_viewer.strategy.%s.class', $project->getStrategy()));
		$strategy = new $class();

		$strategy->setContainer($this->container);
		$strategy->setProject($project);

		return $strategy;
	}
}
