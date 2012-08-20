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
     * @Route("/{username}/{project}/{build}/{feature}", name="behatviewer.feature", requirements={"build" = "\d+"})
     * @Template()
     */
    public function indexAction(Entity\User $user, Entity\Project $project, Entity\Build $build, Entity\Feature $feature)
    {
        return $this->getResponse(array(
            'feature' => $feature,
            'build' => $build,
            'features' => $build->getFeatures()
        ));
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}/{feature}/source", name="behatviewer.feature.source", requirements={"id" = "\d+"})
     * @Template()
     */
    public function sourceAction(Entity\User $user, Entity\Project $project, Entity\Build $build, Entity\Feature $feature)
    {
        $file = $feature->getFile();
        $source = file_exists($file) ? file_get_contents($file) : '';

        return $this->getResponse(array(
            'feature' => $feature,
            'build' => $build,
            'source' => $source
        ));
    }

  /**
   * @return string
   *
   * @Route("/screenshot/{id}", name="behatviewer.screenshot", options={"expose"=true}, requirements={"id" = "\d+"})
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
