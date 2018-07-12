<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 *
 */
$settings['install_profile'] = 'standard';

// Configure fast_404.
if (file_exists('./modules/contrib/fast_404/fast404.inc')) {
  include_once './modules/contrib/fast_404/fast404.inc';
  fast404_preboot($settings);
}

// HTTP HOST Header attacks.
if (defined('PANTHEON_ENVIRONMENT')) {
  if (in_array($_ENV['PANTHEON_ENVIRONMENT'], ['dev', 'test', 'live'])) {
    $settings['trusted_host_patterns'][] = "{$_ENV['PANTHEON_ENVIRONMENT']}-{$_ENV['PANTHEON_SITE_NAME']}.getpantheon.io";
    $settings['trusted_host_patterns'][] = "{$_ENV['PANTHEON_ENVIRONMENT']}-{$_ENV['PANTHEON_SITE_NAME']}.pantheon.io";
    $settings['trusted_host_patterns'][] = "{$_ENV['PANTHEON_ENVIRONMENT']}-{$_ENV['PANTHEON_SITE_NAME']}.pantheonsite.io";
    $settings['trusted_host_patterns'][] = "{$_ENV['PANTHEON_ENVIRONMENT']}-{$_ENV['PANTHEON_SITE_NAME']}.panth.io";
  }
}

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$config_directories = array(
  CONFIG_SYNC_DIRECTORY => dirname(DRUPAL_ROOT) . '/config',
);

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}
