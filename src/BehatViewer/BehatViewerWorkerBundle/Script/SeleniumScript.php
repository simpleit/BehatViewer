<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class SeleniumScript extends Script
{
	public function __construct() {
		$this->addCommands(array(
			'export JAVA="/usr/bin/env java"',
			'export BROWSERS="*firefox env DISPLAY=:99 firefox *chrome env DISPLAY=:99 google-chrome"',
			'$JAVA -jar /usr/local/bin/selenium.jar $BROWSER > /dev/null 2>&1 &'
		));
	}
}
