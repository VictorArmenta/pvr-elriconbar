<?php

class Categorie_expence extends ActiveRecord\Model {

   public static $table_name = 'ck_cat_gastos';

   static $validates_uniqueness_of = array(
     array('name'),
     array(array('name'), 'message' => 'Can\'t have duplicate code.')
	);
}