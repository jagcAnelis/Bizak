<?php

class Ets_crosssellOverride extends Ets_crosssell
{
	public function hookDisplayContentWrapperBottom()
    {
        if(Tools::getValue('controller')=='search')
            return $this->_execHook('search_page');
        return parent::hookDisplayContentWrapperBottom();
    }	
}
