<?php
include_once "../lib/adodb5/adodb.inc.php";
define('DB_DRIVER', 'pdo');
define('DB_TYPE', 'sqlite');
define('DB_PATH', '../../data/data.db');

abstract class SqliteItemDao {
  var $conn = null;
  var $errMsg = '';

  function SqliteItemDao() {
    $this->conn = $this->get_connection(DB_PATH);
  }

  function get_connection($path) {
    $conn = ADONewConnection(DB_DRIVER);
    //$conn->debug=true;
    $conn->PConnect(DB_TYPE.':'.$path);
    return $conn;
  }

  function close() {
    $this->conn->Close();
    $this->conn = null;
  }

  abstract function sql_select();
  abstract function sql_update();
  abstract function sql_insert();
  abstract function sql_delete();

  abstract function getItems($rs);

  function getAll() {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select();
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else {
      for($i=0; !$rs->EOF; $i++) {
        $array[$i] = $this->getItems($rs);
        $rs->MoveNext();
      }
    }
    return $array;
  }

  function getById($id) {
    $array = array();
    //$this->conn->debug = true;
    $sql = $this->sql_select()." where id = '$id'";
    $rs = $this->conn->Execute($sql);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
    } else if($rs || !$rs->EOF) {
      $array = $this->getItems($rs);
    }
    return $array;
  }

  function add($data) {
    $returnValue = false;
    $this->conn->StartTrans();
    //$this->conn->debug = true;
    $sql = $this->sql_insert();
    $rs = $this->conn->Execute($sql, $data);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
      $returnValue = false;
    } else {
      $returnValue = true;
    }
    $this->conn->CompleteTrans();
    return $returnValue;
  }

  function update($data) {
    $returnValue = false;
    $this->conn->StartTrans();
    //$this->conn->debug = true;
    $sql = $this->sql_update();
    $rs = $this->conn->Execute($sql, $data);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
      $returnValue = false;
    } else {
      $returnValue = true;
    }
    $this->conn->CompleteTrans();
    return $returnValue;
  }

  function delete($data) {
    $returnValue = false;
    //$this->conn->debug = true;
    $sql = $this->sql_delete();
    $rs = $this->conn->Execute($sql, $data);
    if(!$rs) {
      $this->errMsg = $this->conn->ErrorMsg();
      $returnValue = false;
    } else {
      $returnValue = true;
    }
    return $returnValue;
  }
}
?>
