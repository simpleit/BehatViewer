Vagrant::Config.run do |config|
    config.vm.box = "bv-precise32"

    config.vm.network :hostonly, "10.0.0.2", :netmask => "255.255.255.0"
    config.vm.share_folder("v-root", "/vagrant", ".", :nfs => true)

    config.vm.forward_port 22, 2222
    config.vm.forward_port 80, 8888
end
