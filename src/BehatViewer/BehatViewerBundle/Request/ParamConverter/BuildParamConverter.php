<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

class BuildParamConverter extends BehatViewerParamConverter
{
	public function getObject(Request $request, array $options) {
		$repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Build');

		$id = $request->get($options['mapping']['id']);

		return $repository->findOneById($id);
	}

	protected function getClass() {
		return 'BehatViewer\\BehatViewerBundle\\Entity\\Build';
	}

	protected function getDefaultOptions()
	{
		return array(
			'mapping' => array(
				'id' => 'build'
			),
		);
	}
}
