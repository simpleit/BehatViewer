<?php

namespace BehatViewer\BehatViewerBundle\Features\Context;

use Behat\MinkExtension\Context\RawMinkContext,
    Behat\Symfony2Extension\Context\KernelAwareInterface,
    Behat\Behat\Context\Step,
    Behat\Mink\Exception\ElementNotFoundException,
    Symfony\Component\HttpKernel\KernelInterface;

class StepContext extends RawMinkContext implements KernelAwareInterface
{
    /** @var array */
    protected $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    public function setKernel(KernelInterface $kernel)
    {
      $this->kernel = $kernel;
    }

    /**
     * @Then /^I should see (?P<count>(?:an?|[0-9]+)) (?P<type>(?:passed|failed|skipped|pending|undefined)) steps?$/
     */
    public function iShouldSeeSteps($count, $type)
    {
        $class = $this->getClass($type);

        if(!is_numeric($count)) {
            $count = 1;
        }

        return new Step\Then(sprintf('I should see ' . $count . ' "%s" element', $class));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getClass($type)
    {
        return '.step-' . $type;
    }
}
