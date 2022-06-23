<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <?php foreach($details AS $det) { ?>
                        <form id='CustomerHead'>
                            <div class="card-header">
                               <!--  <h4>Add Customer</h4> -->
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Participant Name</label>
                                            <!-- <textarea class="form-control" rows="2" readonly=""><?php echo $det['participant_name'];?></textarea> -->
                                            <input class="form-control" rows="2" value="<?php echo $det['participant_name'];?>" name="participant_name">
                                        </div>
                                        <div class="form-group">
                                            <label>Billing ID</label>
                                            <input type="text" class="form-control" value="<?php echo $det['billing_id'];?>" name="billing_id">
                                        </div>
                                        <div class="form-group">
                                            <label>Region</label>
                                            <select class="form-control" name="region" id="region">
                                                <option value='' selected></option>
                                                <?php foreach($region as $r) { ?>
                                                    <option value='<?php echo $r->region; ?>' <?php echo (($r->region == $det['region']) ? ' selected' : ''); ?>><?php echo $r->region; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" value="<?php echo $det['category'];?>" name="category">
                                        </div>
                                        <div class="form-group">
                                            <label>Membership</label>
                                            <input type="text" class="form-control" value="<?php echo $det['membership'];?>" name="membership">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>BIR Registered Address</label>
                                            <input class="form-control" rows="2" value="<?php echo $det['registered_address'];?>" name="registered_address">
                                        </div>
                                        <div class="form-group">
                                            <label>Settlement ID</label>
                                            <input type="text" class="form-control" value="<?php echo $det['settlement_id'];?>" name="settlement_id">
                                        </div>
                                        <div class="form-group">
                                            <label>Resource</label>
                                            <input type="text" class="form-control" value="<?php echo $det['resource'];?>" name="resource">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>TIN</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['tin'];?>" name="tin">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Effective</label>
                                                    <input type="date" class="form-control" value="<?php echo $det['effective_date'];?>" name="effective_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['participant_email'];?>" name="participant_email">
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
                                                    <input type="radio" name="wht_agent" value="Yes" <?php if($det['wht_agent']=='Yes'){ ?> checked <?php } else { ?><?php } ?>>
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="wht_agent" value="No" <?php if($det['wht_agent']=='No'){ ?> checked <?php } else { ?><?php } ?>>
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
                                                    <input type="radio" name="vat_zerorated" value="Yes" <?php if($det['vat_zerorated']=='Yes'){ ?> checked <?php } else { ?> <?php } ?> >
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="vat_zerorated" value="No" <?php if($det['vat_zerorated']=='No'){ ?> checked <?php } else { ?> <?php } ?>>
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
                                                    <input type="radio" name="income_tax_holiday" value="Yes" <?php if($det['income_tax_holiday']=='YES'){ ?> checked <?php } else { ?> <?php } ?>>
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="income_tax_holiday" value="No" <?php if($det['income_tax_holiday']=='No'){ ?> checked <?php } else { ?> <?php } ?>>
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
                                            <input type="text" class="form-control" value="<?php echo $det['contact_person'];?>" name="contact_person">
                                        </div>
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" class="form-control" value="<?php echo $det['contact_position'];?>" name="contact_position">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="<?php echo $det['office_address'];?>" name="office_address">
                                        </div>
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control" value="<?php echo $det['zip_code'];?>" name="zip_code">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value='' selected></option>
                                                <?php foreach($status as $s) { ?>
                                                    <option value='<?php echo $s->status; ?>' <?php echo (($s->status == $det['status']) ? ' selected' : ''); ?>><?php echo $s->status; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['mobile'];?>" name="mobile">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Landline Number</label>
                                                    <input type="dateSS" class="form-control" value="<?php echo $det['landline'];?>" name="landline">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Person's Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['contact_email'];?>" name="contact_email">
                                        </div>
                                        <div class="form-group">
                                            <label>Documents Submitted</label>
                                            <input type="text"  name="documents_submitted" id="documents_submitted" value="<?php echo $det['documents_submitted'];?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <!-- <input class="btn btn-primary mr-1 btn-block" value="Save" type="button"> -->
                                        <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                        <input type='button' id="updateCustomer" class="btn btn-primary mr-1 btn-block" value='Update Customer' onclick='UpdateCustomer()'>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
