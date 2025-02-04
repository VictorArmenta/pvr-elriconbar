<!-- Page Content -->
<div class="container">
  <div class="row" style="margin-top:13px;">
      <div class="panel panel-default">
        <div class="panel-body">
          <h3><?=label('SubCategory_product');?></h3>
        </div>
      </div>
      <table id="Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th><?=label("SubCategoryName");?></th>
                  <th><?=label("CreatedAt");?></th>
                  <th><?=label("Action");?></th>
              </tr>
          </thead>

          <tbody>
             <?php foreach ($subcategories as $subcategory):?>
              <tr>
                 <td><?=$subcategory->name;?></td>
                 <td><?=$subcategory->created_at;?></td>
                 <td><div class="btn-group">
                       <?php //if($this->user->role === "admin"){?><a class="btn btn-danger" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='<?=label("Areyousure");?>' data-content='<a class="btn btn-danger" href="subcategories/delete/<?=$subcategory->id;?>"><?=label("yesiam");?></a>'><i class="fa fa-times"></i></a><?php// } ?>
                       <a class="btn btn-primary" href="subcategories/edit/<?=$subcategory->id;?>" data-toggle="tooltip" data-placement="top" title="<?=label('Edit');?>"><i class="fa fa-pencil"></i></a>
                     </div>
                  </td>
              </tr>
           <?php endforeach;?>
          </tbody>
      </table>
   </div>
   <!-- Button trigger modal -->
   <button type="button" class="btn btn-add btn-lg" data-toggle="modal" data-target="#Addcategory">
     <?=label("SubAddCategory");?>
   </button>
</div>
<!-- /.container -->
<!-- Modal -->
<div class="modal fade" id="Addcategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=label("SubAddCategory");?></h4>
      </div>
      <?php echo form_open_multipart('subcategories/add'); ?>
      <div class="modal-body">
           <div class="form-group">
             <label for="CategoryName"><?=label("SubCategoryName");?></label>
             <input type="text" maxlength="50" name="name" class="form-control" id="CategoryName" placeholder="<?=label("CategoryName");?>" required>
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
