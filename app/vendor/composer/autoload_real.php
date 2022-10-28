<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit20d80f0e28ec5b004565ba30f1795308
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit20d80f0e28ec5b004565ba30f1795308', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit20d80f0e28ec5b004565ba30f1795308', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit20d80f0e28ec5b004565ba30f1795308::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
