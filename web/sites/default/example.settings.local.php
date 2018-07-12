<?php

$settings['hash_salt'] = 'ror41kyzwoAtJORGLyJzQCFOFfZ0K_OX_8Xjc1qyfJo7GBglZ8JrhanM18uVcl8h0Rt6glyYKw';

$databases['default']['default'] = array(
  'database' => 'default',
  'username' => 'user',
  'password' => 'user',
  'prefix' => '',
  'host' => 'db',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['container_yamls'][] = __DIR__ . '/services.local.yml';
