<?php
namespace BehatViewer\BehatViewerBundle\Controller;

/**
 *
 */
abstract class BehatViewerBuildController extends BehatViewerProjectController
{
    /**
     * @param array $variables
     *
     * @return array
     */
    public function getResponse(array $variables = array())
    {
        return array_merge(
            array(
                'build' => $this->getRequest()->get('build')
            ),
            parent::getResponse($variables)
        );
    }
}
