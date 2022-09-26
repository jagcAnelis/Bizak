<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd7f7bf6dd30706f3407ee555d624e3cb
{
    public static $classMap = array (
        'Ps_Checkpayment' => __DIR__ . '/../..' . '/ps_checkpayment.php',
        'Ps_CheckpaymentPaymentModuleFrontController' => __DIR__ . '/../..' . '/controllers/front/payment.php',
        'Ps_CheckpaymentValidationModuleFrontController' => __DIR__ . '/../..' . '/controllers/front/validation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitd7f7bf6dd30706f3407ee555d624e3cb::$classMap;

        }, null, ClassLoader::class);
    }
}