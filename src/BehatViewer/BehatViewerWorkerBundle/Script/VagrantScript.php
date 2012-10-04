<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class VagrantScript extends Script
{
    public function __construct($script)
    {
        $this->addCommands(array(
            'vagrant up',
            'vagrant ssh -c "cd /vagrant/app/data/repos/admin/test && sudo sh -e ./' . $script . '"',
            'vagrant halt'
        ));
    }
}
