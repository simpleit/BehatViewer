<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security,
    BehatViewer\BehatViewerBundle\Entity;

class HistoryController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     * @param int                                           $page
     *
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/history", name="behatviewer.history", defaults={"page" = 1})
     * @Configuration\Route("/{username}/{project}/history/page/{page}", name="behatviewer.history.page", requirements={"page" = "\d+"})
     * @Configuration\Template()
     */
    public function indexAction(Entity\User $user, Entity\Project $project, $page = 1)
    {
        $builds = array();
        $pages = $page <= 0 ? 1 : $page;
        if ($project !== null) {
            $total = $this->getDoctrine()->getRepository('BehatViewerBundle:Build')->findBy(
                array(
                    'project' => $project->getId()
                )
            );
            $pages = ceil(sizeof($total) / 10);

            $page = $page < 1 ? 1 : $page;
            $page = $page > $pages ? $pages : $page;

            $builds = $this->getDoctrine()->getRepository('BehatViewerBundle:Build')->findBy(
                array(
                    'project' => $project->getId()
                ),
                array(
                    'id' => 'DESC'
                ),
                10,
                10 * (($page - 1) < 0 ? 0 : ($page - 1))
            );
        }

        return $this->getResponse(array(
            'items' => $builds,
            'current' => $page,
            'pages' => $pages
        ));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User       $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project    $project
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     * @param string|null                                      $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Configuration\Route("/{username}/{project}/{build}", requirements={"build" = "\d+"}, name="behatviewer.history.entry")
     * @Configuration\Route("/{username}/{project}/{build}/{type}", requirements={"type" = "list|thumb", "build" = "\d+"}, name="behatviewer.history.entry.switch")
     * @Configuration\Template()
     */
    public function entryAction(Entity\User $user, Entity\Project $project, Entity\Build $build = null, $type = null)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $build = $repository->findOneByProjectAndId($project, $build);

        if (null === $type) {
            $type = $this->getViewType('thumb');
        }
        $this->setViewType($type);

        $view = 'entry' . ($type !== null ? '-' . $type : '');

        return $this->render(
            'BehatViewerBundle:History:' . $view . '.html.twig',
            $this->getResponse(
                array(
                    'build' => $build,
                    'items' => null !== $build ? $build->getFeatures() : array()
                )
            )
        );
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User       $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project    $project
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     *
     * @Configuration\Route("/{username}/{project}/history/delete/{build}", requirements={"build" = "\d+"}, name="behatviewer.history.delete")
     * @Configuration\Template("BehatViewerBundle:History:index.html.twig")
     * @Security\Secure(roles="ROLE_USER")
     * @Security\SecureParam(name="project", permissions="EDIT")
     */
    public function deleteAction(Entity\User $user, Entity\Project $project, Entity\Build $build)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($build);
        $manager->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @param \BehatViewer\BehatViewerBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerBundle\Entity\Project $project
     *
     * @return array
     *
     * @Configuration\Method({"POST"})
     * @Configuration\Route("/{username}/{project}/history/delete", name="behatviewer.history.delete.selected")
     * @Configuration\Template("BehatViewerBundle:History:index.html.twig")
     * @Security\Secure(roles="ROLE_USER")
     * @Security\SecureParam(name="project", permissions="EDIT")
     */
    public function deleteSelectedAction(Entity\User $user, Entity\Project $project)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');

        foreach ($this->getRequest()->get('delete', array()) as $id) {
            $build = $repository->findOneById($id);
            $manager->remove($build);
            $manager->flush();
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}
