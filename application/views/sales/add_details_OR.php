<div class="card">
    <form>
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
                        <input type="text" class="form-control" name="amount" id="amount">
                    </div>
                    <div class="form-group">
                        <label>Vatable Sales</label>
                        <input type="text" style="text-align:right" class="form-control" name="vat_sales" id="vat_sales">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Zero Rated Sales</label>
                        <input type="text" style="text-align:right" class="form-control" name="zero_rated_sales" id="zero_rated_sales">
                    </div>
                    <div class="form-group">
                        <label>Zero Rated EcoZones Sales</label>
                        <input type="text" style="text-align:right" class="form-control" name="zero_rated_ecozone" id="zero_rated_ecozone">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Vat On Sales</label>
                        <input type="text" style="text-align:right" class="form-control" name="vat" id="vat">
                    </div>
                 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>EWT</label>
                        <input type="text" style="text-align:right" class="form-control" name="ewt" id="ewt">
                    </div>
                </div>
           
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <input type='hidden' name='sales_id' id='sales_id' value="<?php echo $sales_id; ?>">
                    <input type='hidden' name='sales_detail_id' id='sales_detail_id' value="<?php echo $sales_detail_id; ?>">
                    <button class="btn btn-primary mr-1 btn-block" value="Save" onclick = "window.open('<?php echo base_url(); ?>sales/print_OR', '_blank'); window.close();">Save and Print OR </button>
                </div>
            </div>
        </div>
    </form>
</div>