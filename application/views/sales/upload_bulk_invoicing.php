<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk Upload - Invoicing of Sales Adjustment</h4>
                        </div>
                        <div class="card-body">
                            <form id='bulkinvoicing'> 
                                <div class="row">
                                    <?php if($saved==0){ ?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 offset-lg-2" >
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <?php if(empty($due_date)){ ?>
                                            <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value=''>-- Select Due Date --</option>
                                                        <?php 
                                                            foreach($due AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->due_date; ?>"><?php echo $r->due_date; ?></option>
                                                        <?php } ?>
                                            </select>
                                            <?php } else { ?>
                                            <input type="text" class="form-control" name='due_date' id="due_date" value="<?php echo $due_date ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($due_date) && empty($identifier)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_bulk_invoicing' type="button" onclick="proceed_sales_invoicing()" value="Proceed" style="width:100%">
                                                 <input type='button' class="btn btn-danger" id="cancel_bulkinvoicing_adjustment" onclick="cancelSalesInvoicing()" value="Cancel Transaction" style='display: none;width:100%'>
                                             <?php } elseif ($saved==0){ ?>
                                                 <input type='button' class="btn btn-danger" id="cancel_bulkinvoicing_adjustment" onclick="cancelSalesInvoicing()" value="Cancel Transaction" style="width:100%">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } else {?>
                                    <div class="col-lg-4">
                                        <span>Due Date:</span> <?php echo $due_date ?>
                                    </div> 
                                <?php } ?>  
                                </div> 
                            </form>
                            <?php if(!empty($due_date)){ if($saved==0){ ?>
                            <form method="POST" id="upload_bulkinvoicing_adjustment">
                                <div id="upload_bulk_invoicing_adjustment">
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" name="doc" placeholder="" id="bulkinvoicing_adjustment">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_bulkinvoicing_adjustment" onclick="upload_bulkinvoicing_adjustment()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                 <input type='hidden' name='due' id='due'  value="<?php echo (!empty($due_date) ? $due_date : ''); ?>">
                                 <input type="hidden" name="identifier" id="identifier" value="<?php echo $identifier_code;?>">
                                 <input type="hidden" name="adjustment_identifier" id="adjustment_identifier" value="<?php echo $identifier;?>">
                            </form>
                            <center><span id="alt"></span></center>
                         <?php } } ?>
                         <?php if(!empty($identifier) && !empty($details)){ ?>
                            <div class="table-responsive" id="table-invoicing" >
                                <hr>
                                <table class="table-bordered table table-hover" id="table-7" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">Transaction No</th>
                                            <th width="10%">Billing ID</th>
                                            <th width="10%">Actual Billing ID</th>
                                            <th width="10%">Invoice No</th>
                                            <th width="10%">CSR Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach($details AS $d){ 
                                                if(!empty($d['sales_adjustment_id'])){ 
                                        ?>
                                    	<tr>
                                            <td><?php echo $d['reference_number']; ?></td>
                                            <!-- <input type='hidden' name='ref_no' id='ref_no'  value="<?php echo $d['reference_number']; ?>"> -->
                                     
                                            <td><?php echo $d['billing_id']; ?></td>
                                            <td><?php echo $d['actual_billing_id']; ?></td>
                                            <td><?php echo $d['serial_no']; ?></td>
                                    		<td><?php echo $d['csr_number']; ?></td>
                                    	</tr>
                                     <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(!empty($details)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitbulkadjustment" class="btn btn-success btn-md btn-block" onclick="saveBulkInvoicingAdjustment();" value="Save">
                        <?php } } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>