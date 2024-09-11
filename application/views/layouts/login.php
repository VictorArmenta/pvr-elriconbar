<?php if ($this->user) { redirect('');} ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Login - <?=label('title');?> <?= $this->setting->companyname;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script> -->
    <link rel="stylesheet" href="<?=base_url();?>assets/css/normalize.min.css"  type='text/css'>
    <link rel="stylesheet" href="<?=base_url();?>assets/css/reset.min.css"  type='text/css'>
    <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/Style-<?=$this->setting->theme?>.css" rel="stylesheet">
    <link rel="icon" href="<?=base_url();?>fav.ico" type="image/x-icon">
    <link rel="icon" href="<?=base_url();?>/assets/img/favicon.png?v=2" type="image/x-icon">
    <style media="screen">
    body {
             background: url(<?=base_url()?>assets/img/b-login.png) no-repeat center center fixed;
            /*background:#1B2631;*/
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
         }
    </style>
  </head>
  <body>
     <div class="modal fade" id="login-modal" tabindex="-1" role="dialog">
       <div class="modal-dialog">
            <div class="loginmodal-container">
               <?php if($this->setting->logo){ ?><img src="<?=base_url()?>files/Setting/<?=$this->setting->logo;?>" alt="logo"  style='max-height: 110px; max-width: 200px; margin: 0 auto'><?php } else { ?><img src="<?=base_url()?>assets/img/logo.png" alt="logo"><?php } ?>
                
               <h1><?=label('Loginaccount');?></h1><br>


               <?php if(isset($message)){
                echo "<div class='alert alert-dismissible alert-danger'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>".$message.
                "</div>";}?>
               <?php
              $attributes = array('class' => 'login');
              echo form_open('login', $attributes);
              ?>
               <input type="text" autofocus name="username" value="<?=isset($username)?$username:''?>" placeholder="<?=label("Username");?>" required>
               <input type="password" name="password" placeholder="<?=label("Password");?>" required>
               <?php
                  echo form_submit('submit', label("Login"), "class='login loginmodal-submit'");
               ?>

              <?=form_close()?>

              <div class="login-help">
               Prososoft Restaurantes 1.1 <br> <?=date("Y");?> <?=$this->setting->companyname?>
              </div>
            </div>
         </div>
       </div>

    <!--   <div class="panel2 panel-login">
        <div class="panel-log">
          Basic panel
        </div>
      </div> -->


      <script type="text/javascript" src="<?=base_url()?>assets/js/jquery-2.2.2.min.js"></script>
      <script type="text/javascript" src="<?=base_url()?>assets/js/waves.min.js"></script>
      <script type="text/javascript" src="<?=base_url()?>assets/js/bootstrap.min.js"></script>

      <script type="text/javascript">
      $(document).ready(function() {
         $('#login-modal').modal('show').on('hide.bs.modal', function (e) {
            e.preventDefault();
         });
      });
      </script>
   </body>
</html>
