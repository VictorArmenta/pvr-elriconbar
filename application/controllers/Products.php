<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
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
      $type = $this->input->post('filtertype') || $this->input->post('filtertype') === '0' ? $this->input->post('filtertype') : '99';
      $typeF = $type === '99' ? '99' : 'type';
      // echo $supplierF.' = '.$supplier. ' // ' .$typeF.' = '.$type;
      $this->view_data['products'] = Product::find('all', array('conditions' => array($supplierF.' = ? AND '.$typeF.' = ?', $supplier, $type)));
      $this->view_data['productsing'] = Product::all();
      $this->view_data['supplierF'] = $supplier;
      $this->view_data['typeF'] = $type;
        $this->view_data['categories'] = Category::all();
		$this->view_data['subcategories'] = Subcategory::all();
        $this->view_data['suppliers'] = Supplier::all();
        $this->content_view = 'product/view';
    }

    //exportar en formato csv la tabla productos
    //
    public function csv()
    {  /*
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "products.csv";
        $query = "SELECT code, name, category, subcategory, supplier, cost, container, size, size_unit, ck_productos.price, FROM ck_productos";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result , $delimiter, $newline);
        force_download($filename, $data);*/
	}
	 public function xls()
    {		   
	 $this->load->library('excel');	
	 $this->excel->setActiveSheetIndex(0);         
     $this->excel->getActiveSheet()->setTitle('test worksheet');         
     $this->excel->getActiveSheet()->setCellValue('A1', 'CODIGO');
     $this->excel->getActiveSheet()->setCellValue('B1', 'TIPO');
     $this->excel->getActiveSheet()->setCellValue('C1', 'AREA');	
     $this->excel->getActiveSheet()->setCellValue('D1', 'NOMBRE');
	 $this->excel->getActiveSheet()->setCellValue('E1', 'CATEGORIA');	
     $this->excel->getActiveSheet()->setCellValue('F1', 'SUB CATEGORIA');	
	 $this->excel->getActiveSheet()->setCellValue('G1', 'PRESENTACION');	
	 $this->excel->getActiveSheet()->setCellValue('H1', 'CONTENIDO');	
	 $this->excel->getActiveSheet()->setCellValue('I1', 'UM CONTENIDO');	
	 $this->excel->getActiveSheet()->setCellValue('J1', 'PROVEEDOR');	
	 $this->excel->getActiveSheet()->setCellValue('K1', 'COSTO');	
	 $this->excel->getActiveSheet()->setCellValue('L1', 'PRECIO');	
	 $this->excel->getActiveSheet()->setCellValue('M1', 'STOCK MINIMO'); 	
     $columna = "N";
     $tiendas = Sale_item::find_by_sql("SELECT * FROM `ck_locales` ORDER BY id");
     foreach ($tiendas as $tienda):
	    $this->excel->getActiveSheet()->setCellValue($columna.'1', 'EXISTENCIA TIENDA ' . $tienda->name );	
	   ++$columna + PHP_EOL;
	 endforeach;
	 $amacenes = Sale_item::find_by_sql("SELECT * FROM `ck_almacen` ORDER BY id");
     foreach ($amacenes as $amacen):
	    $this->excel->getActiveSheet()->setCellValue($columna.'1', 'EXISTENCIA ALMACEN ' . $amacen->name );	
	   ++$columna + PHP_EOL;
	 endforeach;
	 $this->excel->getActiveSheet()->setCellValue($columna.'1', 'UM');
	 
	 
	 $linea = 1;
	 $type  = "";
	 $site  = "";
	 $prducts = Sale_item::find_by_sql("SELECT * FROM `ck_productos` ORDER BY name");
	 foreach ($prducts as $product):
	   $linea = $linea + 1;
	   switch ($product->type) {
	     case '0': $type = label("Standard"); break;
         case '1': $type = label("Service"); break;
         case '2': $type = label("combination"); break;
	     case '3': $type = label("yendo"); break;
	   }
	   switch ($product->site){
	     case '0': $site = label("kitchen"); break;
         case '1': $site = label("drink"); break;
	   }	 
	   
	   
	   $this->excel->getActiveSheet()->setCellValue('A'.$linea, $product->code);
	   $this->excel->getActiveSheet()->setCellValue('B'.$linea, $type); 
	   $this->excel->getActiveSheet()->setCellValue('C'.$linea, $site);
	   $this->excel->getActiveSheet()->setCellValue('D'.$linea, $product->name); 
	   $this->excel->getActiveSheet()->setCellValue('E'.$linea, $product->category);
	   $this->excel->getActiveSheet()->setCellValue('F'.$linea, $product->subcategory);
	   $this->excel->getActiveSheet()->setCellValue('G'.$linea, $product->container);
	   $this->excel->getActiveSheet()->setCellValue('H'.$linea, $product->size);
	   $this->excel->getActiveSheet()->setCellValue('I'.$linea, $product->size_unit);
	   $this->excel->getActiveSheet()->setCellValue('J'.$linea, $product->supplier);
	   $this->excel->getActiveSheet()->setCellValue('K'.$linea, $product->cost);
	   $this->excel->getActiveSheet()->setCellValue('L'.$linea, $product->price);
	   $this->excel->getActiveSheet()->setCellValue('M'.$linea, $product->alertqt);
	   $columna = "N";
	   $tiendas = Sale_item::find_by_sql("SELECT * FROM `ck_locales` ORDER BY id");
       foreach ($tiendas as $tienda):
			$tiendainvs = Sale_item::find_by_sql("SELECT * FROM `ck_stocks` where product_id =". $product->id." and store_id = ". $tienda->id." ORDER BY id");
			foreach ($tiendainvs as $tiendainv):
				$this->excel->getActiveSheet()->setCellValue($columna.$linea, $tiendainv->quantity );	
			endforeach;
			++$columna + PHP_EOL;
	   endforeach;
	   
	   $amacenes = Sale_item::find_by_sql("SELECT * FROM `ck_almacen` ORDER BY id");
	   foreach ($amacenes as $amacen):
			$tiendainvs = Sale_item::find_by_sql("SELECT * FROM `ck_stocks` where product_id =". $product->id." and warehouse_id = ". $amacen->id." ORDER BY id");
			foreach ($tiendainvs as $tiendainv):
				$this->excel->getActiveSheet()->setCellValue($columna.$linea, $tiendainv->quantity );	
			endforeach;
			++$columna + PHP_EOL;
	   endforeach;
	   $this->excel->getActiveSheet()->setCellValue($columna.$linea, $product->unit );
	   
	 endforeach;
	
	//$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);         
    //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);         
    //$this->excel->getActiveSheet()->mergeCells('A1:D1');           

    header('Content-Type: application/vnd.ms-excel');         
    header('Content-Disposition: attachment;filename="Productos.xls"');
    header('Cache-Control: max-age=0'); //no cache         
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');         
    
    // Forzamos a la descarga         
    $objWriter->save('php://output');
		
		
		
		
		/*
		//Cargamos la librería de excel.
        $this->load->library('excel'); $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Llamadas');
	
		//Contador de filas
        $contador = 1;
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'Número de teléfono');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'Fecha');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Mensaje');
		
		
		//Le ponemos un nombre al archivo que se va a generar.
        $archivo = "llamadas_cliente_{$id_cliente}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');*/
        
    }

    public function importcsv()
    {
        $config['upload_path'] = './files/products';
        $config['allowed_types'] = 'csv';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '500';

        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $file = $data['upload_data']['file_name'];

            $fileopen = fopen('files/products/' . $file, "r");
            if ($fileopen) {
                while (($row = fgetcsv($fileopen, 2075, ",")) !== FALSE) {
                    $filearray[] = $row;
                }
                fclose($fileopen);
            }
            array_shift($filearray);

            $fields = array(
                'code',
                'name',
                'category',
                'cost',
                'tax',
                'description',
                'price'
            );

            $final = array();
            foreach ($filearray as $key => $value) {
                $products[] = array_combine($fields, $value);
            }

            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            foreach ($products as $prdct) {
                $data = array(
                    "code" => $prdct['code'],
                    "name" => $prdct['name'],
                    "category" => $prdct['category'],
                    "cost" => $prdct['cost'],
                    "description" => $prdct['description'],
                    "tax" => $prdct['tax'],
                    "price" => $prdct['price'],
                    "color" => 'color01',
                    "photo" => '',
                    "created_at" => $date,
                    "modified_at" => $date
                );
                Product::create($data);
            }
            unlink('./files/products/' . $file);
            redirect('products');
        }
        redirect('products');
    }


    public function edit($id = FALSE)
    {
        $this->view_data['categories'] = Category::all();
		$this->view_data['subcategories'] = Subcategory::all();
        $this->view_data['suppliers'] = Supplier::all();
	    $this->view_data['productsing'] = Product::all();
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        if ($_POST) {
            $config['upload_path'] = './files/products/';
            $config['encrypt_name'] = TRUE;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_width'] = '1000';
            $config['max_height'] = '1000';

            $product = Product::find($id);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                $data = array(
                    'upload_data' => $this->upload->data()
                );
                if ($product->photo !== '') {
                    unlink('./files/products/' . $product->photo);
                    unlink('./files/products/' . $product->photothumb);
                }
                $this->resize($data['upload_data']['full_path'], $data['upload_data']['file_name']);
                $image = $data['upload_data']['file_name'];
                $image_thumb = $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext'];
                $data = array(
                   "type" => $this->input->post('type'),
				   "site" => $this->input->post('site'),
                   "code" => $this->input->post('code'),
                   "name" => $this->input->post('name'),
                   "category" => $this->input->post('category'),
                   "cost" => $this->input->post('cost'),
                   "description" => $this->input->post('description'),
                   "tax" => $this->input->post('tax'),
                   "alertqt" => $this->input->post('alertqt'),
                   "price" => $this->input->post('price'),
                   "color" => $this->input->post('color'),
                   "supplier" => $this->input->post('supplier'),
                   "unit" => $this->input->post('unit'),
                   "taxmethod" => $this->input->post('taxmethod'),
                   "options" => $this->input->post('options'),
                   "photo" => $image,
                   "photothumb" => $image_thumb,
                   "created_at" => $date,
				   "subcategory" => $this->input->post('subcategory'),
				   "size" => $this->input->post('size'),
				   "size_unit" => $this->input->post('size_unit'),
				   "size_product_id" => $this->input->post('size_product_id'),
				   "container" => $this->input->post('container'),
                   "modified_at" => $date
                );
                $product->update_attributes($data);
                if ($product->is_valid()) {
                    redirect("products", "refresh");
                } else {
                    $errorm = label('codeerror');
                    $this->session->set_flashdata('error', $errorm);
                    redirect("products/edit/" . $id);
                }
            } else {
                $data = array(
                   "type" => $this->input->post('type'),
				   "site" => $this->input->post('site'),
                   "code" => $this->input->post('code'),
                   "name" => $this->input->post('name'),
                   "category" => $this->input->post('category'),
                   "description" => $this->input->post('description'),
                   "alertqt" => $this->input->post('alertqt'),
                   "cost" => $this->input->post('cost'),
                   "tax" => $this->input->post('tax'),
                   "price" => $this->input->post('price'),
                   "color" => $this->input->post('color'),
                   "supplier" => $this->input->post('supplier'),
                   "unit" => $this->input->post('unit'),
                   "taxmethod" => $this->input->post('taxmethod'),
                   "options" => $this->input->post('options'),
                   "created_at" => $date,
				   "subcategory" => $this->input->post('subcategory'),
				   "size" => $this->input->post('size'),
				   "size_unit" => $this->input->post('size_unit'),
				   "size_product_id" => $this->input->post('size_product_id'),
				   "container" => $this->input->post('container'),
                   "modified_at" => $date
                );
                $product->update_attributes($data);
                if ($product->is_valid()) {
                    redirect("products", "refresh");
                } else {
                    $errorm = label('codeerror');
                    $this->session->set_flashdata('error', $errorm);
                    redirect("products/edit/" . $id);
                }
            }
        } else {
            $this->view_data['product'] = Product::find($id);
            $this->content_view = 'product/edit';
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product->photo !== '') {
            unlink('./files/products/' . $product->photo);
            unlink('./files/products/' . $product->photothumb);
        }
        $stock = Stock::delete_all(array('conditions' => array('product_id = ?', $id)));
        $combos = Combo_item::delete_all(array('conditions' => array('product_id = ?',$id)));
        $product->delete();
        redirect("products", "refresh");
    }

    function resize($path, $file)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = TRUE;
        $config['maintain_thum'] = TRUE;
        $config['width'] = 120;
        $config['height'] = 120;
        $config['new_image'] = './files/products/' . $file;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }

    function updatestock()
    {
      $quant = $this->input->post('quant');
      $quantw = $this->input->post('quantw');
      $pricest = $this->input->post('pricest');
      $productID = $this->input->post('productID');
      if ($quant) {
         foreach ($quant as $qt) {
            if($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $qt['store_id'], $productID))))
            {
               $item->quantity = $qt['quantity'];
               $item->save();
            } else {
               $qt['product_id'] = $productID;
               Stock::create($qt);
            }
         }
      }
      if ($pricest) {
         foreach ($pricest as $pr) {
            if($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $pr['store_id'], $productID))))
            {
               $item->price = $pr['price'];
               $item->save();
            } else {
               $pr['product_id'] = $productID;
               Stock::create($pr);
            }
         }
      }
      if ($quantw) {
         foreach ($quantw as $qt) {
            if($item = Stock::find('first', array('conditions' => array('warehouse_id = ? AND product_id = ?', $qt['warehouse_id'], $productID))))
            {
               $item->quantity = $qt['quantity'];
               $item->save();
            } else {
               $qt['product_id'] = $productID;
               Stock::create($qt);
            }
         }
      }
   }
   
   function updatestock2()
    { 
	 $quantity1 = $this->input->post('quantity1');
	 $store1 = $this->input->post('store1');
	 $productID = $this->input->post('productID');
     $stock1 = $this->input->post('stock1');   
	 	
	 
	 $prducts = Sale_item::find_by_sql("SELECT * FROM `ck_productos` where id =".$productID." ORDER BY name");
	 foreach ($prducts as $product):
	
	      if($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $store1, $product->id))))
            {
               $item->quantity = $stock1 - $quantity1 ;
               $item->save();
            }
			
		  if($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $store1, $product->size_product_id))))
            {
			   $stock2 = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $store1, $product->size_product_id)));	
			   $stocksize2 = $stock2 ? $stock2->quantity : '0';	
				
               $item->quantity = $stocksize2 + ($quantity1 * $product->size) ;
               $item->save();
            }
			else {
               $qt['product_id'] = $product->size_product_id;
			   $qt['quantity']   = $quantity1 * $product->size;
			   $qt['store_id']   = $store1;
               Stock::create($qt);
            }	
			
	 endforeach;		
	  
   }
   
}
