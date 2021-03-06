<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Core\Site\Admin\Application\Countries\Action;

  use osCommerce\OM\Core\ApplicationAbstract;
  use osCommerce\OM\Core\Site\Admin\Application\Countries\Countries;
  use osCommerce\OM\Core\Registry;
  use osCommerce\OM\Core\OSCOM;

  class BatchDelete {
    public static function execute(ApplicationAbstract $application) {
      if ( isset($_POST['batch']) && is_array($_POST['batch']) && !empty($_POST['batch']) ) {
        $application->setPageContent('batch_delete.php');

        if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
          $error = false;

          foreach ( $_POST['batch'] as $id ) {
            if ( !Countries::delete($id) ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_success_action_performed'), 'success');
          } else {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_error_action_not_performed'), 'error');
          }

          osc_redirect_admin(OSCOM::getLink());
        }
      }
    }
  }
?>
