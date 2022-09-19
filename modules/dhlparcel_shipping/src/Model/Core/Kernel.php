<?php

namespace DHLParcel\Shipping\Model\Core;

use AppKernel;
use DHLParcel\Shipping\Model\Twig\DHLParcelExtension;
use Twig_Environment;

class Kernel extends SingletonAbstract
{
    /** @var AppKernel */
    public $kernel;
    /** @var Twig_Environment */
    private $twig;

    public static function instance()
    {
        $instance = parent::instance();
        $instance->init();
        return $instance;
    }

    protected function init()
    {
        if (!$this->kernel) {
            global $kernel;

            if (isset($kernel)) {
                $this->kernel = $kernel;
            } else {
                require_once _PS_ROOT_DIR_ . '/app/AppKernel.php';
                $env = _PS_MODE_DEV_ ? 'dev' : 'prod';
                $debug = _PS_MODE_DEV_ ? true : false;
                $this->kernel = $kernel = new AppKernel($env, $debug);
                $this->kernel->boot();
            }
        }
    }

    /**
     * @return object|Twig_Environment
     * adding a singular twig
     */
    protected function getTwig()
    {
        if (!$this->twig) {
            $this->twig = $this->kernel->getContainer()->get('twig');
            $this->addTwigFunctions($this->twig);
        }
        return $this->twig;
    }

    /**
     * @param Twig_Environment $twig
     * adds extra function to fix pre 1.7.5 installations to allow dynamic importing
     * 1.7.4 doesn't have @Modules
     *
     * in future tense are more twig functions are needed they should be moved to a twig extension instead
     * view link bellow for more info
     * https://twig.symfony.com/doc/1.x/advanced.html#id2
     */
    protected function addTwigFunctions($twig)
    {
        $function = new \Twig\TwigFunction('addBase', function ($path) {
            if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
                $template = '@Modules/dhlparcel_shipping/views/templates/' . $path;
            } elseif (version_compare(_PS_VERSION_, '1.7.0', '>=') && version_compare(_PS_VERSION_, '1.7.5', '<')) {
                $template = _PS_MODULE_DIR_ . 'dhlparcel_shipping/views/templates/' . $path;
            } else {
                $template = '';
            }
            return $template;
        });
        $twig->addFunction($function);
    }

    public function render($path, $data = [])
    {
        if (!$this->kernel) {
            return '';
        }
        if (version_compare(_PS_VERSION_, '1.7.5', '>=') && version_compare(_PS_VERSION_, '1.8.0', '<')) {
            $template = '@Modules/dhlparcel_shipping/views/templates/' . $path . '.twig';
        } elseif (version_compare(_PS_VERSION_, '1.7.0', '>=') && version_compare(_PS_VERSION_, '1.7.5', '<')) {
            $template = _PS_MODULE_DIR_ . 'dhlparcel_shipping/views/templates/' . $path . '.twig';
        } else {
            return false;
        }

        return $this->getTwig()->render($template, $data);
    }
}
