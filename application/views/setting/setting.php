<!-- Page Content -->
<div class="container">
   <div class="row" style="margin-top:100px;">
      <div class="col-md-12">
         <!-- tab navigation -->
         <?php $tab = (isset($_GET['tab'])) ? $_GET['tab'] : null; ?>
         <ul class="nav nav-tabs">
            <li class="<?php echo ($tab == 'setting') ? 'active' : ''; ?>"><a href="#setting" data-toggle="tab"><i class="fa fa-cog" aria-hidden="true"></i> <?=label("Settings");?></a></li>
            <li class="<?php echo ($tab == 'users') ? 'active' : ''; ?>"><a href="#users" data-toggle="tab"><i class="fa fa-users" aria-hidden="true"></i> <?=label("users");?></a></li>
            <li class="<?php echo ($tab == 'warehouses') ? 'active' : ''; ?>"><a href="#warehouses" data-toggle="tab"><i class="fa fa-building" aria-hidden="true"></i> <?=label("Warehouses");?></a></li>
			<li class="<?php echo ($tab == 'new') ? 'active' : ''; ?>"><a href="#new" data-toggle="tab"><i class="fa fa-bell" aria-hidden="true"></i> <?=label("New");?></a></li>
         </ul>

         <!-- tab sections -->
         <div class="tab-content">
            <!-- General setting tab -->
            <div class="tab-pane fade in <?php echo ($tab == 'setting') ? 'active' : ''; ?>" id="setting">
              <div class="panel panel-default">
                <div class="panel-body">
                  <h3><?=label('Settings');?></h3>
                  <p><?=label("SettingsDesciption");?></p> <a href="respaldo_guardar_copia.php" target="_blank">Respaldo</a>
                  </div>
                </div>
               
               <?php echo form_open_multipart('settings/updateSettings'); ?>

            <div class="panel panel-default">
                <div class="panel-body">
                 <div class="form-group col-md-6">
                   <label for="companyName"><?=label("Company");?></label>
                   <input type="text" value="<?=$this->setting->companyname;?>" name="companyname" class="form-control" id="companyName" placeholder="<?=label("Company");?>">
                 </div>
                 <div class="form-group col-md-6">
                    <label for="logo"><?=label("CompanyLogo");?></label>
                    <input type="file" name="userfile" id="logo">
                    <?php if($this->setting->logo){ ?><img src="<?=base_url()?>files/Setting/<?=$this->setting->logo;?>" alt="" class="float-right" width="100px"/><?php } else { ?><img src="<?=base_url()?>assets/img/logo.png" alt="logo" class="float-right" width="100px"><?php } ?>
                 </div>
                 <div class="form-group col-md-6">
                   <label for="phone"><?=label("Phone");?></label>
                   <input type="text" value="<?=$this->setting->phone;?>" name="phone" class="form-control" id="phone" placeholder="<?=label("Phone");?>">
                 </div>
                 <div class="form-group col-md-3">
                   <label for="currency"><?=label("Currency");?></label>
                   <input type="text" value="<?=$this->setting->currency;?>" name="currency" class="form-control" id="currency" placeholder="MXN, USD" disabled>
                 </div>
                 <div class="form-group col-md-3">
                   <label for="dollar">Dolar</label>
                   <input type="text" value="<?=$this->setting->dollar;?>" name="dollar" class="form-control" id="dollar" placeholder="Valor a MXN">
                 </div>
				  <!-- jar02
                 <div class="form-group col-md-3">
                   <label for="DefaultDiscount"><?=label("DefaultDiscount");?></label>
                   <input type="text" value="<?=$this->setting->discount;?>" name="discount" class="form-control" id="DefaultDiscount" placeholder="<?=label("DefaultDiscount");?>">
                 </div>
				-->
				 <div class="form-group col-md-3">
                   <label for="DefaultTip"><?=label("DefaultTip");?></label>
                   <input type="text" value="<?=$this->setting->tip;?>"  required name="tip" class="form-control" id="DefaultTip" placeholder="<?=label("DefaultTip");?>">
                 </div>
                 <div class="form-group col-md-3">
                   <label for="DefualtTax"><?=label("DefualtTax");?></label>
                   <input type="text" value="<?=$this->setting->tax;?>" required name="tax" class="form-control" id="DefualtTax" placeholder="<?=label("DefualtTax");?>">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="numberDecimal"><?=label("numberDecimal");?></label>
                   <select class="form-control" name="decimals" id="numberDecimal">
                      <option value="1" <?=$this->setting->decimals===1 ? 'selected' : '';?>>0.1</option>
                      <option value="2" <?=$this->setting->decimals===2 ? 'selected' : '';?>>0.01</option>
                      <option value="3" <?=$this->setting->decimals===3 ? 'selected' : '';?>>0.001</option>
                   </select>
                 </div>
                 <div class="form-group col-md-6">
                   <label>
                     <input type="hidden" name="keyboard" value="0" />
                     <input type="checkbox" name="keyboard" value="1" <?=strval($this->setting->keyboard) === '1' ? 'checked' : '';?>>
                     <span class="label-text"><?=label("keyboardDisplay");?></span>
                   </label>
                 </div>
				 <div class="form-group col-md-6">
                   <label>
                     <input type="hidden" name="table_assig" value="0" />
                     <input type="checkbox" name="table_assig" value="1" <?=strval($this->setting->table_assig) === '1' ? 'checked' : '';?>>
                     <span class="label-text"><?=label("TableAssignment");?></span>
                   </label>
                 </div>
				 <div class="form-group col-md-6">
                   <label>
                     <input type="hidden" name="ticket_prev" value="0" />
                     <input type="checkbox" name="ticket_prev" value="1" <?=strval($this->setting->ticket_prev) === '1' ? 'checked' : '';?>>
                     <span class="label-text"><?=label("TicketPrevious");?></span>
                   </label>
                 </div>
                 <div class="form-group col-md-6">
                   <label><?=label("timezone");?>
                      <select name="timezone" class="form-control">
                         <option value="0"><?=label("timezone");?></option>
                         <?php foreach($Timezones as $t) { ?>
                           <option value="<?php print $t['zone'] ?>" <?= $t['zone'] === $this->setting->timezone ? 'selected' : ''; ?>>
                             <?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                           </option>
                         <?php } ?>
                       </select>
                   </label>
                 </div>
				  <div class="form-group col-md-6">
                   <label for="fontTicket"><?=label("fontTicket");?></label>
                   <select class="form-control" name="fontticket" id="fontTicket">
                      <option value="Arial" <?=$this->setting->fontticket==='Arial' ? 'selected' : '';?>>Arial</option>
                      <option value="Arial Black" <?=$this->setting->fontticket==='Arial Black' ? 'selected' : '';?>>Arial Black</option>
                      <option value="Arial Narrow" <?=$this->setting->fontticket==='Arial Narrow' ? 'selected' : '';?>>Arial Narrow</option>
					     <option value="Comic Sans MS" <?=$this->setting->fontticket==='Comic Sans MS' ? 'selected' : '';?>>Comic Sans MS</option>
					     <option value="Courier New" <?=$this->setting->fontticket==='Courier New' ? 'selected' : '';?>>Courier New</option>
					     <option value="Lucida Sans Typewriter" <?=$this->setting->fontticket==='Lucida Sans Typewriter' ? 'selected' : '';?>>Lucida Sans Typewriter</option>
					     <option value="Tahoma" <?=$this->setting->fontticket==='Tahoma' ? 'selected' : '';?>>Tahoma</option>
					     <option value="Times New Roman" <?=$this->setting->fontticket==='Times New Roman' ? 'selected' : '';?>>Times New Roman</option>
					     <option value="Trebuchet MS" <?=$this->setting->fontticket==='Trebuchet MS' ? 'selected' : '';?>>Trebuchet MS</option>
					     <option value="True Type" <?=$this->setting->fontticket==='True Type' ? 'selected' : '';?>>True Type</option>
					     <option value="Verdana" <?=$this->setting->fontticket==='Verdana' ? 'selected' : '';?>>Verdana</option>
					  
                   </select>
                 </div>
				 <div class="form-group col-md-3">
                   <label for="sizeHead"><?=label("sizeHead");?></label>
                   <select class="form-control" name="sizehead_ticket" id="sizeHead">
                      <option value="1" <?=$this->setting->sizehead_ticket==='1' ? 'selected' : '';?>>1</option>
                      <option value="2" <?=$this->setting->sizehead_ticket==='2' ? 'selected' : '';?>>2</option>
                      <option value="3" <?=$this->setting->sizehead_ticket==='3' ? 'selected' : '';?>>3</option>
					  <option value="4" <?=$this->setting->sizehead_ticket==='4' ? 'selected' : '';?>>4</option>
					  <option value="5" <?=$this->setting->sizehead_ticket==='5' ? 'selected' : '';?>>5</option>
                   </select>
                 </div>
				 <div class="form-group col-md-3">
                   <label for="sizeDetail"><?=label("sizeDetail");?></label>
                   <select class="form-control" name="sizedetail_ticket" id="sizeDetail">
                      <option value="1" <?=$this->setting->sizedetail_ticket==='1' ? 'selected' : '';?>>1</option>
                      <option value="2" <?=$this->setting->sizedetail_ticket==='2' ? 'selected' : '';?>>2</option>
                      <option value="3" <?=$this->setting->sizedetail_ticket==='3' ? 'selected' : '';?>>3</option>
					  <option value="4" <?=$this->setting->sizedetail_ticket==='4' ? 'selected' : '';?>>4</option>
					  <option value="5" <?=$this->setting->sizedetail_ticket==='5' ? 'selected' : '';?>>5</option>
                   </select>
                 </div>
				 
                </div>
              </div>  

                 <div class="col-md-6">
                    <h4><?=label("ReceiptHeader");?></h4>
                    <textarea id="summernote" name="receiptheader"><?=$this->setting->receiptheader;?></textarea>
                  </div>
                  <div class="form-group col-md-6">
                     <h4><?=label("ReceiptFooter");?></h4>
                     <textarea  id="summernote2" name="receiptfooter"><?=$this->setting->receiptfooter;?></textarea>
                  </div>

                <div class="form-group col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body" >
                        <!-- skin para el sistema -->
                        <!-- theme -->
                        <h4><?=label("themesPick");?></h4>
                         <!-- Cookie -->
                         <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Cookie" <?=$this->setting->theme === 'Cookie' ? 'checked' : '';?>/>
                            <img src="<?=base_url()?>assets/img/Cookie-theme.jpg" alt="Cookie-theme" width="200" height="150">
                          </label>

                          <!-- dark -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Dark" <?=$this->setting->theme === 'Dark' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Black-theme.jpg" alt="Dark-theme" width="200" height="150">
                          </label>

                          <!-- orange -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Orange" <?=$this->setting->theme === 'Orange' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Orange-theme.jpg" alt="Orange-theme" width="200" height="150">
                          </label>

                          <!-- pink -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Pink" <?=$this->setting->theme === 'Pink' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Pink-theme.jpg" alt="Pink-theme" width="200" height="150">
                          </label>

                          <!-- Yellow -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Yellow" <?=$this->setting->theme === 'Yellow' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Yellow-theme.jpg" alt="Yellow-theme" width="200" height="150">
                          </label>

                          <!-- red -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Red" <?=$this->setting->theme === 'Red' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Red-theme.jpg" alt="Red-theme" width="200" height="150">
                          </label>

                          <!-- green -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Green" <?=$this->setting->theme === 'Green' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Green-theme.jpg" alt="Green-theme" width="200" height="150">
                          </label>


                          <!-- Blue -->
                          <label class="themesPick col-md-3">
                            <input type="radio" name="theme" value="Blue" <?=$this->setting->theme === 'Blue' ? 'checked' : '';?> />
                            <img src="<?=base_url()?>assets/img/Blue-theme.jpg" alt="Blue-theme" width="200" height="150">
                          </label>
                      
                        </div>
                    </div>
                </div>
                 <div class="col-md-12">
                    <button type="submit" class="btn btn-add btn-lg"><?=label("Submit");?></button>
                 </div>
               <?php echo form_close(); ?>
            </div>


            <!-- tab usuarios -->
            <div class="tab-pane fade in <?php echo ($tab == 'users') ? 'active' : ''; ?>" id="users">
               <table class="table">
                  <tr>
                     <th><b><?=label("Avatar");?></b></th>
                     <th><b><?=label("firstname");?></b></th>
                     <th><b><?=label("lastname");?></b></th>
                     <th><b><?=label("Username");?></b></th>
                     <th><b>*<?=label("Role");?></b></th>
                     <th><b><?=label("lastActive");?></b></th>
                     <th><b><?=label("Action");?></b></th>
                     <th><b><?=label("Store");?></b></th>
                  </tr>
                  <?php foreach ($Users as $user):?>
                   <tr>
                      <td><img class="img-circle topbar-userpic hidden-xs" src="<?=$user->avatar ? base_url().'files/Avatars/'.$user->avatar : base_url().'assets/img/Avatar.jpg' ?>" width="30px" height="30px"></td>
                      <td><?=$user->firstname;?></td>
                      <td><?=$user->lastname;?></td>
                      <td><?=$user->username;?></td>
                      <td><?=$user->role;?></td>
                      <td><?=$user->last_active;?></td>
                      <td><div class="btn-group">
                      <!-- borrar -->
                            <?php if($user->id !== 1){?><a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="settings/deleteUser/<?=$user->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i></a><?php } ?>

                      <!-- editar -->
                            <a class="btn btn-primary" href="settings/editUser/<?=$user->id;?>" data-toggle="tooltip" data-placement="top" title="<?=label('Edit');?>"><i class="fa fa-pencil"></i></a>
                          </div>
                       </td>
                       <td><?php foreach ($stores as $store):?>
                          <?php if($store->id == $user->store_id) { echo $store->name; }?>
                       <?php endforeach;?></td>
                   </tr>
                <?php endforeach;?>
               </table>
               <!-- Button trigger modal -->
               <div class="alert alert-dismissible alert-info">
                    <strong>* </strong>
                    (<strong>admin</strong>= Administrador)
                    (<strong>sales</strong>= Personal de venta)
                    (<strong>waiter</strong>= Camarer@)
                    (<strong>kitchen</strong>= Cociner@)
                </div>
               <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#AddUser">
                  <?=label("Adduser");?>
               </button>
            </div>
            <!-- tab almacen -->
            <div class="tab-pane fade in <?php echo ($tab == 'warehouses') ? 'active' : ''; ?>" id="warehouses">
              <table class="table">
                  <tr>
                     <th><?=label("WarehouseName");?></th>
                     <th><?=label("WarehousePhone");?></th>
                     <th><?=label("Email");?></th>
                     <th><?=label("Adresse");?></th>
                     <th><?=label("Action");?></th>
                  </tr>
                  <?php foreach ($warehouses as $warehouse):?>
                   <tr>
                      <td><?=$warehouse->name;?></td>
                      <td><?=$warehouse->phone;?></td>
                      <td><?=$warehouse->email;?></td>
                      <td><?=$warehouse->adresse;?></td>
                      <td><div class="btn-group">

                            <a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="warehouses/delete/<?=$warehouse->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i></a>

                            <a class="btn btn-primary" href="warehouses/edit/<?=$warehouse->id;?>" data-toggle="tooltip" data-placement="top" title="<?=label('Edit');?>"><i class="fa fa-pencil"></i></a>
                          </div>
                       </td>
                   </tr>
                   <?php endforeach;?>
              </table>
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#AddWarehouse">
                     <?=label("AddWarehouse");?>
                  </button>
            </div>
			<!-- tab Noticias-->
            <div class="tab-pane fade in <?php echo ($tab == 'new') ? 'active' : ''; ?>" id="new">
               <iframe src="https://prosoft-team.tech/noticias.html" width="100%" height="500" allow="fullscreen" ></iframe>
			  
            </div>
			
         </div>
      </div>
   </div>
</div>
<!-- /.container -->
<!-- add user Modal -->
<div class="modal fade" id="AddUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("Adduser");?></h4>
      </div>
      <?php echo form_open_multipart('settings/addUser'); ?>
      <div class="modal-body">
            <div class="form-group">
             <label for="username"><?=label("Username");?> *</label>
             <input type="text" name="username" class="form-control" id="username" placeholder="<?=label("Username");?>" required>
           </div>
           <div class="form-group">
             <label for="firstname"><?=label("firstname");?> *</label>
             <input type="text" name="firstname" class="form-control" id="firstname" placeholder="<?=label("firstname");?>" required>
           </div>
           <div class="form-group">
             <label for="lastname"><?=label("lastname");?></label>
             <input type="text" name="lastname" class="form-control" id="lastname" placeholder="<?=label("lastname");?>">
           </div>
           <div class="form-group">
               <label for="role"><?=label("Role");?> *</label><br>
               <label class="radio-inline">
                 <input type="radio" name="role" id="role" value="admin" checked> <?=label("RoleAdimn");?>
               </label>
               <label class="radio-inline">
                 <input type="radio" name="role" id="role" value="sales"> <?=label("RoleSales");?>
               </label>
               <label class="radio-inline">
                 <input type="radio" name="role" id="role" value="waiter"> <?=label("Waiter");?>
               </label>
               <label class="radio-inline">
                 <input type="radio" name="role" id="role" value="kitchen"> <?=label("Kitchen");?>
               </label>
            </div>
            <div class="form-group" id="Storeslist">
              <label for="store_id"><?=label("Store");?></label>
                    <select class="form-control" name="store_id" id="store_id">
                      <?php foreach ($stores as $store):?>
                         <option value="<?=$store->id;?>"><?=$store->name;?></option>
                      <?php endforeach;?>
                    </select>

            </div>
           <div class="form-group">
             <label for="email"><?=label("Email");?></label>
             <input type="email" name="email" class="form-control" id="email" placeholder="<?=label("Email");?>">
           </div>
           <div class="form-group">
             <label for="password"><?=label("Password");?> *</label>
             <input type="password" name="password" class="form-control" id="password" placeholder="<?=label('Password');?>" required>
          </div>
           <div class="form-group">
             <label for="confirm_password"><?=label("PasswordRepeat");?> *</label>
             <input type="password" name="PasswordRepeat" class="form-control" id="confirm_password" placeholder="<?=label('PasswordRepeat');?>" required>
           </div>
           <div class="form-group">
             <label for="Avatar"><?=label("Avatar");?> <label style="color:#e74c3c"> (Logo 150px x 150px)</label></label>
             <input type="file" name="userfile" id="Avatar">
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


<!-- add warehouse Modal -->
<div class="modal fade" id="AddWarehouse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("AddWarehouse");?></h4>
      </div>
      <?php echo form_open_multipart('warehouses/add'); ?>
      <div class="modal-body">
            <div class="form-group">
             <label for="WarehouseName"><?=label("WarehouseName");?> *</label>
             <input type="text" name="name" class="form-control" id="WarehouseName" placeholder="<?=label("WarehouseName");?>" required>
           </div>
           <div class="form-group">
             <label for="WarehousePhone"><?=label("WarehousePhone");?></label>
             <input type="text" name="phone" class="form-control" id="WarehousePhone" placeholder="<?=label("WarehousePhone");?>">
          </div>
           <div class="form-group">
             <label for="email"><?=label("Email");?></label>
             <input type="email" name="email" class="form-control" id="email" placeholder="<?=label("Email");?>">
          </div>
           <div class="form-group">
             <label for="Adresse"><?=label("Adresse");?></label>
             <input type="text" name="adresse" class="form-control" id="Adresse" placeholder="<?=label("Adresse");?>">
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

<script type="text/javascript">

/******** passwors confirmacion validacion ****************/

var currency = document.getElementById("currency");

function validatecurrency(){
  if(currency.value.length < 3) {
    currency.setCustomValidity("The Currency code must be at least 3 characters length");
  } else {
    currency.setCustomValidity('');
  }
}
if(currency) currency.onchange = validatecurrency;


$(document).ready(function () {

$("#Storeslist").slideUp();

$('input[type=radio][name=role]').on('change', function() {
  if( this.value == "waiter" || this.value == "kitchen" ) //si camarero o cocina
  {
    $("#Storeslist").slideDown();
  } else {
     $("#Storeslist").slideUp();
  }
});

});
$('.collapse').collapse()
</script>
