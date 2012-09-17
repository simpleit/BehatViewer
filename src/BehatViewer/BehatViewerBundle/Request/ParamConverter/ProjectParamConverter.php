<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Symfony\Component\HttpFoundation\Request;

class ProjectParamConverter extends BehatViewerParamConverter
{
    public function getObject(Request $request, array $options)
    {
        $repository = $this->container->get('doctrine')->getRepository('BehatViewerCoreBundle:Project');

        $slug = $request->get($options['mapping']['project']);

        if (null !== ($user = $request->get('user'))) {
            $object = $repository->findOneByUsernameAndSlug($user->getUsername(), $slug);
        } else {
            $username = $request->get($options['mapping']['username']);
            $object = $repository->findOneByUsernameAndSlug($username, $slug);
        }

        return $object;
    }

    protected function getClass()
    {
        return 'BehatViewer\\BehatViewerCoreBundle\\Entity\\Project';
    }

    protected function getDefaultOptions()
    {
        return array(
            'mapping' => array(
                'username' => 'username',
                'project' => 'project'
            ),
        );
    }
}
