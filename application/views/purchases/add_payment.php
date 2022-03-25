<div class="card">
    <form>
        <div class="card-header">
            <h4>Add Payment Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Date of Payment</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label>Particulars</label>
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Mode</label>
                        <select class="form-control">
                            <option>Vatable Purchase</option>
                            <option>Zero-Rated Purchase</option>
                            <option>Zero-Rated Ecozones Purchase</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Amount of Purchase</label>
                        <input type="number" style="text-align:right"  placeholder="00.00" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Amount for VAT on Purchases</label>
                        <input type="number" style="text-align:right"  placeholder="00.00" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>EWT amount </label>
                        <input type="number" style="text-align:right"  placeholder="00.00" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <button class="btn btn-primary mr-1 btn-block" value="Save" onclick="parent.window.opener.location='<?php echo base_url(); ?>purchases/print_2307'; window.close();">Save and Print</button>
                </div>
            </div>
        </div>
    </form>
</div>