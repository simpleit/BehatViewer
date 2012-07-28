Vagrant::Config.run do |config|
    config.vm.box = "precise32"
    
    config.vm.provision :puppet, :module_path => "modules"

    config.vm.forward_port 80, 8888
end
