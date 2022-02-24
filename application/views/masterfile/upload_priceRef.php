<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white mr-2">
                  <i class="mdi mdi-upload"></i>
                </span> Upload
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Price Reference</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-success card-img-holder text-white">
                        <h4 class="m-0">Upload Price Reference</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>/masterfile/edit_category">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" name="">
                                    </div>
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="">
                                        <div class="thumbnail mt-3">
                                            <img id="pic3" class="addImage" src="<?php echo base_url() ?>assets/images/default-img.jpg" alt="your image" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Upload" class="btn btn-success btn-block" name="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
        

