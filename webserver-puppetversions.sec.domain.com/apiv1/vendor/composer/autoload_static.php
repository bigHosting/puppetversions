<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68c4aadf86002dc8af6f8ad4baabc18c
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 
            array (
                0 => __DIR__ . '/..' . '/psr/log',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit68c4aadf86002dc8af6f8ad4baabc18c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit68c4aadf86002dc8af6f8ad4baabc18c::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit68c4aadf86002dc8af6f8ad4baabc18c::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
