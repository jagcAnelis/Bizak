<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite63c1864d7aa7bf5c1e822930b3004fc
{
    public static $classMap = array (
        'AdminPsThemeCustoAdvancedController' => __DIR__ . '/../..' . '/controllers/admin/AdminPsThemeCustoAdvanced.php',
        'AdminPsThemeCustoConfigurationController' => __DIR__ . '/../..' . '/controllers/admin/AdminPsThemeCustoConfiguration.php',
        'ThemeCustoRequests' => __DIR__ . '/../..' . '/classes/ThemeCustoRequests.php',
        'ps_themecusto' => __DIR__ . '/../..' . '/ps_themecusto.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite63c1864d7aa7bf5c1e822930b3004fc::$classMap;

        }, null, ClassLoader::class);
    }
}
