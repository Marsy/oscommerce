<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  use osCommerce\OM\Core\OSCOM;
?>

<h1><?php echo $OSCOM_Template->getIcon(32) . osc_link_object(OSCOM::getLink(), $OSCOM_Template->getPageTitle()); ?></h1>

<?php
  if ( $OSCOM_MessageStack->exists() ) {
    echo $OSCOM_MessageStack->get();
  }
?>

<div class="infoBox">
  <h3><?php echo osc_icon('trash.png') . ' ' . OSCOM::getDef('action_heading_batch_delete_administrators'); ?></h3>

  <form name="aDeleteBatch" class="dataForm" action="<?php echo OSCOM::getLink(null, null, 'action=BatchDelete'); ?>" method="post">

  <p><?php echo OSCOM::getDef('introduction_batch_delete_administrators'); ?></p>

<?php
  $Qadmins = $OSCOM_Database->query('select id, user_name from :table_administrators where id in (":id") order by user_name');
  $Qadmins->bindRaw(':id', implode('", "', array_unique(array_filter(array_slice($_POST['batch'], 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
  $Qadmins->execute();

  $names_string = '';

  while ( $Qadmins->next() ) {
    $names_string .= osc_draw_hidden_field('batch[]', $Qadmins->valueInt('id')) . '<b>' . $Qadmins->valueProtected('user_name') . '</b>, ';
  }

  if ( !empty($names_string) ) {
    $names_string = substr($names_string, 0, -2);
  }

  echo '<p>' . $names_string . '</p>';
?>

  <p><?php echo osc_draw_hidden_field('subaction', 'confirm') . osc_draw_button(array('priority' => 'primary', 'icon' => 'trash', 'title' => OSCOM::getDef('button_delete'))) . ' ' . osc_draw_button(array('href' => OSCOM::getLink(), 'priority' => 'secondary', 'icon' => 'close', 'title' => OSCOM::getDef('button_cancel'))); ?></p>

  </form>
</div>
