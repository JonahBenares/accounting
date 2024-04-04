<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <?php foreach($details AS $det) { ?>
                        <form id='ReserveCustomerHead'>
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Participant Name</label>
                                            <input class="form-control" rows="2" value="<?php echo $det['res_participant_name'];?>" name="res_participant_name">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Unique Billing ID</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['res_billing_id'];?>" name="res_billing_id">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Actual Billing ID</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['res_actual_billing_id'];?>" name="res_actual_billing_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Region</label>
                                            <select class="form-control" name="res_region" id="res_region">
                                                <option value='' selected></option>
                                                <?php foreach($region as $r) { ?>
                                                    <option value='<?php echo $r->res_region; ?>' <?php echo (($r->res_region == $det['res_region']) ? ' selected' : ''); ?>><?php echo $r->res_region; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_category'];?>" name="res_category">
                                        </div>
                                        <div class="form-group">
                                            <label>Membership</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_membership'];?>" name="res_membership">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>BIR Registered Address</label>
                                            <input class="form-control" rows="2" value="<?php echo $det['res_registered_address'];?>" name="res_registered_address">
                                        </div>
                                        <div class="form-group">
                                            <label>Settlement ID</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_settlement_id'];?>" name="res_settlement_id">
                                        </div>
                                        <div class="form-group">
                                            <label>Resource</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_resource'];?>" name="res_resource">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>TIN</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['res_tin'];?>" name="res_tin">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Effective</label>
                                                    <input type="date" class="form-control" value="<?php echo $det['res_effective_date'];?>" name="res_effective_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['res_participant_email'];?>" name="res_participant_email">
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
                                                    <input type="radio" name="res_wht_agent" value="Yes" <?php if($det['res_wht_agent']=='Yes'){ ?> checked <?php } else { ?><?php } ?>>
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="res_wht_agent" value="No" <?php if($det['res_wht_agent']=='No'){ ?> checked <?php } else { ?><?php } ?>>
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
                                                    <input type="radio" name="res_vat_zerorated" value="Yes" <?php if($det['res_vat_zerorated']=='Yes'){ ?> checked <?php } else { ?> <?php } ?> >
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="res_vat_zerorated" value="No" <?php if($det['res_vat_zerorated']=='No'){ ?> checked <?php } else { ?> <?php } ?>>
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
                                                    <input type="radio" name="res_income_tax_holiday" value="Yes" <?php if($det['res_income_tax_holiday']=='YES'){ ?> checked <?php } else { ?> <?php } ?>>
                                                    <div class="state p-warning">
                                                        <i class="icon material-icons">done</i>
                                                        <label> YES</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-icon p-curve p-jelly">
                                                    <input type="radio" name="res_income_tax_holiday" value="No" <?php if($det['res_income_tax_holiday']=='No'){ ?> checked <?php } else { ?> <?php } ?>>
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
                                            <input type="text" class="form-control" value="<?php echo $det['res_contact_person'];?>" name="res_contact_person">
                                        </div>
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_contact_position'];?>" name="res_contact_position">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_office_address'];?>" name="res_office_address">
                                        </div>
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control" value="<?php echo $det['res_zip_code'];?>" name="res_zip_code">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="res_status" id="res_status">
                                                <option value='' selected></option>
                                                <?php foreach($status as $s) { ?>
                                                    <option value='<?php echo $s->res_status; ?>' <?php echo (($s->status == $det['res_status']) ? ' selected' : ''); ?>><?php echo $s->res_status; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['res_mobile'];?>" name="res_mobile">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Landline Number</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['res_landline'];?>" name="res_landline">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Person's Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['res_contact_email'];?>" name="res_contact_email">
                                        </div>
                                        <div class="form-group">
                                            <label>Documents Submitted</label>
                                            <input type="text"  name="res_documents_submitted" id="documents_submitted" value="<?php echo $det['res_documents_submitted'];?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                        <input type='button' id="updateCustomer" class="btn btn-primary mr-1 btn-block" value='Update Customer' onclick='ReserveUpdateCustomer()'>
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
