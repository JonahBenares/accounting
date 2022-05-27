<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="card">
    <form method="POST" id="serial">
        <div class="card-header">
            <h4>Add Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label>Date Collected</label>
                        <input type="date" class="form-control">
                    </div>
                </div> -->
                <div class="col-lg-8 col-md-8 col-sm-8 offset-lg-2 offset-md-2 offset-sm-2">
                    <div class="form-group">
                        <label>Series Number</label>
                        <input type="text" id="serial_no" name="serial_no" class="form-control" required="">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                     <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                     <input type="hidden" name="purchase_detail_id" id="purchase_detail_id" value="<?php echo $purchase_detail_id;?>">
                    <button type="button" class="btn btn-primary mr-1 btn-block" value="Save" onclick = "saveBS()">Save and Print Billing Statement</button>
                </div>
            </div>
        </div>
    </form>
</div>