<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <?php foreach($details AS $det) { ?>
                        <form>
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Participant Name</label>
                                            <input class="form-control" rows="2" value="<?php echo $det['participant_name'];?>" readonly="">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Unique Billing ID</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['billing_id'];?>" readonly="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Actual Billing ID</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['actual_billing_id'];?>" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Region</label>
                                            <input type="text" class="form-control" value="<?php echo $det['region'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" value="<?php echo $det['category'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Membership</label>
                                            <input type="text" class="form-control" value="<?php echo $det['membership'];?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>BIR Registered Address</label>
                                            <input class="form-control" rows="2" value="<?php echo $det['registered_address'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Settlement ID</label>
                                            <input type="text" class="form-control" value="<?php echo $det['settlement_id'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Resource</label>
                                            <input type="text" class="form-control" value="<?php echo $det['resource'];?>" readonly="">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>TIN</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['tin'];?>" readonly="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Effective</label>
                                                    <input type="text" class="form-control" value="<?php echo (empty($det['effective_date']) ? '' : date('M j, Y', strtotime($det['effective_date'])));?>" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['participant_email'];?>" readonly="">
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
                                            <input type="text" class="form-control" value="<?php echo $det['contact_person'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="text" class="form-control" value="<?php echo $det['contact_position'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" value="<?php echo $det['office_address'];?>" readonly="">
                                        </div>
                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control" value="<?php echo $det['zip_code'];?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <input type="text" class="form-control" value="<?php echo $det['status'];?>" readonly="">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" class="form-control" value="<?php echo $det['mobile'];?>" readonly="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Landline Number</label>
                                                    <input type="dateSS" class="form-control" value="<?php echo $det['landline'];?>" readonly="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Person's Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo $det['contact_email'];?>" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <!-- <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <input class="btn btn-primary mr-1 btn-block" value="Save" type="button">
                                    </div>
                                </div> -->
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
