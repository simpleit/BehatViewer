# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
    config.vm.box = "precise32"
    
    config.vm.provision :puppet, :module_path => "modules"
end
