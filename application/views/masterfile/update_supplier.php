<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-import"></i>
                </span> Supplier
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masterfile/supplier_list">Supplier List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Supplier</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-info card-img-holder text-white">
                        <h4 class="m-0">Update Supplier</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_supplier">
                            <?php foreach($update_supplier AS $s){ ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Supplier Code</label>
                                            <input type="text" class="form-control" name="supplier_code" value = "<?php echo $s->supplier_code; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Supplier Name</label>
                                            <input type="Text" class="form-control" name="supplier_name" value = "<?php echo $s->supplier_name; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" name="address" value = "<?php echo $s->address; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" class="form-control" name="contact_number" value = "<?php echo $s->contact_number; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Term</label>
                                            <input type="text" class="form-control" name="terms" value = "<?php echo $s->terms; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" type="text" name="active">
                                                <option value = "1" <?php echo (($s->active == '1') ? 'selected' : '');?>>Acive</option>
                                                <option value = "0" <?php echo (($s->active == '0') ? 'selected' : '');?>>Inactive</option>
                                            </select>
                                        </div>
                                        <input type = "hidden" name = "supplier_id" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-info">Update</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
        