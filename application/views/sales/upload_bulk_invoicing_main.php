<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk Upload - Invoicing of Sales Adjustment</h4>
                        </div>
                        <div class="card-body">
                            <form id='bulkinvoicing'> 
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="form-control select2" name="year" id="year">
                                                <option value=''>-- Select Year --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Transaction Reference</label>
                                            <select class="form-control select2" name="transaction_ref" id="transaction_ref">
                                                <option value=''>-- Select Transaction Reference --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
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
                                            <input type='button' class="btn btn-primary" id='save_bulk_invoicing' type="button" onclick="proceed_sales_invoicing()" value="Proceed" style="width:100%">
                                            <input type='button' class="btn btn-danger" id="cancel_bulkinvoicing_adjustment" onclick="cancelSalesInvoicing()" value="Cancel Transaction" style='display: none;width:100%'>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-4">
                                        <span>Due Date:</span> 
                                    </div>  -->
                                </div> 
                            </form>
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
                            </form>
                            <center><span id="alt"></span></center>
                            <div class="table-responsive" id="table-invoicing" >
                                <hr>
                                <table class="table-bordered table table-hover" id="table-7" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">Transaction No</th>
                                            <th width="10%">Billing ID</th>
                                            <th width="10%">Actual Billing ID</th>
                                            <th width="10%">Invoice No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                    	</tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitbulkadjustment" class="btn btn-success btn-md btn-block" onclick="saveBulkInvoicingAdjustment();" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>