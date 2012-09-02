<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class SahiScript extends Script
{
	public function __construct() {
		$this->addCommands(array(
			'export SAHI_HOME=/usr/local/bin/sahi',
			'/usr/local/bin/sahi/bin/sahi.sh > /dev/null 2>&1 &'
		));
	}
}
