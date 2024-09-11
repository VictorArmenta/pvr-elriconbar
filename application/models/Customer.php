<?php

class Customer extends ActiveRecord\Model {

   public static $table_name = 'ck_clientes';

   /*static $validates_uniqueness_of = array(
     array('name'),
     array(array('name'), 'message' => 'Can\'t have duplicate code.')
	);*/
}