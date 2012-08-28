<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerBundle\Entity,
    JMS\SecurityExtraBundle\Annotation as Security;

class DefinitionsController extends BehatViewerProjectController
{
    /**
     * @return array
     *
     * @Configuration\Route("/{username}/{project}/definitions", name="behatviewer.definitions")
     * @Configuration\Template()
     * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
     */
    public function indexAction(Entity\User $user, Entity\Project $project)
    {
        $definitions = array();
        $contexts = array();
        $repository = $this->getDoctrine()->getRepository('BehatViewerBundle:Definition');

        if ($project !== null) {
            $definitions = $repository->findByProject($project->getId());
            $contexts = $repository->getContexts($project);
        }

        return $this->getResponse(array(
            'items' => $definitions,
            'contexts' => $contexts
        ));
    }
}
