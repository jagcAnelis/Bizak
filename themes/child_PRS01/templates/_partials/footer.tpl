{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 <div class="footer-top">
<div class="container">
  <div class="row">
    {block name='hook_footer_before'}
      {hook h='displayFooterBefore'}
    {/block}
  </div>
  </div>
</div>
<div class="footer-container">
  <div class="container">
    <div class="row">


      <div class="col-md-3 linklist links">
        <div class="row">
          <div> 
            <h3 class="h3 hidden-sm-down">LEGAL</h3>
            <div id="footerDropdown" class="title clearfix hidden-md-up" data-target="#footer_sub_menu_legal" data-toggle="collapse">
              <hr class="hrFooter1 tb mb">
              <span id="footerDropdown" class="h3">LEGAL</span>
              <span class="float-xs-right">
                <span class="navbar-toggler collapse-icons">
                  <i class="material-icons add">&#xE313;</i>
                  <i class="material-icons remove">&#xE316;</i>
                </span>
              </span>
            </div>

              <ul id="footer_sub_menu_legal" class="collapse tb mb">
              
                <li><a class="legal-list" title="Política de privacidad" href="/content/politica-de-privacidad?rewrite=politica-de-privacidad">Política de privacidad</a></li>
                <li><a class="legal-list" title="Terminos y condiciones" href="/content/terminos-y-condiciones-de-uso?rewrite=terminos-y-condiciones-de-uso">Terminos y condiciones</a></li>
                <li><a class="legal-list" title="Aviso Legal" href="/content/aviso-legal?rewrite=aviso-legal">Aviso Legal</a></li>
                <li><a class="legal-list" title="Política de cookies" href="/content/politica-de-cookies?rewrite=politica-de-cookies">Política de cookies</a></li>
              </ul>
          </div>
        </div>
      </div>

      <div class="col-md-4 linklist links">
        <div class="row">
          <div >
            <h3 class="h3 hidden-sm-down">MI CUENTA</h3>
            <div id="footerDropdown" class="title clearfix hidden-md-up" data-target="#footer_sub_menu_myaccount" data-toggle="collapse">
              <hr class="hrFooter1 tb mb">
              <span id="footerDropdown" class="h3">MI CUENTA</span>
              <span class="float-xs-right">
                <span class="navbar-toggler collapse-icons">
                  <i class="material-icons add">&#xE313;</i>
                  <i class="material-icons remove">&#xE316;</i>
                </span>
              </span>
            </div>

              <ul id="footer_sub_menu_myaccount" class="collapse tb mb">
              
                <li><a class="myaccount-list" title="Información Personal" href="/datos-personales">Información Personal</a></li>
                <li><a class="myaccount-list" title="Devoluciones de mercancía" href="/seguimiento-pedido">Devoluciones de mercancía</a></li>
                <li><a class="myaccount-list" title="Pedidos" href="/historial-compra">Pedidos</a></li>
                <li><a class="myaccount-list" title="Mi lista de los deseos" href="/module/ttproductwishlist/mywishlist">Mi lista de deseos</a></li>
              </ul>
          </div>
        </div>
      </div>

      <div class="col-md-5 linklist links">
        <div class="row">
          <div >
            <h3 class="h3 hidden-sm-down">CONTACTO</h3>
            <div id="footerDropdown" class="title clearfix hidden-md-up" data-target="#footer_sub_menu_contact" data-toggle="collapse">
              <hr class="hrFooter1 tb mb">
              <span id="footerDropdown" class="h3">CONTACTO</span>
              <span class="float-xs-right">
                <span class="navbar-toggler collapse-icons">
                  <i class="material-icons add">&#xE313;</i>
                  <i class="material-icons remove">&#xE316;</i>
                </span>
              </span>
            </div>

              <ul id="footer_sub_menu_contact" class="collapse tb mb">
              
                <li><span class="contact-list" title="Información Personal">944 341 490</span></li>
                <li><span class="contact-list" title="Devoluciones de mercancía">Camino de Ibarsusi, S/N</span></li>
                <li><span class="contact-list" title="Pedidos">48004 - Bilbao, Bizkaia, España</span></li>
                <li><a class="contact-list" id="footer-mail" title="Mi lista de los deseos" href="mailto:shop@bizak.es">shop@bizak.es</a></li>
                
              </ul>
              
              <div id="footerDropdown" class="title clearfix hidden-md-up">
                <hr class="hrFooter1 tb mb">
              </div>
          </div>
          
        </div>
      </div>
      
      
      <div class="footerImg tb mb">
        <a class="_blank" href="https://www.bizakshop.com" target="_blank">
          <img  src="../../../../../../../../img/f/logo/tagline.png">
        </a>
      </div>
      <div class="rightDivFooter tb mb">
        {block name='copyright_link'}
            <a class="_blank tb mb" id="copyFooter" href="#" target="_blank">
              Bizak, S.A. © 2020 <br/>
              Todos los derechos reservados.
            </a>
          {/block}
      </div>
    </div>

    <hr class="hrFooter tb mb">

    <div class="col-md-6 linklist links">
      <div class="row">
        <hr class="hrFooter1 tb mb">
      </div>
    </div>

    <div class="row">
      {block name='hook_footer_after'}
        {hook h='displayFooterAfter'}
      {/block}
      
      <div class="bottomFooterImg tb mb">
        <div class="bottomFooterImages tb mb">
          <a class="_blank">
            <img  src="../../../../../../../../img/f/paypal.svg"/>
          </a>
        </div>
        <div class="bottomFooterImages tb mb">
          <a class="_blank">
            <img  src="../../../../../../../../img/f/mastergard.svg"/>
          </a>
        </div>
        <div class="bottomFooterImages tb mb">
          <a class="_blank">
            <img height="24" src="/themes/child_PRS01/assets/img/red/visa.png"/>
          </a>
        </div>
      </div>
    </div>
    
  </div>
</div>

<a href="#" id="goToTop" title="Back to top"> <i class="material-icons arrow-up">&#xE316;</i></a>
