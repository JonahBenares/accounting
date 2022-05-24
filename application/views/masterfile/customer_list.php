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
                                                        <a class="dropdown-item" id="getId" data-toggle="modal" data-target="#basicModal" data-id="<?php echo $p['participant_id']; ?>"><span class="fas fa-building mr-2"></span>Add Sub Company</a>
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


<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Sub Participant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
<!--                 <div class="modal-body">
                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Sub Participant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($sub_participant)){
                        foreach($sub_participant AS $sp){ ?>
                        <tr >
                            <td data-toggle="modal"><?php echo $sp->sub_participant;?></td>
                        </tr>
                        <?php } } else { ?>
                    <tr>
                        <td align="center" colspan='9'><center>No Data Available.</center></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div> -->
            <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/insert_sub">
            <div class="modal-body">
                <div class="form-group addSub">
            <label>Add Sub Participant </label>
            <table class="m-b-10 append" width="100%">
            <tr>
                <td width="90%"><input type="" class="form-control " name="sub_participant[]" id="sub_participant" required="required"></td>
                <td>
                    <div class="btn-group addmoresub">
                        <button type="button" id="btnsub" class="btn btn-sm btn-primary addSub"><span class="fa fa-plus"></span></button>
                        <!-- <button class="btn btn-sm btn-danger"><span class="fa fa-times"></span></button> -->
                    </div>
                </td>
            </tr>
            </table>
            </div>
            </div>
            <input type='hidden' name='participant_id' id='participant_id' >
            <div class="modal-footer bg-whitesmoke br">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
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
<script type="text/javascript">

$(document).on("click", "#getId", function () {
     var participant_id = $(this).attr("data-id");
     $("#participant_id").val(participant_id);

});
    var  z = 1;
        $("body").on("click", ".addSub", function() {
            z++;
            var $append = $(this).parents('.append');
            var nextHtml = $append.clone().find("input").val("").end();
            nextHtml.attr('id', 'append' + z);
            var hasRmBtn = $('.remSub', nextHtml).length > 0;
            if (!hasRmBtn) {
                var rm = "<button class='btn-danger btn-sm btn-fill remSub' style='color:white'><span class='fa fa-times'></span></button>"
                $('.addmoresub', nextHtml).append(rm);
            }
            $append.after(nextHtml); 

        });

        $("body").on("click", ".remSub", function() {
            $(this).parents('.append').remove();
        });
</script>
