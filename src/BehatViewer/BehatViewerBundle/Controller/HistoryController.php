<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    JMS\SecurityExtraBundle\Annotation\Secure,
    BehatViewer\BehatViewerBundle\Entity;

class HistoryController extends BehatViewerProjectController
{
    /**
     * @param int $page
     *
     * @return array
     *
     * @Route("/{username}/{project}/history", name="behatviewer.history", defaults={"page" = 1})
     * @Route("/{username}/{project}/history/page/{page}", name="behatviewer.history.page", requirements={"page" = "\d+"})
     * @Template()
     */
    public function indexAction($username, $project, $page = 1)
    {
        $project = $this->getProject();

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
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}", requirements={"build" = "\d+"}, name="behatviewer.history.entry")
     * @Route("/{username}/{project}/{build}/{type}", requirements={"type" = "list|thumb", "build" = "\d+"}, name="behatviewer.history.entry.switch")
     * @Template()
     */
    public function entryAction($username, $project, $build, $type = 'thumb')
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $build = $repository->findOneByProjectAndId($this->getProject(), $build);

        $type = $this->setViewType($type);
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
     * @param \BehatViewer\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     *
     * @Route("/{username}/{project}/history/delete/{build}", requirements={"id" = "\d+"}, name="behatviewer.history.delete")
     * @Secure(roles="ROLE_USER")
     * @Template("BehatViewerBundle:History:index.html.twig")
     */
    public function deleteAction($username, $project, $build)
    {
        $this->beforeAction();

        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
        $build = $repository->findOneByProjectAndId($this->getProject(), $build);
        if (null === $build) {
            throw $this->createNotFoundException();
        }

        $manager = $this->getDoctrine()->getEntityManager();
        $manager->remove($build);
        $manager->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @return array
     *
     * @Method({"POST"})
     * @Route("/{username}/{project}/history/delete", name="behatviewer.history.delete.selected")
     * @Secure(roles="ROLE_USER")
     * @Template("BehatViewerBundle:History:index.html.twig")
     */
    public function deleteSelectedAction($username, $project)
    {
        $this->beforeAction();

        $manager = $this->getDoctrine()->getEntityManager();
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');

        foreach ($this->getRequest()->get('delete') as $id) {
            $build = $repository->findOneById($id);
            $manager->remove($build);
            $manager->flush();
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}
