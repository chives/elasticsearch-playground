$host_name = "elastic.dev"
$db_name = "elastic"
$db_name_dev = "${db_name}-dev"
$db_name_tst = "${db_name}-tst"

group { 'puppet': ensure => present }
Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }
File { owner => 0, group => 0, mode => 0644 }

file { "/dev/shm/elastic":
  ensure => directory,
  purge => true,
  force => true,
  owner => vagrant,
  group => vagrant
}

file { "/var/lock/apache2":
  ensure => directory,
  owner => vagrant
}

exec { "ApacheUserChange" :
  command => "sed -i 's/export APACHE_RUN_USER=.*/export APACHE_RUN_USER=vagrant/ ; s/export APACHE_RUN_GROUP=.*/export APACHE_RUN_GROUP=vagrant/' /etc/apache2/envvars",
  require => [ Package["apache"], File["/var/lock/apache2"] ],
  notify  => Service['apache'],
}

class {'apt':
  always_apt_update => true,
}

Class['::apt::update'] -> Package <|
    title != 'python-software-properties'
and title != 'software-properties-common'
|>

apt::key { '4F4EA0AAE5267A6C': }

apt::ppa { 'ppa:ondrej/php5-oldstable':
  require => Apt::Key['4F4EA0AAE5267A6C']
}

package {
  [
    'curl',
    'git-core',
    'mc'
  ]:
  ensure  => 'installed',
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { "${host_name}":
  server_name   => "${host_name}",
  serveraliases => [
    "www.${host_name}"
  ],
  docroot       => "/var/www/elastic/web/",
  port          => '80',
  env_variables => [],
  priority      => '1',
}

class { 'php':
  service             => 'apache',
  service_autorestart => false,
  module_prefix       => '',
}

php::module { 'php5-mysql': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }
php::module { 'php5-gd': }
php::module { 'php-apc': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}

file { "/var/www/vagrant_env.php":
    replace => "no",
    ensure  => "present",
    content => "<?php define('VAGRANT', 'VAGRANT'); ",
    mode    => 644,
}

php::ini { 'php_ini_configuration':
  value   => [
    'date.timezone = "Europe/Warsaw"',
    'display_errors = On',
    'error_reporting = -1',
    'short_open_tag = 0',
    'auto_prepend_file = "/var/www/vagrant_env.php"'
  ],
  notify  => Service['apache'],
  require => [ Class['php'], File['/var/www/vagrant_env.php'] ]
}

class { 'mysql::server':
  override_options => { 'root_password' => '', }
}

mysql::db { "${db_name}":
  grant    => [
    'ALL'
  ],
  user     => "${db_name}",
  password => "${db_name}",
  host     => 'localhost',
  charset  => 'utf8',
  require  => Class['mysql::server'],
}

class { 'java': }

class { 'elasticsearch':
  package_url => 'https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-0.90.10.deb'
}

#exec { 'install marvel':
#  command => ''
#}