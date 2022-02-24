<script src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Supplier 
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Supplier List&nbsp;
                        <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                    <!--  <li class="breadcrumb-item"><a href="#">Editors</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Buyer List</li> -->
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="m-0">Supplier List</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-gradient-primary btn-sm btn-rounded" data-toggle="modal" data-target="#addSupplier">
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
                                    <th width="10%"> Supplier Code </th>
                                    <th width="30%"> Supplier Name </th>
                                    <th width="20%"> Address</th>
                                    <th width="10%"> Contact No. </th>
                                    <th width="15%"> Terms </th>
                                    <th width="5%"> Status</th>
                                    <th width="10%"><center><span class="mdi mdi-menu"></span></center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($supplier)){
                                foreach($supplier AS $sup){ ?>
                                    <tr>
                                        <td><?php echo $sup->supplier_code;?></td>
                                        <td><?php echo $sup->supplier_name;?></td>
                                        <td><?php echo $sup->address;?></td>
                                        <td><?php echo $sup->contact_number;?></td>
                                        <td><?php echo $sup->terms;?></td>
                                        <?php if($sup->active == '1') { ?>
                                        <td><?php echo '<span class = "badge badge-gradient-success">Active</span>'; ?></td>
                                        <?php } else { ?>
                                        <td><?php echo '<span class = "badge badge-gradient-danger">Inactive</span>'; ?></td>
                                        <?php } ?>
                                        <td align="center">
                                            <a href="<?php echo base_url(); ?>masterfile/update_supplier/<?php echo $sup->supplier_id; ?>" class="btn btn-gradient-info btn-rounded btn-xs" data-toggle="tooltip" data-placement="top" title="Update">
                                                <span class="mdi mdi-pencil"></span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>index.php/masterfile/delete_supplier/<?php echo $sup->supplier_id;?>" data-toggle="tooltip" data-placement="top" title="Delete" onclick="confirmationDelete(this);return false;" class = "btn btn-gradient-danger btn-rounded btn-xs">
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

        <!-- //Add Supplier// -->
        <div class="modal fade" id="addSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header primary-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/add_supplier">
                            <div class="form-group">
                                <label>Supplier Code</label>
                                <input type="text" class="form-control" name="supplier_code" placeholder="Supplier Code">
                            </div>
                            <div class="form-group">
                                <label>Supplier Name</label>
                                <input type="Text" class="form-control" name="supplier_name" placeholder="Supplier Name">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" name="contact_number" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label>Term</label>
                                <textarea class="form-control" name="terms" placeholder="Terms"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" type="text" name="active">
                                    <option value='1'>Active</option>
                                    <option value='0'>Inactive</option>
                                </select>
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

        <!-- //Filter Supplier// -->
        <div class="modal fade" id="filterSupplier" tabindex="-1" role="dialog" aria-labelledby="filterSupplier Name" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header success-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Filter Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Supplier Code</label>
                                <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Supplier Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Supplier Name</label>
                                <input type="Text" class="form-control" id="exampleInputEmail1" placeholder="Supplier Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Address</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Contact Number</label>
                                <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Term</label>
                                <textarea class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Status</label>
                                <select class="form-control">
                                    <option>Status 1</option>
                                    <option>Status 1</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- //Update Supplier// -->
        <div class="modal fade" id="updateSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header info-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Update Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Supplier Code</label>
                                <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Supplier Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Supplier Name</label>
                                <input type="Text" class="form-control" id="exampleInputEmail1" placeholder="Supplier Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Address</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Contact Number</label>
                                <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Term</label>
                                <textarea class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Status</label>
                                <select class="form-control">
                                    <option>Status 1</option>
                                    <option>Status 1</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info">Update Supplier Name</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- //Delete Supplier// -->
        <div class="modal fade" id="deleteSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " >
                    <div class="modal-header danger-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Supplier</h5>
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
        




