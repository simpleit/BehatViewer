<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    BehatViewer\BehatViewerBundle\Entity;

class TagController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\Tag $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/tag/{slug}", name="behatviewer.tag", defaults={"build" = null})
     * @Route("/{username}/{project}/{build}/tag/{slug}", name="behatviewer.tag.build", requirements={"build_id" = "\d+"})
     * @Template("BehatViewerBundle:History:index.html.twig")
     */
    public function indexAction(Entity\User $user, Entity\Project $project, Entity\Tag $tag, Entity\Build $build = null, $type = null)
    {
		if(null === $build) {
			$build = $project->getLastBuild();
		}

        $features = $this->getDoctrine()->getRepository('BehatViewerBundle:Feature')->findByTagAndBuild($tag, $build);
        $scenarios = $this->getDoctrine()->getRepository('BehatViewerBundle:Scenario')->findByTagAndBuild($tag, $build);

        foreach ($scenarios as $scenario) {
            if (!in_array($scenario->getFeature(), $features)) {
                $features[] = $scenario->getFeature();
            }
        }

		if(null === $type) {
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
