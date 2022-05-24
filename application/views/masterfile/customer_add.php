<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form id='CustomerHead'>
                            <div class="card-header">
                                <h4>Add Customer</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Participant Name</label>
                                            <textarea class="form-control"  name="participant_name" id="participant_name" rows="2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Billing ID</label>
                                            <input type="text"  name="billing_id" id="billing_id" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Region</label>
                                            <select class="form-control" name="region" id="region">
                                                <option value='' selected></option>
                                                <?php foreach($region as $r) { ?>
                                                    <option value='<?php echo $r->region; ?>'><?php echo $r->region; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text"  name="category" id="category" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Membership</label>
                                            <input type="text"  name="membership" id="membership" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>BIR Registered Address</label>
                                            <textarea class="form-control"  name="registered_address" id="registered_address" rows="2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Settlement ID</label>
                                            <input type="text"  name="settlement_id" id="settlement_id" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Resource</label>
                                            <input type="text"  name="resource" id="resource" class="form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>TIN</label>
                                                    <input type="text"  name="tin" id="tin" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Effective</label>
                                                    <input type="date"  name="effective_date" id="effective_date" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email"  name="participant_email" id="participant_email" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <center>
                                            <label>Witholding Tax Agent</label>
                                            <div class="form-group mb-2">
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="wht_agent" value="Yes">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="wht_agent" value="No">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> NO</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <center>
                                            <label>VAT zero-rated</label>
                                            <div class="form-group mb-2">
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="vat_zerorated" value="Yes">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="vat_zerorated" value="No">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> NO</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <center>
                                            <label>Income tax holiday</label>
                                            <div class="form-group mb-2">
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="income_tax_holiday" value="Yes">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="income_tax_holiday" value="No">
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> NO</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Contact Person</label>
                                            <input type="text"  name="contact_person" id="contact_person" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text"  name="contact_position" id="contact_position" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text"  name="office_address" id="office_address" class="form-control">
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Billing ID</label>
                                                    <input type="text"  name="billing_id" id="billing_id" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Settlement ID</label>
                                                    <input type="text"  name="settlement_id" id="settlement_id" class="form-control">
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value='' selected></option>
                                                <?php foreach($status as $s) { ?>
                                                    <option value='<?php echo $s->status; ?>'><?php echo $s->status; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text"  name="mobile" id="mobile" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Landline Number</label>
                                                    <input type="dateSS"  name="landline" id="landline" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Person's Email Address</label>
                                            <input type="email"  name="contact_email" id="contact_email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Documents Submitted</label>
                                            <input type="text"  name="documents_submitted" id="documents_submitted" class="form-control" name="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <!-- <input class="btn btn-primary mr-1 btn-block" value="Save" type="button"> -->
                                        <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                        <input type='button' id="saveCustomer" class="btn btn-primary mr-1 btn-block" value='Save Customer' onclick='saveCustomer()'>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


<!-- <div class="col-lg-3 col-md-3 col-sm-3">
    <center>
    <label>Documents submitted</label>
        <div class="form-group mb-2">
            <div class="pretty p-icon p-curve p-jelly">
                <input type="radio" name="docu">
                <div class="state p-warning">
                    <i class="icon material-icons">done</i>
                    <label> YES</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="pretty p-icon p-curve p-jelly">
                <input type="radio" name="docu">
                <div class="state p-warning">
                    <i class="icon material-icons">done</i>
                    <label> NO</label>
                </div>
            </div>
        </div>
    </center>
</div> -->