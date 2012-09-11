<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity,
    BehatViewer\BehatViewerBundle\DBAL\Type\EnumProjectTypeType;

/**
 *
 */
class DefaultController extends BehatViewerController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException
     *
     * @Configuration\Template()
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');

        if (null !== ($user = $this->getUser())) {
            $projects = $repository->findByUser($this->getUser());
        } else {
            $projects = $repository->findByType(EnumProjectTypeType::TYPE_PUBLIC);
        }

        if (0 === count($projects)) {
            throw new \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException();
        }

        return $this->getResponse(array(
            'projects' => $projects,
            'user' => $this->getUser()
        ));
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException
     *
     * @Configuration\Template("BehatViewerBundle:Default:index.html.twig")
     */
    public function userAction(Entity\User $user)
    {
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Project');
        if ($user === $this->getUser()) {
            return $this->forward('BehatViewerBundle:Default:index');
        }

        $projects = $repository->findByUserAndType($user, EnumProjectTypeType::TYPE_PUBLIC);

        return $this->getResponse(array(
            'projects' => $projects
        ));
    }
}
