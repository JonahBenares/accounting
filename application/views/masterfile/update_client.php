<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-import"></i>
                </span> Client
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masterfile/client_list">Client List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Client</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-info card-img-holder text-white">
                        <h4 class="m-0">Update Client</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>masterfile/edit_client">
                            <?php foreach($update_client AS $b){ ?>
                            <div class="form-group">
                                <label >Client</label>
                                <input type="text" class="form-control" name="buyer_name" value = "<?php echo $b->buyer_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <input type="Text" class="form-control" name="address"value = "<?php echo $b->address; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person" value = "<?php echo $b->contact_person; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Contact Number</label>
                                <input type="text" class="form-control" name="contact_no" value = "<?php echo $b->contact_no; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Tin Number</label>
                                <input type="text" class="form-control" name="tin" value = "<?php echo $b->tin; ?>">
                            </div>
                            <input type = "hidden" name = "buyer_id" value="<?php echo $id; ?>">
                            <center><button type="submit" class="btn btn-primary">Update</button></center>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
        

