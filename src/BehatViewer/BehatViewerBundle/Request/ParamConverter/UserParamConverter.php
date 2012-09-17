<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

class UserParamConverter extends BehatViewerParamConverter
{
    protected function getObject(Request $request, array $options)
    {
        $repository = $this->container->get('doctrine')->getRepository('BehatViewerCoreBundle:User');
        $username = $request->get($options['mapping']['username']);

        return $repository->findOneByUsername($username);
    }

    protected function getClass()
    {
        return 'BehatViewer\\BehatViewerCoreBundle\\Entity\\User';
    }

    protected function getDefaultOptions()
    {
        return array(
            'mapping' => array(
                'username' => 'username'
            ),
        );
    }
}
