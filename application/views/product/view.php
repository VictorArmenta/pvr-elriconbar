<!-- Page Content -->
<div class="container">
   <div class="row" style="margin-top:13px;">
      <div class="panel panel-default">
        <div class="panel-body">
          <h3><?=label('Product');?></h3>
        </div>
      </div>
      <form action="products" method="post" class="form-inline float-right hidden-xs hidden-sm" style="margin-bottom:-50px;">
         <label for="Supplier"><?=label("Supplier");?></label>
         <select class="form-control" id="Supplier" name="filtersupp">
            <option value=''><?=label("All");?></option>
            <?php foreach ($suppliers as $supplier):?>
               <option value="<?=$supplier->name;?>" <?=$supplierF === $supplier->name ? 'selected' : ''; ?>><?=$supplier->name;?></option>
            <?php endforeach;?>
         </select>
         <label for="Producttype"><?=label("ProductType");?></label>
         <select class="form-control" id="Producttype" name="filtertype">
            <option value=''><?=label("All");?></option>
            <option value="0" <?=$typeF === '0' ? 'selected' : ''; ?>><?=label("Standard");?></option>
            <!--<option value="1" <?=$typeF === '1' ? 'selected' : ''; ?>><?=label("Service");?></option>-->
            <option value="2" <?=$typeF === '2' ? 'selected' : ''; ?>><?=label("combination");?></option>
			<option value="3" <?=$typeF === '3' ? 'selected' : ''; ?>><?=label("yendo");?></option>
         </select>
         <button type="submit" class="btn btn-default"><?=label("ApplyFilter");?></button>
      </form>
      <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="hidden-xs"><?=label("ProductCode");?></th>
				  
                  <th><?=label("ProductName");?></th>
                  <th><?=label("Category");?></th>
                  <th class="hidden-xs"><?=label("Type");?></th>
                  <!--<th class="hidden-xs"><?=label("ProductTax");?></th>-->
                  <!-- <th><?=label("price");?></th> -->
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php foreach ($products as $product):
			 $sTipoProd = "";
			 switch ($product->type) {
				case 0: $sTipoProd = label("Standard"); break;
				case 1: $sTipoProd = label("Service"); break;
				case 2: $sTipoProd = label("combination"); break;
				case 3: $sTipoProd = label("yendo"); break;
				}
			 
			 ?>
              <tr>
                 <td class="hidden-xs productcode"><?=$product->code;?></td>
                 <td><?=$product->name;?></td>
                 <td><?=$product->category;?></td>
                 <td><?=$sTipoProd;?></td>
              <td>
                 <div class="btn-group">
                       <?php //if($this->user->role === "admin"){?><a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="products/delete/<?=$product->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i></a><?php// } ?>
                       <a class="btn btn-warning" href="javascript:void(0)" onclick="Viewproduct(<?=$product->id;?>)"><i class="fa fa-file-text" data-toggle="tooltip" data-placement="top" title="<?=label('Viewproduct');?>"></i></a>
                       <?php// if ($this->user->role === "admin"){?><a class="btn btn-primary" href="products/edit/<?=$product->id;?>" data-toggle="tooltip" data-placement="top" title="<?=label('Edit');?>"><i class="fa fa-pencil"></i></a>  <?//}?>
                       <?php if($this->user->role === "admin" && ($product->type === 0 || $product->type === 3) ){?><a class="btn btn-success" href="javascript:void(0)" onclick="modifystock(<?=$product->id;?>)"><i class="fa fa-tasks" data-toggle="tooltip" data-placement="top"  title='<?=label("ModifyStock");?>'></i></a>
					   <a class="btn btn-warning" href="javascript:void(0)" onclick="Viewproductcompra(<?=$product->id;?>)"><i class="fa fa-shopping-cart" data-toggle="tooltip" data-placement="top" title="<?=label('Viewpurchase');?>"></i></a>
					   
					   <?php } ?>
					   <? if ($product->size > 0 && $product->size_product_id <> 0 && ($this->user->role === "admin" && ($product->type === 0) )){
						?><a class="btn btn-success" href="javascript:void(0)" onclick="modifystock2(<?=$product->id;?>)"><i class="fa fa-share-square-o" data-toggle="tooltip" data-placement="top"  title='<?=label("ModifyStock");?>'></i></a>   
					   <?}?>   
                       <?php if($product->photo){ ?>
                       <a class="btn <?=$product->color;?> white open-modalimage"data-id="<?=$product->photo;?>" href="" data-toggle="modal" data-target="#ImageModal">
                         <i class="fa fa-picture-o" data-toggle="tooltip" data-placement="top" title="<?=label('ViewImage');?>"></i>
                       </a><?php } ?>
                       <a class="btn btn-default" href="javascript:void(0)" data-toggle="modal" data-target="#barcode" onclick="productBcode = <?=$product->code;?>"><i class="fa fa-barcode" data-toggle="tooltip" data-placement="top" title="<?=label('printBarcodes');?>"></i></a>
                     </div>
                  </td>
              </tr>
           <?php endforeach;?>
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#Addproduct"><?=label("AddProduct");?></button>

   <div class=" float-right">
     <a class="btn btn-add" href="products/xls"><?=label("DownloadXLS");?></a>
     <!--  <a class="btn btn-add" href="products/csv"><?=label("DownloadCSV");?></a>   
     <a class="btn btn-add btn-xs" data-toggle="modal" data-target="#ImportProducts"><?=label("UploadCSVfile");?></a> -->
      <a class="btn btn-lg btn-red" style="margin-bottom: 100px;" data-toggle="modal" data-target="#PrintMenu"><?=label("PrintMenu");?> <i class="fa fa-print"></i></a>
   </div>
