<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerCoreBundle\Entity;

class TagController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User       $user
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Project    $project
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Tag        $tag
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Build|null $build
     * @param string|null                                          $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Template("BehatViewerBundle:History:index.html.twig")
     */
    public function indexAction(Entity\User $user, Entity\Project $project, Entity\Tag $tag, Entity\Build $build = null, $type = null)
    {
        if (null === $build) {
            $build = $project->getLastBuild();
        }

        $features = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Feature')->findByTagAndBuild($tag, $build);
        $scenarios = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Scenario')->findByTagAndBuild($tag, $build);

        foreach ($scenarios as $scenario) {
            if (!in_array($scenario->getFeature(), $features)) {
                $features[] = $scenario->getFeature();
            }
        }

        if (null === $type) {
            $type = $this->getViewType('thumb');
        }
        $this->setViewType($type);

        $view = 'entry' . ($type !== null ? '-' . $type : '');

        return $this->render(
            'BehatViewerBundle:History:' . $view . '.html.twig',
            $this->getResponse(
                array(
                    'tag' => $tag,
                    'build' => $build,
                    'items' => $features
                )
            )
        );
    }
}
