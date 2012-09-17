<?php
namespace BehatViewer\BehatViewerUiBundle\Features\Context;

use Behat\Behat\Context\Step,
    Behat\Mink\Exception,
    Behat\MinkExtension\Context\RawMinkContext;

class BehatViewerUiIconicButtonContext extends RawMinkContext
{
    /** @var array */
    protected $parameters = array();

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * @Then /^I should see an? "(?P<type>[^"]*)" iconic button$/
     */
    public function iShouldSeeAButton($type)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should see a "%s" element', $class));
    }

    /**
     * @Then /^I should not see an? "(?P<type>[^"]*)" iconic button$/
     */
    public function iShouldNotSeeAButton($type)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should not see a "%s" element', $class));
    }

    /**
     * @param string $type
     *
     * @return null|string
     */
    protected function getType($type)
    {
        return ('' === trim($type) ? null : strtolower(trim($type)));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getClass($type)
    {
        $type = $this->getType($type);
        $class = '.icon-' . $type;

        return $class;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getButtons($type)
    {
        $buttons = $this->getSession()->getPage()->findAll('css', '.btn');

        $buttons = array_filter(
            $buttons,
            function($value) use ($type) {
                $icon = $value->findAll('css', $this->getClass($type));

                return (count($icon) > 0);
            }
        );

        return $buttons;
    }

    /**
     * @param string $type
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     *
     * @return array
     */
    protected function hasButtons($type)
    {
        $buttons = $this->getButtons($type);

        if (count($buttons) < 1) {
            $type = $this->getType($type);

            throw new Exception\ExpectationException(
                sprintf(
                    'No %s button found',
                    $type
                ),
                $this->getSession()
            );
        }

        return $buttons;
    }

    /**
     * @param $button
     * @param string $text
     *
     * @return bool
     */
    protected function hasText($button, $text)
    {
        return (false !== strpos($button->getText(), $text));
    }
}
