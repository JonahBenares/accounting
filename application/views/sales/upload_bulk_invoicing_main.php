<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk Upload - Invoicing of Sales (Main)</h4>
                        </div>
                        <div class="card-body">
                            <form id='bulkinvoicing'> 
                                <div class="row">
                                   <div class="col-lg-2 col-md-2 col-sm-2">
                                       <div class="form-group">
                                        <label>Year</label>
                                        <?php if(empty($year_disp)){ ?>
                                            <select class="form-control select2" name="year" id="year">
                                                <option value=''>-- Select Year --</option>
                                                <?php 
                                                    foreach($years as $y){
                                                ?>
                                                <option value="<?php echo $y->year; ?>"><?php echo $y->year; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <input type="text" class="form-control" name='year' id="year" value="<?php echo ($year_disp != 'null') ? $year_disp : '' ?>" readonly>
                                        <?php } ?>
                                    </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Transaction Reference</label>
                                            <?php if(empty($reference_number)){ ?>
                                            <select class="form-control select2" name="reference_number" id="reference_number">
                                                <option value=''>-- Select Transaction Reference --</option>
                                                <?php 
                                                    foreach($reference AS $ref){
                                                ?>
                                                <option value="<?php echo $ref->reference_number; ?>"><?php echo $ref->reference_number; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php } else { ?>
                                            <input type="text" class="form-control" name='reference_number' id="reference_number" value="<?php echo ($reference_number != 'null') ? $reference_number : '' ?>" readonly>
                                            <?php } ?>
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
                                            <input type="text" class="form-control" name='due_date' id="due_date" value="<?php echo ($due_date != 'null') ? $due_date : '' ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($year_disp) && empty($reference_number) && empty($due_date) && empty($identifier)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_bulk_invoicing' type="button" onclick="proceed_sales_main_invoicing()" value="Proceed" style="width:100%">
                                                 <input type='button' class="btn btn-danger" id="cancel_bulk_invoicing" onclick="cancelSalesMainInvoicing()" value="Cancel Transaction" style='display: none;width:100%'>
                                             <?php } elseif ($saved==0){ ?>
                                                 <input type='button' class="btn btn-danger" id="cancel_bulk_invoicing" onclick="cancelSalesMainInvoicing()" value="Cancel Transaction" style="width:100%">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-4">
                                        <span>Due Date:</span> 
                                    </div>  -->
                                </div> 
                            </form>
                            <?php if($saved==0){ ?>
                            <form method="POST" id="upload_bulk_invoicing_main">
                                <div id="upload_bulk_invoicing_main">
                                    <hr>
                                    <?php if(!empty($year_disp) || !empty($reference_number) || !empty($due_date)){ if($saved==0 && $identifier==''){ ?>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" name="doc" placeholder="" id="bulkinvoicing_main">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_bulkinvoicing_main" onclick="upload_bulkinvoicing_main()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } } ?>
                                    <br>
                                </div>
                                <input type='hidden' name='year_disp' id='year_disp'  value="<?php echo (!empty($year_disp) ? $year_disp : ''); ?>">
                                <input type='hidden' name='reference_number' id='reference_number'  value="<?php echo (!empty($reference_number) ? $reference_number : ''); ?>">
                                <input type='hidden' name='due' id='due'  value="<?php echo (!empty($due_date) ? $due_date : ''); ?>">
                                <input type="hidden" name="identifier" id="identifier" value="<?php echo $identifier_code;?>">
                                <input type="hidden" name="main_identifier" id="main_identifier" value="<?php echo $identifier;?>">
                            </form>
                            <center><span id="alt"></span></center>
                            <?php } ?>
                            <center><span id="alt"></span></center>
                             <?php if(!empty($identifier) && !empty($details)){ ?>
                            <div class="table-responsive" id="table-invoicing" >
                                <hr>
                                <table class="table-bordered table table-hover" id="table-7" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th width="10%">Settlement ID</th>
                                            <th width="10%">Billing ID</th>
                                            <th width="10%">Invoice No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach($details AS $d){ 
                                                if(!empty($d['sales_id'])){ 
                                        ?>
                                    	<tr>
                                             <td><?php echo $d['settlement_id']; ?></td>
                                            <td><?php echo $d['billing_id']; ?>
                                            <td><?php echo $d['serial_no']; ?></td>
                                    	</tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         <?php if(!empty($details)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitbulkmain" class="btn btn-success btn-md btn-block" onclick="saveBulkInvoicingMain();" value="Save">
                        <?php } } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>