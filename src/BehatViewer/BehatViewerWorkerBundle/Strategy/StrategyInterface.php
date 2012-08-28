<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

interface StrategyInterface
{
	/**
	 * @abstract
	 *
	 * @return string
	 */
	static function getId();

	/**
	 * @abstract
	 *
	 * @return string
	 */
	static function getLabel();

	static function getForm();

	static function getNewConfiguration();
}
