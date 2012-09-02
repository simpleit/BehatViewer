<?php
namespace BehatViewer\BehatViewerReportBundle\Analyzer;

use BehatViewer\BehatViewerBundle\Entity;

interface AnalyzerInterface
{
	/**
	 * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
	 * @param array                                         $data
	 */
	function analyze(Entity\Project $project, array $data);
}
