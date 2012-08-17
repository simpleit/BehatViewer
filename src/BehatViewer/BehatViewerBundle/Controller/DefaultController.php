<?php
namespace BehatViewer\BehatViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    BehatViewer\BehatViewerBundle\Entity,
    JMS\SecurityExtraBundle\Annotation\Secure,
    BehatViewer\BehatViewerBundle\DBAL\Type\EnumProjectTypeType;

/**
 *
 */
class DefaultController extends BehatViewerController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="behatviewer.homepage", options={"expose"=true})
     * @Template()
     */
    public function indexAction()
    {
        $this->beforeAction();

        $projects = $this->getDoctrine()->getRepository('BehatViewerBundle:Project')->findByType(EnumProjectTypeType::TYPE_PUBLIC);

        if (0 === count($projects)) {
            throw new \BehatViewer\BehatViewerBundle\Exception\NoProjectConfiguredException();
        }

        if (1 === count($projects)) {
            return $this->redirect(
                $this->generateUrl(
                    'behatviewer.project',
                    array(
                        'project' => $projects[0]->getSlug(),
                        'username' => $projects[0]->getUser()->getUsername()
                    )
                )
            );
        }

        return $this->getResponse(array(
            'projects' => $projects
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/analyze", name="behatviewer.analyze")
     * @Method({"PUT"})
     */
    public function analyzeAction()
    {
        $data = json_decode($this->getRequest()->getContent(), true);

        $project = $this->getSession()->getProject();
        $analyzer = $this->get('behat_viewer.analyzer');
        $analyzer->analyze($project, $data);

        return  new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/thumb", name="behatviewer.homepage.thumb")
     * @Template("BehatViewerBundle:Default:index.html.twig")
     */
    public function indexthumbAction()
    {
        return $this->indexAction();
    }
}
