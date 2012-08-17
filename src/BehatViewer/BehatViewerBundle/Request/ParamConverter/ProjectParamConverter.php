<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface,
	Symfony\Component\HttpFoundation\Request,
	Symfony\Component\DependencyInjection\ContainerAware,
	Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectParamConverter extends ContainerAware implements ParamConverterInterface
{
	public function apply(Request $request, ConfigurationInterface $configuration) {
		$options = $this->getOptions($configuration);

		$repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Project');

		$username = $request->get($options['mapping']['username']);
		$slug = $request->get($options['mapping']['project']);
		$object = $repository->findOneByUsernameAndSlug($username, $slug);

		if (null === $object && false === $configuration->isOptional()) {
			throw new NotFoundHttpException(sprintf('%s/%s project not found.', $username, $slug));
		}

		$request->attributes->set($configuration->getName(), $object);

		return true;
	}

	public function supports(ConfigurationInterface $configuration) {
		if (null === $configuration->getClass()) {
			return false;
		}

		return true;
	}

	protected function getOptions(ConfigurationInterface $configuration)
	{
		return array_replace(
			array(
				'mapping' => array(
					'username' => 'username',
					'project' => 'project'
				),
			),
			$configuration->getOptions()
		);
	}
}
