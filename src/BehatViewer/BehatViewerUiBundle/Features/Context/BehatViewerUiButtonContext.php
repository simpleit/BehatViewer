<?php
namespace BehatViewer\BehatViewerUiBundle\Features\Context;

use Behat\Behat\Context\Step,
    Behat\Mink\Exception,
    Behat\MinkExtension\Context\RawMinkContext;

class BehatViewerUiButtonContext extends RawMinkContext
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
     * @Then /^I should see an? (?P<type>(?:|default |success |error |information |warning ))button$/
     */
    public function iShouldSeeAButton($type = null)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should see a "%s" element', $class));
    }

    /**
     * @Then /^I should not see an? (?P<type>(?:|default |success |error |information |warning ))button$/
     */
    public function iShouldNotSeeAButton($type = null)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should not see a "%s" element', $class));
    }

    /**
     * @Then /^I should see an? (?P<type>(?:|default |success |error |information |warning ))button with text "(?P<text>[^"]*)"$/
     */
    public function iShouldSeeAButtonWithText($type = null, $text)
    {
        $buttons = $this->hasButtons($type);

        foreach ($buttons as $button) {
            if (true === $this->hasText($button, $text)) {
                return;
            }
        }

        $type = $this->getType($type);

        throw new Exception\ExpectationException(
            sprintf(
                'No%s button found with text "%s"',
                null !== $type ? ' ' . $type : '',
                $text
            ),
            $this->getSession()
        );
    }

    /**
     * @Then /^I should not see an? (?P<type>(?:|default |success |error |information |warning ))button with text "(?P<text>[^"]*)"$/
     */
    public function iShouldNotSeeAButtonWithText($type, $text)
    {
        $exception = null;
        try {
            $this->iShouldSeeAButtonWithText($type, $text);
        } catch (Exception\ExpectationException $exception) {}

        if (null === $exception) {
            $type = $this->getType($type);

            throw new Exception\ExpectationException(
                sprintf(
                    'A%s button found with text "%s"',
                    null !== $type ? ' ' . $type : '',
                    $text
                ),
                $this->getSession()
            );
        }
    }

    /**
     * @param string $type
     *
     * @return null|string
     */
    protected function getType($type = null)
    {
        $type = ('' === trim($type) ? null : trim($type));

        if ('error' === $type) {
            $type = 'danger';
        }

        return $type;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getClass($type = null)
    {
        $type = $this->getType($type);
        $class = '.btn';

        if (null !== $type) {
            $class .= '.btn-' . $type;
        }

        return $class;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getButtons($type = null)
    {
        return $this->getSession()->getPage()->findAll('css', $this->getClass($type));
    }

    /**
     * @param string $type
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     *
     * @return array
     */
    protected function hasButtons($type = null)
    {
        $buttons = $this->getButtons($type);

        if (count($buttons) < 1) {
            throw new Exception\ExpectationException(
                sprintf(
                    'No%s button found',
                    null !== $type ? ' ' . $type : ''
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
