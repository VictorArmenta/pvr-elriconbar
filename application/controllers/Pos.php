<?php
date_default_timezone_set('America/Mexico_City');
defined('BASEPATH') or exit('No direct script access allowed');


class Pos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
		
		$this->user = $this->session->userdata('user_id') ? User::find_by_id($this->session->userdata('user_id')) : FALSE;
		
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
        $this->lang->load($lang, $lang);
        $this->register = $this->session->userdata('register') ? $this->session->userdata('register') : FALSE;
        $this->store = $this->session->userdata('store') ? $this->session->userdata('store') : FALSE;
        $this->selectedTable = $this->session->userdata('selectedTable') ? $this->session->userdata('selectedTable') : FALSE;
        $this->load->database();

        $this->setting = Setting::find(1);
        date_default_timezone_set($this->setting->timezone);
		
    }

    public function findproduct($code)
    {
        $product = Product::find('first', array(
            'conditions' => array(
                'code = ?',
                $code
            )
        ));
        echo $product->id;
    }

    public function openregister($id = 0, $userRole)
    {
        try {
            if ($_POST) {
                $cash = $this->input->post('cash');
                $id = $this->input->post('store');
                $waitersCach = $this->input->post('waitersCach');
                $waitercc = '';
                foreach ($waitersCach as $key => $value) {
                $waitercc .= $value ? $key.','.$value.',' : '';
                }
                $data = array(
                    "status" => 1,
                    "user_id" => $this->session->userdata('user_id'),
                    "cash_inhand" => $cash,
                    "waiterscih" => $waitercc,
                    "store_id" => $id
                );
                $register = Register::create($data);

                $store = Store::find($id);
                if (!$store) {
                    throw new Exception("Store not found or invalid.");
                }
                $store->status = 1;
                $store->save();
                
                $CI = & get_instance();
                $CI->session->set_userdata('register', $register->id);
                $CI->session->set_userdata('store', $id);

                $Posales = Posale::find('all');  // Asigna la mesa y la venta al nuevo registro de la nueva cuenta
                foreach ($Posales as $Posale) {
                    $Posale->register_id = $register->id;
                    $Posale->status = 1;
                    $Posale->save();
                }
                
                $holds = Hold::find('all');  // Asigna la mesa y la venta al nuevo registro de la nueva cuenta
                foreach ($holds as $Hold) {
                    $Hold->register_id = $register->id;
                    $Hold->save();
                }
                
                redirect("", "location");
            }

            $open_reg = Register::find('first', array(
                'conditions' => array(
                    'store_id = ? AND status= ?',
                    $id,
                    1
                )
            ));
            if (!$open_reg) {
                throw new Exception("Could not find open register for store.");
            }

            $CI = & get_instance();
            $CI->session->set_userdata('register', $open_reg->id);
            $CI->session->set_userdata('store', $id);
            
            $this->db->select('area');
            $this->db->from('ck_usuarios');
            $this->db->where('id', $this->session->userdata('user_id'));
            $query = $this->db->get();
            
            $sql = $this->db->last_query(); // Obtén la consulta SQL generada
            log_message('debug', 'Consulta SQL: ' . $sql); 

            $userArea = 0;
            
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $userArea = $row->area;

            }
            log_message('debug', 'El area es: ' . $userArea); 
            if ($userRole === 'kitchen' && $userArea == 1) {
                redirect("Drinks", "location");
            } else if ($userRole === 'kitchen' && $userArea == 0) {
                redirect("kitchens", "location");
            } else {
                redirect("", "location");
            }
        } catch (Exception $e) {
            // Log the error
            log_message('error', 'Error in openregister: ' . $e->getMessage());
            
            // Optionally, show an error message to the user
            echo "Error: " . $e->getMessage();
            // Or redirect to an error page
            // redirect("error_page", "location");
        }
    }


    public function selectTable($id)
    {  
	  if ($id ==0){
		  $hold = Hold::find('first', array('conditions' => array('register_id = ? AND table_id = ?', $this->register, $id)));
		  if(!$hold){
				$attributes = array(
				'number' => 1,
				'time' => date("H:i"),
				"table_id" => $id,
				'register_id' => $this->register
			);
			Hold::create($attributes);
			}else{
				Posale::update_all(array(
				'set' => array(
				'status' => 1
				),
				'conditions' => array(
				'number = ? AND register_id = ? AND table_id = ?',
               1,
               $this->register,
               $id
				)
			));
			}
		$CI = & get_instance();
		$CI->session->set_userdata('selectedTable', $id.'h');
		redirect("", "location");	  
	  }
	  else {
			$table = Table::find($id); /*JAR01*/
			if(($this->user->role == 'admin' || $this->user->role == 'sales'|| $table->user_id == $this->user->id || $table->user_id == 0) || strval($this->setting->table_assig) === '0') 
			{	
			$hold = Hold::find('first', array('conditions' => array('register_id = ? AND table_id = ?', $this->register, $id)));
			if(!$hold){
				$attributes = array(
					'number' => 1,
					'time' => date("H:i"),			
					"table_id" => $id,
					'register_id' => $this->register
				);
				Hold::create($attributes);
			}else{
			Posale::update_all(array(
					'set' => array(
					'status' => 1
					),
					'conditions' => array(
					'number = ? AND register_id = ? AND table_id = ?',
					1,
					$this->register,
					$id
					)
				));
			}
			if($id > 0){

				$table = Table::find($id);
				if($table->status != 1){
					$table->status = 1;
					$table->time = date("H:i");
					$table->timedri = date("H:i");
					$table->hora = date("H:i"); /*JAR*/
					$table->user_id = $this->user->id; /*JAR01*/
					$table->save();
				}
			}
			$CI = & get_instance();
			$CI->session->set_userdata('selectedTable', $id.'h');
			redirect("", "location");
			}
			else redirect("", "location");
		}	
	}

    public function switshregister()
    {
        $CI = & get_instance();
        $CI->session->set_userdata('register', 0);
        $CI->session->set_userdata('store', 0);
        redirect("", "location");
    }

    public function switshtable()
    {
      Posale::update_all(array(
          'set' => array(
             'status' => 0
          ),
          'conditions' => array(
             'status = ? AND register_id = ?',
             1,
             $this->register
          )
      ));
        $CI = & get_instance();
        $CI->session->set_userdata('selectedTable', 0);
        redirect("", "location");
    }

    public function addpdc()
    {
      $product = Product::find($this->input->post('product_id'));
      $PostPrice = $this->input->post('price');
      $price = !$product->taxmethod || $product->taxmethod == '0' ? floatval($PostPrice) : floatval($PostPrice)*(1 + $product->tax / 100);
      /******************************************* sock version *************************************************************/
      if($product->type == '0')
      {
         $register = Register::find($this->register);
         $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $this->input->post('product_id'))));
         $quantity = $stock ? $stock->quantity : 0;
        $posale = Posale::find('first', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND product_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->input->post('product_id'),
                $this->selectedTable
            )
        )); /* JAR resgistro nuevo por seleccion de producto desde las categorias
        if ($posale) {
           if($posale->qt < $quantity) {
            $posale->qt ++;
            $posale->time = date("Y-m-d H:i:s");
            $posale->save();
            echo json_encode(array(
                "status" => TRUE
            ));
         }else {
            echo 'stock';
         }
      } else */ if($quantity != 0){
            $data = array(
                "product_id" => $this->input->post('product_id'),
                "name" => $this->input->post('name'),
				"site" => $product->site, /*jar04*/
                "price" => $price,
                "number" => $this->input->post('number'),
                "register_id" => $this->input->post('registerid'),
                "table_id" => $this->selectedTable,
                "qt" => 1,
                "status" => 1,
				"timedri" => date("Y-m-d H:i:s"),
                "time" => date("Y-m-d H:i:s")
            );
            Posale::create($data);
            echo json_encode(array(
                "status" => TRUE
            ));
        }else {
           echo 'stock';
        }
       /******************************************* combo version *************************************************************/
     }elseif ($product->type == '2') {
        $posale = Posale::find('first', array(
           'conditions' => array(
             'status = ? AND register_id = ? AND product_id = ? AND table_id = ?',
             1,
             $this->register,
             $this->input->post('product_id'),
             $this->selectedTable
          )
       ));
        $register = Register::find($this->register);
        $quantity = 1;
        $combos = Combo_item::find('all', array('conditions' => array('product_id = ?', $this->input->post('product_id'))));
        foreach ($combos as $combo) {
           $prd = Product::find($combo->item_id);
           if($prd->type == '0' || $prd->type == '3' ){
               $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $combo->item_id)));
               if ($posale)
                  $diff = $stock ? ($stock->quantity - $combo->quantity*($posale->qt+1)) : 1;
               else
                 $diff = $stock ? ($stock->quantity - $combo->quantity) : 1;
              $quantity = $stock ? ($diff >= 0 ? 1 : 0) : $quantity;
           }
        } /** JAR  resgistro nuevo por seleccion de producto desde las categorias
      if ($posale) {
          if($quantity > 0) {
           $posale->qt ++;
           $posale->time = date("Y-m-d H:i:s");
           $posale->save();
           echo json_encode(array(
               "status" => TRUE
           ));
        }else {
           echo 'stock';
        }
     } else **/if($quantity > 0){
           $data = array(
               "product_id" => $this->input->post('product_id'),
               "name" => $this->input->post('name'),
			   "site" => $product->site, /*jar04*/
               "price" => $price,
               "number" => $this->input->post('number'),
               "register_id" => $this->input->post('registerid'),
               "table_id" => $this->selectedTable,
               "qt" => 1,
               "status" => 1,
			   "timedri" => date("Y-m-d H:i:s"),
               "time" => date("Y-m-d H:i:s")
           );
           Posale::create($data);
           echo json_encode(array(
               "status" => TRUE
           ));
      }else {
          echo 'stock';
      }
     }
     /******************************************* service version *************************************************************/
     else {
        $posale = Posale::find('first', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND product_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->input->post('product_id'),
                $this->selectedTable
            )
        ));
        if ($posale) {
            $posale->qt ++;
            $posale->time = date("Y-m-d H:i:s");
            $posale->save();
            echo json_encode(array(
                "status" => TRUE
            ));
        } else {
            $data = array(
                "product_id" => $this->input->post('product_id'),
                "name" => $this->input->post('name'),
                "price" => $price,
                "number" => $this->input->post('number'),
                "register_id" => $this->input->post('registerid'),
                "table_id" => $this->selectedTable,
                "qt" => 1,
                "status" => 1,
                "time" => date("Y-m-d H:i:s")
            );
            Posale::create($data);
            echo json_encode(array(
                "status" => TRUE
            ));
        }
     }
    }

    public function load_posales()
    {
        $setting = Setting::find(1, array(
            'select' => 'currency'
        ));
        $posales = Posale::find('all', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->selectedTable
            )
        ));
        $data = '';
        if ($posales) {
            foreach ($posales as $posale) {
               $alertqt = Product::find($posale->product_id)->alertqt;
               $type = Product::find($posale->product_id)->type;
               $options = $posale->options;
               $options = trim($options, ",");
               $storeid = Register::find($this->register)->store_id;
               $alert = $type == '0' ? (Stock::find('first', array('conditions' => array('product_id = ? AND store_id = ?', $posale->product_id, $storeid)))->quantity - $posale->qt <= $alertqt ? 'background-color:pink; color:#fc1616;' : '') : '';
               
			               
			   
                $row = '<div class="col-xs-12">
                <div class="panel panel-default product-details">
                <div class="panel-body" style="'.$alert.'">
              
                

                  <div class="col-xs-5 nopadding">
                  <div class="col-xs-2 nopadding">'; 
				 				
							    
				$row .= ' 
                  <a href="javascript:void(0)" onclick="delete_posale(' . "'" . $posale->id . "'" . ')">
                  <span class="fa-stack fa-sm productD">
                  <i class="fa fa-circle fa-stack-2x delete-product"></i>
                  <i class="fa fa-times fa-stack-1x fa-fw fa-inverse"></i></span></a>';
				
				$row .= '</div>
				         <div class="col-xs-10 nopadding"><span class="textPD">' . $posale->name . '</span></div></div>
			    
				<div class="col-xs-2"><span class="textPD">' . number_format((float)$posale->price, $this->setting->decimals, '.', '') . '</span></div>';
              	
				$row .= '
				<div class="col-xs-3 nopadding productNum">
                <a href="javascript:void(0)"><span class="fa-stack fa-sm decbutton"><i class="fa fa-square fa-stack-2x light-grey"></i><i class="fa fa-minus fa-stack-1x fa-inverse white"></i></span></a>
				<input type="text" id="qt-' . $posale->id . '" onchange="edit_posale(' . $posale->id . ')" class="form-control" value="' . $posale->qt . '" placeholder="0" maxlength="3">
				<a href="javascript:void(0)">
                <span class="fa-stack fa-sm incbutton">
                <i class="fa fa-square fa-stack-2x light-grey"></i>
                <i class="fa fa-plus fa-stack-1x fa-inverse white"></i>
                </span></a></div>';
				

				$row .= '
                <div class="col-xs-2 nopadding ">
                <span class="subtotal textPD">' . number_format((float)$posale->price*$posale->qt, $this->setting->decimals, '.', '') . '  ' . $setting->currency . '</span>
                </div>
                </div>
                <button type="button" onclick="addoptions('.$posale->product_id.', '.$posale->id.')" class="btn btn-success btn-xs">'.label("Options").'</button> 
                <span id="pooptions-'.$posale->id.'"> '.$options.'</sapn>
                </div>
                </div>';

                $data .= $row;
            }
            // adding script for the +/- buttons
            $data .= '<script type="text/javascript">$(".incbutton").on("click", function() {var $button = $(this);var oldValue = $button.parent().parent().find("input").val();var newVal = parseFloat(oldValue) + 1;$button.parent().parent().find("input").val(newVal);edit_posale($button.parent().parent().find("input").attr("id").slice(3));});$(".decbutton").on("click", function() {var $button = $(this);var oldValue = $button.parent().parent().find("input").val();if (oldValue > 1) {var newVal = parseFloat(oldValue) - 1;} else {newVal = 1;}$button.parent().parent().find("input").val(newVal);edit_posale($button.parent().parent().find("input").attr("id").slice(3));});</script>';
        } else {

            $data = '<div class="messageVide">' . label("EmptyList") . ' <span>(' . label("SelectProduct") . ')</span></div>';
        }
        echo $data;
    }

    public function delete($id)
    {
        $posale = Posale::find($id);
        $posale->delete();
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function edit($id)
    {
        $posale = Posale::find($id);
        $product = Product::find($posale->product_id);
       if($product->type == '0'){
          $register = Register::find($this->register);
          $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $posale->product_id)));
          $quantity = $stock ? $stock->quantity : 0;
          if(intval($this->input->post('qt')) <= intval($quantity)) {

             $data = array(
                 "qt" => $this->input->post('qt'),
				 "timedri" => date('Y-m-d H:i:s'),
                 "time" => date('Y-m-d H:i:s')
             );
             $posale->update_attributes($data);
             echo json_encode(array(
                 "status" => TRUE
             ));

        }else {
           echo 'stock';
        }
    /******************************************* combo version *************************************************************/
   }elseif ($product->type == '2') {
     $register = Register::find($this->register);
     $quantity = 1;
     $combos = Combo_item::find('all', array('conditions' => array('product_id = ?', $posale->product_id)));
     foreach ($combos as $combo) {
         $prd = Product::find($combo->item_id);
         if($prd->type == '0' || $prd->type == '3'){
             $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $combo->item_id)));
            $diff = $stock ? ($stock->quantity - $combo->quantity*($this->input->post('qt'))) : 1;
            $quantity = $stock ? ($diff >= 0 ? 1 : 0) : $quantity;
         }
     }
        if($quantity > 0) {
           $data = array(
              "qt" => $this->input->post('qt'),
			  "timedri" => date('Y-m-d H:i:s'),
              "time" => date('Y-m-d H:i:s')
          );
          $posale->update_attributes($data);
          echo json_encode(array(
              "status" => TRUE
          ));
     }else {
         echo 'stock';
     }
   }else {
        $data = array(
            "qt" => $this->input->post('qt'),
			"timedri" => date('Y-m-d H:i:s'),
            "time" => date('Y-m-d H:i:s')
        );
        $posale->update_attributes($data);
        echo json_encode(array(
            "status" => TRUE
        ));
     }

    }

    public function subtot()
    {
        $posales = Posale::find('all', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->selectedTable
            )
        ));
        $sub = 0;
        foreach ($posales as $posale) {
            $sub += $posale->price * $posale->qt;
        }
        echo number_format((float)$sub, $this->setting->decimals, '.', '');
    }

    public function totiems()
    {
        $posales = Posale::find('all', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->selectedTable
            )
        ));
        $sub = 0;
        foreach ($posales as $posale) {
            $sub += $posale->qt;
        }
        echo $sub;
    }

    public function GetDiscount($id)
    {
        $customer = Customer::find($id);
        $Discount = stripos($customer->discount, '%') > 0 ? $customer->discount : number_format((float)$customer->discount, $this->setting->decimals, '.', '');
        echo $Discount . '~' . $customer->name;
    }

    public function ResetPos()
    {
        Posale::delete_all(array(
            'conditions' => array(
                'status = ? AND register_id = ?',
                1,
                $this->register
            )
        ));
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function AddNewSale($type)
    {    
	    date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        $hour = date("H:i:s");
        $_POST['created_at_hour'] = $hour;
        $_POST['created_at'] = $date;
        $_POST['register_id'] = $this->register;
        $register = Register::find($this->register);
        $store = Store::find($register->store_id);

        if ($type == 2) {
            try {
                Stripe::setApiKey($this->setting->stripe_secret_key);
                $myCard = array(
                    'number' => $this->input->post('ccnum'),
                    'exp_month' => $this->input->post('ccmonth'),
                    'exp_year' => $this->input->post('ccyear'),
                    "cvc" => $this->input->post('ccv')
                );
                $charge = Stripe_Charge::create(array(
                    'card' => $myCard,
                    'amount' => (floatval($this->input->post('paid')) * 100),
                    'currency' => $this->setting->currency
                ));
                echo "<p class='bg-success text-center'>" . label('saleStripesccess') . '</p>';
            } catch (Stripe_CardError $e) {
                // Since it's a decline, Stripe_CardError will be caught
                $body = $e->getJsonBody();
                $err = $body['error'];
                echo "<p class='bg-danger text-center'>" . $err['message'] . '</p>';
            }
        }
        unset($_POST['ccnum']);
        unset($_POST['ccmonth']);
        unset($_POST['ccyear']);
        unset($_POST['ccv']);
        $paystatus = $_POST['paid'] - $_POST['total'];
        $_POST['firstpayement'] = $paystatus > 0 ? $_POST['total'] : $_POST['paid'];
        
		
		
        $posales = Posale::find('all', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->selectedTable
            )
        ));
	  $sub = 0;
      foreach ($posales as $posale) {  //si no tiene paridas no realiza registro JAR
          $sub = $sub + 1; 
      } 
      if ($sub <> 0){ 
	  
	    $sale = Sale::create($_POST);
        $sale2 = Sale::find('last');//ultima venta
        $waiters = Waiter::find($sale2->waiter_id); // nombre de cajero //captura id devuelve nombre

        foreach ($posales as $posale) {
            $data = array(
                "product_id" => $posale->product_id,
                "name" => $posale->name,
                "price" => $posale->price,
                "qt" => $posale->qt,
                "subtotal" => $posale->qt * $posale->price,
                "sale_id" => $sale->id,
                "date" => $date
            );
            $number = $posale->number;
            $register = Register::find($this->register);
            $prod = Product::find($posale->product_id);
            if($prod->type == "2"){
            /****************************************** combo case *************************************************************/
            $combos = Combo_item::find('all', array('conditions' => array('product_id = ?', $posale->product_id)));
            foreach ($combos as $combo) {
               $prd = Product::find($combo->item_id);
               if($prd->type == '0' || $prd->type == '3'){
                  $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $combo->item_id)));
                  $stock->quantity = $stock->quantity - ($combo->quantity*$posale->qt);
                  $stock->save();
               }
            }
            /*******************************************************************************************************/
         }else if($prod->type == "0"){
            $stock = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $register->store_id, $posale->product_id)));
            $stock->quantity = $stock->quantity - $posale->qt;
            $stock->save();
         }
            $pos = Sale_item::create($data);
        }

        if($this->setting->logo){  /*JAR*/
		   $img = '<img src="'. base_url(). 'files/Setting/' . $this->setting->logo .'" alt="" class="float-right" width="100px"/>';
	     } else { 
		    $img = '<img src="'.base_url().'assets/img/logo.png" alt="logo" class="float-right" width="100px">';
	     }
		$tableid=intval($this->selectedTable); /*JAR*/
	   
	    if ($tableid > 0){ 
          $table = Table::find($tableid);
          $tableN = $table->name;
		} 
		
        $ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
		$ticket .= '<div class="col-xs-10">' .
		//'<div class="text-center">' . $img. '</div>'. /*JAR*/
		'<br><div class="text-center">' . $this->setting->receiptheader .'</div>

        <div class="text-center"><b>' . label("SaleNum") . '.: ' . sprintf("%05d", $sale->id) . '</b></center> 
        <div style="clear:both;"></div>
        <span class="float-left">' . label("Date") . ': ' . $sale->created_at->format('d-m-Y') . '</span>
        <div style="clear:both;">
         <span class="float-left">' . label("Hour") . ': ' . $sale->created_at_hour . '</span>
        <div style="clear:both;">
        <span class="float-left">' . label("Customer") . ': ' . $sale->clientname . '</span>
        <div style="clear:both;">
		<span class="float-left">' . label("Table") . ': ' . $tableN . '</span>
        <div style="clear:both;">
        <span class="float-left">' . label("Waiters") . ': ' . $waiters->name . '</span>
        <div style="clear:both;"></div>
		</font><font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" > 
        <table class="table" cellspacing="0" border="0">
          <thead><tr>' .  //<th><em>#</em></th>
           '<th style=" font-weight:bold;">PRODUCTOS</th>
            <th style=" font-weight:bold;">CANT</th>
            <th style="text-align:right; font-weight:bold;">IMPORTE</th>
          </tr>
          </thead>
        <tbody>';

        $i = 1;
        foreach ($posales as $posale) {
            $ticket .= '<tr>' . //<td style="text-align:left;">' . $i . '</td>
            '<td style="text-align:left; ">' . $posale->name . '</td>
            <td style="text-align:center; ">' . $posale->qt . '</td>
            <td style="text-align:right;  ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . '</td></tr>'; // . $this->setting->currency . '</td></tr>'; 
            $i ++;
        }
		

        $bcs = 'code128';
        $height = 20;
        $width = 3;
        $ticket .= '<tr><td colspan="3" > &nbsp;</td><tr>
        <tr>
        <td style="text-align:right; ">' . $sale->totalitems . '</td>
        <td style="text-align:left; ">' . label("Total") . '</td>
        <td style="text-align:right;font-weight:bold;">' . $sale->subtotal . ' '.$sale->divisa.'</td></tr>';// . $this->setting->currency . '</td></tr>'; JAR
        if (intval($sale->tax))
            $ticket .= '<tr>
   
          <td colspan="2" style="text-align:left; ">' . label("tax") . " " . $sale->tax .'</td>
          <td style="text-align:right;font-weight:bold;">' . number_format((float)$sale->taxamount, $this->setting->decimals, '.', '') . '</td></tr>';
		
		if (intval($sale->discount))
            $ticket .= '<tr>
          
          <td colspan="2" style="text-align:left;">' . label("Discount") . " " . $sale->discount . '</td>
          <td style="text-align:right; font-weight:bold;">' . number_format((float)$sale->discountamount, $this->setting->decimals, '.', '') . '</td></tr>';
        
		if (intval($sale->tip))
            $ticket .= '<tr>
          <td colspan="2" style="text-align:left; ">' . label("Tip") . " Sugerida  ". $sale->tip . '</td>
          <td style="text-align:right;font-weight:bold;">' . number_format((float)$sale->tipamount, $this->setting->decimals, '.', '') . '</td>
		  </tr>';
        
		
		$ticket .= '<tr><td colspan="2"  style="text-align:left; font-weight:bold; ">'.
		'<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' . 'TOTAL A PAGAR</font></td><td style="border-top:1px dashed #000; text-align:right; font-weight:bold;">' .
        '<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' .
		number_format((float)$sale->total, $this->setting->decimals, '.', '') . ' '.$sale->divisa. '</font></td></tr><tr>'; // . $this->setting->currency . '</td></tr><tr>';  JAR

        $PayMethode = explode('~', $sale->paidmethod);

        switch ($PayMethode[0]) {
            case '1': // case Credit Card
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("CreditCard") . '</td>
                <td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">xxxx xxxx xxxx ' . substr($PayMethode[1], - 4) . '</td>
                </tr>
                <tr><td colspan="2" style="text-align:left; font-weight:bold;">' . label("CreditCardHold") . '</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">' . $PayMethode[2] . '</td></tr></tbody></table>';
                break;
            case '2': // case ckeck
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("ChequeNum") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[1] . '</td></tr></tbody></table>';
                break;
            default:
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("Paid") . '</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">' . number_format((float)$sale->paid, $this->setting->decimals, '.', '') . ' '.$sale->divisa. ' </td></tr><tr>'  . //$this->setting->currency . '</td></tr><tr> JAR
                '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Change") . '</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">' . number_format((float)(floatval($sale->paid) - floatval($sale->total)), $this->setting->decimals, '.', '') . ' </td>' .  //$this->setting->currency . '</td>
                '</tr>
                </tbody>
                </table>';
        }

        $ticket .= '</font><div style="border-top:1px solid #000; padding-top:10px;"><span class="float-left">' . $store->name . '</span><span class="float-right">' . label("Tel") . ' ' . ($store->phone ? $store->phone : $this->setting->phone) . '</span><div style="clear:both;"><center><img style="margin-top:30px" src="' . site_url('pos/GenerateBarcode/' . sprintf("%05d", $sale->id) . '/' . $bcs . '/' . $height . '/' . $width) . '" alt="' . $sale->id . '" /></center><p class="text-center" style="margin:0 auto;margin-top:10px;">' . $store->footer_text . '</p><div class="text-center" style="background-color:#95a5a6;padding:5px;width:85%;color:#fff;margin:0 auto;border-radius:2px;margin-top:20px;">' . $this->setting->receiptfooter . '</div>';

		if ($sale->client_id <> 1 )
		{   
	        $Customers = Customer::find($sale->client_id); //cliente
			$ticket .= '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
			$ticket .= '<b><br>* D I R E C C I O N * </b>' .
			'<br><div style="clear:both;">
			<span class="float-left">' . label("Calle")     . ': <b></span><span class="float-right">' . $Customers->calle  . '</b></span><br>
            <span class="float-left">' . label("Num_ext")   . ': <b></span><span class="float-right">' . $Customers->numero_ext .'</b></span><br>    
			<span class="float-left">' . label("Piso_depto"). ': <b></span><span class="float-right">' . $Customers->piso_depto .'</b></span><br>
            <span class="float-left">' . label("Colonia")   . ': <b></span><span class="float-right">' . $Customers->colonia . '</b></span><br>
            <span class="float-left">' . label("Municipio") . ': <b></span><span class="float-right">' . $Customers->municipio .'</b></span><br>    
			<span class="float-left">' . label("Estado")     .': <b></span><span class="float-right">' . $Customers->estado.'</b></span><br>
            <span class="float-left">Entre: <b></span><span class="float-right">' . $Customers->entre_calles . '</b></span>
         
			</font>';
		
		}
        $ticket .= '</div>';
        Posale::delete_all(array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                rtrim($this->selectedTable, "h")
            )
        ));
        if (isset($number)) {
            if ($number != 1)
                Hold::delete_all(array(
                    'conditions' => array(
                        'number = ? AND register_id = ? AND table_id = ?',
                        $number,
                        $this->register,
                        rtrim($this->selectedTable, "h")
                    )
                ));
        }
        $hold = Hold::find('last', array(
            'conditions' => array(
                'register_id = ? AND table_id = ?',
                $this->register,
                $this->selectedTable
            )
        ));
        if ($hold) {
            Posale::update_all(array(
                'set' => array(
                    'status' => 1
                ),
                'conditions' => array(
                    'number = ? AND register_id = ? AND table_id = ?',
                    $hold->number,
                    $this->register,
                    rtrim($this->selectedTable, "h")
                )
            ));
        }
        echo $ticket;
	  } //jar
    }

    function GenerateBarcode($code = NULL, $bcs = 'code128', $height = 60, $width = 1)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array(
            'text' => $code,
            'barHeight' => $height,
            'barThinWidth' => $width,
            'drawText' => FALSE
        );
        $rendererOptions = array(
            'imageType' => 'png',
            'horizontalPosition' => 'center',
            'verticalPosition' => 'middle'
        );
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }

    // ******************************************************** hold functions
    public function holdList($registerid)
    {
        $holds = Hold::find('all', array(
            'conditions' => array(
                'register_id = ? AND table_id = ?',
                $registerid,
                $this->selectedTable
            ),
            'order' => 'number asc'
        ));
        $posale = Posale::find('last', array(
            'conditions' => array(
                'status = ? AND register_id = ? AND table_id = ?',
                1,
                $this->register,
                $this->selectedTable
            )
        ));
        $Tholds = ''; 
        if (empty($holds))
            $Tholds = '<span class="Hold selectedHold">1<span id="Time">' . date("H:i") . '</span></span>';
        else {
            if (empty($posale)) {
                $numItems = count($holds);
                $i = 0;
                foreach ($holds as $hold) {
                    if (++ $i === $numItems)
                        $Tholds .= '<span class="Hold selectedHold" id="' . $hold->number . '"  onclick="SelectHold(' . $hold->number . ')">' . $hold->number . '<span id="Time">' . $hold->time . '</span></span>';
                    else
                        $Tholds .= '<span class="Hold" id="' . $hold->number . '"  onclick="SelectHold(' . $hold->number . ')">' . $hold->number . '<span id="Time">' . $hold->time . '</span></span>';
                }
            } else {
                foreach ($holds as $hold) {
                    if ($hold->number == $posale->number)
                        $selected = 'selectedHold';
                    else
                        $selected = '';
                    $Tholds .= '<span class="Hold ' . $selected . '" id="' . $hold->number . '" onclick="SelectHold(' . $hold->number . ')">' . $hold->number . '<span id="Time">' . $hold->time . '</span></span>';
                }
            }
        }
        echo $Tholds;
    }

    public function AddHold($registerid)
    {
        $hold = Hold::find('last', array(
            'conditions' => array(
                'register_id = ? AND table_id = ?',
                $registerid,
                $this->selectedTable
            )
        ));
        $number = ! empty($hold) ? intval($hold->number) + 1 : 1;
        Posale::update_all(array(
            'set' => array(
                'status' => 0
            ),
            'conditions' => array(
                'status = ? AND register_id = ?',
                1,
                $this->register
            )
        ));
        $attributes = array(
            'number' => $number,
            'time' => date("H:i"),
			'customer_id' => "1",
            'register_id' => $registerid,
            'table_id' => $this->selectedTable
        );
        Hold::create($attributes);
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function RemoveHold($number, $registerid)
    {
        $hold = Hold::find('first', array(
            'conditions' => array(
                'number = ? AND register_id = ? AND table_id = ?',
                $number,
                $registerid,
                $this->selectedTable
            )
        ));
        $hold->delete();
        Posale::delete_all(array(
            'conditions' => array(
                'number = ? AND register_id = ?',
                $number,
                $registerid
            )
        ));
        $hold = Hold::find('last', array(
            'conditions' => array(
                'register_id = ? AND table_id = ?',
                $registerid,
                $this->selectedTable
            )
        ));
        Posale::update_all(array(
            'set' => array(
                'status' => 1
            ),
            'conditions' => array(
                'number = ? AND register_id = ?',
                $hold->number,
                $registerid
            )
        ));
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function SelectHold($number)
    {
        Posale::update_all(array(
            'set' => array(
                'status' => 0
            ),
            'conditions' => array(
                'status = ? AND register_id = ?',
                1,
                $this->register
            )
        ));
        Posale::update_all(array(
            'set' => array(
                'status' => 1
            ),
            'conditions' => array(
                'number = ? AND register_id = ?',
                $number,
                $this->register
            )
        ));
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    /**
     * ****************** register functions ***************
     */
    public function CloseRegister() {
        try {
            // Iniciar variables
            $cash = 0;
            $cheque = 0;
            $cc = 0;
            $propina = 0; // Inicializar propina
            
            // Obtener el registro
            $register = Register::find($this->register);
            if (!$register) {
                throw new Exception('Register not found.');
            }
    
            // Obtener el usuario
            try {
                $user = User::find($register->user_id);
                $createdBy = $user->firstname . ' ' . $user->lastname;
            } catch (Exception $e) {
                // Manejar el caso en que el usuario no existe
                $createdBy = 'Usuario desconocido';
            }
    
            // Obtener ventas y pagos
            $sales = Sale::find('all', array(
                'conditions' => array('register_id = ?', $this->register)
            ));
            $payaments = Payement::find('all', array(
                'conditions' => array('register_id = ?', $this->register)
            ));
            $waiters = Waiter::find('all', array('conditions' => array('store_id = ?', $register->store_id)));
    
            // Calcular pagos
            foreach ($payaments as $payament) {
                $PayMethode = explode('~', $payament->paidmethod);
                switch ($PayMethode[0]) {
                    case '1': // Credit Card
                        $cc += $payament->paid;
                        break;
                    case '2': // Cheque
                        $cheque += $payament->paid;
                        break;
                    default:
                        $cash += $payament->paid;
                }
            }
    
            foreach ($sales as $sale) {
                $PayMethode = explode('~', $sale->paidmethod);
                $paystatus = $sale->paid - $sale->total;
                $propina += $sale->tipamount; // Agregar propina
                switch ($PayMethode[0]) {
                    case '1': // Credit Card
                        $cc += $paystatus > 0 ? $sale->total : $sale->firstpayement;
                        break;
                    case '2': // Cheque
                        $cheque += $paystatus > 0 ? $sale->total : $sale->firstpayement;
                        break;
                    default:
                        if ($sale->divisa == 'MXN') {
                            $cashMXN += $paystatus > 0 ? $sale->total : $sale->firstpayement;
                        } elseif ($sale->divisa == 'USD') {
                            $cashUSD += $paystatus > 0 ? $sale->total : $sale->firstpayement;
                        }
                }
            }
    
            // Calcular gastos
            $id = $register->id;
            $gastotals = 0;
            $expences = Expence::find_by_sql("SELECT * FROM `ck_gastos` WHERE register_id = '$id' ORDER BY date");			
            foreach ($expences as $expence) {
                $gastotals += $expence->amount;
            }
    
            // Preparar los datos para la respuesta
            $CashinHand = $register->cash_inhand;
            $date = new DateTime($register->date);
            
            $USDaMXN = $cashUSD * $this->setting->decimals;
            $data = "<div class='col-md-3'><blockquote><footer>" . label("Openedby") . "</footer><p>$createdBy</p></blockquote></div>
            <div class='col-md-3'><blockquote><footer>" . label("CashinHand") . "</footer><p>" . number_format((float)$CashinHand, $this->setting->decimals, '.', '') . ' ' . $this->setting->currency . "</p></blockquote></div>
            <div class='col-md-4'><blockquote><footer>" . label("Openingtime") . "</footer><p>" . $date->format('d-m-Y h:i:s') . "</p></blockquote></div>
            <div class='col-md-2'><img src='" . site_url() . "/assets/img/register.svg' alt=''></div>
            <h2>" . label("PaymentsSummary") . "</h2>
            <table class='table table-striped'><tr>
            <th width='20%'>" . label("PayementType") . "</th>
            <th width='20%'>" . label("EXPECTED") . " </th>
            <th width='20%'>" . label("Gastos") . " </th>
            <th width='20%'>" . label("COUNTED") . " </th>
            <th width='20%'>" . label("DIFFERENCES") . "</th></tr><tr>
            <td>Efectivo MXN</td>
            <td><span id='expectedcash'>" . number_format((float)$cashMXN, $this->setting->decimals, '.', '') . "</span></td>
            <td>". number_format((float) $gastotals, $this->setting->decimals, '.', '') ."</td>
            <td><input type='text' class='total-input' value='" . number_format((float)$cash - $gastotals, $this->setting->decimals, '.', '') . "' placeholder='0.00' maxlength='11' id='countedcash'></td>
            <td><span id='diffcash'>0.00</span></td></tr>
            <td>Efectivo USD</td>
            <td><span id='expectedcashusd'>" . number_format((float)$cashUSD, $this->setting->decimals, '.', '') . " = ". $USDaMXN." MXN</span></td>
            <td>0.0</td>
            <td><input type='text' class='total-input' value='' placeholder='0.00' maxlength='11' id='countedcashusd' disabled></td>
            <td><span id='diffcash'>0.00</span></td></tr>
            <tr><td>" . label("Cheque") . "</td>
            <td><span id='expectedcheque'>" . number_format((float)$cheque, $this->setting->decimals, '.', '') . "</span></td>
            <td>0.00</td>
            <td><input type='text' class='total-input' value='" . number_format((float)$cheque, $this->setting->decimals, '.', '') . "' placeholder='0.00' maxlength='11' id='countedcheque'></td>
            <td><span id='diffcheque'>0.00</span></td></tr><tr class='warning'>
            <td>Total en MXN:</td>
            <td><span id='total'>" . number_format((float)($cheque + $cashMXN + $cc+ $USDaMXN), $this->setting->decimals, '.', '') . "</span></td>
            <td>". number_format((float) $gastotals, $this->setting->decimals, '.', '') ."</td>
            <td><span id='countedtotal'>" . number_format((float)(($cheque + $cashMXN + $cc + $USDaMXN) - $gastotals), $this->setting->decimals, '.', '') . "</span></td>
            <td><span id='difftotal'>0.00</span></td></tr></table>";
    
            $data .= "<div class='waitercount'><center><b>Efectivo en caja: <span id='efectivocaja'>" . number_format((float) ($CashinHand + $cashMXN + $USDaMXN ) - $gastotals, $this->setting->decimals, '.', '') . "</span> " . $this->setting->currency ."</b></center></div>";
            $data .= "<div class='waitercount'><b>Propinas: <span id='tiptotal'>" . number_format((float) $propina, $this->setting->decimals, '.', '') . "</span> " . $this->setting->currency ."</b></div>";
            $data .= "<div class='form-group'><h2>" . label("Note") . "</h2><textarea id='RegisterNote' class='form-control' rows='3'></textarea></div>";
    
            // Enviar la respuesta
            echo $data;
    
        } catch (Exception $e) {
            // Registrar el error y enviar respuesta de error
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    
    
    public function SubmitRegister() {
        try {
            $expectedcash = $this->input->post('expectedcash');
            $countedcash = $this->input->post('countedcash');
            $countedcashusd = $this->input->post('cashusd');
            $expectedcc = $this->input->post('expectedcc');
            $countedcc = $this->input->post('countedcc');
            $expectedcheque = $this->input->post('expectedcheque');
            $countedcheque = $this->input->post('countedcheque');
            $RegisterNote = $this->input->post('RegisterNote');
            $tiptotal = $this->input->post('tiptotal');
    
            // Verifica que todos los datos requeridos estén presentes
            if (is_null($expectedcash) || is_null($countedcash) || is_null($expectedcc) || is_null($countedcc) || is_null($expectedcheque) || is_null($countedcheque) || is_null($RegisterNote) || is_null($tiptotal)) {
                echo json_encode(array("error" => "Missing required POST parameters."));
                return;
            }
    
            // Obtenemos el registro de caja
            $Register = Register::find($this->register);

            if ($Register) {
                // Obtenemos la tienda asociada al registro de caja
                $store = Store::find($Register->store_id);


                if ($store) {
                    // Actualizamos el estado de la tienda
                    $store->status = 0;
                    $store->save();
                }

                // Creamos los datos para actualizar el registro de caja
                $data = array(
                    "cash_total" => $expectedcash,
                    "cash_sub" => $countedcash,
                    "cash_usd" =>$countedcashusd,
                    "cc_total" => $expectedcc,
                    "cc_sub" => $countedcc,
                    "cheque_total" => $expectedcheque,
                    "cheque_sub" => $countedcheque,
                    "tip_total" => $tiptotal,
                    "note" => $RegisterNote,
                    "closed_by" => $this->session->userdata('user_id'),
                    "closed_at" => date("Y-m-d H:i:s"),
                    "status" => 0
                );
                // Actualizamos el registro de caja
                $Register->update_attributes($data);

                // Actualizamos la sesión del registro actual
                $CI = & get_instance();
                $CI->session->set_userdata('register', 0);

                echo json_encode(array("status" => TRUE));
            } else {
                echo json_encode(array("error" => "Couldn't find Register with ID={$this->register}"));
            }
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage(), "store" => $store, "register" => $Register));
        }
    }
        
    
    public function email()
    {
        $email = $this->input->post('email');
        $content = $this->input->post('content');
        $this->load->library('email');//carga la libreria email, antes configurar

        $this->email->set_mailtype("html");
        $this->email->from('no-reply@' . $this->setting->companyname . '.com', $this->setting->companyname);
        $this->email->to('$email');

        $this->email->subject('Su recibo, Gracias por su compra');
        $this->email->message($content);

        $this->email->send();

        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function pdfreceipt()
    {
        $content = $this->input->post('content');
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Copia Boleta');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(5);
        $pdf->setFooterMargin(7);
        $pdf->SetAutoPageBreak(false);//true habilita más páginas
        $pdf->SetAuthor('Pixelandhost Restaurante');
        $pdf->SetDisplayMode('real', 'default');
        // add a page
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, 'C', true);//L=left C=center

        ob_end_clean();
        $pdf->Output('cookie.pdf', 'D');
    }

        public function pdfinvoice()
    {
        $content = $this->input->post('content');
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Copia Factura');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Pixelandhost Restaurante');
        $pdf->SetDisplayMode('real', 'default');
        // add a page
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);//L=left C=center

        ob_end_clean();
        $pdf->Output('Factura.pdf', 'D');
    }



    public function storewaitercash($id)
    {
      $waiters = Waiter::find('all', array('conditions' => array('store_id = ?', $id)));
      $content = '';
      foreach ($waiters as $waiter) {
         $content .= '<div class="form-group"><label for="CashinHand"><u>'.$waiter->name.'</u> '.label("CashinHand").'</label><input type="number" step="any" class="form-control" id="waiterid" waiter-id="'.$waiter->id.'" placeholder="'.$waiter->name.' '.label("CashinHand").'" Required></div>';
      }
      echo $content;
   }

   public function WaiterName($num = null)
   {
      $waiterid = Hold::find('first', array(
          'conditions' => array(
             'number = ? AND register_id = ? AND table_id = ?',
             $num,
             $this->register,
             $this->selectedTable
          )
      ))->waiter_id;
      echo $waiterid;
   }
   public function changewaiterS()
   {
      $num = $this->input->post('num');
      $id = $this->input->post('id');
      $hold = Hold::find('first', array(
          'conditions' => array(
             'number = ? AND register_id = ? AND table_id = ?',
             $num,
             $this->register,
             $this->selectedTable
          )
      ));
      $hold->waiter_id = $id;
      $hold->save();

      echo json_encode(array(
          "status" => TRUE
      ));
   }

   public function CustomerName($num = null)
   {
      $customerid = Hold::find('first', array(
          'conditions' => array(
             'number = ? AND register_id = ? AND table_id = ?',
             $num,
             $this->register,
             $this->selectedTable
          )
      ))->customer_id;
      echo $customerid;
   }
   public function changecustomerS()
   {
      $num = $this->input->post('num');
      $id = $this->input->post('id');
      $hold = Hold::find('first', array(
          'conditions' => array(
             'number = ? AND register_id = ? AND table_id = ?',
             $num,
             $this->register,
             $this->selectedTable
          )
      ));
      $hold->customer_id = $id;
      $hold->save();

      echo json_encode(array(
          "status" => TRUE
      ));
   }

// solo muestra ticket guardado // no muestra ticket de cada sucursal o tienda
   public function ShowTicket($id)
    {
        $register = Register::find($this->register);//agregado recientemente
        $store = Store::find($register->store_id);//agregado recientemente
        $sale = Sale::find('last');//ultima venta
        $waiters = Waiter::find($sale->waiter_id);// nombre de cajero //captura id devuelve nombre
        $posales = Sale_item::find('all', array(
            'conditions' => array(
                'sale_id = ?',$sale->id,
            )
        ));

        //diseño del ticket producto,cantidad y subtotal 
        //
       $ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
	   $ticket .= '<div class="col-xs-10">
        <div class="text-center">' . $this->setting->receiptheader . '</div>

        <div class="text-center"><b>' . label("SaleNum") . '.: ' . sprintf("%05d", $sale->id) . '</b></div> 
        <div style="clear:both;"></div>
        <span class="float-left">' . label("Date") . ': ' . $sale->created_at->format('d-m-Y') . '</span>
        <div style="clear:both;"></div>
         <span class="float-left">' . label("Hour") . ': ' . $sale->created_at_hour . '</span>
        <div style="clear:both;">
        <span class="float-left">' . label("Customer") . ': ' . $sale->clientname . '</span>
        <div style="clear:both;">
        <span class="float-left">' . label("Waiters") . ': ' . $waiters->name . '</span>
        <div style="clear:both;"></div>
		</font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
        <table class="table" cellspacing="0" border="0">
        <thead><tr>
        <th style="width:180px; font-weight:bold;">PRODUCTOS</th>
        <th style="width:65px; font-weight:bold;">CANT</th>
        <th style="text-align:right; width:85px; font-weight:bold;">IMPORTE</th>
        </tr>
        </thead><tbody>';

        $i = 1;
        foreach ($posales as $posale) {
            $ticket .= '<tr>
            <td style="text-align:left; width:180px;">' . $posale->name . '</td>
            <td style="text-align:center; width:65px;">' . $posale->qt . '</td>
            <td style="text-align:right; width:85px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') .'</td>
            </tr>';
            $i ++;
        }

        // barcode 
        $bcs = 'code128';
        $height = 20;
        $width = 3;
        $ticket .= '<tr><td colspan="3" > &nbsp;</td><tr>
		<tr>
        <td style="text-align:right; ">' . $sale->totalitems . '</td>
        <td style="text-align:left;">' . label("Total") . '</td>
        <td style="text-align:right;font-weight:bold;">' . number_format((float)$sale->subtotal, $this->setting->decimals, '.', '') . ' '.$sale->divisa.'</td></tr>';
        
		
		if (intval($sale->tax))
            $ticket .= '<tr>
          <td colspan="2" style="text-align:left;">' . label("tax") . " " . $sale->tax .'</td>
          <td style="text-align:right;font-weight:bold;">' . number_format((float)$sale->taxamount, $this->setting->decimals, '.', '') . '</td></tr>';
		
		if (intval($sale->discount))
            $ticket .= '<tr>
          <td colspan="2" style="text-align:left;">' . label("Discount") . " " . $sale->discount . '</td>
          <td style="text-align:right; font-weight:bold;">' . number_format((float)$sale->discountamount, $this->setting->decimals, '.', '') . '</td></tr>';
        
		if (intval($sale->tip))
            $ticket .= '<tr>
          <td colspan="2" style="text-align:left;">' . label("Tip") . " Sugerida  ". $sale->tip . '</td>
          <td style="text-align:right;font-weight:bold;">' . number_format((float)$sale->tipamount, $this->setting->decimals, '.', '') .'</td>
		  </tr>';
		
		

        $ticket .= '<tr>
        <td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">TOTAL A PAGAR</td>
        <td colspan="2" style="border-top:1px dashed #000; padding-top:5px; text-align:right; font-weight:bold;">' . number_format((float)$sale->total, $this->setting->decimals, '.', '') . ' '.$sale->divisa.'</td>
        </tr><tr>';

        $PayMethode = explode('~', $sale->paidmethod);
        switch ($PayMethode[0]) {
            case '1': // case tarjeta de credito //anulada
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("CreditCard") . '</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">xxxx xxxx xxxx ' . substr($PayMethode[1], - 4) . '</td>
                </tr><tr>
                <td colspan="2" style="text-align:left; font-weight:bold; ">' . label("CreditCardHold") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[2] . '</td>
                </tr>';
                break;
            case '2': // case ckeck
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("ChequeNum") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[1] . '</td></tr>';
                break;
            default:
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; ">' . label("Paid") . ' (' . label("Cash") . ')</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">' . number_format((float)$sale->firstpayement, $this->setting->decimals, '.', ''). ' '.$sale->divisa.'</td></tr><tr>

                <td colspan="2" style="text-align:left; font-weight:bold;">' . label("Change") . '</td>
                <td colspan="2" style="text-align:right; font-weight:bold;">' . number_format((float)(floatval($sale->paid) - floatval($sale->total)), $this->setting->decimals, '.', '') . '</td>


                </tr>';
        }

        $payements = Payement::find('all', array('conditions' => array('sale_id = ?', $id)));
        if($payements){
           $ticket .= '<tr>';
          foreach ($payements as $pay) {
             $PayMethode = explode('~', $pay->paidmethod);
             switch ($PayMethode[0]) {
                case '1': // case Credit Card
                    $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("CreditCard") . ' (xxxx xxxx xxxx ' . substr($PayMethode[1], - 4) . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">'. number_format((float)$pay->paid, $this->setting->decimals, '.', '') .'</td></tr><tr><td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("CreditCardHold") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[2] . '</td></tr>';
                    break;
                case '2': // case ckeck
                    $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("ChequeNum") . ' (' . $PayMethode[1] . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">'.number_format((float)$pay->paid, $this->setting->decimals, '.', '').'</td></tr>';
                    break;
                default:
                    $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold;">' . label("Paid") . ' (' . label("Cash") . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">'. number_format((float)$pay->paid, $this->setting->decimals, '.', '') . '</td></tr>';
           }
          }
       } else{
          $ticket .= '</tbody></table>';
       }

        $ticket .= '</font><div style="border-top:1px solid #000; padding-top:10px;">
        <span class="float-left">' . $store->name . '</span>
        <span class="float-right">' . label("Tel") . ' ' . ($store->phone ? $store->phone : $this->setting->phone) . '</span>
        <div style="clear:both;"><center><img style="margin-top:30px" src="' . site_url('pos/GenerateBarcode/' . sprintf("%05d", $sale->id) . '/' . $bcs . '/' . $height . '/' . $width) . '" alt="' . $sale->id . '" /></center><p class="text-center" style="margin:0 auto;margin-top:10px;">' . $store->footer_text . '</p><div class="text-center" style="background-color:#95a5a6;padding:5px;width:85%;color:#fff;margin:0 auto;border-radius:2px;margin-top:40px;">' . $this->setting->receiptfooter . '</div>
        </div>';

        echo $ticket;
    }

    public function showticketKit($tableid)
    {
        $table = Table::find($tableid);
        $tableN = $table->name;
        $posales = Posale::find('all', array(
            'conditions' => array(
                'table_id = ?',
                $tableid
            )
        ));
        foreach ($posales as $posale) {
            if ($posale->site === 0) {
                $d1 = new DateTime($posale->time);
                $d2 = new DateTime($table->checked);
                if ($d1 < $d2) {
                    $posale->time = 'y';
                } else {
                    $posale->time = 'n';
                }
            }
        }
        $table->checked = date("Y-m-d H:i:s");
        $formattedCheckedTime = (new DateTime($table->checked))->format('H:i:s');
        $table->save();

        $ticket = '<div style="font-family:' . $this->setting->fontticket . ';">';
        $ticket .= '<div class="text-center" style="font-size: 24px; color:Black;">'; // Cambié el tamaño de la fuente a 24px
        $ticket .= label("Table") . ': ' . $tableN . ' - ' . $formattedCheckedTime . '</div><br><br>';
        $ticket .= '<div style="font-size:18px; color:Black;">'; // Cambié el tamaño de la fuente a 18px
        $ticket .= '<table class="table" cellspacing="0" border="0">
        <thead><tr>
        <th style="text-align:left; width:250px;"><b>' . strtoupper(label("Product")) . '</b></th>
        <th style="text-align:center; width:100px;"><b>' . strtoupper(label("Quantity")) . '</b></th>
        </tr>
        </thead><tbody>';

        foreach ($posales as $posale) {
            if ($posale->time != "n") {
                if ($posale->site === 0) {
                    $ticket .= '<tr>';
                    $ticket .= '<td style="text-align:left; width:250px; font-size:18px;">' . $posale->name . '<br>'; // Cambié el tamaño de la fuente a 18px
                    $ticket .= '<span style="font-size:16px;color:#666">' . $posale->options . '</span></td>'; // Cambié el tamaño de la fuente a 16px
                    $ticket .= '<td style="text-align:center; width:100px; font-size:18px;">' . $posale->qt . '</td>'; // Cambié el tamaño de la fuente a 18px
                    $ticket .= '</tr>';
                }
            }
        }

        $ticket .= '<tr><td colspan="2"><b>AGREGADOS RECIENTEMENTE:</b></td></tr>';

        foreach ($posales as $posale) {
            if ($posale->time == "n") {
                if ($posale->site === 0) {
                    $ticket .= '<tr style="background-color:#2ecc71;">';
                    $ticket .= '<td style="text-align:left; width:250px; font-size:18px;">' . $posale->name . '<br>'; // Cambié el tamaño de la fuente a 18px
                    $ticket .= '<span style="font-size:16px;color:#666">' . $posale->options . '</span></td>'; // Cambié el tamaño de la fuente a 16px
                    $ticket .= '<td style="text-align:center; width:100px; font-size:18px;">' . $posale->qt . '</td>'; // Cambié el tamaño de la fuente a 18px
                    $ticket .= '</tr>';
                }
            }
        }

        $ticket .= '</tbody></table></div></div>';

        echo $ticket;
    }


   public function showticketKitmesa()
   { 
      $tableid=intval($this->selectedTable);  
      
	  if ($tableid <> 0) {
		$table = Table::find($tableid);
		$tableN = $table->name;
		$posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			$tableid
			)
		));
		foreach ($posales as $posale) {
			if ($posale->site === 0){   
				$d1 = new DateTime($posale->time);
				$d2 = new DateTime($table->checked);
				if($d1 < $d2){
					$posale->time = 'y';
				}else{
					$posale->time = 'n';
				}			
			}
		}
		$table->checked = date("Y-m-d H:i:s");
		$table->save();

		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
		$ticket .= '<div class="col-md-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br></font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<div style="clear:both;">' . label("Table") . ' :' . $tableN . '</span>
		<div style="clear:both;"><br><br>
		<table class="table" cellspacing="0" border="0">
		<thead><tr>' . //<th style="text-align:center; width:30px;"><em>#</em></th>
		'<th style="text-align:left; width:180px;"><b>' . strtoupper(label("Product")) . '</b></th>
		<th style="text-align:center; width:50px;"><b>' . strtoupper(label("Quantity")) . '</b></th>' .
		//<th style="text-align:center; width:50px;">Hora</th>' .
		//<th style="text-align:right; width:70px;">' . label("SubTotal") . '</th>
		'</tr>
		</thead><tbody>';

		$i = 1;
		foreach ($posales as $posale) {
			if ($posale->time != "n"){ 
				if ($posale->site === 0){ 
					$ticket .= '<tr style="'.($posale->time == "n" ? 'background-color:#2ecc71;' : '').'">';
					//<td style="text-align:center; width:30px;">' . $i . '</td>
					$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
					<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
					<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
					//<td style="text-align:right; width:70px;font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . ' ' . $this->setting->currency . '</td>
					//'<td style="text-align:center; width:50px;">' . date($posale->time) . '</td>' .
					'</tr>';
				}
			}
			$i ++;		  
		}
		$ticket .= '<tr ><td> </td><td></td></tr><tr><td colspan=2><B>AGREGADOS  RESIENTEMENTE:</B></td></tr>'; 
	  
		foreach ($posales as $posale) {
			if ($posale->time == "n"){ 
				if ($posale->site === 0){ 
					$ticket .= '<tr style="'.($posale->time == "n" ? 'background-color:#2ecc71;' : '').'">';
					// <td style="text-align:center; width:30px;">' . $i . '</td>
					$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
					<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
					<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
					//<td style="text-align:right; width:70px;font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . ' ' . $this->setting->currency . '</td>
					//'<td style="text-align:center; width:50px;">' . date($posale->time) . '</td>' .
					'</tr>';
				}
			}
			$i ++;
		}

		$ticket .= '</tbody></table></font>';
	  }
	  else {
		$posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			0
			)
		));
		  
		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
		$ticket .= '<div class="col-md-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br></font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<div style="clear:both;">' . label("Table") . ' :</span>
		<div style="clear:both;"><br><br>
		<table class="table" cellspacing="0" border="0">
		<thead><tr>' . //<th style="text-align:center; width:30px;"><em>#</em></th>
		'<th style="text-align:left; width:180px;"><b>' . strtoupper(label("Product")) . '</b></th>
		<th style="text-align:center; width:50px;"><b>' . strtoupper(label("Quantity")) . '</b></th>' .
		'</tr>
		</thead><tbody>';
	  
		$i = 1;
		foreach ($posales as $posale) {
			if ($posale->site === 0){ 
				$ticket .= '<tr style="'.($posale->time == "n" ? 'background-color:#2ecc71;' : '').'">';
				$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
				<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
				<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
				'</tr>';
			}
           $i ++;  
		}
		$ticket .= '<tr ><td> </td><td></td></tr><tr></tr>'; 
      }
      echo $ticket;
   }

   
   public function showticketDri($tableid) /*JAR03*/
    {
        $table = Table::find($tableid);
        $tableN = $table->name;
        $posales = Posale::find('all', array(
            'conditions' => array(
                'table_id = ?',
                $tableid
            )
        ));
        foreach ($posales as $posale) {
            if ($posale->site === 1) {
                $d1 = new DateTime($posale->timedri);
                $d2 = new DateTime($table->checkeddri);
                if ($d1 < $d2) {
                    $posale->timedri = 'y';
                } else {
                    $posale->timedri = 'n';
                }
            }
        }
        $table->checkeddri = date("Y-m-d H:i:s");
        $formattedCheckedDriTime = (new DateTime($table->checkeddri))->format('H:i:s');
        $table->save();

        $ticket = '<div style="font-family:' . $this->setting->fontticket . ';">';
        $ticket .= '<div class="text-center" style="font-size: 24px; color:Black;">';
        $ticket .= label("Table") . ': ' . $tableN . ' - ' . $formattedCheckedDriTime . '</div><br><br>';
        $ticket .= '<div style="font-size:18px; color:Black;">';
        $ticket .= '<table class="table" cellspacing="0" border="0">
        <thead><tr>
        <th style="text-align:left; width:250px;"><b>' . strtoupper(label("Product")) . '</b></th>
        <th style="text-align:center; width:100px;"><b>' . strtoupper(label("Quantity")) . '</b></th>
        </tr>
        </thead><tbody>';

        foreach ($posales as $posale) {
            if ($posale->timedri != "n") {
                if ($posale->site === 1) {
                    $ticket .= '<tr>';
                    $ticket .= '<td style="text-align:left; width:250px; font-size:18px;">' . $posale->name . '<br>';
                    $ticket .= '<span style="font-size:16px;color:#666">' . $posale->options . '</span></td>';
                    $ticket .= '<td style="text-align:center; width:100px; font-size:18px;">' . $posale->qt . '</td>';
                    $ticket .= '</tr>';
                }
            }
        }

        $ticket .= '<tr><td colspan="2"><b>AGREGADOS RECIENTEMENTE:</b></td></tr>';

        foreach ($posales as $posale) {
            if ($posale->timedri == "n") {
                if ($posale->site === 1) {
                    $ticket .= '<tr style="background-color:#2ecc71;">';
                    $ticket .= '<td style="text-align:left; width:250px; font-size:18px;">' . $posale->name . '<br>';
                    $ticket .= '<span style="font-size:16px;color:#666">' . $posale->options . '</span></td>';
                    $ticket .= '<td style="text-align:center; width:100px; font-size:18px;">' . $posale->qt . '</td>';
                    $ticket .= '</tr>';
                }
            }
        }

        $ticket .= '</tbody></table></div></div>';

        echo $ticket;
    }

   
   public function showticketDrimesa() /*JAR03*/
   {  
	  $tableid=intval($this->selectedTable);
      
	  if ($tableid<> 0){
		$table = Table::find($tableid);
		$tableN = $table->name;
		$posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			$tableid
			)
		));
		foreach ($posales as $posale) {
			if ($posale->site === 1){ 		 
				$d1 = new DateTime($posale->timedri);
				$d2 = new DateTime($table->checkeddri);
				if($d1 < $d2){
					$posale->timedri = 'y';
				}else{
					$posale->timedri = 'n';
				}	
			}
		}
		$table->checkeddri = date("Y-m-d H:i:s");
		$table->save();

		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';	
		$ticket .= '<div class="col-md-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br</font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<div style="clear:both;">' . label("Table") . ' :' . $tableN . '</span>
		<div style="clear:both;"><br><br>
		<table class="table" cellspacing="0" border="0">
		<thead><tr>' . //<th style="text-align:center; width:30px;"><em>#</em></th>
		'<th style="text-align:left; width:180px;"><b>' . strtoupper(label("Product")) . '</b></th>
		<th style="text-align:center; width:50px;"><b>' . strtoupper(label("Quantity")) . '</b></th>' .
		'</tr>
		</thead><tbody>';

		$i = 1;
		foreach ($posales as $posale) {
			if ($posale->timedri != "n"){ 
				if ($posale->site === 1){ 
					$ticket .= '<tr style="'.($posale->timedri == "n" ? 'background-color:#2ecc71;' : '').'">';
					//<td style="text-align:center; width:30px;">' . $i . '</td>
					$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
					<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
					<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
					//<td style="text-align:right; width:70px;font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . ' ' . $this->setting->currency . '</td>
					//'<td style="text-align:center; width:50px;">' . date($posale->time) . '</td>' .
					'</tr>';
				}
			}
			$i ++;
		}
		$ticket .= '<tr ><td> </td><td></td></tr><tr><td colspan=2><B>AGREGADOS  RESIENTEMENTE:</B></td></tr>'; 
	  
		foreach ($posales as $posale) {
			if ($posale->timedri == "n"){ 
				if ($posale->site === 1){ 
					$ticket .= '<tr style="'.($posale->timedri == "n" ? 'background-color:#2ecc71;' : '').'">';
					// <td style="text-align:center; width:30px;">' . $i . '</td>
					$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
					<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
					<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
					//<td style="text-align:right; width:70px;font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . ' ' . $this->setting->currency . '</td>
					//'<td style="text-align:center; width:50px;">' . date($posale->time) . '</td>' .
					'</tr>';
				}
			}
			$i ++;
		}

		$ticket .= '</tbody></table></font>';
	  }
	  else {
		$posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			0
			)
		));
			
		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';	
		$ticket .= '<div class="col-md-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br</font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<div style="clear:both;">' . label("Table") . ' :</span>
		<div style="clear:both;"><br><br>
		<table class="table" cellspacing="0" border="0">
		<thead><tr>' . //<th style="text-align:center; width:30px;"><em>#</em></th>
		'<th style="text-align:left; width:180px;"><b>' . strtoupper(label("Product")) . '</b></th>
		<th style="text-align:center; width:50px;"><b>' . strtoupper(label("Quantity")) . '</b></th>' .
		'</tr>
		</thead><tbody>'; 
		$i = 1;
		foreach ($posales as $posale) {
			if ($posale->site === 1){ 
				$ticket .= '<tr style="'.($posale->timedri == "n" ? 'background-color:#2ecc71;' : '').'">';
				$ticket .= '<td style="text-align:left; width:180px;">' . $posale->name . '<br>
				<span style="font-size:12px;color:#666">'.$posale->options.'</span></td>
				<td style="text-align:center; width:50px;">' . $posale->qt . '</td>' .
				'</tr>';
			}
			$i ++;
		}
		$ticket .= '<tr ><td> </td><td></td></tr>'; 
	  }
    
      echo $ticket;
   }
   
   public function showticketKit1 ($taxamount,$discountamount,$Subtotal,$tipamount,$total) /*JAR*/
   {  
      $tableid=intval($this->selectedTable);
	  if ($tableid<> 0){	
	   
		$table = Table::find($tableid);
		$tableN = $table->name;  
		$posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			$tableid
			)
		));
		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
		$ticket .= '<div class="col-xs-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br>
		<div style="clear:both; font-weight:bold;">' . label("Table") . ' :' . $tableN . '</div>'.
		'<div style="clear:both;">' . date("Y-m-d H:i:s"). '</span>' .
		'<div style="clear:both;"><br><br></font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<table class="table" cellspacing="0" border="0">
		<thead><tr>
		<th style="text-align:left; font-weight:bold;">PRODUCTOS</th>
		<th style="text-align:center; font-weight:bold;">CANT</th>
		<th style="text-align:right;  font-weight:bold;">IMPORTE</th>
		</tr>
		</thead><tbody>';

		$i = 1;
		$totalitems = 0;
		$saldototal = 0;
		foreach ($posales as $posale) {
		
           $ticket .= '<tr>';
           $ticket .= '<td style="text-align:left; ">' . $posale->name . '<br>
           <span style="color:#666">'.$posale->options.'</span></td>
           <td style="text-align:center; ">' . $posale->qt . '</td>' .
           '<td style="text-align:right; font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . '</td>
		   </tr>';
		  
           $i ++;
		   $totalitems = $totalitems + $posale->qt;
	       $saldototal =  $saldototal + ($posale->qt * $posale->price);
		  
		}
	  
		// barcode 
			$bcs = 'code128';
			$height = 20;
			$width = 3;
			$ticket .= '</tbody></table>
			<table class="table" cellspacing="0" border="0" style="margin-bottom:8px; border-top:1px solid #000;">
			<tbody><tr><td style="text-align:left;">' . label("TotalItems") . '</td>
			<td style="text-align:right; padding-right:1.5%;">' . $totalitems . '</td>
			<td style="text-align:left; padding-left:1.5%;">' . label("Total") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$saldototal, $this->setting->decimals, '.', '') . '</td></tr>';
      
		// si hay un descuento, se mostrará
			if (intval($discountamount))
			$ticket .= '<tr><td style="text-align:left; padding-left:1.5%;"></td>
			<td style="text-align:right;font-weight:bold;"></td>
			<td style="text-align:left;">' . label("Discount") . '</td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;">' .  number_format((float)$discountamount, $this->setting->decimals, '.', '') .  '</td>
			</tr>'; 
         // lo mismo para el impuesto de la orden
			if (intval($taxamount))
            $ticket .= '<tr><td style="text-align:left;"></td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;"></td>
			<td style="text-align:left; padding-left:1.5%;">' . label("tax") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$taxamount, $this->setting->decimals, '.', '') . '</td></tr>';

			if (intval($tipamount))
            $ticket .= '<tr><td style="text-align:left;"></td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;"></td>
			<td style="text-align:left; padding-left:1.5%;">' . label("Tip") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$tipamount, $this->setting->decimals, '.', '') . '</td></tr>';

           // $ticket .= '</font><font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
			$ticket .= '<tr>
			<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' .
			'<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' .label("GrandTotal") . '</font></td>
			<td colspan="2" style="border-top:1px dashed #000; padding-top:5px; text-align:right; font-weight:bold;">' . 
			'<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' .number_format((float)$total, $this->setting->decimals, '.', '') .'</font></td>
			</tr><tr>';
	  
			$ticket .= '</tbody></table>';
			$ticket .= '</font><div style="border-top:1px solid #000; padding-top:10px;"><span class="float-left">' . $store->name . '</span><span class="float-right">' . label("Tel") . ' ' . ($store->phone ? $store->phone : $this->setting->phone) . '</span><div style="clear:both;"><p class="text-center" style="margin:0 auto;margin-top:10px;">' . $store->footer_text . '</p><div class="text-center" style="background-color:#95a5a6;padding:5px;width:85%;color:#fff;margin:0 auto;border-radius:2px;margin-top:20px;">' . $this->setting->receiptfooter . '</div></div>';
	  }
	  else {
		  $posales = Posale::find('all', array(
			'conditions' => array(
			'table_id = ?',
			0
			)
		));
		$ticket = '<font size='.$this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >';
		$ticket .= '<div class="col-xs-10">
		<div class="text-center">' . $this->setting->receiptheader . '</div>
		<div style="clear:both;"><br>
		<div style="clear:both;">
		<div style="clear:both;"><br>
		<div style="clear:both; font-weight:bold;">' . label("Table") . ' :</div>
		<div style="clear:both;"><br><br></font> <font size='.$this->setting->sizedetail_ticket.' color="Black" face="'.$this->setting->fontticket.'" >
		<table class="table" cellspacing="0" border="0">
		<thead><tr>
		<th style="text-align:left; font-weight:bold;">PRODUCTOS</th>
		<th style="text-align:center; font-weight:bold;">CANT</th>
		<th style="text-align:right;  font-weight:bold;">IMPORTE</th>
		</tr>
		</thead><tbody>';

		$i = 1;
		$totalitems = 0;
		$saldototal = 0;
		foreach ($posales as $posale) {
		
           $ticket .= '<tr>';
           $ticket .= '<td style="text-align:left; ">' . $posale->name . '<br>
           <span style="color:#666">'.$posale->options.'</span></td>
           <td style="text-align:center; ">' . $posale->qt . '</td>' .
           '<td style="text-align:right; font-size:14px; ">' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . '</td>
		   </tr>';
		  
           $i ++;
		   $totalitems = $totalitems + $posale->qt;
	       $saldototal =  $saldototal + ($posale->qt * $posale->price);
		  
		}
	  
		// barcode 
			$bcs = 'code128';
			$height = 20;
			$width = 3;
			$ticket .= '</tbody></table>
			<table class="table" cellspacing="0" border="0" style="margin-bottom:8px; border-top:1px solid #000;">
			<tbody><tr><td style="text-align:left;">' . label("TotalItems") . '</td>
			<td style="text-align:right; padding-right:1.5%;">' . $totalitems . '</td>
			<td style="text-align:left; padding-left:1.5%;">' . label("Total") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$saldototal, $this->setting->decimals, '.', '') . '</td></tr>';
      
		// si hay un descuento, se mostrará
			if (intval($discountamount))
			$ticket .= '<tr><td style="text-align:left; padding-left:1.5%;"></td>
			<td style="text-align:right;font-weight:bold;"></td>
			<td style="text-align:left;">' . label("Discount") . '</td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;">' .  number_format((float)$discountamount, $this->setting->decimals, '.', '') .  '</td>
			</tr>'; 
         // lo mismo para el impuesto de la orden
			if (intval($taxamount))
            $ticket .= '<tr><td style="text-align:left;"></td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;"></td>
			<td style="text-align:left; padding-left:1.5%;">' . label("tax") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$taxamount, $this->setting->decimals, '.', '') . '</td></tr>';

			if (intval($tipamount))
            $ticket .= '<tr><td style="text-align:left;"></td>
			<td style="text-align:right; padding-right:1.5%;font-weight:bold;"></td>
			<td style="text-align:left; padding-left:1.5%;">' . label("Tip") . '</td>
			<td style="text-align:right;font-weight:bold;">' . number_format((float)$tipamount, $this->setting->decimals, '.', '') . '</td></tr>';


			$ticket .= '<tr>
			<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' .
            '<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' .	label("GrandTotal") . '</font></td>
			<td colspan="2" style="border-top:1px dashed #000; padding-top:5px; text-align:right; font-weight:bold;">' .
			'<font size='. $this->setting->sizehead_ticket.' color="Black" face="'.$this->setting->fontticket.'" >' . number_format((float)$total, $this->setting->decimals, '.', '') .'</font></td>
			</tr><tr>';
	  
			$ticket .= '</tbody></table>';
			$ticket .= '</font><div style="border-top:1px solid #000; padding-top:10px;"><span class="float-left">' . $store->name . '</span><span class="float-right">' . label("Tel") . ' ' . ($store->phone ? $store->phone : $this->setting->phone) . '</span><div style="clear:both;"><p class="text-center" style="margin:0 auto;margin-top:10px;">' . $store->footer_text . '</p><div class="text-center" style="background-color:#95a5a6;padding:5px;width:85%;color:#fff;margin:0 auto;border-radius:2px;margin-top:20px;">' . $this->setting->receiptfooter . '</div></div>';
	  }
      echo $ticket;
   }	 
   

   public function getoptions($id, $posale)
   {
      $options = Product::find($id)->options;
      $options = trim($options, ",");
      $array = explode(',', $options); //dividir cadena en matriz separada por ','
      $poOptions = Posale::find($posale)->options;
      $poOptions = trim($poOptions, ",");
      $array2 = explode(',', $poOptions); //dividir cadena en matriz separada por ','
      $result = '<div class="col-md-12"><input type="hidden" value="'.$posale.'" id="optprd"><select class="js-select-basic-multiple form-control" multiple="multiple" id="optionsselect">';
      foreach ($array as $value) {
         $selected = '';
         foreach ($array2 as $value2) { $selected = $value == $value2 ? 'selected="selected"' : $selected;}
         $result .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
      }
      $result .= '</select></div>';
      echo $result;
   }

   public function addposaleoptions()
   {
      $options = $this->input->post('options');
      $posaleid = $this->input->post('posale');
      $option = '';
      foreach ($options as $value) {
         $option .= $value.',';
      }
      $posale = Posale::find($posaleid);
      $posale->options = $option;
      $posale->time = date("Y-m-d H:i:s");
      $posale->save();

      echo json_encode(array(
          "status" => TRUE
      ));
   }

   public function CloseTable()
   {
	   
      Hold::delete_all(array(
          'conditions' => array(
              'table_id = ? AND register_id = ?',
              intval($this->selectedTable),
              $this->register
          )
      ));
      Posale::delete_all(array(
          'conditions' => array(
             'table_id = ? AND register_id = ?',
             intval($this->selectedTable),
             $this->register
          )
      ));

      if($this->selectedTable != '0h'){

         $table = Table::find($this->selectedTable);
            $table->status = 0;
            $table->time = '';
			$table->timedri = '';
			$table->hora = ''; /*JAR*/
			$table->user_id = 0; /*JAR01*/
            $table->save();
      }

      $CI = & get_instance();
      $CI->session->set_userdata('selectedTable', 0);

      echo json_encode(array(
          "status" => TRUE
      ));
   }
}
