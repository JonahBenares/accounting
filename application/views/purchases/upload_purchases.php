<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <h4>Upload WESM Transaction - Purchases</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (From)</label>
                                            <input type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (To)</label>
                                            <input type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label><br></label>
                                            <button class="btn btn-block btn-primary" type="button" onclick="proceed_btn()">Proceed</button>
                                        </div>
                                    </div>
                                </div>                                
                                <div id="upload" style="display: none;">
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" placeholder="" aria-label="">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" onclick="upload_btn()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div id="table-wesm" style="display: none;">
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table-bordered table table-hover " id="table-1" style="width:200%;">
                                            <thead>
                                                <tr>
                                                    <th>Trading Participant Name</th>
                                                    <th>Facility Type </th>
                                                    <th>WHT Agent Tag</th>
                                                    <th>Non Vatable Tag</th>
                                                    <th>Zero-rated Tag</th>
                                                    <th>Vatable Sales</th>
                                                    <th>Zero Rated Sales</th>
                                                    <th>Zero Rated EcoZones Sales</th>
                                                    <th>Vat On Sales</th>
                                                    <th>Vatable Purchses</th>
                                                    <th>Zero Rated Purchaseses</th>
                                                    <th>Zero Rated EcoZones Purchases </th>
                                                    <th>Vat On Purchases</th>
                                                    <th>EWT</th>
                                                    <th width="1%" align="center" style="background:rgb(245 245 245)">
                                                        <center><span class="fas fa-bars"></span></center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" onClick="click()" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Tiger Nixon</td>
                                                    <td>System Architect</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td>Edinburgh</td>
                                                    <td align="center" style="background: #fff;">
                                                        <div class="btn-group mb-0">
                                                            <a href="<?php echo base_url(); ?>purchases/print_2307sample" target="_blank" onClick="onClick()" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                                <span class="m-0 fas fa-print"></span>
                                                            </a>
                                                        </div>
                                                        <a id="clicks"></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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


                
                                       
         