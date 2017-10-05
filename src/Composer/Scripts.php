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
        $fs = new Filesystem();

        /**
         * Create/overwrite 'build.xml'
         */
        $fs->copy(
            static::$phingDist . '/build.xml',
            static::$rootDir . '/build.xml',
            true
        );

        /**
         * Create '.gitignore' if not exist
         */
        $fs->copy(
            static::$phingDist . '/_.gitignore',
            static::$rootDir . '/.gitignore',
            false
        );

        /**
         * Create 'build.env.properties' if not exist
         */
        $fs->copy(
            static::$phingDist . '/build.env.properties',
            static::$rootDir . '/build.env.properties',
            false
        );

        /**
         * Create 'build.hook.xml' if not exist
         */
        $fs->copy(
            static::$phingDist . '/build.hook.xml',
            static::$rootDir . '/build.hook.xml',
            false
        );

        /**
         * Create 'build.custom.properties' if not exist
         */
        $fs->copy(
            static::$phingDist . '/build.custom.properties',
            static::$rootDir . '/build.custom.properties',
            false
        );

        /**
         * Create 'typo3' if not exist
         */
        if (false === $fs->exists(static::$rootDir . '/typo3')) {
            $fs->mkdir(
                static::$rootDir . '/typo3'
            );
        }
    }

}