</div>

<div class="modal fade" id="ImageModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title" id="myModalLabel"><?=label("ProductImage");?></h4>
         </div>
         <div class="modal-body">
           <img id="image" src="" class="img-responsive center" alt="" />
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
         </div>
      </div>
   </div>
</div>


<script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript">
var items = [];
$(function() {
   $('#addform').submit(function()
   {
      var error = false;
      $('.productcode').each(function() {
         if($(this).text() === $("#ProductCode").val()){
            $('#codeError').show();
            error = true;
         }
      });
      if(error) return false;
       // ... continue work
   });

   $('#Type').on('change', function() {
     if( this.value == 1 ) //if service
     {
        $("#pushaceP").slideUp();
        $("#alertqty").slideUp();
        $("#supply").slideUp();
        $("#UnitP").slideUp();
		$("#Container").slideUp();
		$("#Size").slideUp();
		$("#Size_unit").slideUp();
		$("#Size_product_id").slideUp();
     } else if ( this.value == 2 ) {
        $("#pushaceP").slideUp();
        $("#alertqty").slideUp();
        $("#supply").slideUp();
        $("#UnitP").slideUp();
		$("#Container").slideUp();
		$("#Size").slideUp();
		$("#Size_unit").slideUp();
		$("#Size_product_id").slideUp();
     } else if ( this.value == 0 ) {
		 $("#pushaceP").slideDown();
        $("#alertqty").slideDown();
        $("#supply").slideDown();
        $("#UnitP").slideDown();
		$("#Container").slideDown();
		$("#Size").slideDown();
		$("#Size_unit").slideDown();
		$("#Size_product_id").slideDown(); 
	 }else {
        $("#pushaceP").slideDown();
        $("#alertqty").slideDown();
        $("#supply").slideDown();
        $("#UnitP").slideDown();
		$("#Container").slideDown();
		$("#Size").slideDown();
		$("#Size_unit").slideDown();
		$("#Size_product_id").slideUp();
     }
   });
});


$(document).on("click", ".open-modalimage", function () {
  var myId = $(this).data('id');
  $(".modal-body #image").attr("src","<?php echo site_url()?>/files/products/"+myId);
});


