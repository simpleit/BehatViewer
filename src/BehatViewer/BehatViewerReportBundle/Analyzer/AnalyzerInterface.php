<?php
namespace BehatViewer\BehatViewerReportBundle\Analyzer;

use BehatViewer\BehatViewerCoreBundle\Entity;

interface AnalyzerInterface
{
    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     * @param array                                             $data
     */
    public function analyze(Entity\Project $project, array $data);
}
