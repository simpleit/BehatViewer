<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

class FeatureParamConverter extends BehatViewerParamConverter
{
	protected function getObject(Request $request, array $options) {
		$repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Feature');
		$slug = $request->get($options['mapping']['slug']);

		return $repository->findOneByBuildAndSlug($request->get('build'), $slug);
	}

	protected function getClass() {
		return 'BehatViewer\\BehatViewerBundle\\Entity\\Feature';
	}

	protected function getDefaultOptions()
	{
		return array(
			'mapping' => array(
				'slug' => 'feature'
			),
		);
	}
}
