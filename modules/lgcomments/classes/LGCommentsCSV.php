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

require_once(
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGProductComment.php'
);
require_once(
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGStoreComment.php'
);

class LGCommentsCSV
{
    public static function importProductComments($module)
    {
        $link        = new Link();
        $debug       = array();
        $html        = '';
        $separator1  = (int)Tools::getValue('separator1', 1);
        $sp          = self::getSeparator($separator1);
        $encoding1 = (int)Tools::getValue('encoding1', 1);
        if (is_uploaded_file($_FILES['csv1']['tmp_name'])) {
            $type = explode(".", $_FILES['csv1']['name']);
            if (Tools::strtolower(end($type)) == 'csv') {
                if (move_uploaded_file(
                    $_FILES['csv1']['tmp_name'],
                    dirname(dirname(__FILE__)) . '/csv/' . $_FILES['csv1']['name']
                )) {
                    $lineas_procesadas = 0;

                    $archivo = $_FILES['csv1']['name'];
                    $fp      = fopen(dirname(dirname(__FILE__)) . '/csv/' . $archivo, 'r');
                    while (($datos = fgetcsv($fp, 1000, '' . $sp . '')) !== false) {
                        $datos[0]    = str_replace('/', '-', $datos[0]);
                        $date        = strtotime($datos[0]);

                        $date        = date('Y-m-d H:i:s', $date);
                        $csv_comment = $datos[4];
                        $csv_title   = $datos[8];
                        $csv_answer  = $datos[9];
                        $nick        = pSQL(((isset($datos[10]) && !empty($datos[10]))?$datos[10]:''));
                        if ($encoding1 == 2) {
                            $csv_comment = mb_convert_encoding($datos[4], 'UTF-8', 'auto');
                            $csv_title   = mb_convert_encoding($datos[8], 'UTF-8', 'auto');
                            $csv_answer  = mb_convert_encoding($datos[9], 'UTF-8', 'auto');
                            $nick        = (isset($datos[10])
                                && (!empty(pSQL(mb_convert_encoding($datos[10], 'UTF-8', 'auto')))))
                                ? mb_convert_encoding($datos[10], 'UTF-8', 'auto')
                                : '';
                        }

                        $data = array(
                            'date'        => $date,
                            'id_customer' => (int)$datos[1],
                            'id_product'  => (int)$datos[2],
                            'stars'       => (int)$datos[3],
                            'comment'     => pSQL(stripslashes($csv_comment), true),
                            'id_lang'     => (int)$datos[5],
                            'active'      => (int)$datos[6],
                            'position'    => (int)$datos[7],
                            'title'       => pSQL(stripslashes($csv_title), true),
                            'answer'      => pSQL(stripslashes($csv_answer), true),
                            'nick'        => pSQL(stripslashes($nick), true),
                        );

                        $aux = array(
                            'data' => $data
                        );
                        $lineas_procesadas++;

                        // If we don't have a value for nick, we will try to fill
                        // it using the selected option by customer.
                        if (empty(trim($nick)) || trim($nick) == '') {
                            if ((int)Tools::getValue('LGCOMMENTS_NICK_OPTIONS', 0) == 2) {
                                $nick2 = trim(pSQL(Tools::getValue('LGCOMMENTS_FORCED_NICK', '')));
                                if (!empty($nick2)) {
                                    $data['nick'] = pSQL($nick2);
                                }
                            } elseif ((int)Tools::getValue('LGCOMMENTS_NICK_OPTIONS', 0) == 1) {
                                $id_customer = ((isset($datos[1]))?(int)$datos[1]:0);
                                $customer    = new Customer($id_customer);
                                if (Validate::isLoadedObject($customer)) {
                                    $first_letter = Tools::substr($customer->firstname, 0, 1);
                                    $data['nick'] = pSQL($first_letter . '. ' . $customer->lastname);
                                }
                            }
                        }

                        try {
                            $result = Db::getInstance()->insert(
                                LGProductComment::$definition['table'],
                                $data,
                                false,
                                false
                            );
                            $aux['result'] = $result?'success':'error';
                        } catch (Exception $e) {
                            $aux['error'] = array(
                                'code' => $e->getCode(),
                                'message' => $e->getMessage(),
                            );
                        }
                        $data['lines'][] = $aux;
                    }
                    fclose($fp);

                    $debug['lines processed'] = $lineas_procesadas;
                    if ($_SERVER['REMOTE_ADDR'] == '80.59.14.111') {
                        $html .= "<pre>".json_encode($debug, JSON_PRETTY_PRINT)."</pre>";
                    }
                    $m = $module->l('Click here to manage your product reviews');
                    $html .= $module->displayConfirmation(
                        $module->l('The comments have been successfully added') .
                        '&nbsp;'.
                        $module->getAnchor(
                            array(
                                'lgcomments_warning_link_href' => $link->getAdminlink('AdminLGCommentsProducts'),
                                'lgcomments_warning_link_target'  => '_blank',
                                'lgcomments_warning_link_message' => $m,
                            )
                        )
                    );
                } else {
                    $html .= $module->displayError(
                        $module->l('Error moving temporal file to final destination.')
                    );
                }
            } else {
                $html .= $module->displayError(
                    $module->l('The format of the file is not valid, it must be saved in ".csv" format.')
                );
            }
        } else {
            $html .= $module->displayError($module->l('An error occurred while uploading the CSV file'));
        }

        return $html;
    }

