<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Core\Site\Shop\Application\Account\Action\AddressBook;

  use osCommerce\OM\Core\ApplicationAbstract;
  use osCommerce\OM\Core\Registry;
  use osCommerce\OM\Core\OSCOM;
  use osCommerce\OM\Core\Site\Shop\AddressBook;

  class Delete {
    public static function execute(ApplicationAbstract $application) {
      $OSCOM_Customer = Registry::get('Customer');
      $OSCOM_MessageStack = Registry::get('MessageStack');
      $OSCOM_Service = Registry::get('Service');
      $OSCOM_Breadcrumb = Registry::get('Breadcrumb');

      if ( $_GET['Delete'] == $OSCOM_Customer->getDefaultAddressID() ) {
        $OSCOM_MessageStack->add('AddressBook', OSCOM::getDef('warning_primary_address_deletion'), 'warning');
      } else {
        if ( AddressBook::checkEntry($_GET['Delete']) === false ) {
          $OSCOM_MessageStack->add('AddressBook', OSCOM::getDef('error_address_book_entry_non_existing'), 'error');
        }
      }

      if ( $OSCOM_MessageStack->size('AddressBook') > 0 ) {
        osc_redirect(OSCOM::getLink(null, null, 'AddressBook', 'SSL'));
      }

      if ( $OSCOM_Service->isStarted('Breadcrumb') ) {
        $OSCOM_Breadcrumb->add(OSCOM::getDef('breadcrumb_address_book_delete_entry'), OSCOM::getLink(null, null, 'AddressBook&Delete=' . $_GET['Delete'], 'SSL'));
      }

      $application->setPageTitle(OSCOM::getDef('address_book_delete_entry_heading'));
      $application->setPageContent('address_book_delete.php');
    }
  }
?>
