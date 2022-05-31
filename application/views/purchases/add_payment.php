<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="card">
    <form method="POST" id="paymentdata">
        <div class="card-header">
            <h4>Add Payment Details</h4>
        </div>
        <?php foreach($payment AS $p){ ?>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Date of Payment</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label>Particulars</label>
                        <textarea class="form-control" name="particulars" id="particulars" rows="2" readonly><?php echo $p['company_name'];?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Mode</label>
                        <input type="text" class="form-control" name="purchase_mode" id="purchase_mode" value="<?php echo $p['mode_name']." ~ ".$p['mode_amount']; ?>" readonly>
                        <!-- <select class="form-control" name="payment_mode" id="payment_mode">
                            <option>Vatable Purchase</option>
                            <option>Zero-Rated Purchase</option>
                            <option>Zero-Rated Ecozones Purchase</option>
                        </select> -->
                    </div>
                    
                    <div class="form-group">
                        <label>Amount of Purchase</label>
                        <input type="number" style="text-align:right" onkeyup="calculatePayment()" name="purchase_amount" id="purchase_amount" placeholder="00.00" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Amount for VAT on Purchases</label>
                        <input type="number" style="text-align:right" name="vat" id="vat" placeholder="00.00" class="form-control" value="<?php echo $p['vat_on_purchases'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>EWT amount </label>
                        <input type="number" style="text-align:right" name="ewt" id="ewt" placeholder="00.00" class="form-control" value="<?php echo $p['ewt'];?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total amount </label>
                        <input type="number" style="text-align:right" name="total_amount" id="total_amount" placeholder="00.00" class="form-control" readonly>
                        <input type="hidden" name="total_calculation" id="total_calculation" value="<?php echo $p['total_amount']; ?>">
                    </div>
                </div>
            </div>
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
        </div>
        <?php } ?>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
                    <input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo $purchase_id; ?>">
                    <input type="hidden" name="purchase_detail_id" id="purchase_detail_id" value="<?php echo $purchase_detail_id; ?>">
                    <button class="btn btn-primary mr-1 btn-block" value="Save" id="save_payment" onclick = "savePayment()">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
