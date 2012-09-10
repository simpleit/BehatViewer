Vagrant::Config.run do |config|
    config.vm.box = "bv-precise32"

    config.vm.network :hostonly, "10.0.0.2", :netmask => "255.255.255.0"
    config.vm.share_folder("v-root", "/vagrant", ".", :nfs => true)
end
