<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Client
            </h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Client List &nbsp;
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
                                <h4 class="m-0">Client List</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="pull-right">
                                    <button type=   "button" class="btn btn-gradient-primary btn-sm btn-rounded" data-toggle="modal" data-target="#addClient">
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
                                    <th width="40%"> Client </th>
                                    <th width="20%"> Address</th>
                                    <th width="10%"> Contact Person </th>
                                    <th width="10%"> Contact No. </th>
                                    <th width="10%"> Tin No. </th>
                                    <th width="8%"> <center><span class="mdi mdi-menu"></span> </center> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(!empty($buyer)){
                                    foreach($buyer AS $buy){ ?>
                                    <tr>
                                        <td><?php echo $buy->buyer_name;?></td>
                                        <td><?php echo $buy->address;?></td>
                                        <td><?php echo $buy->contact_person;?></td>
                                        <td><?php echo $buy->contact_no;?></td>
                                        <td><?php echo $buy->tin;?></td>
                                        <td align="center">
                                            <a href="<?php echo base_url(); ?>masterfile/update_client/<?php echo $buy->buyer_id; ?>" class="btn btn-gradient-info btn-rounded btn-xs" data-toggle="tooltip" data-placement="top" title="Update">
                                                <span class="mdi mdi-pencil"></span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>index.php/masterfile/delete_client/<?php echo $buy->buyer_id;?>" data-toggle="tooltip" data-placement="top" title="Delete" onclick="confirmationDelete(this);return false;" class = "btn btn-gradient-danger btn-rounded btn-xs">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td align="center" colspan='5'><center>No Data Available.</center></td>
                                </tr>
                                <?php } ?>                         
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

             <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header primary-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/add_client">
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Client</label>
                                <input type="text" class="form-control" name="buyer_name" placeholder="Client">
                            </div>
                            <div class="form-group">
                                <label >Address</label>
                                <input type="Text" class="form-control" name="address" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <label >Contact Person</label>
                                <input type="text" class="form-control" name="contact_person" placeholder="Contact Person">
                            </div>
                            <div class="form-group">
                                <label >Contact Number</label>
                                <input type="text" class="form-control" name="contact_no" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label >TIN</label>
                                <input type="text" class="form-control" name="tin" placeholder="TIN">
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
        




