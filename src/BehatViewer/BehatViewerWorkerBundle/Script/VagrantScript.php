<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class VagrantScript extends Script
{
	public function __construct($script) {
		$this->addCommands(array(
			'vagrant up',
			'vagrant ssh -c "sudo rm -rf /var/www && sudo cp -rf /vagrant /var/www && cd /var/www && sudo sh -e ./' . $script . '; cp -f behat-viewer.json /vagrant/behat-viewer.json"',
			'vagrant halt',
			'vagrant destroy -f'
		));
	}
}
