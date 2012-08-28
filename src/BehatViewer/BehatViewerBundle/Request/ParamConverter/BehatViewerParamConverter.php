<?php
namespace BehatViewer\BehatViewerBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BehatViewerParamConverter extends ContainerAware implements ParamConverterInterface
{
    abstract protected function getClass();

    abstract protected function getObject(Request $request, array $options);

    protected function getDefaultOptions()
    {
        return array();
    }

    protected function getOptions(ConfigurationInterface $configuration)
    {
        return array_replace($this->getDefaultOptions(), $configuration->getOptions());
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $options = $this->getOptions($configuration);
        $object = $this->getObject($request, $options);

        if (null === $object) {
            if (false === $configuration->isOptional()) {
                throw new NotFoundHttpException();
            }
        }

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    public function supports(ConfigurationInterface $configuration)
    {
        return ($this->getClass() === $configuration->getClass());
    }
}
