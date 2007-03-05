<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<h1><?php echo osc_link_object(osc_href_link(FILENAME_DEFAULT, $osC_Template->getModule()), $osC_Template->getPageTitle()); ?></h1>

<?php
  if ($osC_MessageStack->size($osC_Template->getModule()) > 0) {
    echo $osC_MessageStack->output($osC_Template->getModule());
  }
?>

<div class="infoBoxHeading"><?php echo osc_icon('configure.png', IMAGE_EDIT) . ' Batch Edit'; ?></div>
<div class="infoBoxContent">
  <form name="ccEditBatch" action="<?php echo osc_href_link(FILENAME_DEFAULT, $osC_Template->getModule() . '&page=' . $_GET['page'] . '&action=batchSave'); ?>" method="post">

  <p><?php echo TEXT_EDIT_BATCH_INTRO; ?></p>

<?php
  $Qcc = $osC_Database->query('select id, credit_card_name from :table_credit_cards where id in (":id") order by credit_card_name');
  $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
  $Qcc->bindRaw(':id', implode('", "', array_unique(array_filter(array_slice($_POST['batch'], 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
  $Qcc->execute();

  $names_string = '';

  while ($Qcc->next()) {
    $names_string .= osc_draw_hidden_field('batch[]', $Qcc->valueInt('id')) . '<b>' . $Qcc->valueProtected('credit_card_name') . '</b>, ';
  }

  if ( !empty($names_string) ) {
    $names_string = substr($names_string, 0, -2) . osc_draw_hidden_field('subaction', 'confirm');
  }

  echo '<p>' . $names_string . '</p>';

  echo '<p>' . osc_draw_radio_field('type', array(array('id' => 'activate', 'text' => 'Activate'), array('id' => 'deactivate', 'text' => 'Deactivate')), 'activate') . '</p>';
?>

  <p align="center"><?php echo '<input type="submit" value="' . IMAGE_SAVE . '" class="operationButton" /> <input type="button" value="' . IMAGE_CANCEL . '" onclick="document.location.href=\'' . osc_href_link_admin(FILENAME_DEFAULT, $osC_Template->getModule() . '&page=' . $_GET['page']) . '\';" class="operationButton" />'; ?></p>

  </form>
</div>