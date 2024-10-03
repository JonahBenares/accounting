
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <!-- General CSS Files -->    

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" >
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/pretty-checkbox/pretty-checkbox.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<br>
<div class="container-fluid">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                    <form method="POST" action="http://tradingsvr/accounting/sales/sales_wesm_pdf_scan_directory">
                            <div class="card-header">
                                <div class="d-flex justify-content-start">  
                                    <div>
                                        <a href="#" onclick="window.close()" class="btn btn-warning mr-2">Back</a>
                                    </div>
                                    <div>
                                        <h4 class="pt-1">WESM Transaction - Sales</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                            <?php if(!empty($result)){ ?>
                                
                                <div class="alert alert-danger  alert-dismissible fade show mt-2" role="alert">
                                        <span class="p-2">The following file/s are not downloaded. Please click Download button below.</span>
                                        
                                        <button class="close" onclick="history.back()" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> 
                                <table class="table-bordered table table-hosver mt-2"> 
                                    <thead>
                                        <tr>
                                            <th width="1%">Filename</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                               
                                            foreach($result AS $r){ ?>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1"><?php echo $r ?></td>
                                                <input type="hidden" name="filenames[]" value="<?php echo $r; ?>">
                                            </tr>
                                            <?php }  ?>
                                       
                                    </tbody>
                                </table>
                                <div class="">
                                    <center>
                                        <input type="submit" class="btn btn-success" value="Download All">
                                    </center>
                                </div>
                                <?php } else {?>
                                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                        <span class="p-2">All files were successfully downloaded!</span>
                                        
                                        <button class="close" onclick="history.back()" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> 
                                    
                                <?php } ?>
                            </div>
                            
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
