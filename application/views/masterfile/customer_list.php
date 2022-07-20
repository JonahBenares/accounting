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
                                        <button type="button" class="btn btn-warning " data-target="#bulk_upload" data-toggle="modal">
                                            <span class="fas fa-upload"></span> Bulk Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-respsonsive">
                                <table class="table table-hover" id="save-stage"  style="width:100%;">
                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>Participant Name</th>
                                            <th>Billing ID</th>
                                            <th>Settlement ID</th>
                                            <th>Category</th>
                                            <th width="1%" align="center">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $x = 1;
                                        if(!empty($participant)){
                                        foreach($participant AS $p){ ?>
                                        <tr>
                                            <td class="td-vertical">
                                                <div id="accordion">
                                                    <div class="accordion mb-0">
                                                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-<?php echo $x; ?>">
                                                            <h4><?php echo $p['participant_name'];?></h4>
                                                        </div>
                                                        <div class="accordion-body collapse" id="panel-body-<?php echo $x; ?>" data-parent="#accordion">
                                                            <table class="" width="100%">
                                                                <?php 
                                                                    if(!empty($sub_participant)){
                                                                    foreach($sub_participant AS $s){ 
                                                                        if($p['participant_id']==$s['participant_id']){
                                                                ?>
                                                                <tr>
                                                                    <td class="p-0"><?php echo $s['billing_id']; ?> - <?php echo $s['subparticipant_name']; ?></td>
                                                                </tr>
                                                                <?php } } } ?>
                                                                <!-- <tr>
                                                                    <td class="p-0">Sub Company 2</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="p-0">Sub Company 3</td>
                                                                </tr> -->
                                                            </table>
                                                            <hr class="mb-0">
                                                        </div>
                                                      </div>
                                                </div>
                                            </td>
                                            <td class="td-vertical"><?php echo $p['billing_id'];?> </td>
                                            <td class="td-vertical"><?php echo $p['settlement_id'];?></td>
                                            <td class="td-vertical"><?php echo $p['category'];?></td>
                                            <td class="td-vertical" align="center">
                                                <div class="btn-group mb-0 dropleft">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                        <span class="fas fa-bars">
                                                    </button>
                                                    <div class="dropdown-menu dropleft p-0" style="width:0px!important;text-align:center;box-shadow: 0 0 0 rgba(0,0,0,0)!important;background: #fff0;">
                                                        <span style="background:#fff;right: 0;position: absolute;">
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="View" data-original-title="View" href="<?php echo base_url(); ?>masterfile/customer_view/<?php echo $p['participant_id'];?>"><span class="fas fa-eye m-0"></span></a>
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Update" data-original-title="Update" href="<?php echo base_url(); ?>masterfile/customer_update/<?php echo $p['participant_id'];?>"><span class="far fa-edit m-0"></span></a>
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete" data-original-title="Delete" href="<?php echo base_url(); ?>masterfile/customer_delete/<?php echo $p['participant_id'];?>" onclick="confirmationDelete(this);return false;"><span class="fas fa-trash m-0"></span></a>
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Add Sub Company" data-original-title="Add Sub Company" href="javascript:void(0)" onclick="subparticipant('<?php echo base_url(); ?>','<?php echo $p['participant_id']; ?>')"><span class="fas fa-building m-0"></span></a>
                                                        </span>
                                                    </div>
                                                </div> 
                                            </td>  
                                        </tr>
                                        <?php 
                                         $x++;
                                        } 

                                        }

                                        ?>

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

