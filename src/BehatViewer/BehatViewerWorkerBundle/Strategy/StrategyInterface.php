<?php
namespace BehatViewer\BehatViewerWorkerBundle\Strategy;

interface StrategyInterface
{
    /**
     * @abstract
     *
     * @return string
     */
    public static function getId();

    /**
     * @abstract
     *
     * @return string
     */
    public static function getLabel();

    public static function getForm();

    public static function getNewConfiguration();
}
