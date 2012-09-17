<?php
namespace BehatViewer\BehatViewerUiBundle\Features\Context;

use Behat\Behat\Context\Step,
    Behat\Mink\Exception,
    Behat\MinkExtension\Context\RawMinkContext;

class BehatViewerUiMessageContext extends RawMinkContext
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
     * @Then /^I should see an? (?P<type>(?:alert |success |error |information |warning ))message$/
     */
    public function iShouldSeeAnAlertMessage($type)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should see a "%s" element', $class));
    }

    /**
     * @Then /^I should not see an? (?P<type>(?:alert |success |error |information |warning ))message$/
     */
    public function iShouldNotSeeAnAlertMessage($type)
    {
        $class = $this->getClass($type);

        return new Step\Then(sprintf('I should not see a "%s" element', $class));
    }

    /**
     * @Then /^I should see an? (?P<type>(?:alert |success |error |information |warning ))message with title "(?P<title>[^"]*)" and text "(?P<text>[^"]*)"$/
     */
    public function iShouldSeeAnAlertMessageWithTitleAndText($type, $title, $text)
    {
        $alerts = $this->hasAlertMessages($type);

        foreach ($alerts as $alert) {
            if (true === $this->hasTitle($alert, $title) && true === $this->hasText($alert, $text)) {
                return;
            }
        }

        throw new Exception\ExpectationException(
            sprintf(
                'No %s message found with title "%s" and text "%s"',
                null !== $type ? $type : 'alert',
                $title,
                $text
            ),
            $this->getSession()
        );
    }

    /**
     * @Then /^I should not see an? (?P<type>(?:alert |success |error |information |warning ))message with title "(?P<title>[^"]*)" and text "(?P<text>[^"]*)"$/
     */
    public function iShouldNotSeeAnAlertMessageWithTitleAndText($type, $title, $text)
    {
        $exception = null;
        try {
            $this->iShouldSeeAnAlertMessage($type, $title, $text);
        } catch (Exception\ExpectationException $exception) {}

        if (null === $exception) {
            throw new Exception\ExpectationException(
                sprintf(
                    'A %s message found with title "%s" and text "%s"',
                    null !== $type ? $type : 'alert',
                    $title,
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
    protected function getType($type)
    {
        $type = ('' === trim($type) ? null : trim($type));

        switch ($type) {
            case 'alert':
                $type = null;
                break;

            case 'error':
                $type = 'danger';
                break;
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
        $class = '.alert';

        if (null !== $type) {
            $class .= '.alert-' . $type;
        }

        return $class;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function getAlertMessages($type = null)
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
    protected function hasAlertMessages($type = null)
    {
        $alerts = $this->getAlertMessages($type);

        if (count($alerts) < 1) {
            throw new Exception\ExpectationException(
                sprintf(
                    'No %s message found',
                    null !== $type ? $type : 'alert'
                ),
                $this->getSession()
            );
        }

        return $alerts;
    }

    /**
     * @param $alert
     * @param string $title
     *
     * @return bool
     */
    protected function hasTitle($alert, $title)
    {
        $heads = $alert->findAll('css', '.alert-heading');

        foreach ($heads as $head) {
            if ($head->getText() === $title) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $alert
     * @param string $text
     *
     * @return bool
     */
    protected function hasText($alert, $text)
    {
        return (false !== strpos($alert->getText(), $text));
    }
}
