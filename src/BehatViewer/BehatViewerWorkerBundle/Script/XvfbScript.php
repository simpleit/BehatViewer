<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class XvfbScript extends Script
{
    public function __construct()
    {
        $this->addCommands(array(
            'export DISPLAY=:99',
            '/usr/bin/Xvfb -ac $DISPLAY -nolisten tcp -screen 0 1280x1024x24 > /dev/null 2>&1 &'
        ));
    }
}
