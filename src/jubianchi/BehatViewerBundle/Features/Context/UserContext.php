<?php
namespace jubianchi\BehatViewerBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface,
    Behat\Behat\Context\Step;

class UserContext extends BehatViewerContext
{
    private $logged = false;

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        if (true === $this->logged) {
            $this->getMainContext()->getSubContext('browser')->getSession()->stop();
        }
    }

    /**
     * @Given /^I am a logged in (admin|user)$/
     */
    public function iAmALoggedInUser($profile)
    {
        $this->logged = true;

        return array(
            new Step\Given(sprintf('I load the "%s.sql" fixture', $profile)),
            new Step\Given('I am on "/login"'),
            new Step\Then(sprintf('I fill in "Username" with "%s"', $profile)),
            new Step\Then(sprintf('I fill in "Password" with "%s"', $profile)),
            new Step\Then('I press "Log in"'),
            new Step\Then(sprintf('I should see "Logged in as %s"', $profile))
        );
    }

    /**
     * @Given /^I am not logged in$/
     * @Then /^I logout$/
     * @Then /^I log out$/
     */
    public function iAmNotLoggedIn()
    {
        /** @var $browser \jubianchi\BehatViewerBundle\Features\Context\BrowserContext */
        $browser = $this->getMainContext()->getSubcontext('browser');
        $browser->visit('/logout');

        $this->logged = false;
    }
}
