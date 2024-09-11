<?php
require_once('traits/Uniquecheck.php');
class Product extends ActiveRecord\Model {

   public static $table_name = 'ck_productos';
  //  static $validates_uniqueness_of = array(
  //     array('code')
  //  );


   static $validates_uniqueness_of = array(
     array('code'),
     array(array('code'), 'message' => 'Can\'t have duplicate code.')
  );
  /*use uniquecheck;
  public function validate() {
       $this->uniquecheck(array('code','message' => 'Can\'t have duplicate code.'));
   }*/

}