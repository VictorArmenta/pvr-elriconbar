<div class="container container-small">
   <div class="row" style="margin-top:56px;">
      <a class="btn btn-default float-right" href="#" onclick="history.back(-1)"style="margin-bottom:10px;">
         <i class="fa fa-arrow-left"></i> <?=label("Back");?></a>
      <?php echo form_open_multipart('customers/edit/'.$customer->id); ?>
           <div class="form-group col-md-12">
            <label for="CustomerName"><?=label("CustomerName");?></label>
            <input type="text" maxlength="50" Required name="name" value="<?=$customer->name;?>" class="form-control" id="CustomerName" placeholder="<?=label("CustomerName");?>">
           </div>
           <div class="form-group col-md-12">
            <label for="CustomerPhone"><?=label("CustomerPhone");?></label>
            <input autocomplete="off" type="text" name="phone" maxlength="30" value="<?=$customer->phone;?>" class="form-control" id="CustomerPhone" placeholder="<?=label("CustomerPhone");?>">
           </div>
           <div class="form-group col-md-8" >
            <label for="CustomerEmail"><?=label("CustomerEmail");?></label>
            <input autocomplete="off" type="email" maxlength="50" name="email" value="<?=$customer->email;?>" class="form-control" id="CustomerEmail" placeholder="<?=label("CustomerEmail");?>">
           </div>
           <div class="form-group col-md-4">
            <label for="CustomerDiscount"><?=label("CustomerDiscount");?></label>
            <input autocomplete="off" type="text" maxlength="5" name="discount" value="<?=$customer->discount;?>" class="form-control" id="CustomerDiscount" placeholder="<?=label("CustomerDiscount");?>">
           </div>
		   <div class="form-group col-md-12">
            <label for="Calle"><?=label("Calle");?></label>
            <input autocomplete="off" type="text" maxlength="70" name="calle" value="<?=$customer->calle;?>" class="form-control" id="Calle" placeholder="<?=label("Calle");?>">
           </div>
		    <div class="form-group col-md-6">
            <label for="Num_ext"><?=label("Num_ext");?></label>
            <input autocomplete="off" type="text" maxlength="10" name="numero_ext" value="<?=$customer->numero_ext;?>" class="form-control" id="Num_ext" placeholder="<?=label("Num_ext");?>">
           </div>
		   <div class="form-group col-md-6">
            <label for="Piso_depto"><?=label("Piso_depto");?></label>
            <input autocomplete="off" type="text" maxlength="10" name="piso_depto" value="<?=$customer->piso_depto;?>" class="form-control" id="Piso_depto" placeholder="<?=label("Piso_depto");?>">
           </div>
		   <div class="form-group col-md-12">
            <label for="Colonia"><?=label("Colonia");?></label>
            <input autocomplete="off" type="text" maxlength="40" name="colonia" value="<?=$customer->colonia;?>" class="form-control" id="Colonia" placeholder="<?=label("Colonia");?>">
           </div>
		   <div class="form-group col-md-6">
            <label for="Municipio"><?=label("Municipio");?></label>
            <input autocomplete="off" type="text" maxlength="30" name="municipio" value="<?=$customer->municipio;?>" class="form-control" id="Municipio" placeholder="<?=label("Municipio");?>">
           </div>
		   <div class="form-group col-md-6">
            <label for="Estado"><?=label("Estado");?></label>
            <input autocomplete="off" type="text" maxlength="30" name="estado" value="<?=$customer->estado;?>" class="form-control" id="Estado" placeholder="<?=label("Estado");?>">
           </div>
		   <div class="form-group col-md-12">
            <label for="Entre_calles"><?=label("Entre_calles");?></label>
            <input autocomplete="off" type="text" maxlength="70" name="entre_calles" value="<?=$customer->entre_calles;?>" class="form-control" id="Entre_calles" placeholder="<?=label("Entre_calles");?>">
           </div>
      </div>
      <div class="form-group">
       <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
      </div>
      <?php echo form_close(); ?>
</div>
