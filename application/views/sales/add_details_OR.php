<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="card">
    <form id='collectiondetails'>
        <div class="card-header">
            <h4>Add Details</h4>
        </div>
       
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <p>Total Amount Due: <b>29,9100.00</b></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Date Collected</label>
                        <input type="date" class="form-control" name="date_collected" id="date_collected">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Series Number</label>
                        <input type="text" class="form-control" name="series_number" id="series_number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control" name="amount" id="amount" onkeypress="return isNumberKey(event)">
                    </div>
                    <div class="form-group">
                        <label>Zero Rated</label>
                        <input type="text" style="text-align:right" class="form-control" name="zero_rated" id="zero_rated" onkeypress="return isNumberKey(event)">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>VAT</label>
                        <input type="text" style="text-align:right" class="form-control" name="vat" id="vat" onkeypress="return isNumberKey(event)">
                    </div>
                    <div class="form-group">
                        <label>Zero Rated EcoZones</label>
                        <input type="text" style="text-align:right" class="form-control" name="zero_rated_ecozone" id="zero_rated_ecozone" onkeypress="return isNumberKey(event)">
                    </div>
                </div>
            </div>
            <div class="row">
              
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>EWT</label>
                        <input type="text" style="text-align:right" class="form-control" name="ewt" id="ewt" onkeypress="return isNumberKey(event)">
                    </div>
                </div>
           
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <input type='hidden' name='sales_id' id='sales_id' value="<?php echo $sales_id; ?>">
                    <input type='hidden' name='sales_detail_id' id='sales_detail_id' value="<?php echo $sales_detail_id; ?>">
                    <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                    <input type='button' class='btn btn-primary mr-1 btn-block' onclick='collection_process()'  value="Save and Print OR">
                </div>
            </div>
        </div>
    </form>
</div>