var quant = [];
var quantw = [];
var pricestore = [];
var productID;
$(document).ready(function() {

   $('#addform').ajaxForm({ //FormID - id of the form.

         success: function (data) {
            if(data === "service")
            {
               location.reload();
            }else if($('#Type').val() == "0") {
               $('#stockcontent').html(data);
               $('#stock').modal('show');
               $('#Addproduct').modal('hide');


               productID = $('#prodctID').val();
            } else {

               productID = $('#prodctID').val();
               $('#combocontent').html(data);
               $('#combo').modal('show');
               $('#Addproduct').modal('hide');

               $("#add_item").autocomplete({
                  source: '<?= site_url('productcontroller/suggest'); ?>',
                  minLength: 1,
                  autoFocus: false,
                  delay: 200,
                  select: function( event, ui ) {

                           event.preventDefault();
                           if (ui.item.id !== 0) {
                              var row = add_product_item(ui.item);
                              if (row) {
                                 $(this).val('');
                              }
                           } else {
                              alert('<?= label('NoProduct') ?>');
                              return false;
                           }
                        },
                  response: function (event, ui) {
                       if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                           alert('<?= label('NoProduct') ?>');
                           $('#add_item').focus();
                           $(this).val('');
                       }
                       else if (ui.content.length == 1 && ui.content[0].id != 0) {
                           ui.item = ui.content[0];
                           $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                           $(this).autocomplete('close');
                           $(this).removeClass('ui-autocomplete-loading');
                       }
                       else if (ui.content.length == 1 && ui.content[0].id == 0) {
                           alert('<?= label('NoProduct') ?>');
                           $('#add_item').focus();
                           $(this).val('');

                       }
                  }
               });

            }
      }
   });
});

function add_product_item(item, noitem) {
   if (item == null && noitem == null) {
      return false;
   }
   if(noitem != 1) {
      var item_id = 0;
      $.each(items, function(i){
         if(items[i].item_id == item.id) {
            items[i].quantity = (parseFloat(items[i].quantity) + 1);
            item_id = item.id;
            return false;
         }
      });
      if(item_id == 0) {
         item.qty = 1;
         items.push({
            'item_id': item.id,
            'quantity': item.qty,
            'code': item.code,
            'name': item.name
         });
      }
   }


   $("#Comboprd tbody").empty();
   items.forEach(function(item) {
      var Tr = $('<tr id="rowid_' + item.item_id + '" class="item_' + item.item_id + '"></tr>');
      td = '<td>' + item.name + ' (' + item.code + ')</td>';
      td += '<td><input class="form-control text-center" name="quantity" type="text" value="' + item.quantity + '" item-id="' + item.item_id + '" id="quantit"></td>';
      td += '<td class="text-center"><i class="fa fa-times tip delt" id="' + item.item_id + '" title="Remove" style="cursor:pointer;"></i></td>';
      Tr.html(td);
      Tr.prependTo("#Comboprd");
   });
   console.log(items);
   $( "[id='quantit']" ).on('change', function() {
      var itemID = $(this).attr("item-id");
      var val = $(this).val();
      items.forEach(function(e) {
         if(e.item_id == itemID) {
            e.quantity = val;
         }
      });
      console.log(items);
   });
   return true;

}

function addcombo(){
   var productID = $('#prodctID').val();
   $.ajax({
          url : "<?php echo site_url('productcontroller/addcombo')?>/",
          type: "POST",
          data: {items: items, productID: productID},
          success: function(data)
          {
             location.reload();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             alert("error");
          }
     });
}

function updatestock(){
      $.ajax({
          url : "<?php echo site_url('products/updatestock')?>/",
          data: {quant: quant, quantw: quantw, productID: productID, pricest: pricestore},
          type: "POST",
          success: function(data)
          {
            location.reload();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             alert("error");
          }
     });
    };
	
