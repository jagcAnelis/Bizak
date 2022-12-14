<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * @author    PresTeamShop SAS (Registered Trademark) <info@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *
 * @category  PrestaShop
 * @category  Module
 */

namespace OnePageCheckoutPS\Application\Core;

use OnePageCheckoutPS\Exception\OPCException;

abstract class AbstractStepCheckout
{
    private $parameters;
    private $errors;

    public function setParameters(array $requestParameters)
    {
        $this->parameters = $requestParameters;

        return $this;
    }

    public function getParameters()
    {
        if (is_array($this->parameters)) {
            return $this->parameters;
        }

        return array();
    }

    public function getParameter(string $name)
    {
        if (!array_key_exists($name, $this->getParameters())) {
            throw new OPCException(
                sprintf('The parameter %s has not been sent.', $name),
                OPCException::PARAMETER_NOT_SENT
            );
        }

        return $this->getParameters()[$name];
    }

    public function exitsParameter(string $name)
    {
        if (!array_key_exists($name, $this->getParameters())) {
            return false;
        }

        return true;
    }

    public function addError($errorString)
    {
        $this->errors[] = $errorString;

        return $this;
    }

    public function getErrors()
    {
        if (is_array($this->errors)) {
            return $this->errors;
        }

        return array();
    }

    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    public function getTemplateVars()
    {
        return array();
    }

    abstract public function render();
}
