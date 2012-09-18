<?php
namespace BehatViewer\BehatViewerWorkerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration,
    BehatViewer\BehatViewerCoreBundle\Entity,
    BehatViewer\BehatViewerCoreBundle\DBAL\Type\EnumProjectTypeType,
    BehatViewer\BehatViewerBundle\Controller\BehatViewerController;

class DefaultController extends BehatViewerController
{
    /**
     * @return array
     *
     * @Configuration\Route("/", name="behatviewer.worker")
     * @Configuration\Template()
     */
    public function indexAction()
    {
        $rabbitHost = $this->container->getParameter('rabbitmq_host');
        $rabbitPort = $this->container->getParameter('rabbitmq_api_port');
        $rabbitUser = $this->container->getParameter('rabbitmq_user');
        $rabbitPassword = $this->container->getParameter('rabbitmq_password');

        $curl = curl_init('http://' . $rabbitHost . ':' . $rabbitPort . '/api/connections');
        curl_setopt($curl, CURLOPT_USERPWD, $rabbitUser . ':' . $rabbitPassword);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $workers = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init('http://' . $rabbitHost . ':' . $rabbitPort . '/api/overview');
        curl_setopt($curl, CURLOPT_USERPWD, $rabbitUser . ':' . $rabbitPassword);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $overview = curl_exec($curl);
        curl_close($curl);

        return $this->getResponse(array(
            'items' => json_decode($workers),
            'overview' => json_decode($overview),
        ));
    }

    /**
     * @return array
     *
     * @Configuration\Route("/log", name="behatviewer.worker.job")
     * @Configuration\Template()
     */
    public function jobsAction()
    {
		$pusherHost = $this->container->getParameter('pusher_host');
		$pusherPort = $this->container->getParameter('pusher_port');
		$pusherKey = $this->container->getParameter('pusher_key');
		$pusherSecret = $this->container->getParameter('pusher_secret');

		$curl = curl_init('http://' . $pusherHost . ':' . $pusherPort . '/api/1.0.0/' . $pusherKey . '/users/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-Thunder-Secret-Key: ' . $pusherSecret,
		));

        $jobs = $this->getDoctrine()->getRepository('BehatViewerCoreBundle:Job')->findAll();

        return $this->getResponse(array(
            'items' => $jobs,
			'status' => json_decode(curl_exec($curl))
        ));
    }

    /**
     * @return array
     *
     * @Configuration\Route("/log/{id}", name="behatviewer.worker.job.log")
     * @Configuration\Template()
     */
    public function logAction(\BehatViewer\BehatViewerCoreBundle\Entity\Job $job)
    {
        return $this->getResponse(array(
            'job' => $job
        ));
    }
}
