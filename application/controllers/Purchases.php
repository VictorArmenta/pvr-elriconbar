<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchases extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
    }

    public function index()
    { 
        $supplier = $this->input->post('filtersupp') ? $this->input->post('filtersupp') : '99';
        $supplierF = $supplier === '99' ? '99' : 'supplier';
        $this->view_data['purchases'] = Purchase::find('all', array('conditions' => array($supplierF.' = ?', $supplier)));
		$this->view_data['supplierF'] = $supplier;
		$this->view_data['suppliers'] = Supplier::all();
		
        $this->content_view = 'purchase/view';
    }
   
    public function delete($id)
    {
        $purchase = Purchase::find($id); 
		$combos = Purchase_item::delete_all(array('conditions' => array('purchase_id = ?',$id)));
        $purchase->delete();
        redirect("purchases", "refresh");
    }
       
   function updatestock2($id)
    { 
	  $purchase = Purchase::find($id); 
	  $purchase_items = Purchase_item::find('all', array('conditions' => array('	purchase_id = ?', $id)));
	  foreach ($purchase_items as $purchase_item) {
		  if($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $purchase->store_id, $purchase_item->product_id))))
            {
			   $stock2 = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $purchase->store_id, $purchase_item->product_id)));	
			   $stocksize2 = $stock2 ? $stock2->quantity : '0';	
				
               $item->quantity = $stocksize2 + $purchase_item->quantity ;
               $item->save();
            }
			else {
               $qt['product_id'] = $purchase_item->product_id;
			   $qt['quantity']   = $purchase_item->quantity;
			   $qt['store_id']   = $purchase->store_id;
               Stock::create($qt);
            }	
	   }
	  $purchase = Purchase::find($id); 
      $purchase->received = 1;
	  $purchase->save();
	  
	  redirect("purchases", "refresh");
	  
   }
   
}