function updatestock2(){
	var stock1= parseInt($("#stock1").val());
	var quantity1= parseInt($("#quantity1").val());
	var store1= parseInt($("#store1").val());

	if ( parseInt(quantity1) <= parseInt(stock1)){
      $.ajax({
          url : "<?php echo site_url('products/updatestock2')?>/",
          data: {productID: productID, quantity1: quantity1, store1: store1, stock1: stock1},
          type: "POST",
          success: function(data)
          {  
            location.reload();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             alert("error");
          }
     });
	 
	}
	else{
		 swal("<?=label("Lowinventory");?>");  	
	} 
		
	
    };	
	
	
function modifystock(id){
   $.ajax({
       url : "<?php echo site_url('productcontroller/modifystock')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#stockcontent').html(data);
          $('#stock').modal('show');

          $( "[id='quantity']" ).on('change', function() {
            var storeID = $(this).attr("store-id");
            quant.push({
               'store_id': storeID,
               'quantity': $(this).val()
           });
           });

           $( "[id='quantityw']" ).on('change', function() {
            var warehouseID = $(this).attr("warehouse-id");
            quantw.push({
               'warehouse_id': warehouseID,
               'quantity': $(this).val()
           });
           });

           $( "[id='pricestr']" ).on('change', function() {
            var storeID = $(this).attr("store-id");
            pricestore.push({
               'store_id': storeID,
               'price': $(this).val()
           });
           });

           productID = $('#prodctID').val();
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}

function modifystock2(id){
   $.ajax({
       url : "<?php echo site_url('productcontroller/modifystock2')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#stockcontent2').html(data);
          $('#stock2').modal('show');

          $( "[id='quantity']" ).on('change', function() {
            var storeID = $(this).attr("store-id");
            quant.push({
               'store_id': storeID,
               'quantity': $(this).val()
           });
           });

           productID = $('#prodctID').val();
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}


function Viewproduct(id){
   $.ajax({
       url : "<?php echo site_url('productcontroller/Viewproduct')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#viewSectionProduct').html(data);
          $('#Viewproduct').modal('show');
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}

function Viewproductcompra(id){
   $.ajax({
       url : "<?php echo site_url('productcontroller/Viewproductcompra')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#viewSectionProduct').html(data);
          $('#Viewproduct').modal('show');
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}

$(document).on('click', '.delt', function () {
    var id = $(this).attr('id');
    $.each(items, function(i){
       if(items[i].item_id == id) {
           items.splice(i,1);
           return false;
       }
   });
    $(this).closest('#rowid_' + id).remove();
    console.log(items);
});

function modifycombo(id){
   $.ajax({
       url : "<?php echo site_url('productcontroller/modifycombo')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#combocontent').html(data);
          $('#Viewproduct').modal('hide');
          $('#combo').modal('show');
          $.ajax({
              url : "<?php echo site_url('productcontroller/getcombos')?>/"+id,
              type: "POST",
              success: function(data){
                 dataitems = JSON.parse(data);
                 dataitems.forEach(function(e) {
                    items.push({
                       'item_id': e.item_id,
                       'quantity': e.quantity,
                       'code': e.code,
                       'name': e.name
                    });
                  });
            },
              error: function (jqXHR, textStatus, errorThrown){alert("error");}
         });
          console.log(items);
          $("#add_item").autocomplete({
             source: '<?= site_url('productcontroller/suggest'); ?>',
             minLength: 1,
             autoFocus: false,
             delay: 200,
             select: function( event, ui ) {

                      event.preventDefault();
                      if (ui.item.id !== 0) {
                         var row = add_product_item(ui.item);
                         if (row) {
                            $(this).val('');
                         }
                      } else {
                         alert('<?= label('NoProduct') ?>');
                         return false;
                      }
                   },
             response: function (event, ui) {
                  if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                      alert('<?= label('NoProduct') ?>');
                      $('#add_item').focus();
                      $(this).val('');
                  }
                  else if (ui.content.length == 1 && ui.content[0].id != 0) {
                      ui.item = ui.content[0];
                      $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                      $(this).autocomplete('close');
                      $(this).removeClass('ui-autocomplete-loading');
                  }
                  else if (ui.content.length == 1 && ui.content[0].id == 0) {
                      alert('<?= label('NoProduct') ?>');
                      $('#add_item').focus();
                      $(this).val('');

                  }
             }
          });
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}


function barcode(){
   row = $('#Brrows').val();
   num = $('#Brnum').val();
   $.ajax({
       url : "<?php echo site_url('productcontroller/barcode')?>/"+row+"/"+num+"/"+productBcode,
       type: "POST",
       success: function(data)
       {
          $('#printSection').html(data);
          $('#barcodeP').modal('show');
       },
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}

function Printbarcode() {
   $('.modal-body').removeAttr('id');
   window.print();
   $('.modal-body').attr('id', 'modal-body');
}

function makePrdInvis(id, prd) {
   $.ajax({
       url : "<?php echo site_url('productcontroller/makePrdInvis')?>/"+id+"/"+prd,
       type: "POST",
       success: function(data){},
       error: function (jqXHR, textStatus, errorThrown)
       {
          alert("error");
       }
  });
}

function PrintTicket() {
 /* $('.modal-body').removeAttr('id');
   window.print();
   $('.modal-body').attr('id', 'modal-body');*/
   /*sha...*/
    printDivCSS = new String ('<link rel="stylesheet" href="<?=base_url();?>assets/css/font-awesome.min.css"><link href="https://fonts.googleapis.com/css?family=Pinyon+Script" rel="stylesheet"><link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css">');
    var newWindow = window.open();
    newWindow.document.write(printDivCSS + '<div class="container">' + document.getElementById("printmenucode").innerHTML + '</div>');
    setTimeout(function(){newWindow.print();
    newWindow.close();});
/*
   $('.modal-body').removeAttr('id');
   window.print();
   $('.modal-body').attr('id', 'modal-body');*/
  //  $('.modal-body').removeAttr('id');
  //
  //  window.print();
  //  $('.modal-body').attr('id', 'modal-body');
}

</script>

<!-- Modal -->
<div class="modal fade" id="Addproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddProduct");?></h4>
      </div>
      <?php
      $attributes = array('id' => 'addform');
      echo form_open_multipart('productcontroller/add', $attributes);
      ?>
      <div class="modal-body">    
			<div class="form-group col-md-6">		
             <label for="Type"><?=label("Type");?></label>
             <select class="form-control" name="type" id="Type">
               <option value="0"><?=label("Standard");?></option>
               <!--<option value="1"><?=label("Service");?></option>-->
               <option value="2"><?=label("combination");?></option>
			   <option value="3"><?=label("yendo");?></option>
             </select>
            </div>
			<div class="form-group col-md-6">
             <label for="Site"><?=label("Site");?></label>
             <select class="form-control" name="site" id="site">
               <option value="0"><?=label("kitchen");?></option>
               <option value="1"><?=label("drink");?></option>
             </select>
            </div>
            <div class="form-group controls  col-md-12">
             <label for="ProductCode"><?=label("ProductCode");?>*</label>
             <input type="text" maxlength="30" Required name="code" class="form-control" id="ProductCode" placeholder="<?=label("ProductCode");?>">
             <p id="codeError" class="red" hidden><?=label("codeerror");?></p>
           </div>
           <div class="form-group  col-md-12">
             <label for="ProductName"><?=label("ProductName");?>*</label>
             <input type="text" name="name" maxlength="25" Required class="form-control" id="ProductName" placeholder="<?=label("ProductName");?>">
           </div>
		   
           <div class="form-group col-md-6">
             <label for="Category"><?=label("Category");?></label>
             <select class="form-control" name="category" id="Category">
               <option value=""><?=label("Category");?></option>
               <?php foreach ($categories as $category):?>
                  <option value="<?=$category->name;?>"><?=$category->name;?>*</option>
               <?php endforeach;?>
            </select>
           </div>
		   <div class="form-group col-md-6">
             <label for="SubCategory"><?=label("SubCategory");?></label>
             <select class="form-control" name="subcategory" id="SubCategory">
               <option value="" ><?=label("SubCategory");?></option>
               <?php foreach ($subcategories as $subcategory):?>
                  <option value="<?=$subcategory->name;?>"><?=$subcategory->name;?>*</option>
               <?php endforeach;?>
            </select>
           </div>
		   <div class="form-group col-md-4" id="alertqty">
             <label for="AlertQt"><?=label("AlertQt");?></label>
             <input type="number" value="0" name="alertqt" class="form-control" id="AlertQt" placeholder="<?=label("AlertQt");?>">
           </div>
		   <div class="form-group col-md-4" id="UnitP">
             <label for="Unit"><?=label("Unit");?></label>
             <input type="text" step="any" name="unit" class="form-control" id="Unit" placeholder="<?=label("UnitM");?>">
           </div>
		    <div class="form-group col-md-4" id="Container">
             <label for="Container"><?=label("Container");?></label>
             <input type="text" step="any" name="container" class="form-control" id="Container" placeholder="<?=label("Container");?>">
           </div>
		   <div class="form-group col-md-4" id="Size">
             <label for="Size"><?=label("Size");?></label>
             <input type="number" value="0" name="size" class="form-control" id="Size" placeholder="<?=label("Size");?>">
           </div>
		   <div class="form-group col-md-4" id="Size_unit">
             <label for="Size_unit"><?=label("Size_unit");?></label>
             <input type="text" step="any" name="size_unit" class="form-control" id="Size_unit" placeholder="<?=label("UnitM");?>">
           </div>
		    <div class="form-group col-md-4">
             <label for="Price"><?=label("Price");?> Vent<!--* (<?=$this->setting->currency;?>)--></label>
             <input type="number" step="any" Required name="price" class="form-control" id="Price" placeholder="<?=label("Price");?>">
           </div> 
		   
		   <div class="form-group col-md-12" id="Size_product_id">
             <label for="Size_product_id"><?=label("Size_product_id");?></label>
             <select class="form-control" name="size_product_id" id="Size_product_id">
               <option><?=label("Size_product_id");?></option>
               <?php foreach ($productsing as $size_product_id): if ($size_product_id->type === 3){?>
                  <option value="<?=$size_product_id->id;?>"><?=$size_product_id->name;?></option>
               <?php }endforeach;?>
            </select>
           </div>
		   
		    <div class="form-group  col-md-8" id="supply">
             <label for="Supplier"><?=label("Supplier");?></label>
             <select class="form-control" name="supplier" id="Supplier">
               <option value=""><?=label("Supplier");?></option>
               <?php foreach ($suppliers as $supplier):?>
                  <option value="<?=$supplier->name;?>"><?=$supplier->name;?></option>
               <?php endforeach;?>
            </select>
           </div>
		   
		   
           <div class="form-group col-md-4" id="pushaceP">
             <label for="PurchasePrice"><?=label("PurchasePrice");?><!-- (<?=$this->setting->currency;?>)--></label>
             <input type="number" step="any" value="0" Required name="cost" class="form-control" id="PurchasePrice" placeholder="<?=label("PurchasePrice");?>">
           </div>
		   <!--
		   <div class="form-group col-md-4">
              <label for="taxType"><?=label("TaxMethod");?></label>
              <select class="form-control" name="taxmethod" id="taxType">
                <option value="0"><?=label("inclusive");?></option>
                <option value="1"><?=label("exclusive");?></option>
              </select>
           </div>	
           <div class="form-group col-md-4">
             <label for="Tax"><?=label("ProductTax");?></label>
             <input type="text" maxlength="10" name="tax" class="form-control" id="Tax" placeholder="<?=label("ProductTax");?>">
           </div>-->
          
           
           <div class="form-group">
             <label for="ProductOptions"><?=label("ProductOptions");?></label>
             <textarea class="form-control" rows="4"  id="ProductOptions" name="options"></textarea>
          </div>
           <div class="form-group">
             <label for="exampleInputFile"><?=label("Imageinput");?> (solo formato gif|jpg|jpeg|png, máximo largo y ancho 1000)</label>
             <input type="file" name="userfile" id="ImageInput">
           </div>
           <div class="form-group">
             <label for="ProductDescription"><?=label("ProductDescription");?></label>
             <textarea class="form-control" rows="6" name="description"></textarea>
          </div>
           <div class="btn-group white" data-toggle="buttons">
              <p class="help-block"><?=label("ProductColor");?></p>
              <label class="btn color01">
                 <input type="radio" name="color" id="option1" value="color01" autocomplete="off" checked> C1
              </label>
              <label class="btn color02">
                 <input type="radio" name="color" id="option2" value="color02" autocomplete="off"> C2
              </label>
              <label class="btn color03">
                 <input type="radio" name="color" id="option3" value="color03" autocomplete="off"> C3
              </label>
              <label class="btn color04">
                 <input type="radio" name="color" id="option4" value="color04" autocomplete="off"> C4
              </label>
              <label class="btn color05">
                 <input type="radio" name="color" id="option5" value="color05" autocomplete="off"> C5
              </label>
              <label class="btn color06">
                 <input type="radio" name="color" id="option6" value="color06" autocomplete="off"> C6
              </label>
              <label class="btn color07">
                 <input type="radio" name="color" id="option7" value="color07" autocomplete="off"> C7
              </label>
              <label class="btn color08">
                 <input type="radio" name="color" id="option8" value="color08" autocomplete="off"> C7
              </label>
           </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
        <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
      </div>
   <?php echo form_close(); ?>
    </div>
 </div>
</div>
<!-- /.Modal -->

<!-- Modal -->
<div class="modal fade" id="ImportProducts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddProduct");?></h4>
      </div>
      <?php
      $attributes = array('id' => 'addform');
      echo form_open_multipart('products/importcsv', $attributes);
      ?>
      <div class="modal-body">
         <div class="form-group">
           <label for="exampleInputFile"><?=label("UploadCSVfile");?></label>
           <input type="file" name="userfile" id="ImageInput">
           <p class="help-block"><a href="<?=site_url('files/products_csv.csv');?>"><?=label('DownloadSample');?></a></p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
        <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
      </div>
   <?php echo form_close(); ?>
    </div>
 </div>
</div>
<!-- /.Modal -->


  <!-- Modal combo -->
  <div class="modal fade" id="combo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="comboModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="combo"><?=label("combinations");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="combocontent">
              <!-- combo goes here -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" onclick="location.reload();"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="addcombo()"><?=label("Submit");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->

  <!-- Modal stock -->
  <div class="modal fade" id="stock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" id="stockModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="stock"><?=label("Stock");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
          <div class="alert alert-dismissible alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            El <strong>Precio final</strong> debe ser superior al <strong>Precio Compra</strong>
          </div>
           <div id="stockcontent">
              <!-- el stock está aquí -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" onclick="location.reload();"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="updatestock()"><?=label("Submit");?></button>
        </div>
      </div>
  </div>
  </div>
  <!-- /.Modal -->
  
  <!-- Modal stock 2 -->
  <div class="modal fade" id="stock2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" id="stockModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="stock2"><?=label("Stock");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="stockcontent2">
              <!-- el stock2 está aquí -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" onclick="location.reload();"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="updatestock2()"><?=label("Submit");?></button>
        </div>
      </div>
  </div>
  </div>
  <!-- /.Modal -->


  <!-- Modal view -->
  <div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view"><?=label("Viewproduct");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?=label("Close");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->
  
  <!-- Modal view compras-->
  <div class="modal fade" id="Viewproductcompra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view"><?=label("Viewproduct");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="viewSectionProduct">
              <!-- view goes here -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?=label("Close");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->


  <!-- Modal barcode -->
  <div class="modal fade" id="barcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="stockModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="barcode"><?=label("Stock");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div class="form-group col-md-6">
             <label for="Price"><?=label("RowsNumber");?></label>
             <select Required class="form-control" id="Brrows">
                <option value="12">1</option>
                <option value="6">2</option>
                <option value="4">3</option>
             </select>
           </div>
           <div class="form-group col-md-6">
             <label for="Price"><?=label("Number");?></label>
             <input type="number" Required name="num" class="form-control" id="Brnum" placeholder="<?=label("Number");?>" value="10">
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="barcode()"><?=label("Generate");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->


  <!-- Modal barcode -->
  <div class="modal fade" id="barcodeP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="stockModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="barcodeP"><?=label("Stock");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
           <div id="printSection" style="text-align: center;">
             <!-- content -->
           </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default hiddenpr" data-dismiss="modal"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="Printbarcode()"><?=label("print");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->


  <!-- Modal -->
  <div class="modal fade" id="PrintMenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="PrintMenu">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><?=label("PrintMenu");?></h4>
        </div>
        <div class="modal-body" id="modal-body">
          <div id="printmenucode">
            <div id="printSectionInvoice">
            
            <style> .headline { font-family: "Raleway Medium", cursive; background-color: #2980b9; color: white; text-align: center; padding: 2px 0; margin-top: 10px; position: relative; } .headline::before, .headline::after { content: ""; height: 1px; width: 100%; background-color: #2980b9; display: block; left: 0; position: absolute; } .headline::before { top: -6px; } .headline::after { bottom: -6px; } .opacity-small { font-size: 30px; opacity: 0.7; filter: Alpha(opacity=70); } .opacity-medium { font-size: 20px; opacity: 0.5; filter: Alpha(opacity=50); } .opacity-large { font-size: 15px; opacity: 0.25; filter: Alpha(opacity=25); } .logo-menu{ margin: 0 auto; padding: 50px 0 0 0; } .grey{ color: #aaa; }@media print { html, body { zoom: 100%; overflow:hidden !important; }}</style>

              <center><h1 style="font-family: 'Raleway Medium', font-size:65px;">Menu</h1></center>
              <?php foreach ($categories as $category):?>
              <div class="headline">
                <h1>
                  <i class="fa fa-star opacity-large"></i>
                  <i class="fa fa-star opacity-medium"></i>
                  <i class="fa fa-star opacity-medium"></i>
                  <i class="fa fa-star opacity-small"></i>
                  &nbsp;
                  <?=$category->name;?>
                  &nbsp;
                  <i class="fa fa-star opacity-small"></i>
                  <i class="fa fa-star opacity-medium"></i>
                  <i class="fa fa-star opacity-medium"></i>
                  <i class="fa fa-star opacity-large"></i>
                </h1>
              </div><hr>
                 <div class="row">
                   <?php foreach ($products as $product): if ($product->type <> 3){?>
                     <?php if($category->name == $product->category) : ?>
                     <div class="col-xs-6">
                       <div class="media" style="height: 100px;">
                         <div class="media-left">
                           <a href="#">
                             <img class="img-rounded media-object" style="max-width: 64px;max-height: 64px;" src="<?=base_url()?>files/products/<?=$product->photothumb;?>" alt="food">
                           </a>
                         </div>
                         <div class="media-body">
                           <h4 class="media-heading" style="font-family: 'Raleway Medium';"><?=$product->name;?> <span class="label label-danger pull-right"><?=number_format((float)$product->price, $this->setting->decimals, '.', '');?> <?=$this->setting->currency;?></span></h4>
                           <div class="grey"><?=$product->description;?></div>
                         </div>
                       </div>
                     </div>
                    <?php endif; ?>
                   <?php } endforeach;?>
                 </div>
            <?php endforeach;?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?=label("Close");?></button>
          <button type="button" class="btn btn-add hiddenpr" onclick="PrintTicket()"><?=label("print");?></button>
        </div>
      </div>
   </div>
  </div>
  <!-- /.Modal -->
