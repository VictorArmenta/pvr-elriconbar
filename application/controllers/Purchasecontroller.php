<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchasecontroller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->session->userdata('user_id') ? User::find_by_id($this->session->userdata('user_id')) : FALSE;
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
        $this->lang->load($lang, $lang);

        $this->setting = Setting::find(1);
    }


    public function add()
    {  
	  $register = Register::find($this->session->userdata('register'));
      $content = "";
      $content .= '<div><label for="add_item">'.label("AddProduct").'</label><input type="text" id="add_item" class="col-md-12"></div><div><label for="Comboprd">'.label("combination").'</label><table id="Comboprd" class="table items table-striped table-bordered table-condensed table-hover"><thead><tr><th class="col-xs-7">'.label("ProductName").'</th><th class="col-xs-2">'.label("Quantity").'</th><th class="col-xs-2">'.label("Cost").'</th><th class=" col-xs-1 text-center"><i class="fa fa-trash-o trash-opacity-50"></i></th></tr></thead><tbody></tbody></table></div>';
      
      $data = array(
				"reference" => $this->input->post('reference'),
                "date" =>  $this->input->post('date'),
				"note" =>  $this->input->post('note'),
				"store_id" =>  $register->store_id,
				"created_by" =>  $this->session->userdata('user_id'),
				"received" =>  0,
				"supplier_id" => $this->input->post('supplier')
            );
      $purchase = Purchase::create($data);
      $content .= '<input type="hidden" id="prodctID" value="'.$purchase->id.'">';
		
      echo $content;    
    }

	
    public function Viewproduct($id)
    { 
      $stores = Store::find('all');
      $purchase = Purchase::find($id);
	  if ($purchase->supplier_id <> 0){$supplier = Supplier::find($purchase->supplier_id);}
           
	  
	  $content = '<div class="media-body"><h1 class="media-heading">'.$purchase->reference.'</h1>'.
	             '<b>   '.label("ID").' :</b> '.$purchase->id.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
				 '<b>'.label("Fecha").' :</b> '.$purchase->date->format('Y-m-d').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	  if ($purchase->supplier_id <> 0){
	  $content .='<b>'.label("Supplier").' :</b> '.$supplier->name.' <br><br>';}
	
	   $content .=
				 //'<b>'.label("Total").' :</b> '.$purchase->total.' '.$this->setting->currency.' <br>'.
				 '<b>'.label("Nota").' :</b> '.$purchase->note.' <br>' . '</div>';
    
	 
	
        $purchase_items = Purchase_item::find('all', array('conditions' => array('	purchase_id = ?', $id)));
         $content .= '<div class="row"><div class="col-md-12"><h1>'.label("list_prod_purchases").'</h1></div><div class="col-md-12"><table class="table">
             <thead>
                <tr>
                   <th class="col-xs-4"><b>'.label("ProductName").'</b></th>
                   <th class="col-xs-2"><b>'.label("Quantity").'</b></th>
				   <th class="col-xs-2"><b>'.label("UM").'</b></th>
				   <td align="right"><b>'.label("Cost").'</b></td>
				   <td align="right"><b>'.label("Subtotal").'</b></td>
                </tr>
             </thead>
             <tbody>';
			 
			 $costototal = 0; 
			 
			 //$productall = product::find('all');
             foreach ($purchase_items as $purchase_item) {
                	
				$prod = Product::find('first', array(
					'conditions' => array(
					'id = ?',
					$purchase_item->product_id
					)
				));
				
				//$prod = product::find($purchase_item->product_id); 
			    
                
				$costo = $purchase_item->cost * $purchase_item->quantity ;
                $content .= '<tr>
                  <td>'.$prod->name.' ('. $prod->code .')</td>
                  <td ><b>'. $purchase_item->quantity.'</b></td>
				  <td ><b>'. $prod->unit.'</b></td>
				  <td align="right"><b>'. number_format((float)$purchase_item->cost, $this->setting->decimals, '.', '').'</b></td>
				  <td align="right"><b>'. number_format((float)$purchase_item->subtotal, $this->setting->decimals, '.', '').'</b></td>
               </tr>';
				
			   $costototal = $costototal + $costo;
             } 
			 $content .= '<tr><td></td><td></td><td></td><td align="right"><b>TOTAL '." " . $this->setting->currency .':</b></td><td align="right"><b>'.number_format((float)$costototal, $this->setting->decimals, '.', '').'</b></td><tr>';
             $content .= '  </tbody>
           </table></div></div>';
		   if ($purchase->received ==0){
		   $content .='<button type="submit" class="btn btn-add col-md-12" style="margin-bottom:0" onclick="modifycombo('.$id.')">'.label("Modify").'</button>';
           }
      echo $content;

   }

   
   /******* combo functions *********/

   public function getProductNames($term) {
      $prd = Product::find('all', array('select' => 'name'), array('conditions' => "(name LIKE '%" . $term . "%'  OR code LIKE '%" . $term . "%') AND type != 2"));
      if ($prd) {
           return $prd;
      }
      return FALSE;
   }

   public function suggest()
   {
        $term = $this->input->get('term', TRUE);

        $rows = $this->getProductNames($term);
        if ($rows) {
            foreach ($rows as $row) {
               $pr[] = array('id' => $row->id, 'label' => $row->name, 'name' => $row->name, 'code' => $row->code, 'cost' => $row->cost);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array('id' => 0, 'label' => label('NoProduct')));
        }
    }

    public function addcombo()
    {
      $items = $this->input->post('items');
      $productID = $this->input->post('productID');
      if ($items) {
         $combos = Purchase_item::delete_all(array(
            'conditions' => array(
                'purchase_id = ?',
                $productID
            )
        ));
		 $montotal = 0;
         foreach ($items as $item) {
            $item['purchase_id'] = $productID;
			$item['subtotal'] = $item['quantity'] * $item['cost'];
			$montotal = $montotal + $item['subtotal'];
            unset($item['code']);
            unset($item['name']);
            Purchase_item::create($item);
         }
		 $purchase = Purchase::find($productID); 
         $purchase->total = $montotal;
		 $purchase->save();
		 
      }
   }

   public function modifycombo($id)
   {
      $combos = Purchase_item::find('all', array('conditions' => array('purchase_id = ?', $id)));

      $content = '<div><label for="add_item">'.label("AddProduct").'</label><input type="text" id="add_item" class="col-md-12"></div>
         <div>
         <label for="Comboprd">'.label("combination").'</label>
         <table id="Comboprd" class="table items table-striped table-bordered table-condensed table-hover">
         <thead>
         <tr>
         <th class="col-xs-8">'.label("ProductName").'</th>
         <th class="col-xs-2">'.label("Quantity").'</th>
		 <th class="col-xs-2">'.label("Cost").'</th>
         <th class=" col-xs-1 text-center"><i class="fa fa-trash-o trash-opacity-50"></i></th>
         </tr>
         </thead>
         <tbody>';
         foreach ($combos as $combo) {
            //$prod = Product::find($combo->product_id);
             $prod = Product::find('first', array(
					'conditions' => array(
					'id = ?',
					$combo->product_id
					)
				));
			
			$content .= '<tr id="rowid_' . $combo->product_id . '" class="item_' . $combo->product_id . '">
              <td>'.$prod->name.' ('. $prod->code .')</td>
              <td><b>'.$combo->quantity.'</b></td>
			  <td><b>'.$combo->cost.'</b></td>
              <td><i class="fa fa-times tip delt" id="' . $combo->product_id . '" title="Remove" style="cursor:pointer;"></i></td>
              </tr>';
         }
         $content .= '</tbody>
         </table>
         </div>
         <input type="hidden" id="prodctID" value="'.$id.'">';

      echo $content;

   }

   public function getcombos($id){
      $combos = Purchase_item::find('all', array('conditions' => array('purchase_id = ?', $id)));

      if ($combos) {
          foreach ($combos as $row) {
             //$prod = Product::find($row->product_id);
             $prod = Product::find('first', array(
					'conditions' => array(
					'id = ?',
					$row->product_id
					)
				));
			 	
			 
			 $pr[] = array('product_id' => $row->product_id, 'quantity' => $row->quantity, 'cost' => $row->cost, 'code' => $prod->code, 'name' => $prod->name);
          }
          echo json_encode($pr);
      }else {
         echo json_encode();
      }

   }

}
