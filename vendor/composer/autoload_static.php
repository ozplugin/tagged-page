<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2e299aba0667f223a51d949141ebe49f
{
    public static $files = array (
        '5868abb87aad7dcbe4aa5efae964b16d' => __DIR__ . '/../..' . '/src/helpers/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Demo\\TaggedPage\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Demo\\TaggedPage\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2e299aba0667f223a51d949141ebe49f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2e299aba0667f223a51d949141ebe49f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2e299aba0667f223a51d949141ebe49f::$classMap;

        }, null, ClassLoader::class);
    }
}
