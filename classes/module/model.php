<?php
abstract class Model {

  const TABLENAME = '';
  const CLASSNAME = '';

  abstract public static function select($clause = "");
  abstract protected function insert();
  abstract public static function updates($clause = "");
  abstract protected function update();
  abstract public static function deletes($clause = "");
  abstract public function delete();
  abstract public function save();
  abstract public static function byId();
}
?>