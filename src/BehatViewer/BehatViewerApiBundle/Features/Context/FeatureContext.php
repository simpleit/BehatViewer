<?php
namespace BehatViewer\BehatViewerApiBundle\Features\Context;

use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand,
    Behat\Behat\Event\SuiteEvent,
    Symfony\Component\HttpKernel\KernelInterface,
    BehatViewer\BehatViewerBundle\Features\Context,
    BehatViewer\BehatViewerCoreBundle\Features\Context\BehatViewerCoreFixtureContext;

class FeatureContext extends Context\BehatViewerContext
{
    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);

        $this->useContext('fixture', new BehatViewerCoreFixtureContext($parameters));
        $this->useContext('brower', new Context\BrowserContext($parameters));
    }

    public function setKernel(KernelInterface $kernel)
    {
        parent::setKernel($kernel);

        foreach ($this->getSubcontexts() as $context) {
            $context->setKernel($kernel);
        }
    }
}
