<?php

class AdminFeaturesController extends AdminFeaturesControllerCore
{

    public function __construct()
    {
        $this->table = 'feature';
        $this->className = 'Feature';
        $this->list_id = 'feature';
        $this->identifier = 'id_feature';
        $this->lang = true;

        parent::__construct();

        $this->fields_list = array(
            'id_feature' => array(
                'title' => $this->trans('ID', array(), 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'name' => array(
                'title' => $this->trans('Name', array(), 'Admin.Global'),
                'width' => 'auto',
                'filter_key' => 'b!name',
            ),
            'html_data' => array(
                'title' => $this->trans('Page content', array(), 'Admin.Design.Feature'),
                'width' => 'auto',
            ),
            'value' => array(
                'title' => $this->trans('Values', array(), 'Admin.Global'),
                'orderby' => false,
                'search' => false,
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'position' => array(
                'title' => $this->trans('Position', array(), 'Admin.Global'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'position' => 'position',
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Actions'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Warning'),
            ),
        );
    }

    public function initFormFeatureValue()
    {
        $this->setTypeValue();

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->trans('Feature value', array(), 'Admin.Catalog.Feature'),
                'icon' => 'icon-info-sign',
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans('Feature', array(), 'Admin.Catalog.Feature'),
                    'name' => 'id_feature',
                    'options' => array(
                        'query' => Feature::getFeatures($this->context->language->id),
                        'id' => 'id_feature',
                        'name' => 'name',
                    ),
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Value', array(), 'Admin.Global'),
                    'name' => 'value',
                    'lang' => true,
                    'size' => 33,
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->trans('Page content', array(), 'Admin.Design.Feature'),
                    'name' => 'html_data',
                    'autoload_rte' => true,
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->trans('Save then add another value', array(), 'Admin.Catalog.Feature'),
                    'name' => 'submitAdd' . $this->table . 'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save',
                ),
            ),
        );

        $this->fields_value['id_feature'] = (int) Tools::getValue('id_feature');

        // Create Object FeatureValue
        $feature_value = new FeatureValue(Tools::getValue('id_feature_value'));

        $this->tpl_vars = array(
            'feature_value' => $feature_value,
        );

        $this->getlanguages();
        $helper = new HelperForm();
        $helper->show_cancel_button = true;

        $back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex . '&token=' . $this->token;
        }
        if (!Validate::isCleanHtml($back)) {
            die(Tools::displayError());
        }

        $helper->back_url = $back;
        $helper->currentIndex = self::$currentIndex;
        $helper->token = $this->token;
        $helper->table = $this->table;
        $helper->identifier = $this->identifier;
        $helper->override_folder = 'feature_value/';
        $helper->id = $feature_value->id;
        $helper->toolbar_scroll = false;
        $helper->tpl_vars = $this->tpl_vars;
        $helper->languages = $this->_languages;
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->fields_value = $this->getFieldsValue($feature_value);
        $helper->toolbar_btn = $this->toolbar_btn;
        $helper->title = $this->trans('Add a new feature value', array(), 'Admin.Catalog.Feature');
        $this->content .= $helper->generateForm($this->fields_form);
    }

    public function renderForm()
    {
        $this->toolbar_title = $this->trans('Add a new feature', array(), 'Admin.Catalog.Feature');
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Feature', array(), 'Admin.Catalog.Feature'),
                'icon' => 'icon-info-sign',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Name', array(), 'Admin.Global'),
                    'name' => 'name',
                    'lang' => true,
                    'size' => 33,
                    'hint' => $this->trans('Invalid characters:', array(), 'Admin.Notifications.Info') . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => '<hr><strong>html:</strong> for writing free html like this <span class="label label-danger">i\'m a label</span> <span class="badge badge-info">i\'m a badge</span> <button type="button" class="btn btn-default">i\'m a button</button><hr>',
                ),

            ),
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }

        $this->fields_form['submit'] = array(
            'title' => $this->trans('Save', array(), 'Admin.Actions'),
        );

        return AdminController::renderForm();
    }
}
