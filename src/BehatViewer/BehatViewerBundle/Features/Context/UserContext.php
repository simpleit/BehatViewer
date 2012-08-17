<?php
namespace BehatViewer\BehatViewerBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface,
    Behat\Behat\Context\Step;

class UserContext extends BehatViewerContext
{
    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        $this->iAmNotLoggedIn();
        $this->getMainContext()->getSubContext('browser')->getSession()->stop();
    }

    /**
     * @Given /^I am a logged in (admin|user)$/
     */
    public function iAmALoggedInUser($profile)
    {
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
        /** @var $browser \BehatViewer\BehatViewerBundle\Features\Context\BrowserContext */
        $browser = $this->getMainContext()->getSubcontext('browser');
        $browser->visit('/logout');
    }
}
