<div class="container container-small">
   <div class="row" style="margin-top:56px;">
      <a class="btn btn-default float-right" href="#" onclick="history.back(-1)"style="margin-bottom:10px;">
         <i class="fa fa-arrow-left"></i> <?=label("Back");?></a>
     <?php echo form_open_multipart('subcategories/edit/'.$subcategory->id); ?>
         <div class="form-group">
            <label for="CategoryName"><?=label("SubCategoryName");?></label>
            <input type="text" maxlength="50" name="name" value="<?=$subcategory->name;?>" class="form-control" id="CategoryName" placeholder="<?=label("SubCategoryName");?>" required>
         </div>
     <div class="form-group">
       <button type="submit" class="btn btn-add"><?=label("Submit");?></button>
     </div>
     <?php echo form_close(); ?>
   </div>
</div>
