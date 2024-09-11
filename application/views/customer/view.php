<!-- Page Content -->
<div class="container">
   <div class="row" style="margin-top:13px;">
      <div class="panel panel-default">
        <div class="panel-body">
          <h3><?=label('Customers');?></h3>
        </div>
      </div>
      <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th><?=label("CustomerName");?></th>
                  <th><?=label("CustomerPhone");?></th>
                  <th class="hidden-xs"><?=label("CustomerEmail");?></th>
                  <th class="hidden-xs"><?=label("CustomerDiscount");?></th>
                  <th class="hidden-xs"><?=label("CreatedAt");?></th>
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php foreach ($customers as $customer):?>
              <tr>
                 <td><?=$customer->name;?></td>
                 <td><?=$customer->phone;?></td>
                 <td class="hidden-xs"><?=$customer->email;?></td>
                 <td class="hidden-xs"><?=$customer->discount;?></td>
                 <td class="hidden-xs"><?=$customer->created_at;?></td>
                 <td><div class="btn-group">
                    <?php if ($customer->id == 1) {
                        echo "<a class='btn btn-default' href='#'><i class='fa fa-times'></i></a>";
                        echo "<a class='btn btn-default' href='#'><i class='fa fa-pencil'></i></a>";
                    ?>
                    <?php } else{?>
                       <a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="customers/delete/<?=$customer->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i>
                       </a>
                    
                       <a class="btn btn-primary" href="customers/edit/<?=$customer->id;?>" data-toggle="tooltip" data-placement="top" title="<?=label('Edit');?>"><i class="fa fa-pencil"></i></a>
                     <?php  }?> 
                     </div>
                  </td>
              </tr>
           <?php endforeach;?>
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#AddCustomer">
     <?=label("AddCustomer");?>
   </button>
</div>
<!-- /.container -->

<!-- Modal -->
<div class="modal fade" id="AddCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddCustomer");?></h4>
      </div>
      <?php echo form_open_multipart('customers/add'); ?>
      <div class="modal-body">
            <div class="form-group col-md-12">
             <label for="CustomerName"><?=label("CustomerName");?></label>
             <input type="text" name="name" maxlength="50" Required class="form-control" id="CustomerName" placeholder="<?=label("CustomerName");?>">
           </div>
           <div class="form-group col-md-12">
             <label for="CustomerPhone"><?=label("CustomerPhone");?></label>
             <input autocomplete="off" type="text" name="phone" maxlength="30" class="form-control" id="CustomerPhone" placeholder="<?=label("CustomerPhone");?>">
           </div>
           <div class="form-group col-md-8">
             <label for="CustomerEmail"><?=label("CustomerEmail");?></label>
             <input autocomplete="off" type="email" maxlength="50" name="email" class="form-control" id="CustomerEmail" placeholder="<?=label("CustomerEmail");?>">
           </div>
           <div class="form-group col-md-4">
             <label for="CustomerDiscount"><?=label("CustomerDiscount");?></label>
             <input autocomplete="off" type="text" value="" maxlength="5" name="discount" class="form-control" id="CustomerDiscount" placeholder="<?=label("CustomerDiscount");?>">
           </div>
		   <div class="form-group col-md-12">
             <label for="Calle"><?=label("Calle");?></label>
             <input autocomplete="off" type="text" name="calle" maxlength="70" class="form-control" id="Calle" placeholder="<?=label("Calle");?>">
           </div>
		   <div class="form-group col-md-6">
             <label for="Num_ext"><?=label("Num_ext");?></label>
             <input autocomplete="off" type="text" name="numero_ext" maxlength="10" class="form-control" id="Num_ext" placeholder="<?=label("Num_ext");?>">
           </div>
		   <div class="form-group col-md-6">
             <label for="Piso_depto"><?=label("Piso_depto");?></label>
             <input autocomplete="off" type="text" name="piso_depto" maxlength="10" class="form-control" id="Piso_depto" placeholder="<?=label("Piso_depto");?>">
           </div>
		   <div class="form-group col-md-12">
             <label for="Colonia"><?=label("Colonia");?></label>
             <input autocomplete="off" type="text" name="colonia" maxlength="40" class="form-control" id="Colonia" placeholder="<?=label("Colonia");?>">
           </div>
		   <div class="form-group col-md-6">
             <label for="Municipio"><?=label("Municipio");?></label>
             <input autocomplete="off" type="text" name="municipio" maxlength="30" class="form-control" id="Municipio" placeholder="<?=label("Municipio");?>">
           </div>
		   <div class="form-group col-md-6">
             <label for="Estado"><?=label("Estado");?></label>
             <input autocomplete="off" type="text" name="estado" maxlength="30" class="form-control" id="Estado" placeholder="<?=label("Estado");?>">
           </div>
		   <div class="form-group col-md-12">
             <label for="Entre_calles"><?=label("Entre_calles");?></label>
             <input autocomplete="off" type="text" name="entre_calles" maxlength="70" class="form-control" id="Entre_calles" placeholder="<?=label("Entre_calles");?>">
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
