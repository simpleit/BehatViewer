<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    BehatViewer\BehatViewerBundle\Entity;

class FeatureController extends BehatViewerBuildController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}/{feature}", name="behatviewer.feature")
     * @Template()
     */
    public function indexAction(Entity\Project $project, $build, $feature)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Feature');
        $feature = $repository->findOneByBuildAndSlug($this->getBuild(), $feature);

        return $this->getResponse(array(
            'feature' => $feature,
            'build' => $this->getBuild(),
            'features' => $this->getBuild()->getFeatures()
        ));
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}/{feature}/source", name="behatviewer.feature.source", requirements={"id" = "\d+"})
     * @Template()
     */
    public function sourceAction(Entity\Project $project, $build, $feature)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Feature');
        $feature = $repository->findOneByBuildAndSlug($this->getBuild(), $feature);
        $file = $feature->getFile();
        $source = file_exists($file) ? file_get_contents($file) : '';

        return $this->getResponse(array(
            'feature' => $feature,
            'build' => $this->getBuild(),
            'source' => $source
        ));
    }

  /**
   * @return string
   *
   * @Route("/screenshot/{id}", name="behatviewer.screenshot", options={"expose"=true})
   */
  public function screenshotAction(Entity\Step $step)
  {
    return new \Symfony\Component\HttpFoundation\Response(
      $step->getScreen(),
      200,
      array(
        'Content-type' => 'image/png'
      )
    );
  }
}
