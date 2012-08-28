<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

class TagParamConverter extends BehatViewerParamConverter
{
    protected function getObject(Request $request, array $options)
    {
        $repository = $this->container->get('doctrine')->getRepository('BehatViewerBundle:Tag');
        $slug = $request->get($options['mapping']['slug']);

        return $repository->findOneBySlug($slug);
    }

    protected function getClass()
    {
        return 'BehatViewer\\BehatViewerBundle\\Entity\\Tag';
    }

    protected function getDefaultOptions()
    {
        return array(
            'mapping' => array(
                'slug' => 'slug'
            ),
        );
    }
}
