<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy\Configuration;

use
	Symfony\Component\DependencyInjection\ContainerAware,
	BehatViewer\BehatViewerBundle\Entity,
	Symfony\Component\Config
;

abstract class Configuration extends Entity\Configuration
{
	protected $config = array();

	public function setData($data) {
		$config = json_decode($data, true);
		$this->validate($config);

		$this->config = $config;
	}

	public function getData() {
		return json_encode($this->config);
	}

	/**
	 * @param array $config
	 *
	 * @return array
	 */
	public function validate(array $config) {
		$processor = new Config\Definition\Processor();

		$config = array('strategy' => $config);
		return $processor->process($this->getTreeBuilder()->buildTree(), $config);
	}

	/**
	 * @abstract
	 *
	 * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
	 */
	abstract protected function getTreeBuilder();
}
