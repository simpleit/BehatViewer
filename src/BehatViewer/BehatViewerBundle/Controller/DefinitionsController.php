<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerCoreBundle\Entity,
    JMS\SecurityExtraBundle\Annotation as Security;

class DefinitionsController extends BehatViewerProjectController
{
    /**
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\User    $user
     * @param \BehatViewer\BehatViewerCoreBundle\Entity\Project $project
     *
     * @return array
     *
     * @Configuration\Template()
     * @Security\PreAuthorize("hasPermission(#project, 'VIEW') or #project.getType() == 'public'")
     */
    public function indexAction(Entity\User $user, Entity\Project $project)
    {
        $definitions = array();
        $contexts = array();
        $repository = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Definition');

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
