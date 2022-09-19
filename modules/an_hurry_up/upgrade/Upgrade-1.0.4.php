<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2020 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
function upgrade_module_1_0_4($object)
{
	$defaulTitlesLeft['en'] = 'Hurry up! Only';
	$defaulTitlesLeft['fr'] = 'Hurry up! Only';
	$defaulTitlesLeft['es'] = 'Hurry up! Only';
	$defaulTitlesLeft['pl'] = 'Pośpiesz się! Tylko';
	$defaulTitlesLeft['it'] = 'Hurry up! Only';
	$defaulTitlesLeft['nl'] = 'Hurry up! Only';
	$defaulTitlesLeft['de'] = 'Hurry up! Only';
	
	$defaulTitlesRight['en'] = 'item(s) left in Stock!';
	$defaulTitlesRight['fr'] = 'item(s) left in Stock!';
	$defaulTitlesRight['es'] = 'item(s) left in Stock!';
	$defaulTitlesRight['pl'] = 'szt. w magazynie!';
	$defaulTitlesRight['it'] = 'item(s) left in Stock!';
	$defaulTitlesRight['nl'] = 'item(s) left in Stock!';
	$defaulTitlesRight['de'] = 'item(s) left in Stock!';
	
	$defaulTitlesNoitems['en'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['fr'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['es'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['pl'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['it'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['nl'] = 'Sorry, no items left.';
	$defaulTitlesNoitems['de'] = 'Sorry, no items left.';		
			
	$languages = Language::getLanguages(false);
	$title_left = [];
	$title_right = [];
	$title_noitems = [];
	foreach ($languages as $lang) {
		$title_left[$lang['id_lang']] = $defaulTitlesLeft[$lang['iso_code']];
		$title_right[$lang['id_lang']] = $defaulTitlesRight[$lang['iso_code']];
		$title_noitems[$lang['id_lang']] = $defaulTitlesNoitems[$lang['iso_code']];

	}
	Configuration::updateValue('an_hurry_up_title_left', $title_left);	
	Configuration::updateValue('an_hurry_up_title_right', $title_right);	
	Configuration::updateValue('an_hurry_up_title_noitems', $title_noitems);
		
	Configuration::updateValue('an_hurry_up_show_line', 1);


    return true;
}
