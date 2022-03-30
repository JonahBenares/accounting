<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Payment</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-10 offset-lg-1">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input placeholder="Month From" class="form-control"  onfocus="(this.type='month')" type="text" id="start" name="start">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Month To" class="form-control"  onfocus="(this.type='month')" type="text" id="start" name="start">
                                                </td>
                                                <td>
                                                    <select class="form-control">
                                                        <option>-- Select Participant --</option>
                                                    </select>
                                                </td>
                                                <td><button class="btn btn-primary" type="button" onclick="payment_filter()">Filter</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive" id="payment-list" style="display: none;">
                                    <table class="table-bordered table table-hover" id="table-1" style="width:200%; ">
                                        <thead>
                                            <tr>
                                                <th width="1%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         