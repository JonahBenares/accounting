<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Category
            </h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Category List &nbsp;
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="m-0">Category List</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-gradient-warning btn-sm btn-rounded" data-toggle="modal" data-target="#viewcat">
                                        <b><span class="mdi mdi-file"></span> View All</b>
                                    </button>
                                    <button type="button" class="btn btn-gradient-primary btn-sm btn-rounded" data-toggle="modal" data-target="#addCategory">
                                        <b><span class="mdi mdi-plus"></span> Add</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="myTable">
                            <thead>
                                <tr>
                                    <th width="10%">Code</th>
                                    <th width="75%">Category Name</th>
                                    <th width="10%">
                                        <center>
                                            <span class="mdi mdi-menu"></span>
                                        </center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($category)){
                                        foreach($category AS $cat){  ?>
                                    <tr>
                                        <td><?php echo $cat->cat_code; ?></td>
                                        <td><?php echo $cat->cat_name;?></td>
                                        <td align="center">
                                            <a href="<?php echo base_url(); ?>masterfile/view_subcat/<?php echo $cat->cat_id; ?>" class="btn btn-gradient-success btn-rounded btn-xs" data-toggle="tooltip" data-placement="top" title="Add Sub Category">
                                                <span class="mdi mdi-plus"></span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>masterfile/update_category/<?php echo $cat->cat_id; ?>" class="btn btn-gradient-info btn-rounded btn-xs" data-toggle="tooltip" data-placement="top" title="Update">
                                                <span class="mdi mdi-pencil"></span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>index.php/masterfile/delete_category/<?php echo $cat->cat_id;?>" data-toggle="tooltip" data-placement="top" title="Delete" onclick="confirmationDelete(this);return false;" class = "btn btn-gradient-danger btn-rounded btn-xs">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } } else { ?>
                                <tr>
                                    <td align="center" colspan='9'><center>No Data Available.</center></td>
                                </tr>
                                <?php } ?>
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="viewcat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-md" role="document">
                <div class="modal-content">
                    <div class="modal-header warning-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Category List with Sub Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover table-bordered shadow" id="item_table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Category Name</th>
                                    <th>Prefix</th>
                                    <th>Action</th>
                                </tr>                               
                            </thead>
                            <tbody>
                                <?php foreach($category AS $cat){ ?>
                                <tr style="background:#f9e065">
                                    <td><?php echo $cat->cat_code; ?></td>                                                      
                                    <td><?php echo $cat->cat_name; ?></td>                              
                                    <td><?php echo $cat->cat_prefix; ?></td>
                                    <td></td>                                                               
                                </tr>
                                <?php 
                                    foreach($subcat AS $sub){ 
                                        switch($sub){
                                            case($cat->cat_id == $sub->cat_id): 
                                 ?>
                                <tr>
                                    <td><?php echo $sub->subcat_code;?></td>
                                    <td style = "text-indent:30px;"><?php echo $sub->subcat_name;?></td>    
                                    <td><?php echo $sub->subcat_prefix;?></td>
                                    <td align="center">
                                        <a href="#" class="btn btn-gradient-info btn-rounded btn-xs" data-id="<?php echo $sub->subcat_id; ?>" data-prefix="<?php echo $sub->subcat_prefix; ?>" data-name="<?php echo $sub->subcat_name; ?>" data-toggle="modal" id='getSub' data-target="#subcatModal1">
                                            <span class="mdi mdi-pencil"></span>
                                        </a>
                                    </td>
                                </tr>
                                <?php   
                                    break;
                                    default: 
                                    } } }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Code</th>
                                    <th>Category Name</th>
                                    <th>Prefix</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="subcatModal1" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header info-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Update Sub Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/update_subcategory">
                            <label>Sub Category Prefix</label>
                            <input type="text" name="subcat_pref" id="subcat_pref" class = "form-control">
                            <label>Sub Category Name</label>
                            <input type="text" name="subcat_name" id="subcat_name" class = "form-control">
                            <div class="modal-footer">
                                <input type="hidden" name="subcat_id" id="subcat_id">
                                <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
                                <button type="submit" class="btn btn-info btn-block">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- //Add Category// -->
        <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header primary-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/add_category">
                            <div class="form-group">
                                <label>Prefix</label>
                                <input type="text" class="form-control" name="prefix" placeholder="Prefix">
                            </div>
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" name="category_name" placeholder="Category Name">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- //Filter Category// -->
        <div class="modal fade" id="filterDepartment" tabindex="-1" role="dialog" aria-labelledby="filterDepartment" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header success-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Filter Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/search_department">
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" class="form-control" name="department_name" placeholder="Department">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " >
                    <div class="modal-header danger-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <br>
                        <br>
                        <center>Do you want to delete _________?</center>
                        <br>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger">Yes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).on("click", "#getSub", function () {
        var subcat_id = $(this).attr("data-id");
        var subcat_prefix = $(this).attr("data-prefix");
        var subcat_name = $(this).attr("data-name");
        $("#subcat_id").val(subcat_id);
        $("#subcat_pref").val(subcat_prefix);
        $("#subcat_name").val(subcat_name);
    });
</script>
        




