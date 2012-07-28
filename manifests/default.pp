exec { "apt-get update":
    command => "/usr/bin/apt-get update",
}

include php::apache2