<?php

/**
 * @file
 * Contains \DrupalProject\composer\ScriptHandler.
 */

namespace DrupalProject\composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ScriptHandler
{

  protected static function getDrupalRoot($project_root)
  {
    return $project_root .  '/web';
  }

  public static function createRequiredFiles(Event $event)
  {
    $fs = new Filesystem();
    $root = static::getDrupalRoot(getcwd());

    $dirs = [
      'modules',
      'themes',
    ];

    // Required for unit testing
    foreach ($dirs as $dir) {
      if (!$fs->exists($root . '/'. $dir)) {
        $fs->mkdir($root . '/'. $dir);
        $fs->touch($root . '/'. $dir . '/.gitkeep');
      }

      if (!$fs->exists($root . '/'. $dir . '/custom')) {
        $fs->mkdir($root . '/'. $dir . '/custom');
        $fs->touch($root . '/'. $dir . '/custom/.gitkeep');
      }
    }

    // Create the files directory with chmod 0777
    if (!$fs->exists($root . '/sites/default/files')) {
      $oldmask = umask(0);
      $fs->mkdir($root . '/sites/default/files', 0777);
      umask($oldmask);
      $event->getIO()->write("Create a sites/default/files directory with chmod 0777");
    }
  }

  public static function prepareForPantheon()
  {
    $dirsToDelete = [];
    $finder = new Finder();
    foreach (
      $finder
        ->directories()
        ->in(getcwd())
        ->ignoreDotFiles(false)
        ->ignoreVCS(false)
        ->depth('> 0')
        ->name('.git')
      as $dir) {
      $dirsToDelete[] = $dir;
    }
    if (!empty($dirsToDelete)) {
      print("\033[31m These .git folders will be deleted:\n\033[0m" . implode("\n", $dirsToDelete) . "\n");
    }
    $fs = new Filesystem();
    $fs->remove($dirsToDelete);
    // Fix up .gitignore: remove everything above the "::: cut :::" line
    $gitignoreFile = getcwd() . '/.gitignore';
    $gitignoreContents = file_get_contents($gitignoreFile);
    $gitignoreContents = preg_replace('/.*::: cut :::*/s', '', $gitignoreContents);
    file_put_contents($gitignoreFile, $gitignoreContents);
  }

  public static function prepareForDocksal()
  {
    // Prepare the settings file for installation
    if (!$fs->exists($root . '/sites/default/settings.local.php')) {
      $fs->copy($root . '/sites/default/example.settings.local.php', $root . '/sites/default/settings.local.php');
    }
    if (!$fs->exists($root . '/sites/default/services.local.yml')) {
      $fs->copy($root . '/sites/default/example.services.local.yml', $root . '/sites/default/services.local.yml');
    }
  }

}
