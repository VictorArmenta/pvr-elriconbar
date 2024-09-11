<?php

class Subcategory extends ActiveRecord\Model {

   public static $table_name = 'ck_subcategorias';

   static $validates_uniqueness_of = array(
     array('name'),
     array(array('name'), 'message' => 'Can\'t have duplicate code.')
	);
}