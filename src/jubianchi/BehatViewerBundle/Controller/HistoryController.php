<?php

namespace jubianchi\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    JMS\SecurityExtraBundle\Annotation\Secure,
    jubianchi\BehatViewerBundle\Entity;

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
        $this->beforeAction();

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
     * @param \jubianchi\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}", requirements={"build" = "\d+"}, name="behatviewer.history.entry")
     * @Template()
     */
    public function entryAction($username, $project, $build)
    {
        $this->beforeAction();

		$repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Build');
		$build = $repository->findOneByProjectAndId($this->getProject(), $build);
		if(null === $build) {
			throw $this->createNotFoundException();
		}

        return $this->getResponse(array(
            'build' => $build,
            'items' => null
        ));
    }

    /**
     * @param \jubianchi\BehatViewerBundle\Entity\Build|null $build
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{username}/{project}/{build}/list", requirements={"build" = "\d+"}, name="behatviewer.history.entry.list")
     * @Template("BehatViewerBundle:Default:list.html.twig")
     */
    public function entrylistAction($username, $project, $build)
    {
        return $this->entryAction($username, $project, $build);
    }

    /**
     * @param \jubianchi\BehatViewerBundle\Entity\Build|null $build
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
		if(null === $build) {
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
