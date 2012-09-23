<?php
namespace BehatViewer\BehatViewerCoreBundle\Features\Context;

use Behat\Behat\Context\BehatContext;

class BehatViewerCoreContext extends BehatContext
{
    /** @var array */
    protected $parameters = array();

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;

        $this->useContext('fixture', new BehatViewerCoreFixtureContext($parameters));
		$this->useContext('user', new BehatViewerCoreUserContext($parameters));
    }
}
