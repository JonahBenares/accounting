<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="card">
    <form method="POST" id="paymentdataall">
        <div class="card-header" >
            <h4>Add Payment Details</h4>
        </div>
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
                        <textarea class="form-control" name="particulars" id="particulars" rows="2"></textarea>
                    </div>
                </div>
            </div>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                     <label id="tad"> Total Amount Due</label>
                    <input type="text"  class="form-control"   value="<?php //echo number_format($total_amount,2); ?>" readonly>
                </div>
                 <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Total Payment Amount </label>
                        <input type="text" onkeypress="return isNumberKey(this, event)" style="text-align:right" name="payment_amount" id="payment_amount" placeholder="00.00" class="form-control">
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
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
                    <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total_amount; ?>">
                    <input type="hidden" id="purchase_id" name="purchase_id" value="<?php echo $purchase_id; ?>">
                    <input type="hidden" id="total_vatable_purchase" name="total_vatable_purchase" value="<?php echo $total_vatable_purchase; ?>">
                    <input type="hidden" id="total_vat" name="total_vat" value="<?php echo $total_vat; ?>">
                    <input type="hidden" id="total_ewt" name="total_ewt" value="<?php echo $total_ewt; ?>">
                    <button class="btn btn-primary mr-1 btn-block" value="Save" id="save_payment" onclick = "savePaymentAll()">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>