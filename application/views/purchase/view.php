<!-- Page Content -->
<div class="container">
   <div class="row" style="margin-top:13px;">
      <div class="panel panel-default">
        <div class="panel-body">
          <h3><?=label('Purchases');?></h3>
        </div>
      </div>
      <form action="purchases" method="post" class="form-inline float-right hidden-xs hidden-sm" style="margin-bottom:-50px;">
         <label for="Supplier"><?=label("Supplier");?></label>
         <select class="form-control" id="Supplier" name="filtersupp">
            <option value=''><?=label("All");?></option>
            <?php foreach ($suppliers as $supplier):?>
               <option value="<?=$supplier->name;?>" <?=$supplierF === $supplier->name ? 'selected' : ''; ?>><?=$supplier->name;?></option>
            <?php endforeach;?>
         </select>
         <button type="submit" class="btn btn-default"><?=label("ApplyFilter");?></button>
      </form>
      <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th class="hidden-xs"><?=label("ID");?></th>
                  <th><?=label("Fecha");?></th>
                  <th><?=label("Referencia");?></th>
                  <th class="hidden-xs"><?=label("Total");?></th>
                  <th class="hidden-xs"><?=label("Status");?></th>
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php foreach ($purchases as $purchase):
			   if( $purchase->received == 0) {
				   $satus = "label label-warning";
				   $label = 'Pendiente';
			   }
			   else {
				   $satus = 'label label-success';
				   $label = 'Recibido';
			   }
			 ?>
              <tr>
                 <td class="hidden-xs productcode"><?=$purchase->id;?></td>
                 <td><?=$purchase->date->format('Y-m-d'); ?></td>
                 <td><?=$purchase->reference;?></td>
				 <td><?= number_format((float)$purchase->total, $this->setting->decimals, '.', '');?></td>
                 <td><?echo '<span class="' . $satus . '">' . $label . '<span>'; ?></td>
              <td>
                 <div class="btn-group">
                     <?php if($this->user->role === "admin"){?><a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="purchases/delete/<?=$purchase->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i></a><?php } ?>		   
                     <a class="btn btn-warning" href="javascript:void(0)" onclick="Viewproduct(<?=$purchase->id;?>)"><i class="fa fa-file-text" data-toggle="tooltip" data-placement="top" title="<?=label('Viewpurchase');?>"></i></a>                 				  
                     <?if( $purchase->received == 0) {?>
					 <a class="btn btn-success" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-success" href="purchases/updatestock2/<?=$purchase->id;?>"><?=label("yesiamrc");?></a>'><i class="fa fa-sign-in"></i></a>  
					 <?}?>
					 </div>
                  </td>
              </tr>
           <?php endforeach;?>
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <?php if ($this->session->userdata('register'))
    {?>
   <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#Addproduct"><?=label("Add_purchase");?></button>
   <?}
	else{echo "<B>nota:</B>Las compras se capturan cuando la tienda se encuentre abierta, para registrarse en el turno en curso.";}?>
</div>


<script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript">
var items = [];
$(function() {
   $('#addform').submit(function()
   {   /*
      var error = false;
      $('.productcode').each(function() {
         if($(this).text() === $("#ProductCode").val()){
            $('#codeError').show();
            error = true;
         }
      });
      if(error) return false;
       // ... continue work 
      */
   });
});



var quant = [];
var quantw = [];
var pricestore = [];
var productID;
$(document).ready(function() { 
	
    $('#Date').datepicker({
          todayHighlight: true
     }); 

   $('#addform').ajaxForm({ //FormID - id of the form.

         success: function (data) {
			 /*
            if(data === "service")
            {
               location.reload();
            }else if($('#Type').val() == "0") {
               $('#stockcontent').html(data);
               $('#stock').modal('show');
               $('#Addproduct').modal('hide');


               productID = $('#prodctID').val();
            } else {*/

               productID = $('#prodctID').val();
               $('#combocontent').html(data);
               $('#combo').modal('show');
               $('#Addproduct').modal('hide');

               $("#add_item").autocomplete({
                  source: '<?= site_url('Purchasecontroller/suggest'); ?>',
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

            //}
      }
   });
   
   
});

function add_product_item(item, noitem) {
   if (item == null && noitem == null) {
      return false;
   }
   if(noitem != 1) {
      var product_id = 0;
      $.each(items, function(i){
         if(items[i].product_id == item.id) {
            items[i].quantity = (parseFloat(items[i].quantity) + 1);
            product_id = item.id;
            return false;
         }
      });
      if(product_id == 0) {
         item.qty = 1;
		 item.costo = 0;
         items.push({
            'product_id': item.id,
            'quantity': item.qty,
			'cost': item.costo,
            'code': item.code,
            'name': item.name
         });
      }
   }


   $("#Comboprd tbody").empty();
   items.forEach(function(item) {
      var Tr = $('<tr id="rowid_' + item.product_id + '" class="item_' + item.product_id + '"></tr>');
      td = '<td>' + item.name + ' (' + item.code + ')</td>';
      td += '<td><input class="form-control text-center" autocomplete="off" name="quantity" type="text" value="' + item.quantity + '" item-id="' + item.product_id + '" id="quantit"></td>';
	  td += '<td><input class="form-control text-center" autocomplete="off" name="cost" type="text" value="' + item.cost + '" item-id="' + item.product_id + '" id="costot"></td>';
      td += '<td class="text-center"><i class="fa fa-times tip delt" id="' + item.product_id + '" title="Remove" style="cursor:pointer;"></i></td>';
      Tr.html(td);
      Tr.prependTo("#Comboprd");
   });
   console.log(items);
   $( "[id='quantit']" ).on('change', function() {
      var itemID = $(this).attr("item-id");
      var val = $(this).val();
      items.forEach(function(e) {
         if(e.product_id == itemID) {
            e.quantity = val;
         }
      });
      console.log(items);
   });
    
   $( "[id='costot']" ).on('change', function() {
      var itemID = $(this).attr("item-id");
      var val = $(this).val();
      items.forEach(function(e) {
         if(e.product_id == itemID) {
            e.cost = val;
         }
      });
      console.log(items);
   });
   
   return true;

}

function addcombo(){
   var productID = $('#prodctID').val();
   $.ajax({
          url : "<?php echo site_url('Purchasecontroller/addcombo')?>/",
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


function Viewproduct(id){
   $.ajax({
       url : "<?php echo site_url('Purchasecontroller/Viewproduct')?>/"+id,
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
       if(items[i].product_id == id) {
           items.splice(i,1);
           return false;
       }
   });
    $(this).closest('#rowid_' + id).remove();
    console.log(items);
});

function modifycombo(id){
   $.ajax({
       url : "<?php echo site_url('Purchasecontroller/modifycombo')?>/"+id,
       type: "POST",
       success: function(data)
       {
          $('#combocontent').html(data);
          $('#Viewproduct').modal('hide');
          $('#combo').modal('show');
          $.ajax({
              url : "<?php echo site_url('Purchasecontroller/getcombos')?>/"+id,
              type: "POST",
              success: function(data){
                 dataitems = JSON.parse(data);
                 dataitems.forEach(function(e) {
                    items.push({
                       'product_id': e.product_id,
                       'quantity': e.quantity,
					   'cost': e.cost,
                       'code': e.code,
                       'name': e.name
                    });
                  });
            },
              error: function (jqXHR, textStatus, errorThrown){alert("error");}
         });
          console.log(items);
          $("#add_item").autocomplete({
             source: '<?= site_url('Purchasecontroller/suggest'); ?>',
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


function PrintTicket() {
    printDivCSS = new String ('<link rel="stylesheet" href="<?=base_url();?>assets/css/font-awesome.min.css"><link href="https://fonts.googleapis.com/css?family=Pinyon+Script" rel="stylesheet"><link rel="stylesheet" href="<?=base_url();?>assets/css/bootstrap.min.css">');
    var newWindow = window.open();
    newWindow.document.write(printDivCSS + '<div class="container">' + document.getElementById("printmenucode").innerHTML + '</div>');
    setTimeout(function(){newWindow.print();
    newWindow.close();});
}

</script>

<!-- Modal -->
<div class="modal fade" id="Addproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("Add_purchase");?></h4>
      </div>
      <?php
      $attributes = array('id' => 'addform');
      echo form_open_multipart('Purchasecontroller/add', $attributes);
      ?>
      <div class="modal-body">    
           <div class="form-group controls">
               <label for="Date"><?=label("Date");?> *</label>
               <input type="text" maxlength="30" Required name="date" class="form-control" autocomplete="off" id="Date" placeholder="<?=label("Date");?>">
            </div>
            <div class="form-group">
               <label for="Reference"><?=label("Reference");?> *</label>
               <input type="text" name="reference" maxlength="25" Required class="form-control" id="Reference" placeholder="<?=label("Reference");?>">
            </div>
			
			<div class="form-group">
               <label for="Reference"><?=label("Note");?> </label>
               <input type="text" name="note" maxlength="100" class="form-control" id="Note" autocomplete="off" placeholder="<?=label("Note");?>">
            </div>

		    <div class="form-group id="supply">
             <label for="Supplier"><?=label("Supplier");?></label>
             <select class="form-control" name="supplier" id="Supplier">
               <option value=""><?=label("Supplier");?></option>
               <?php foreach ($suppliers as $supplier):?>
                  <option value="<?=$supplier->id;?>"><?=$supplier->name;?></option>
               <?php endforeach;?>
            </select>
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
          <h4 class="modal-title" id="combo"><?=label("list_prod_purchases");?></h4>
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
 
  <!-- Modal view -->
  <div class="modal fade" id="Viewproduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-lg" role="document" id="viewModal">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="view"><?=label("View_purchases");?></h4>
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
  
  

  
