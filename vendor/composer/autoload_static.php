<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb920f4c400bf5e3b6fc82ebf66440344
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb920f4c400bf5e3b6fc82ebf66440344::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb920f4c400bf5e3b6fc82ebf66440344::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
