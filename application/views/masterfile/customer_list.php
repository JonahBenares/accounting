<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-6">
                                    <h4>Customer List</h4>
                                </div>
                                <div class="col-6">
                                    <div class="form-group pull-right">
                                        <a href="<?php echo base_url(); ?>masterfile/customer_add" type="button" class="btn btn-primary">
                                            <span class="fas fa-plus"></span> Add
                                        </a>
                                        <!-- <a href="<?php echo base_url(); ?>masterfile/customer_add" type="button" class="btn btn-warning ">
                                            <span class="fas fa-plus"></span> Add
                                        </a> -->
                                        <button type="button" class="btn btn-warning " data-target="#bulk_upload" data-toggle="modal">
                                            <span class="fas fa-upload"></span> Bulk Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Participant Name</th>
                                            <th>Settlement ID</th>
                                            <th>Category</th>
                                            <th width="1%" align="center">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(!empty($participant)){
                                        foreach($participant AS $p){ ?>
                                        <tr >
                                            <td data-toggle="modal" data-target="#company_list"><?php echo $p['participant_name'];?></td>
                                            <td data-toggle="modal" data-target="#company_list"><?php echo $p['settlement_id'];?></td>
                                            <td data-toggle="modal" data-target="#company_list"><?php echo $p['category'];?></td>
                                            <td align="center">
                                                <div class="btn-group mb-0">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                        Option
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_view/<?php echo $p['participant_id'];?>"><span class="fas fa-eye mr-2"></span>View</a>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_update/<?php echo $p['participant_id'];?>"><span class="far fa-edit mr-2"></span>Update</a>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_delete/<?php echo $p['participant_id'];?>" onclick="confirmationDelete(this);return false;"><span class="fas fa-trash mr-2"></span>Delete</a>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="subparticipant('<?php echo base_url(); ?>','<?php echo $p['participant_id']; ?>')"><span class="fas fa-building mr-2"></span>Add Sub Company</a>
                                                    </div>
                                                </div>
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
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="bulk_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Bulk Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action="<?php echo base_url();?>masterfile/upload_customer" enctype="multipart/form-data" target='_blank'>
            <div class="modal-body">
                <div class="form-group">
                    <label>Add File:</label>
                   <input type="file" name="excelfile_customer" class="form-control">
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <!-- <button type="button" class="btn btn-primary">Upload</button> -->
                <input type="submit" class="btn btn-primary" value='Upload'>
            </div>
             </form>
        </div>
    </div>
</div>