    public static function exportProductComments($module)
    {
        $html         = '';
        $separator1   = (int)Tools::getValue('separator1', 1);
        $sp           = self::getSeparator($separator1);
        $ln           = "\n";
        $prodComments = LGProductComment::getAllProductComments();

        $fp           = fopen(_PS_ROOT_DIR_ . '/modules/' . $module->name . '/csv/save_products.csv', 'w');
        fwrite($fp, "\xEF\xBB\xBF");
        foreach ($prodComments as $prodComment) {
            $comment = str_replace(array('"'), array('\\"'), $prodComment['comment']);
            $comment = preg_replace(
                "/\r\n|\r|\n/",
                '<br>',
                $comment
            );
            $title = str_replace(array('"'), array('\\"'), $prodComment['title']);
            $title = preg_replace(
                "/\r\n|\r|\n/",
                ' ',
                $title
            );
            $answer = str_replace(array('"'), array('\\"'), $prodComment['answer']);
            $answer = preg_replace(
                "/\r\n|\r|\n/",
                '<br>',
                $answer
            );
            fwrite(
                $fp,
                '"'.$prodComment['date'].'"'.$sp.
                '"'.(int)$prodComment['id_customer'].'"'.$sp.
                '"'.(int)$prodComment['id_product'].'"'.$sp.
                '"'.(int)$prodComment['stars'].'"'.$sp.
                '"'.$comment.'"'.$sp.
                '"'.(int)$prodComment['id_lang'].'"'.$sp.
                '"'.(int)$prodComment['active'].'"'.$sp.
                '"'.(int)$prodComment['position'].'"'.$sp.
                '"'.$title.'"'.$sp.
                '"'.$answer.'"'.$sp.
                '"'.(empty($prodComment['nick']) ? '' : $prodComment['nick']).'"'.$ln
            );
        }
        fclose($fp);

        if ($prodComments != false) {
            $html .= $module->displayConfirmation(
                $module->l('The CSV file has been successfully generated,') . '&nbsp;'.
                $module->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => '../modules/' . $module->name . '/csv/save_products.csv',
                        'lgcomments_warning_link_message' => $module->l('click here to download it'),
                    )
                )
            );
        } else {
            $html .= $module->displayError($module->l('There are no product comments to export'));
        }

        return $html;
    }

    public static function importStorecomments($module)
    {
        $link       = new Link();
        $html       = '';
        $separator2 = (int)Tools::getValue('separator2', 1);
        $sp         = self::getSeparator($separator2);
        $encoding2  = (int)Tools::getValue('encoding2', 1);

        if (is_uploaded_file($_FILES['csv2']['tmp_name'])) {
            $type = explode(".", $_FILES['csv2']['name']);
            if (Tools::strtolower(end($type)) == 'csv') {
                if (move_uploaded_file(
                    $_FILES['csv2']['tmp_name'],
                    dirname(dirname(__FILE__)) . '/csv/' . $_FILES['csv2']['name']
                )) {
                    $archivo = $_FILES['csv2']['name'];
                    $fp = fopen(dirname(dirname(__FILE__)) . '/csv/' . $archivo, 'r');
                    while (($datos = fgetcsv($fp, 1000, '' . $sp . '')) !== false) {
                        $datos[0]    = str_replace('/', '-', $datos[0]);
                        $date        = strtotime($datos[0]);
                        $date        = date('Y-m-d H:i:s', $date);
                        $csv_comment = $datos[4];
                        $csv_title   = $datos[8];
                        $csv_answer  = $datos[9];
                        $nick        = pSQL(((isset($datos[10]) && !empty($datos[10]))?$datos[10]:''));
                        if ($encoding2 == 2) {
                            $csv_comment = mb_convert_encoding($datos[4], 'UTF-8', 'auto');
                            $csv_title   = mb_convert_encoding($datos[8], 'UTF-8', 'auto');
                            $csv_answer  = mb_convert_encoding($datos[9], 'UTF-8', 'auto');
                            $nick        = (isset($datos[10])
                                && (!empty(pSQL(mb_convert_encoding($datos[10], 'UTF-8', 'auto')))))
                                ? mb_convert_encoding($datos[10], 'UTF-8', 'auto')
                                : '';
                        }

                        $data = array(
                            'date'        => $date,
                            'id_customer' => (int)$datos[1],
                            'id_order'    => (int)$datos[2],
                            'stars'       => (int)$datos[3],
                            'comment'     => pSQL(stripslashes($csv_comment), true),
                            'id_lang'     => (int)$datos[5],
                            'active'      => (int)$datos[6],
                            'position'    => (int)$datos[7],
                            'title'       => pSQL(stripslashes($csv_title), true),
                            'answer'      => pSQL(stripslashes($csv_answer), true),
                            'nick'        => pSQL(stripslashes($nick), true),
                        );

                        // If we don't have a value for nick, we will try to fill
                        // it using the selected option by customer.
                        if (empty(trim($nick)) || trim($nick) == '') {
                            if ((int)Tools::getValue('LGCOMMENTS_NICK_OPTIONS_STORE', 0) == 2) {
                                $nick2 = trim(pSQL(Tools::getValue('LGCOMMENTS_FORCED_NICK_STORE', '')));
                                if (!empty($nick2)) {
                                    $data['nick'] = $nick2;
                                }
                            } elseif ((int)Tools::getValue('LGCOMMENTS_NICK_OPTIONS_STORE', 0) == 1) {
                                $id_customer = ((isset($datos[1]))?(int)$datos[1]:0);
                                $customer    = new Customer($id_customer);
                                if (Validate::isLoadedObject($customer)) {
                                    $first_letter = Tools::substr($customer->firstname, 0, 1);
                                    $data['nick'] = $first_letter . '. ' . $customer->lastname;
                                }
                            }
                        }

                        Db::getInstance()->insert(LGStoreComment::$definition['table'], $data, false, false);
                    }
                    fclose($fp);
                    $m = $module->l('Click here to manage your store reviews');
                    $html .= $module->displayConfirmation(
                        $module->l('The comments have been successfully added') . '.&nbsp;'.
                        $module->getAnchor(
                            array(
                                'lgcomments_warning_link_href'    => $link->getAdminlink('AdminLGCommentsStore'),
                                'lgcomments_warning_link_target'  => '_blank',
                                'lgcomments_warning_link_message' => $m,
                            )
                        )
                    );
                }
            } else {
                $html .= $module->displayError(
                    $module->l('The format of the file is not valid, it must be saved in ".csv" format.')
                );
            }
        } else {
            $html .= $module->displayError($module->l('An error occurred while uploading the CSV file'));
        }

        return $html;
    }

    public static function exportStoreComments($module)
    {
        $html       = '';
        $separator2 = (int)Tools::getValue('separator2', 1);
        $sp         = self::getSeparator($separator2);

        $ln = "\n";
        $fp = fopen(_PS_ROOT_DIR_ . '/modules/' . $module->name . '/csv/save_store.csv', 'w');
        $storeComments = LGStoreComment::getAllStoreComments();
        fwrite($fp, "\xEF\xBB\xBF");
        foreach ($storeComments as $storeComment) {
            $comment = str_replace(array('"'), array('\\"'), $storeComment['comment']);
            $comment = preg_replace(
                "/\r\n|\r|\n/",
                '<br>',
                $comment
            );
            $title = str_replace(array('"'), array('\\"'), $storeComment['title']);
            $title = preg_replace(
                "/\r\n|\r|\n/",
                ' ',
                $title
            );
            $answer = str_replace(array('"'), array('\\"'), $storeComment['answer']);
            $answer = preg_replace(
                "/\r\n|\r|\n/",
                '<br>',
                $answer
            );
            fwrite(
                $fp,
                '"'.$storeComment['date'].'"'.$sp.
                '"'.(int)$storeComment['id_customer'].'"'.$sp.
                '"'.(int)$storeComment['id_order'].'"'.$sp.
                '"'.(int)$storeComment['stars'].'"'.$sp.
                '"'.$comment.'"'.$sp.
                '"'.(int)$sp.$storeComment['id_lang'].'"'.$sp.
                '"'.(int)$storeComment['active'].'"'.$sp.
                '"'.(int)$storeComment['position'].'"'.$sp.
                '"'.$title.'"'.$sp.
                '"'.$answer.'"'.$sp.
                '"'.(empty($storeComment['nick']) ? '' : $storeComment['nick']).'"'.$ln
            );
        }
        fclose($fp);
        if ($storeComments != false) {
            $html .= $module->displayConfirmation(
                $module->l('The CSV file has been successfully generated,') . '&nbsp;'
                .$module->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => '../modules/' . $module->name . '/csv/save_store.csv',
                        'lgcomments_warning_link_message' => $module->l('click here to download it'),
                    )
                )
            );
        } else {
            $html .= $module->displayError($module->l('There are no store comments to export'));
        }
        return $html;
    }

    public static function getSeparator($separator)
    {
        if ($separator == 2) {
            return ',';
        } else {
            return ';';
        }
    }
}
