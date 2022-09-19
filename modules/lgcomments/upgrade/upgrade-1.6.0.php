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

function upgrade_module_1_6_0()
{
    $columns = array(
        array(
            'table'    => 'lgcomments_orders',
            'old_name' => 'date_email',
            'new_name' => 'date_email',
            'params'   => 'DATETIME NOT NULL',
            'after'    => 'AFTER `sent`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'id_lgcomments_productcomments',
            'new_name' => 'id_productcomment',
            'params'   => 'INT(11) NOT NULL AUTO_INCREMENT',
            'after'    => 'FIRST',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'id_product',
            'new_name' => 'id_product',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_productcomments`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => '',
            'new_name' => 'id_product_attribute',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_product`',
            'action'   => 'ADD',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'id_customer',
            'new_name' => 'id_customer',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_product_attribute`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'id_lang',
            'new_name' => 'id_lang',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_customer`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'stars',
            'new_name' => 'stars',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_lang`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => '',
            'new_name' => 'nick',
            'params'   => 'DATETIME NOT NULL',
            'after'    => 'AFTER `stars`',
            'action'   => 'ADD',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'title',
            'new_name' => 'title',
            'params'   => 'VARCHAR(255)',
            'after'    => 'AFTER `nick`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'comment',
            'new_name' => 'comment',
            'params'   => 'TEXT',
            'after'    => 'AFTER `title`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'answer',
            'new_name' => 'answer',
            'params'   => 'TEXT',
            'after'    => 'AFTER `comment`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'active',
            'new_name' => 'active',
            'params'   => 'TINYINT(1) NOT NULL',
            'after'    => 'AFTER `answer`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'position',
            'new_name' => 'position',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `active`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_productcomments',
            'old_name' => 'date',
            'new_name' => 'date',
            'params'   => 'DATETIME NOT NULL',
            'after'    => 'AFTER `position`',
            'action'   => 'CHANGE COLUMN',
        ),


        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'id_lgcomments_storecomments',
            'new_name' => 'id_storecomments',
            'params'   => 'INT(11) NOT NULL AUTO_INCREMENT',
            'after'    => 'FIRST',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'id_order',
            'new_name' => 'id_order',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_storecomments`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'id_customer',
            'new_name' => 'id_customer',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_order`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'id_lang',
            'new_name' => 'id_lang',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_customer`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'stars',
            'new_name' => 'stars',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `id_lang`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => '',
            'new_name' => 'nick',
            'params'   => 'DATETIME NOT NULL',
            'after'    => 'AFTER `stars`',
            'action'   => 'ADD',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'title',
            'new_name' => 'title',
            'params'   => 'VARCHAR(255)',
            'after'    => 'AFTER `nick`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'comment',
            'new_name' => 'comment',
            'params'   => 'TEXT',
            'after'    => 'AFTER `title`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'answer',
            'new_name' => 'answer',
            'params'   => 'TEXT',
            'after'    => 'AFTER `comment`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'active',
            'new_name' => 'active',
            'params'   => 'TINYINT(1) NOT NULL',
            'after'    => 'AFTER `answer`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'position',
            'new_name' => 'position',
            'params'   => 'INT(11) NOT NULL',
            'after'    => 'AFTER `active`',
            'action'   => 'CHANGE COLUMN',
        ),
        array(
            'table'    => 'lgcomments_storecomments',
            'old_name' => 'date',
            'new_name' => 'date',
            'params'   => 'DATETIME NOT NULL',
            'after'    => 'AFTER `position`',
            'action'   => 'CHANGE COLUMN',
        ),
    );

    $upgraded = true;
    foreach ($columns as $column) {
        $upgraded &= alterColumn(
            $column['table'],
            $column['new_name'],
            $column['params'],
            $column['after'],
            $column['action'],
            $column['old_name']
        );
    }

    return true; //$upgraded;
}

function alterColumn($table, $new_name, $parameters, $after, $action, $old_name = '')
{
    $db        = Db::getInstance();
    $db->execute('SHOW COLUMNS FROM `' . _DB_PREFIX_ . $table.'` LIKE "'.$new_name.'"');
    $exist_new = ($db->numRows())?true:false;
    $exist_old = false;

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . $table.'` ';
    if ($old_name != '' && $action == 'CHANGE COLUMN') {
        $db->execute('SHOW COLUMNS FROM `' . _DB_PREFIX_ . $table.'` LIKE "'.$old_name.'"');
        $exist_old = ($db->numRows())?true:false;
    }
    if (!$exist_new && $action == 'ADD') {
        $sql .= $action.' `'.$new_name.'` '.$parameters.' '.$after;
        return $db->execute($sql);
    } elseif ($exist_old && !$exist_new && $action == 'CHANGE COLUMN') {
        $sql .= $action.' `'.$old_name.'` `'.$new_name.'` '.$parameters.' '.$after;
        return $db->execute($sql);
    }
    return false;
}
