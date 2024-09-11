<div class="container container-small">
   <div class="row" style="margin-top:100px;">
      <a class="btn btn-default float-right" href="#" onclick="history.back(-1)"style="margin-bottom:10px;">  
         <i class="fa fa-arrow-left"></i> <?=label("Back");?></a>
	  	  <?if ($this->user->id == $user->id) {?>	 
      <?php echo form_open_multipart('perfil/editUserPass/'.$user->id); ?>

           <div class="form-group">
             <label for="firstname"><?=label("firstname");?></label>
             <input type="text" name="firstname" value="<?=$user->firstname?>" class="form-control" id="firstname" placeholder="<?=label("firstname");?>">
           </div>
           <div class="form-group">
             <label for="lastname"><?=label("lastname");?></label>
             <input type="text" name="lastname" value="<?=$user->lastname?>" class="form-control" id="lastname" placeholder="<?=label("lastname");?>">
           </div>
          
           <div class="form-group">
             <label for="email"><?=label("Email");?></label>
             <input type="email" name="email" value="<?=$user->email?>" class="form-control" id="email" placeholder="<?=label("Email");?>">
           </div>
           <div class="form-group">
             <label for="password"><?=label("Password");?></label>
             <input type="password" name="password" class="form-control" id="password" placeholder="<?=label('Password');?>">
          </div>
           <div class="form-group">
             <label for="PasswordRepeat"><?=label("PasswordRepeat");?></label>
             <input type="password" name="PasswordRepeat" class="form-control" id="PasswordRepeat" placeholder="<?=label('PasswordRepeat');?>">
           </div>
         
      <div class="form-group">
        <button type="submit" class="btn btn-green col-md-6 flat-box-btn"><?=label("Submit");?></button>
      </div>
      <?php echo form_close(); 
	  } else { echo  '<label for="firstname">Usuario no identificado.</label>';
	   }
	  
	  ?>
    </div>
</div>
<!--
<script type="text/javascript">
$(document).ready(function () {

<?//=$user->role==='admin' || $user->role==='sales' ? '$("#Storeslist").slideUp();' : '';?>

$('input[type=radio][name=role]').on('change', function() {
  if( this.value == "waiter" || this.value == "kitchen" ) //si camarero o cocina
  {
    $("#Storeslist").slideDown();
  } else {
     $("#Storeslist").slideUp();
  }
});

});
</script>-->
