<?php
namespace BehatViewer\BehatViewerUiBundle\Features\Context;

use Behat\Behat\Context\BehatContext;

class BehatViewerUiContext extends BehatContext
{
    /** @var array */
    protected $parameters = array();

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;

        $this->useContext('message', new BehatViewerUiMessageContext($parameters));
        $this->useContext('button', new BehatViewerUiButtonContext($parameters));
        $this->useContext('iconicbutton', new BehatViewerUiIconicButtonContext($parameters));
    }
}
