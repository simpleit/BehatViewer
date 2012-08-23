<?php

namespace BehatViewer\BehatViewerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    JMS\SecurityExtraBundle\Annotation as Security;

class ApiController extends BehatViewerController
{
	/**
	 * @return array
	 *
	 * @Configuration\Route("/api", name="behatviewer.api")
	 */
	public function indexAction()
	{
		return new \Symfony\Component\HttpFoundation\Response();
	}
}
