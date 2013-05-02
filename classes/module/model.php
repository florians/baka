<?php
/**
 * @Author Adrian Locher
 * @Version 5
 * Create Date:   03.04.2013  creation of the file
 * 
 * This class ist the basis on which we base all the other classes in the module forlder upon.
 */
abstract class Model {

  // this constant will define the table name of the MySQL Database table in the subclasses
  const TABLENAME = '';
  // this contant will define the class name, which is used while fetching the object for the subclass
  const CLASSNAME = '';

  // this will be used to select all records machting the given clause of the table of the subclass
  abstract public static function select($clause = "");
  
  // In the subclass it will be used to insert the current object
  abstract protected function insert();
  
  // In the subclass it will be used to update the records that match the given clause
  abstract public static function updates($clause = "");
  
  // In the subclass it will be used to update the record of the current object 
  abstract protected function update();
  
  // in the subclass it will be used to delete all records that match the clause
  abstract public static function deletes($clause = "");
  
  // In the subclass it will be used to delete the current object's record
  abstract public function delete();
  
  // In the subclass it will be used to either insert the current object or update it's record
  abstract public function save();
  
  // In the subclass it will be used to get the Object by it's Id
  abstract public static function byId();
}
?>