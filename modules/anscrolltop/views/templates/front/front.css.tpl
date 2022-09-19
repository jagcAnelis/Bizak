{*
* 2020 Anvanto
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
*}

#scrolltopbtn {
	border: {$BORDER_WIDTH|intval}px solid {$BORDER_COLOR|escape:'htmlall':'UTF-8'};
	border-radius: {$BORDER_RADIUS|intval}px;
	position: fixed;
	top: {$TOP|escape:'htmlall':'UTF-8'};
	bottom: {$BOTTOM|escape:'htmlall':'UTF-8'};
	left: {$LEFT|escape:'htmlall':'UTF-8'};
	right: {$RIGHT|escape:'htmlall':'UTF-8'};
	opacity: {$OPACITY|escape:'htmlall':'UTF-8'};
	background-color: {$BUTTON_BG|escape:'htmlall':'UTF-8'};
	width: {$BUTTON_WIDTH|intval}px;
	height: {$BUTTON_HEIGHT|intval}px;
	
	cursor: pointer;
	z-index: 9999;

  
  justify-content: center;
  align-items: center;
  
	display: none;

	-webkit-transition: opacity 0.5s linear;
	-moz-transition: opacity 0.5s linear;
	-o-transition: opacity 0.5s linear;
	transition: opacity 0.5s linear;
}

#scrolltopbtn svg {
}

#scrolltopbtn:hover { opacity: 1 }