<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white mr-2">
                  <i class="mdi mdi-import"></i>
                </span> Category
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masterfile/category_list"> Category List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Sub Category</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-success card-img-holder text-white">
                        <h4 class="m-0">Add Sub Category</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>/masterfile/add_subcat">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php foreach($cat AS $cat) { ?>
                                    	<div class="form-group">
											<label>Category Name</label>
											<input type = "hidden" name = "cat_code" class = "form-control" value = "<?php echo $cat->cat_code;?>" readonly>
											<input type = "text" name = "cat_name" class = "form-control" value = "<?php echo $cat->cat_name;?>" readonly>
										</div>
									<?php } ?>
                                    <div class="form-group">
                                        <label>Sub Category Prefix</label>
										<input type = "text" name = "prefix" class = "form-control">
                                    </div>
                                   	<div class="form-group">
                                   		<label>Sub Category Name</label>
										<input type = "text" name = "subcategory_name" class = "form-control">
                                   	</div>
                                    <input type = "hidden" name = "id" value = "<?php echo $id; ?>">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
        



