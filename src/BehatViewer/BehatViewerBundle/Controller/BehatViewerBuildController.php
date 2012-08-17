<?php
namespace BehatViewer\BehatViewerBundle\Controller;

/**
 *
 */
abstract class BehatViewerBuildController extends BehatViewerProjectController
{
    private $build;

    protected function getBuild()
    {
        if (null === $this->build) {
            $project = $this->getProject();

            $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
            $this->build = $repository->findOneByProjectAndId($project, $this->getRequest()->get('build'));

            if (null === $this->build) {
                throw $this->createNotFoundException();
            }
        }

        return $this->build;
    }

    /**
     * @return array
     */
    public function getResponse(array $variables = array())
    {
        return array_merge(
            array(
                'build' => $this->getBuild()
            ),
            parent::getResponse($variables)
        );
    }
}
