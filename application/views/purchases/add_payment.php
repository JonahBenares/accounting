<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
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
            <hr>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 offset-lg-3 offset-md-3 offset-sm-3">
                    <center>
                        
                        <div class="custom-control custom-radio custom-control-inline mr-3" >
                            <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input" onclick="checkRadio()">
                            <label class="custom-control-label" for="customRadioInline2" >Check</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline" >
                            <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" onclick="cashRadio()">
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
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>CV No</label>
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Check Date</label>
                            <input type="date" class="form-control" name="">
                        </div>
                    </div>
                </div>
            </div>
            <div id="cashID" style="display:none">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>PCV</label>
                            <input type="text" class="form-control" name="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <button class="btn btn-primary mr-1 btn-block" value="Save" onclick = "window.close();">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
