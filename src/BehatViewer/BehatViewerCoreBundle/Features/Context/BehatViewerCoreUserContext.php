<?php
namespace BehatViewer\BehatViewerCoreBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface,
    Behat\Behat\Context\Step,
	Behat\MinkExtension\Context\RawMinkContext;

class BehatViewerCoreUserContext extends RawMinkContext
{
    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        $this->iAmNotLoggedIn();
        $this->getSession()->stop();
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
        $this->getSession()->visit($this->locatePath('/logout'));
    }

	protected function locatePath($path)
	{
		$startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

		return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
	}
}
