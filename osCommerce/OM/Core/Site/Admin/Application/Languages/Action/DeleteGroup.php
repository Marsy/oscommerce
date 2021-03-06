<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Core\Site\Admin\Application\Languages\Action;

  use osCommerce\OM\Core\ApplicationAbstract;
  use osCommerce\OM\Core\Site\Admin\Application\Languages\Languages;
  use osCommerce\OM\Core\Registry;
  use osCommerce\OM\Core\OSCOM;

  class DeleteGroup {
    public static function execute(ApplicationAbstract $application) {
      if ( Languages::isDefinitionGroup($_GET['id'], $_GET['group']) ) {
        $application->setPageContent('groups_delete.php');

        if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
          if ( Languages::deleteDefinitionGroup($_GET['group']) ) {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_success_action_performed'), 'success');
          } else {
            Registry::get('MessageStack')->add(null, OSCOM::getDef('ms_error_action_not_performed'), 'error');
          }

          osc_redirect_admin(OSCOM::getLink(null, null, 'id=' . $_GET['id']));
        }
      }
    }
  }
?>
