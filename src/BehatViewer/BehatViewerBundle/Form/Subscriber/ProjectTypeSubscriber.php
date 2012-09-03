<?php
namespace BehatViewer\BehatViewerBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

class ProjectTypeSubscriber implements EventSubscriberInterface
{
	private $factory;

	public function __construct(FormFactoryInterface $factory)
	{
		$this->factory = $factory;
	}

	public static function getSubscribedEvents()
	{
		return array(FormEvents::POST_SET_DATA => 'postSetData');
	}

	public function postSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();

		if(null === ($strategy = $data->getStrategy())) {
			$strategy = new \BehatViewer\BehatViewerWorkerBundle\Entity\LocalStrategy();
		}

		$form->add(
			$this->factory->createNamed(
				'strategy',
				$strategy->getFormType(),
				$data->getStrategy(),
				array(
					'label' => 'Project type'
				)
			)
		);
	}
}
