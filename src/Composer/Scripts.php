<?php

namespace Sle\PhingTypo3Deployer\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Scripts
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @copyright Steve Lenz
 * @package Sle\PhingTypo3Deployer\Composer
 */
class Scripts
{
    /**
     * @var Composer
     */
    protected static $composer;

    /**
     * @var IOInterface
     */
    protected static $io;

    /**
     * @var Filesystem
     */
    protected static $fs;

    /**
     * @var string
     */
    protected static $rootDir = '';

    /**
     * @var string
     */
    protected static $phingDist = '';

    /**
     * @param Event $event
     */
    protected static function init(Event $event)
    {
        /** @var Composer composer */
        static::$composer = $event->getComposer();
        /** @var IOInterface io */
        static::$io = $event->getIO();
        /** @var Filesystem fs */
        static::$fs = new Filesystem();

        static::$phingDist = dirname(dirname(__DIR__)) . '/src/phing/dist';
        static::$rootDir = dirname(static::$composer->getConfig()->get('vendor-dir'));
    }

    /**
     * @param Event $event
     */
    public static function postInstall(Event $event)
    {
        static::init($event);
        static::copyFiles();
    }

    /**
     * @param Event $event
     */
    public static function postUpdate(Event $event)
    {
        static::init($event);
        static::copyFiles();
    }

    /**
     *
     */
    protected static function copyFiles()
    {
        /**
         * Create/overwrite 'build.xml'
         */
        static::$fs->copy(
            static::$phingDist . '/build.xml',
            static::$rootDir . '/build.xml',
            true
        );

        /**
         * Create '.gitignore' if not exists
         */
        static::copyIfNotExists(
            static::$rootDir . '/.gitignore',
            static::$phingDist . '/_.gitignore'
        );

        /**
         * Create 'build.env.properties' if not exists
         */
        static::copyIfNotExists(
            static::$rootDir . '/build.env.properties',
            static::$phingDist . '/build.env.properties'
        );

        /**
         * Create 'build.hook.xml' if not exists
         */
        static::copyIfNotExists(
            static::$rootDir . '/build.hook.xml',
            static::$phingDist . '/build.hook.xml'
        );

        /**
         * Create 'build.custom.properties' if not exists
         */
        static::copyIfNotExists(
            static::$rootDir . '/build.custom.properties',
            static::$phingDist . '/build.custom.properties'
        );

        /**
         * Create 'typo3' and 'typo3/composer.json' if not exists
         */
        if (false === static::$fs->exists(static::$rootDir . '/typo3')) {
            $typo3Dir = static::$rootDir . '/typo3';
            static::$fs->mkdir($typo3Dir);
            static::$fs->copy(
                static::$phingDist . '/typo3_composer.json',
                $typo3Dir . '/composer.json',
                true
            );
        }
        /**
         * Create 'rsync' and 'rsync/excludes.txt' if not exists
         */
        if (false === static::$fs->exists(static::$rootDir . '/rsync')) {
            $rsyncDir = static::$rootDir . '/rsync';
            static::$fs->mkdir($rsyncDir);
            static::$fs->copy(
                static::$phingDist . '/rsync_excludes.txt',
                $rsyncDir . '/excludes.txt',
                true
            );
        }
    }

    /**
     * @param string $target Target filename with path
     * @param string $source Source filename with path
     */
    private static function copyIfNotExists($target, $source)
    {
        if (false === static::$fs->exists($target)) {
            static::$fs->copy(
                $source,
                $target
            );
        }
    }
}
