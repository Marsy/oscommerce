<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  namespace osCommerce\OM\Core\Session;

  use osCommerce\OM\Core\Registry;

/**
 * The Session\Database class stores the session data in the database
 */

  class Database extends \osCommerce\OM\Core\SessionAbstract {

/**
 * Initialize database based session storage handler
 *
 * @param string $name The name of the session
 * @access public
 */

    public function __construct($name) {
      $this->setName($name);

      session_set_save_handler(array($this, 'handlerOpen'),
                               array($this, 'handlerClose'),
                               array($this, 'handlerRead'),
                               array($this, 'handlerWrite'),
                               array($this, 'handlerDestroy'),
                               array($this, 'handlerClean'));
    }

/**
 * Opens the database based session storage handler
 *
 * @access public
 */

    public function handlerOpen() {
      return true;
    }

/**
 * Closes the database based session storage handler
 *
 * @access public
 */

    public function handlerClose() {
      return true;
    }

/**
 * Read session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access public
 */

    public function handlerRead($id) {
      $OSCOM_Database = Registry::get('Database');

      $Qsession = $OSCOM_Database->query('select value from :table_sessions where id = :id');

      if ( $this->_life_time > 0 ) {
        $Qsession->appendQuery('and expiry >= :expiry');
        $Qsession->bindInt(':expiry', time());
      }

      $Qsession->bindValue(':id', $id);
      $Qsession->execute();

      if ( $Qsession->numberOfRows() === 1 ) {
        return base64_decode($Qsession->value('value'));
      }

      return false;
    }

/**
 * Writes session data to the database based session storage handler
 *
 * @param string $id The ID of the session
 * @param string $value The session data to store
 * @access public
 */

    public function handlerWrite($id, $value) {
      $OSCOM_Database = Registry::get('Database');

      $Qsession = $OSCOM_Database->query('replace into :table_sessions values (:id, :expiry, :value)');
      $Qsession->bindValue(':id', $id);
      $Qsession->bindInt(':expiry', time() + $this->_life_time);
      $Qsession->bindValue(':value', base64_encode($value));
      $Qsession->execute();

      return ( $Qsession->affectedRows() === 1 );
    }

/**
 * Destroys the session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access public
 */

    public function handlerDestroy($id) {
      return $this->delete($id);
    }

/**
 * Garbage collector for the database based session storage handler
 *
 * @param string $max_life_time The maxmimum time a session should exist
 * @access public
 */

    public function handlerClean($max_life_time) {
// $max_life_time is already added to the time in the _custom_write method

      $OSCOM_Database = Registry::get('Database');

      $Qsession = $OSCOM_Database->query('delete from :table_sessions where expiry < :expiry');
      $Qsession->bindInt(':expiry', time());
      $Qsession->execute();

      return ( $Qsession->affectedRows() > 0 );
    }

/**
 * Deletes the session data from the database based session storage handler
 *
 * @param string $id The ID of the session
 * @access public
 */

    public function delete($id = null) {
      $OSCOM_Database = Registry::get('Database');

      if ( empty($id) ) {
        $id = $this->_id;
      }

      $Qsession = $OSCOM_Database->query('delete from :table_sessions where id = :id');
      $Qsession->bindValue(':id', $id);
      $Qsession->execute();

      return ( $Qsession->affectedRows() === 1 );
    }
  }
?>
