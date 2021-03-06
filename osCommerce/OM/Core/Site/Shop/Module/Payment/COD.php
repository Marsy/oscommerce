<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Core\Site\Shop\Module\Payment;

  use osCommerce\OM\Core\OSCOM;
  use osCommerce\OM\Core\Registry;
  use osCommerce\OM\Core\Site\Shop\Order;

  class COD extends \osCommerce\OM\Core\Site\Shop\Payment {
    var $_title,
        $_code = 'COD',
        $_status = false,
        $_sort_order,
        $_order_id;

    public function __construct() {
      $OSCOM_Database = Registry::get('Database');
      $OSCOM_ShoppingCart = Registry::get('ShoppingCart');

      $this->_title = OSCOM::getDef('payment_cod_title');
      $this->_method_title = OSCOM::getDef('payment_cod_method_title');
      $this->_status = (MODULE_PAYMENT_COD_STATUS == '1') ? true : false;
      $this->_sort_order = MODULE_PAYMENT_COD_SORT_ORDER;

      if ( $this->_status === true ) {
        if ( (int)MODULE_PAYMENT_COD_ORDER_STATUS_ID > 0 ) {
          $this->order_status = MODULE_PAYMENT_COD_ORDER_STATUS_ID;
        }

        if ( (int)MODULE_PAYMENT_COD_ZONE > 0 ) {
          $check_flag = false;

          $Qcheck = $OSCOM_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
          $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_COD_ZONE);
          $Qcheck->bindInt(':zone_country_id', $OSCOM_ShoppingCart->getBillingAddress('country_id'));
          $Qcheck->execute();

          while ( $Qcheck->next() ) {
            if ( $Qcheck->valueInt('zone_id') < 1 ) {
              $check_flag = true;
              break;
            } elseif ( $Qcheck->valueInt('zone_id') == $OSCOM_ShoppingCart->getBillingAddress('zone_id') ) {
              $check_flag = true;
              break;
            }
          }

          if ( $check_flag === false ) {
            $this->_status = false;
          }
        }
      }
    }

    function selection() {
      return array('id' => $this->_code,
                   'module' => $this->_method_title);
    }

    function process() {
      $this->_order_id = Order::insert();
      Order::process($this->_order_id, $this->order_status);
    }
  }
?>
