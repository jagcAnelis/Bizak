<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 */

function upgrade_module_1_1_2()
{
    $sql = array();

    $describe_sql1 = 'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_productcomments';
    $describe_sql2 = 'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_storecomments';
    //Lineagrafica -> Janto Devuelvo el listado de columnas de productcomments
    $products_coments = DB::getInstance()->executeS($describe_sql1);
    $product_comment = false;
    $store_comment = false;
    $change_product_date = false;
    $change_store_date = false;
    //Lineagrafica - Janto -> Compruebo que el estado de las columnas de las tabla,
    //si no existe los ids se crean, si el campo date es tipo int se marca para modificar el tipo
    if ($products_coments) {
        foreach ($products_coments as $comment) {
            if ($comment['Field'] == 'id_lgcomments_products') {
                $product_comment = true;
            }
            if ($comment['Field'] == 'date' && Tools::strtolower($comment['Type']) == 'int(11)') {
                $change_product_date = true;
            }
        }
    }

    $store_comments = DB::getInstance()->executeS($describe_sql2);
    if ($store_comments) {
        foreach ($store_comments as $comment) {
            if ($comment['Field'] == 'id_lgcomments_products') {
                $store_comment = true;
            }
            if ($comment['Field'] == 'date' && Tools::strtolower($comment['Type']) == 'int(11)') {
                $change_store_date = true;
            }
        }
    }

    if (!$product_comment) {
        $sql[] =
        'ALTER TABLE `'._DB_PREFIX_.'lgcomments_productcomments` '.
        'DROP INDEX date; ALTER TABLE `'._DB_PREFIX_.'lgcomments_productcomments` '.
        'ADD `id_lgcomments_products` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ';
    }

    if (!$store_comment) {
        $sql[] =
        'ALTER TABLE `'._DB_PREFIX_.'lgcomments_storecomments` '.
        'DROP INDEX date; ALTER TABLE `'._DB_PREFIX_.'lgcomments_storecomments` '.
        'ADD `id_lgcomments_store` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ';
    }

    //Lineagrafica - Janto -> Si el campo date es tipo int guardo una copia de la tabla para alterar la columnas
    // y transformar los valores de la fecha
    $store_comments = array();
    if ($change_store_date) {
        $query = new DBQuery();
        $query->from('lgcomments_storecomments');
        $store_comments = DB::getInstance()->executeS($query);
        $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'lgcomments_storecomments` MODIFY `date` DATE NOT NULL;';
    }

    $product_comments = array();
    if ($change_product_date) {
        $query = new DBQuery();
        $query->from('lgcomments_productcomments');
        $product_comments = DB::getInstance()->executeS($query);
        $sql[] = 'ALTER TABLE `'._DB_PREFIX_.'lgcomments_productcomments` MODIFY `date` DATE NOT NULL;';
    }

    foreach ($sql as $query) {
        DB::getInstance()->execute($query);
    }

    if ($change_product_date && is_array($product_comments)) {
        foreach ($product_comments as $k => $comment) {
            $product_comments[$k]['date'] = date('Y-m-d H:i:s', $comment['date']);
        }
    }

    if ($change_store_date && is_array($store_comments)) {
        foreach ($store_comments as $k => $comment) {
            $store_comments[$k]['date'] = date('Y-m-d H:i:s', $comment['date']);
        }
    }

    if ($change_store_date && !empty($store_comments)) {
        $drop_sql = 'TRUNCATE TABLE `'._DB_PREFIX_.'lgcomments_storecomments`;';
        DB::getInstance()->execute($drop_sql);
        DB::getInstance()->insert('lgcomments_storecomments', $store_comments);
    }

    if ($change_product_date && !empty($product_comments)) {
        $drop_sql = 'TRUNCATE TABLE `'._DB_PREFIX_.'lgcomments_productcomments`;';
        DB::getInstance()->execute($drop_sql);
        DB::getInstance()->insert('lgcomments_productcomments', $product_comments);
    }

    $module = Module::getInstanceByName('lgcomments');
    $module->registerHook('leftColumn');
    $module->registerHook('productTab');
    $module->registerHook('productTabContent');

    $style = explode('-', Configuration::get('PS_LGCOMMENTS_BGDESIGN', 'customer'));
    $css_config = $module->getExtraRightCSSConfig($style[0]);
    Configuration::updateValue('PS_LGCOMMENTS_CSS_CONF', serialize($css_config));

    @unlink(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'AdminLGCommentsProducts.php'
    );
    @unlink(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'AdminLGCommentsStore.php'
    );
    @unlink(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'views'.
        DIRECTORY_SEPARATOR.'templates'.
        DIRECTORY_SEPARATOR.'admin'.
        DIRECTORY_SEPARATOR.'lg_comments_products'.
        DIRECTORY_SEPARATOR.'products_comments.tpl'
    );
    @unlink(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'views'.
        DIRECTORY_SEPARATOR.'templates'.
        DIRECTORY_SEPARATOR.'admin'.
        DIRECTORY_SEPARATOR.'products_comments.tpl'
    );
    @unlink(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'views'.
        DIRECTORY_SEPARATOR.'templates'.
        DIRECTORY_SEPARATOR.'admin'.
        DIRECTORY_SEPARATOR.'index.php'
    );
    @rmdir(
        _PS_MODULE_DIR_.'lgcomments'.
        DIRECTORY_SEPARATOR.'views'.
        DIRECTORY_SEPARATOR.'templates'.
        DIRECTORY_SEPARATOR.'admin'.
        DIRECTORY_SEPARATOR
    );

    return true;
}
