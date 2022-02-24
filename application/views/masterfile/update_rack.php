<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-import"></i>
                </span> Rack
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masterfile/rack_list">Rack List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Rack</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-info card-img-holder text-white">
                        <h4 class="m-0">Update Rack</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_rack">
                            <?php foreach($update_rack AS $r){ ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label >Rack Name</label>
                                            <input type="text" class="form-control" name="rack_name" value = "<?php echo $r->rack_name; ?>">
                                        </div>
                                        <input type = "hidden" name = "rack_id" value="<?php echo $id; ?>">
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
        
