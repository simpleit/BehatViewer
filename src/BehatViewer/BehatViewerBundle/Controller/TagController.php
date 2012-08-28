<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity;

class TagController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User       $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project    $project
     * @param \BehatViewer\BehatViewerBundle\Entity\Tag        $tag
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     * @param string|null                                      $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/{username}/{project}/tag/{slug}", name="behatviewer.tag", defaults={"build" = null})
     * @Configuration\Route("/{username}/{project}/{build}/tag/{slug}", name="behatviewer.tag.build", requirements={"build_id" = "\d+"})
     * @Configuration\Template("BehatViewerBundle:History:index.html.twig")
     */
    public function indexAction(Entity\User $user, Entity\Project $project, Entity\Tag $tag, Entity\Build $build = null, $type = null)
    {
        if (null === $build) {
            $build = $project->getLastBuild();
        }

        $features = $this->getDoctrine()->getRepository('BehatViewerBundle:Feature')->findByTagAndBuild($tag, $build);
        $scenarios = $this->getDoctrine()->getRepository('BehatViewerBundle:Scenario')->findByTagAndBuild($tag, $build);

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
