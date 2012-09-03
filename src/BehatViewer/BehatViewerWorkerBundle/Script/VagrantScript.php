<?php
namespace BehatViewer\BehatViewerWorkerBundle\Script;

class VagrantScript extends Script
{
    public function __construct($script)
    {
        $this->addCommands(array(
            'vagrant up',
            'vagrant ssh -c "cd /vagrant && sudo sh -e ./' . $script . '"',
            'vagrant halt',
            'vagrant destroy -f'
        ));
    }
}
