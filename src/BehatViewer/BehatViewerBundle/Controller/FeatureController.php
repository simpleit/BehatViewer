<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity,
    JMS\SecurityExtraBundle\Annotation as Security;

class FeatureController extends BehatViewerBuildController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/{username}/{project}/{build}/{feature}", name="behatviewer.feature", requirements={"build" = "\d+"})
     * @Configuration\Template()
	 * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
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
     * @Configuration\Route("/{username}/{project}/{build}/{feature}/source", name="behatviewer.feature.source", requirements={"id" = "\d+"})
     * @Configuration\Template()
	 * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
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
   * @Configuration\Route("/screenshot/{id}", name="behatviewer.screenshot", options={"expose"=true}, requirements={"id" = "\d+"})
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
