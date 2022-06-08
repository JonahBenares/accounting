<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Collection</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group pull-right">
                                            <button type="button" class="btn btn-warning " data-target="#bulk_upload" data-toggle="modal">
                                                <span class="fas fa-upload"></span> Bulk Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%">
                                            <tr>
                                                <td width="5%"></td>
                                                <td width="45%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value="">-- Select Participant --</option>
                                                        <?php 
                                                            foreach($participant AS $p){
                                                        ?>
                                                        <option value="<?php echo $p->billing_id; ?>"><?php echo $p->billing_id." - ".$p->participant_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="44%">
                                                    <select class="form-control select2" name="ref_number" id="ref_number">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="collection_filter()" value="Filter"></td>
                                                <td width="5%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($ref_no)){ ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php foreach($sales_head AS $sh){ ?>
                                        <table width="100%" class="table-borsdered">
                                            <tr>
                                                <td width="13%">Reference Number:</td>
                                                <td><b><?php echo $sh->reference_number; ?></b></td>
                                                <td width="13%">Transaction Date:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->transaction_date)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Billing Period:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->billing_from)); ?> - <?php echo date("F j, Y", strtotime($sh->billing_to)); ?></td>
                                                <td>Due Date:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->due_date)); ?></td>
                                            </tr>
                                        </table>   
                                        <?php } ?>                                     
                                    </div>
                                </div>
                                
                                
                                <div id="collection-list">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th width="5%" align="center">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th width="20%">Company Name</th>
                                                <th width="15%">Billing ID</th>
                                                <th width="15%">Short Name</th>
                                                <th width="10%">Vatable Sales</th>
                                                <th width="15%">Total Amount Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if(!empty($sales)){
                                                    foreach($sales AS $s){ 
                                            ?>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>', '<?php echo $s['sales_id']; ?>','<?php echo $s['sales_detail_id']; ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                  <!--   <a id="clicksOR"></a> -->
                                                </td>
                                                <td><?php echo $s['company_name']; ?></td>
                                                <td><?php echo $s['billing_id']; ?></td>
                                                <td><?php echo $s['short_name']; ?></td>
                                                <td><?php echo number_format($s['vatable_sales'],2); ?></td>
                                                <td align="right"><?php echo number_format($s['balance'],2); ?></td>
                                            </tr>
                                        <?php } } ?>
                                        </tbody>
                                    </table>
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


                
                                       
<div class="modal fade" id="bulk_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width:1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Bulk Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action="<?php echo base_url();?>masterfile/" enctype="multipart/form-data" target='_blank'>
                <div class="modal-body">
                    <span class="m-b-20">
                        <b>Important Note: </b>Make sure the columns are correct before uploading the file to make sure the data are correctly captured. Refer to the details below to ensure your columns are correctly formatted:
                    </span>
                    <br>
                    <br>
                    <table class="table-bordered" width="100%" style="font-size: 13px;">
                        <tr>
                            <td colspan="15">Column Description</td>
                        </tr>
                        <tr>
                            <td width="6%" align="center">A</td>
                            <td width="6%" align="center">B</td>
                            <td width="6%" align="center">C</td>
                            <td width="6%" align="center">D</td>
                            <td width="6%" align="center">E</td>
                            <td width="6%" align="center">F</td>
                            <td width="6%" align="center">G</td>
                            <td width="6%" align="center">H</td>
                            <td width="6%" align="center">I</td>
                            <td width="6%" align="center">J</td>
                            <td width="6%" align="center">K</td>
                            <td width="6%" align="center">L</td>
                            <td width="6%" align="center">M</td>
                            <td width="6%" align="center">N</td>
                            <td width="6%" align="center">O</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Item No</td>
                            <td style="vertical-align: top;">Billing Remarks</td>
                            <td style="vertical-align: top;">Particulars</td>
                            <td style="vertical-align: top;">Received From (STL ID)</td>
                            <td style="vertical-align: top;">Buyer Full Name</td>
                            <td style="vertical-align: top;">Statement No</td>
                            <td style="vertical-align: top;">Vatable Sales</td>
                            <td style="vertical-align: top;">Zero Rated Sales</td>
                            <td style="vertical-align: top;">Zero Rated Ecozone</td>
                            <td style="vertical-align: top;">VAT on Sales</td>
                            <td style="vertical-align: top;">Withholding Tax</td>
                            <td style="vertical-align: top;">Total</td>
                            <td style="vertical-align: top;">OR #</td>
                            <td style="vertical-align: top;">Def Int</td>
                            <td style="vertical-align: top;">Series #  </td>
                        </tr>
                    </table>
                    <br>
                    <span class="">
                        <b>Additional Notes:</b> "Subtotal" word should be in column F. There must be a an empty row every after subtotal. 
                    </span>
                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Upload File here:</label>
                               <input type="file" name="excelfile_customer" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label><br></label>
                                <input type="submit" class="btn btn-primary btn-block" value='Upload'>
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>