<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitca15ac0faa89f2a3147af6117769b135
{
    public static $classMap = array (
        'dashtrends' => __DIR__ . '/../..' . '/dashtrends.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitca15ac0faa89f2a3147af6117769b135::$classMap;

        }, null, ClassLoader::class);
    }
}