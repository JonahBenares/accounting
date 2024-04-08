<script src="<?php echo base_url(); ?>assets/js/reserve.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form id="Paymentfrm">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Reserve Payment</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-10 offset-lg-1">
                                        <table class='' width='100%'>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Due date --</option>
                                                        <?php foreach($due_date AS $d){ ?>    
                                                        <option value="<?php echo $d->due_date;?>" <?php echo ($d->due_date==$due) ? 'selected' : ''; ?>><?php echo date("F d,Y",strtotime($d->due_date));?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                    <button class="btn btn-primary" type="button" onclick="filterDue()">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-10 offset-lg-1">
                                        <table width="100%" class="table-bordsered">
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="reference_number" id="reference_number">
                                                        <option value="">-- Select Reference Number --</option>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option value="<?php echo $r->reserve_id.".".$r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url();?>">
                                                    <button class="btn btn-primary" type="button" onclick="add_reference()">Add</button>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table class="table-bordered" width="100%">
                                            <tr class="td-head">
                                                <td><b>Reference Number</b></td>
                                                <td width="15%" align="center"><b>Total Amount</b></td>
                                            </tr>
                                            <tbody id="item_body"></tbody>
                                            <tfooter>
                                                <tr class="td-yellow">
                                                    <td align="right"><b>Total Amount Due</b></td>
                                                    <td align="right" id="grand" style="font-weight:800"></td>
                                                    <input type="hidden" name="counter" id="counter">
                                                </tr>
                                            </tfooter>
                                        </table>
                                        <br>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Date of Payment</label>
                                                    <input type="date" name="payment_date" id="payment_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Total Payment Amount </label>
                                                    <input type="text" onkeypress="return isNumberKey(this, event)" style="text-align:right" name="payment_amount" id="payment_amount" placeholder="00.00" class="form-control">
                                                </div>
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
                                        <br>
                                        <center><span id="alt"></span></center>
                                        <input type='button' class="btn btn-success btn-md btn-block" id='pay' onclick='savePayment()' style="color:#fff" value='Pay All'>
                                        <br>
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


                
                                       
         