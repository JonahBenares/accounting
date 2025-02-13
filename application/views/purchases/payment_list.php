<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form id="Paymentfrm">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Payment</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-10 offset-lg-1">
                                        <table class='' width='100%'>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Due date --</option>
                                                        <?php foreach($due_date AS $d){ ?>    
                                                        <option value="<?php echo $d->due_date;?>" <?php echo ($d->due_date==$due) ? 'selected' : ''; ?>><?php echo date("F d,Y",strtotime($d->due_date));?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                    <button class="btn btn-primary" type="button" onclick="filterDue()">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-10 offset-lg-1">
                                        <table width="100%" class="table-bordsered">
                                            <tr>
                                                <td>
                                                    <!-- <input type="datalist" class="form-control" name="reference_number" id="reference_number" list="reflist" placeholder="Reference Number" autocomplete="off">	
                                                    <datalist id="reflist">
                                                        <option value="">-- Select Reference Number --</option>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option data-id="<?php echo $r->purchase_id; ?>" value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </datalist> -->

                                                    <select class="form-control select2 reference_number" name="reference_number" id="reference_number" multiple>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option data-id="<?php echo $r->purchase_id; ?>" value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                            <!-- <option value="<?php echo $r->purchase_id.".".$r->reference_number; ?>"><?php echo $r->reference_number; ?></option> -->
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <!-- <td>
                                                    <input type="text" class="form-control" name="market_fee" id="market_fee" placeholder="Market Fee" autocomplete="off">	
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="ewt" id="ewt" placeholder="Withholding Tax" autocomplete="off">	
                                                </td> -->
                                                <td width="1%">
                                                    <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url();?>">
                                                    <button class="btn btn-primary" id="addref" type="button" onclick="add_reference()">Add</button>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table class="table-bordered" width="100%">
                                            <tr class="td-head">
                                                <td><b>Reference Number</b></td>
                                                <td><b>Market Fee</b></td>
                                                <td><b>Withholding Tax</b></td>
                                                <td width="15%" align="center"><b>Total Amount</b></td>
                                                <td width="15%" align="center"><b>x</b></td>
                                            </tr>
                                            <tbody id="item_body">
                                                
                                            </tbody>
                                            <tfooter>
                                            <tr>
                                                    <td>
                                                        <input type="hidden" name="purchase_id[]" value="0">
                                                        <input style="width:100%;border:0px transparent" name="manual_reference" type="text" placeholder="Reference Number">
                                                    </td>
                                                    <td>
                                                        <input style="width:100%;border:0px transparent" name="market_fee[]" id="market_fee" type="text" onkeyup="calculateMarketFee()" onkeypress="return isNumberKey(this, event)" placeholder="Market Fee">
                                                    </td>
                                                    <td>
                                                        <input style="width:100%;border:0px transparent" name="withholding_tax[]" id="withholding_tax" type="text" onkeyup="calculateMarketFee()" onkeypress="return isNumberKey(this, event)" placeholder="Withholding Tax">
                                                    </td>
                                                    <td>
                                                        <input class="text-center" style="width:100%;border:0px transparent" name="total_amount[]" id="total_amount" type="text" readonly>
                                                        <input type="hidden" id="total_vatable_purchase" name="total_vatable_purchase[]" value="0">
                                                        <input type="hidden" id="total_vat" name="total_vat[]" value="0">
                                                        <input type="hidden" id="total_ewt" name="total_ewt[]" value="0">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="td-yellow">
                                                    <td align="right" colspan='4'><b>Total Amount Due</b></td>
                                                    <td align="center" id="grand" style="font-weight:800"></td>
                                                    <input type="hidden" name="counter" id="counter">
                                                </tr>
                                            </tfooter>
                                        </table>
                                        <br>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Payment</label>
                                                    <input type="date" name="payment_date" id="payment_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Total Payment Amount </label>
                                                    <input type="text" onkeypress="return isNumberKey(this, event)" style="text-align:right" name="payment_amount" id="payment_amount" placeholder="00.00" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <label>Particulars</label>
                                                    <textarea class="form-control" name="particulars" id="particulars" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                 <label id="tad"> Total Amount Due</label>
                                                <input type="text"  class="form-control"  readonly>
                                            </div>
                                             
                                        </div> -->

                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6 col-lg-6 offset-lg-3 offset-md-3 offset-sm-3">
                                                <center>
                                                    
                                                    <div class="custom-control custom-radio custom-control-inline mr-3" >
                                                        <input type="radio" id="customRadioInline2" name="customRadioInline1" value="1" class="custom-control-input" onclick="checkRadio()">
                                                        <label class="custom-control-label" for="customRadioInline2" >Check</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline" >
                                                        <input type="radio" id="customRadioInline1" name="customRadioInline1" value="2" class="custom-control-input" onclick="cashRadio()">
                                                        <label class="custom-control-label" for="customRadioInline1">Cash</label>
                                                    </div>
                                                </center>
                                            </div>
                                        </div>
                                        <div id="checkID" style="display:none">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Check No</label>
                                                        <input type="text" class="form-control" name="check_no" id="check_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label>CV No</label>
                                                        <input type="text" class="form-control" name="cv_no" id="cv_no">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="form-group">
                                                        <label>Check Date</label>
                                                        <input type="date" class="form-control" name="check_date" id="check_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cashID" style="display:none">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>PCV</label>
                                                        <input type="text" class="form-control" name="pcv" id="pcv">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <!-- <a style="color:#fff" id="pay" class="btn btn-success btn-md btn-block" onclick="pay_all('<?php echo base_url(); ?>', '<?php echo $purchase_id; ?>')">Pay All</a> -->
                                        <center><span id="alt"></span></center>
                                        <input type='button' class="btn btn-success btn-md btn-block" id='pay' onclick='savePaymentall()' style="color:#fff" value='Pay All'>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%">
                                            <tr>
                                                <td width="5%"></td>
                                                <td>
                                                    <select class="form-control select2" name="reference_number" id="reference_number">
                                                        <option value="">-- Select Reference Number --</option>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                    <button class="btn btn-primary" type="button" onclick="payment_filter()">Filter</button>
                                                </td>
                                                <td width="5%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($details) && !empty($ref_no)){ ?>
                                <div class="row">
                                    <div class="col-lg-4 offset-lg-4">
                                        <a style="color:#fff" class="btn btn-success btn-md btn-block" onclick="pay_all('<?php echo base_url(); ?>', '<?php echo $purchase_id; ?>')">Pay All</a>
                                    </div>
                                </div>   
                                <br> 
                                <div class="table-responsive" id="payment-list">
                                    <table class="table-bordered table table-hover" id="table-1" style="width:200%; ">
                                        <thead>
                                            <tr>
                                                <th width="1%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th>Trading Participant Name</th>
                                                <th>Billing ID</th>
                                                <th>Facility Type </th>
                                                <th>WHT Agent Tag</th>
                                                <th>Non Vatable Tag</th>
                                                <th>Zero-rated Tag</th>
                                                <th>Vatable Purchases</th>
                                                <th>Zero Rated Purchases</th>
                                                <th>Zero Rated EcoZones Purchases </th>
                                                <th>Vat On Purchases</th>
                                                <th>EWT</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($details AS $d){ 
                                                 if($d['balance']!=0){   
                                            ?>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>','<?php echo $d['purchase_id'];?>','<?php echo $d['purchase_detail_id'];?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?php echo $d['company_name'];?></td>
                                                <td><?php echo $d['billing_id'];?></td>
                                                <td><?php echo $d['facility_type'];?></td>
                                                <td><?php echo $d['wht_agent'];?></td>
                                                <td><?php echo $d['non_vatable'];?></td>
                                                <td><?php echo $d['zero_rated'];?></td>
                                                <td><?php echo $d['vatables_purchases'];?></td>
                                                <td><?php echo $d['zero_rated_purchases'];?></td>
                                                <td><?php echo $d['zero_rated_ecozones'];?></td>
                                                <td><?php echo $d['vat_on_purchases'];?></td>
                                                <td><?php echo $d['ewt'];?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php }else{ ?>
                                    <div><center><b>No Available Data...</b></center></div>
                                <?php } ?>
                            </div> -->
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

                
                                       
         