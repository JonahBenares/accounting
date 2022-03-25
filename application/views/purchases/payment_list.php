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
                                    <!-- <div class="col-lg-6 col-md-6">
                                        <div class="input-group">
                                            <select class="custom-select" id="inputGroupSelect04">
                                                <option selected="">Choose Participant</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary m-0" type="button" style="border-radius: 0 .25rem .25rem 0;">Search</button>
                                            </div>
                                        </div>
                                    </div> -->
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
                                                <td><button class="btn btn-primary">Filter</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <table class="table-bordered table table-hover " id="example" style="width:200%;">
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
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
                                                    <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                        <span class="m-0 fas fa-indent"></span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         