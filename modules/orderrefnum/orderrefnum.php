<?php
/**
* 2016 Madman
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author    Madman
*  @copyright 2016 Madman
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class OrderRefNum extends Module
{

    public $ref_num;
    public $ps_org_ref;

    /** Construction of module  **/
    public function __construct()
    {
        $this->name = 'orderrefnum';
        $this->tab = 'administration';
        $this->version = '1.6.2';
        $this->author = 'Madman';
        $this->bootstrap = true;
        $this->module_key = 'ac41685e26bf2af985a071e9e39799cf';
        $this->ps_versions_compliancy = array('min' => '1.5.0.13', 'max' => '1.8');
        $this->author_address = '0xa0c50B4a9BBb7353362dcda4557B15c736bc66a3';
        $this->config = array(
            'PS_USE_REF_NUM' => array(
                'value' => 0,
                'configurable' => true,
            ),
            'PS_REF_FORCE_RESET' => array(
                'value' => 0,
                'configurable' => false,
            ),
            'PS_REF_PREFIX_ON' => array(
                'value' => 0,
                'configurable' => true,
            ),
            'PS_REF_PREFIX' => array(
                'value' => 'NL',
                'configurable' => true,
            ),
            'PS_REF_NUM_LENGTH' =>  array(
                'value' => 6,
                'configurable' => true,
            ),
            'PS_REF_NUM' =>  array(
                'value' => 1,
                'configurable' => true,
            ),
            'PS_REF_NUM_INCREASE' =>  array(
                'value' => 1,
                'configurable' => true,
            ),
            'PS_REF_NUM_MIN_INCREASE' =>  array(
                'value' => 1,
                'configurable' => true,
            ),
            'PS_USE_REF_NUM_RANDOM' => array(
                'value' => 0,
                'configurable' => true,
            ),
            'PS_REF_NUM_ALL_MULTI' => array(
                'value' => 0,
                'configurable' => true,
            ),
        );
        $this->ref_num = 1;

        parent::__construct();

        $this->displayName = $this->l('Order Reference Number');
        $this->description = $this->l('Overwrites the default reference
         and allows the shop to use a 000001 as reference');
    }

    public function install()
    {
        if (!parent::install()|| !$this->checkConfig() || !$this->registerHook('actionValidateOrder')) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = '';
        $info_class = 'alert';
        if (!$this->is16()) {
            $info_class = 'info';
        }
        $module_info = '<div class="'.$info_class.' alert-info"> It\'s recomended to move this module
            to the first position in Modules > Positions > actionValidateOrder.
            This should stop any module / PrestaShop from sending the old reference.<br><br>
            If using the multistore feature, and not using prefixes, and think you might some day get the same
            reference on two orders from different shops, they will be linked together.
            I made a separate module to fix this for PrestaShop 1.6.<br>
            <a href="https://www.prestashop.com/forums/topic/454385-free-module-fix-linked-orders/"
             target="_blank">Fix Linked Orders</a>
            </div>';
        if (Tools::isSubmit('submitUpdateConfig')) {
            $output .= $this->updateConfig();
        }

        $output .= $module_info;
        $output .= $this->renderSettingsForm();

        return $output;
    }

    public function getShopID()
    {
        if (Configuration::get('PS_REF_NUM_ALL_MULTI')) {
            return 0;
        } else {
            return $this->context->shop->getContextShopID();
        }
    }

    public function getShopGroupID()
    {
        if (Configuration::get('PS_REF_NUM_ALL_MULTI')) {
            return 0;
        } else {
            return $this->context->shop->getContextShopGroupID();
        }
    }

    /** Module functions  **/
    public function hookActionValidateOrder($params)
    {
        if (Configuration::get(
            'PS_USE_REF_NUM',
            null,
            $this->getShopGroupID(),
            $this->getShopID()
        )) {
            $order = $params['order'];

            if ($this->ps_org_ref == $order->reference) {
                $reference_num = $this->ref_num; // then use stored reference number
                $order->reference = $reference_num;
                $order->update();
                Db::getInstance()->update(
                    'order_payment',
                    array('order_reference' => $reference_num),
                    'order_reference = "'.$this->ps_org_ref.'"'
                );
            } else {
                $this->ps_org_ref = $order->reference;
                $reference_num = $this->generateReferenceNumber(); // create the reference number

                /* Check for unique reference */
                $id_cart = Db::getInstance()->getValue('SELECT `id_cart` FROM `'._DB_PREFIX_.'orders`
                WHERE `reference` = "'.$reference_num.'" AND
                `id_shop_group` = "'.$this->getShopGroupID().'" AND
                `id_shop` = "'.$this->getShopID().'"');
                if ($id_cart && $order->id_cart != $id_cart) {
                    $new_reference_num = $this->generateReferenceNumber(); // create the reference number
                    if ($new_reference_num == $reference_num) {
                        if (Configuration::get(
                            'PS_USE_REF_NUM_RANDOM',
                            null,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        )) {
                            $increase = mt_rand(
                                Configuration::get(
                                    'PS_REF_NUM_MIN_INCREASE',
                                    null,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                ),
                                Configuration::get(
                                    'PS_REF_NUM_INCREASE',
                                    null,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                )
                            ); // create a random number
                        } else {
                            $increase = Configuration::get(
                                'PS_REF_NUM_INCREASE',
                                null,
                                $this->getShopGroupID(),
                                $this->getShopID()
                            );
                        }
                        Configuration::updateValue(
                            'PS_REF_NUM',
                            Configuration::get(
                                'PS_REF_NUM',
                                null,
                                $this->getShopGroupID(),
                                $this->getShopID()
                            ) + $increase,
                            null,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        );
                        $reference_num = $this->generateReferenceNumber(); // create the reference number
                    }
                }

                $this->ref_num = $reference_num; // update stored reference number
                $order->reference = $reference_num;
                $order->update();
                Db::getInstance()->update(
                    'order_payment',
                    array('order_reference' => $reference_num),
                    '`order_reference` = "'.$this->ps_org_ref.'"'
                );

                if (Configuration::get('PS_USE_REF_NUM_RANDOM')) {
                            $increase = mt_rand(
                                Configuration::get(
                                    'PS_REF_NUM_MIN_INCREASE',
                                    null,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                ),
                                Configuration::get(
                                    'PS_REF_NUM_INCREASE',
                                    null,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                )
                            ); // create a random number
                } else {
                    $increase = Configuration::get(
                        'PS_REF_NUM_INCREASE',
                        null,
                        $this->getShopGroupID(),
                        $this->getShopID()
                    );
                }

                /* increase the reference number for next order */
                Configuration::updateValue(
                    'PS_REF_NUM',
                    Configuration::get(
                        'PS_REF_NUM',
                        null,
                        $this->getShopGroupID(),
                        $this->getShopID()
                    ) + $increase,
                    false,
                    $this->getShopGroupID(),
                    $this->getShopID()
                );
            }
        }
    }

    /**
     * Generate a unique reference for orders generated with the same cart id
     * This references, is usefull for check payment
     *
     * @return String
     */
    public function generateReferenceNumber()
    {
        $reference_num = Configuration::get(
            'PS_REF_NUM',
            null,
            $this->getShopGroupID(),
            $this->getShopID()
        );
        $length = Configuration::get(
            'PS_REF_NUM_LENGTH',
            null,
            $this->getShopGroupID(),
            $this->getShopID()
        );
        $reference = sprintf('%0'.$length.'d', $reference_num);
        if (Configuration::get(
            'PS_REF_PREFIX_ON',
            null,
            $this->getShopGroupID(),
            $this->getShopID()
        )) {
            $prefix = Configuration::get(
                'PS_REF_PREFIX',
                null,
                $this->getShopGroupID(),
                $this->getShopID()
            );
            $reference = $prefix.$reference;
        }
        return $reference;
    }

    /** Settings  **/
    public function renderSettingsForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => $this->getFormType(),
                        'label' => $this->l('Use numeric order reference'),
                        'name' => 'PS_USE_REF_NUM',
                        'is_bool' => true,
                        'hint' => $this->l('Change order reference from AEGOGZXRS to 000001.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ),
                        ),
                    ),
                    array(
                        'type' => $this->getFormType(),
                        'label' => $this->l('Use same reference number in all shops'),
                        'name' => 'PS_REF_NUM_ALL_MULTI',
                        'is_bool' => true,
                        'hint' => $this->l('Sets multishops to use the same reference.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Numeric reference length'),
                        'name' => 'PS_REF_NUM_LENGTH',
                        'size' => 5,
                        'hint' => 'Set the length of reference, max length 9',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Set next reference number'),
                        'name' => 'PS_REF_NUM',
                        'size' => 5,
                        'hint' => 'This is the number that will be used for next order',
                    ),
                    array(
                        'type' => $this->getFormType(),
                        'label' => $this->l('Forcefully set lower reference number'),
                        'name' => 'PS_REF_FORCE_RESET',
                        'is_bool' => true,
                        'hint' => $this->l('Use with caution!
                         PrestaShop will automatically link orders with same reference'),
                        'values' => array(
                            array(
                                'id' => 'force_on',
                                'value' => 1,
                                'label' => $this->l('Force')
                            ),
                            array(
                                'id' => 'force_off',
                                'value' => 0,
                                'label' => $this->l('No Force')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number increase'),
                        'name' => 'PS_REF_NUM_INCREASE',
                        'size' => 5,
                        'hint' => 'The number to increase with on each order.
                        If random is used, this will act as maximum step',
                    ),
                    array(
                        'type' => $this->getFormType(),
                        'label' => $this->l('Use random increase step'),
                        'name' => 'PS_USE_REF_NUM_RANDOM',
                        'is_bool' => true,
                        'hint' => $this->l('Randomize the increased step, default step acts as maximum value'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number increase minimum'),
                        'name' => 'PS_REF_NUM_MIN_INCREASE',
                        'size' => 5,
                        'hint' => 'The minimum number to increase on each order, only used when random is used',
                    ),
                    array(
                        'type' => $this->getFormType(),
                        'label' => $this->l('Enable Prefix'),
                        'name' => 'PS_REF_PREFIX_ON',
                        'is_bool' => true,
                        'hint' => $this->l('Add a prefix on every reference'),
                        'values' => array(
                            array(
                                'id' => 'prefix_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'prefix_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Prefix'),
                        'name' => 'PS_REF_PREFIX',
                        'size' => 5,
                        'hint' => 'Prefix of maximum two letters',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )
        );

        /* Default is now 1.6, change for 1.5 */
        if (!$this->is16()) {
            foreach ($fields_form['form']['input'] as &$cfg) {
                $cfg['desc'] = $cfg['hint'];
                unset($cfg['hint']);
                if ($cfg['type'] == 'radio') {
                    $cfg['class'] = 't';
                }
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUpdateConfig';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $fields_value = array();
        foreach ($this->config as $key => $value) {
            if (Configuration::get(
                $key,
                null,
                $this->getShopGroupID(),
                $this->getShopID()
            )) {
                    $fields_value[$key] = Configuration::get(
                        $key,
                        null,
                        $this->getShopGroupID(),
                        $this->getShopID()
                    );
            } else {
                $fields_value[$key] = $value['value'];
            }
        }

        return $fields_value;
    }

    /** Private functions  **/
    private function getFormType()
    {
        if ($this->is16()) {
            return 'switch';
        } else {
            return 'radio';
        }
    }

    private function is16()
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=') >= 1) {
            return true;
        }

        return false;
    }

    private function updateConfig()
    {
        $output = '';
        foreach ($this->config as $key => $values) {
            if ($values['configurable'] == true) {
                $opt = Tools::getValue($key);
                if ($opt != '') {
                    if ($key == 'PS_REF_NUM') {
                        if ($opt > Configuration::get(
                            $key,
                            null,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        )) { // update only if higher
                            Configuration::updateValue(
                                $key,
                                $opt,
                                false,
                                $this->getShopGroupID(),
                                $this->getShopID()
                            );
                        } elseif ($opt < Configuration::get(
                            $key,
                            null,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        )) { // error message if lower
                            if (Tools::getValue('PS_REF_FORCE_RESET')) {
                                $old_ref = Configuration::get(
                                    $key,
                                    null,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                );
                                Configuration::updateValue(
                                    $key,
                                    $opt,
                                    false,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                );
                                $output .= $this->displayError(
                                    $this->l('Order reference was forced to lower.
                                     Use at own risk! Previous number was '). $old_ref
                                );
                            } else {
                                $output .= $this->displayError($this->l('Reference number can not be lower'));
                            }
                        }
                        // This will automatically ignore the config if it's the same.
                    } elseif ($key == 'PS_REF_NUM_LENGTH') {
                        // Check length of reference
                        if ($opt > 9) {
                            $output .= $this->displayError($this->l('Reference to long!'));
                        }
                        if (Configuration::get(
                            'PS_REF_PREFIX_ON',
                            null,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        )) {
                            $len = Tools::strlen(Configuration::get(
                                'PS_REF_PREFIX',
                                null,
                                $this->getShopGroupID(),
                                $this->getShopID()
                            ));
                            $ref_len = 9 - $len;
                            if ($opt > $ref_len) {
                                Configuration::updateValue(
                                    'PS_REF_NUM_LENGTH',
                                    $ref_len,
                                    false,
                                    $this->getShopGroupID(),
                                    $this->getShopID()
                                );
                                $output .= $this->displayError(
                                    $this->l('Reference was to long for this prefix, length was auto corrected')
                                );
                            }
                        } else {
                            Configuration::updateValue(
                                'PS_REF_NUM_LENGTH',
                                $opt,
                                false,
                                $this->getShopGroupID(),
                                $this->getShopID()
                            );
                        }
                    } elseif ($key == 'PS_REF_NUM_MIN_INCREASE' && $opt > Tools::getValue('PS_REF_NUM_INCREASE')) {
                        $output .= $this->displayError($this->l('Minimum number is higher then maximum value'));
                    } elseif ($key == 'PS_REF_PREFIX') {
                        $prefix = Tools::substr($opt, 0, 2);
                        Configuration::updateValue(
                            $key,
                            $prefix,
                            false,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        );
                    } else {
                        Configuration::updateValue(
                            $key,
                            $opt,
                            false,
                            $this->getShopGroupID(),
                            $this->getShopID()
                        );
                    }
                } else {
                    $output .= $this->displayError($key.': '.$this->l('Invaild choice'));
                }
            }
        }

        $output .= $this->displayConfirmation($this->l('Settings updated'));
        return $output;
    }

    private function checkConfig()
    {
        $res = true;
        foreach ($this->config as $key => $values) {
            if (!Configuration::get(
                $key,
                null,
                $this->getShopGroupID(),
                $this->getShopID()
            )) {
                if (!Configuration::updateValue(
                    $key,
                    $values['value'],
                    false,
                    $this->getShopGroupID(),
                    $this->getShopID()
                )) {
                    $res &= false;
                } else {
                    $res &= true;
                }
            }
        }
        return $res;
    }
}
