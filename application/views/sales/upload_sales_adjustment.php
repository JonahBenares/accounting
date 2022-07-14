<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<?php

if(!empty($sales_id)){
    $readonly = 'readonly';
} else {
    $readonly='';
}
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="line-height: 1.3;" class="p-0">Upload WESM Transaction - Sales <br><small style="letter-spacing:2px">ADJUSTMENT</small></h4>
                        </div>
                        <div class="card-body">
                            <form id=''>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                            <label>File</label>
                                            <input type="file" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <input type="text" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-35">
                                            <button class="btn btn-primary btn-sm"><span class="fas fa-plus"></span></button>
                                            <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-5">
                                            <button class="btn btn-primary btn-sm"><span class="fas fa-plus"></span></button>
                                            <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-5">
                                            <button class="btn btn-primary btn-sm"><span class="fas fa-plus"></span></button>
                                            <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                            <input type="file" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name='' id="" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-5">
                                            <button class="btn btn-primary btn-sm"><span class="fas fa-plus"></span></button>
                                            <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4  offset-lg-4 offset-md-4 offset-sm-4">
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block btn-md">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </form>   
                            <div class="table-responsive" >
                                <hr>
                                <label class="m-0"><b>Reference Number</b>: TWTW-277772-1991</label><br>
                                <label><b>Billing Period</b>: May 25, 2022 - June 25, 2022 </label>
                                <table class="table-bordered table table-hover" style="width:200%;">
                                    <thead>
                                        <tr>    
                                            <th class="p-2" width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>    
                                            <th class="p-2">Item No</th>                                        
                                            <th class="p-2">Series No.</th>
                                            <th class="p-2">STL ID / TPShort Name</th>
                                            <th class="p-2">Billing ID</th>
                                            <th class="p-2">Trading Participant Name</th>
                                            <th class="p-2">Facility Type </th>
                                            <th class="p-2">WHT Agent Tag</th>
                                            <th class="p-2">ITH Tag</th>
                                            <th class="p-2">Non Vatable Tag</th>
                                            <th class="p-2">Zero-rated Tag</th>
                                            <th class="p-2">Vatable Sales</th>
                                            <th class="p-2">Zero Rated Sales</th>
                                            <th class="p-2">Zero Rated EcoZones Sales</th>
                                            <th class="p-2">Vat On Sales</th>
                                            <th class="p-2">EWT</th>
                                            <th class="p-2">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2" align="center" style="background: #fff;">
                                                <div class="btn-group mb-0">
                                                    <a style="color:#fff" onclick="add_details_BS('','')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"></span>
                                                    </a>
                                                </div>
                                                <!-- <div class="btn-group mb-0">
                                                    <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                    </a>
                                                </div> -->
                                            </td>
                                            <td class="p-2"><center></center></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right"></td>
                                            <td class="p-2" align="right">></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" >
                                <hr>
                                <label class="m-0"><b>Reference Number</b>: TWTW-277772-1991</label><br>
                                <label><b>Billing Period</b>: May 25, 2022 - June 25, 2022 </label>
                                <table class="table-bordered table table-hover" style="width:200%;">
                                    <thead>
                                        <tr>    
                                            <th class="p-2" width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>    
                                            <th class="p-2">Item No</th>                                        
                                            <th class="p-2">Series No.</th>
                                            <th class="p-2">STL ID / TPShort Name</th>
                                            <th class="p-2">Billing ID</th>
                                            <th class="p-2">Trading Participant Name</th>
                                            <th class="p-2">Facility Type </th>
                                            <th class="p-2">WHT Agent Tag</th>
                                            <th class="p-2">ITH Tag</th>
                                            <th class="p-2">Non Vatable Tag</th>
                                            <th class="p-2">Zero-rated Tag</th>
                                            <th class="p-2">Vatable Sales</th>
                                            <th class="p-2">Zero Rated Sales</th>
                                            <th class="p-2">Zero Rated EcoZones Sales</th>
                                            <th class="p-2">Vat On Sales</th>
                                            <th class="p-2">EWT</th>
                                            <th class="p-2">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2" align="center" style="background: #fff;">
                                                <div class="btn-group mb-0">
                                                    <a style="color:#fff" onclick="add_details_BS('','')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"></span>
                                                    </a>
                                                </div>
                                                <!-- <div class="btn-group mb-0">
                                                    <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                    </a>
                                                </div> -->
                                            </td>
                                            <td class="p-2"><center></center></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right"></td>
                                            <td class="p-2" align="right">></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" >
                                <hr>
                                <label class="m-0"><b>Reference Number</b>: TWTW-277772-1991</label><br>
                                <label><b>Billing Period</b>: May 25, 2022 - June 25, 2022 </label>
                                <table class="table-bordered table table-hover" style="width:200%;">
                                    <thead>
                                        <tr>    
                                            <th class="p-2" width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>    
                                            <th class="p-2">Item No</th>                                        
                                            <th class="p-2">Series No.</th>
                                            <th class="p-2">STL ID / TPShort Name</th>
                                            <th class="p-2">Billing ID</th>
                                            <th class="p-2">Trading Participant Name</th>
                                            <th class="p-2">Facility Type </th>
                                            <th class="p-2">WHT Agent Tag</th>
                                            <th class="p-2">ITH Tag</th>
                                            <th class="p-2">Non Vatable Tag</th>
                                            <th class="p-2">Zero-rated Tag</th>
                                            <th class="p-2">Vatable Sales</th>
                                            <th class="p-2">Zero Rated Sales</th>
                                            <th class="p-2">Zero Rated EcoZones Sales</th>
                                            <th class="p-2">Vat On Sales</th>
                                            <th class="p-2">EWT</th>
                                            <th class="p-2">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2" align="center" style="background: #fff;">
                                                <div class="btn-group mb-0">
                                                    <a style="color:#fff" onclick="add_details_BS('','')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"></span>
                                                    </a>
                                                </div>
                                                <!-- <div class="btn-group mb-0">
                                                    <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                    </a>
                                                </div> -->
                                            </td>
                                            <td class="p-2"><center></center></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right"></td>
                                            <td class="p-2" align="right">></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" >
                                <hr>
                                <label class="m-0"><b>Reference Number</b>: TWTW-277772-1991</label><br>
                                <label><b>Billing Period</b>: May 25, 2022 - June 25, 2022 </label>
                                <table class="table-bordered table table-hover" style="width:200%;">
                                    <thead>
                                        <tr>    
                                            <th class="p-2" width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>    
                                            <th class="p-2">Item No</th>                                        
                                            <th class="p-2">Series No.</th>
                                            <th class="p-2">STL ID / TPShort Name</th>
                                            <th class="p-2">Billing ID</th>
                                            <th class="p-2">Trading Participant Name</th>
                                            <th class="p-2">Facility Type </th>
                                            <th class="p-2">WHT Agent Tag</th>
                                            <th class="p-2">ITH Tag</th>
                                            <th class="p-2">Non Vatable Tag</th>
                                            <th class="p-2">Zero-rated Tag</th>
                                            <th class="p-2">Vatable Sales</th>
                                            <th class="p-2">Zero Rated Sales</th>
                                            <th class="p-2">Zero Rated EcoZones Sales</th>
                                            <th class="p-2">Vat On Sales</th>
                                            <th class="p-2">EWT</th>
                                            <th class="p-2">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2" align="center" style="background: #fff;">
                                                <div class="btn-group mb-0">
                                                    <a style="color:#fff" onclick="add_details_BS('','')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"></span>
                                                    </a>
                                                </div>
                                                <!-- <div class="btn-group mb-0">
                                                    <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                    </a>
                                                </div> -->
                                            </td>
                                            <td class="p-2"><center></center></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right"></td>
                                            <td class="p-2" align="right">></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" >
                                <hr>
                                <label class="m-0"><b>Reference Number</b>: TWTW-277772-1991</label><br>
                                <label><b>Billing Period</b>: May 25, 2022 - June 25, 2022 </label>
                                <table class="table-bordered table table-hover" style="width:200%;">
                                    <thead>
                                        <tr>    
                                            <th class="p-2" width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>    
                                            <th class="p-2">Item No</th>                                        
                                            <th class="p-2">Series No.</th>
                                            <th class="p-2">STL ID / TPShort Name</th>
                                            <th class="p-2">Billing ID</th>
                                            <th class="p-2">Trading Participant Name</th>
                                            <th class="p-2">Facility Type </th>
                                            <th class="p-2">WHT Agent Tag</th>
                                            <th class="p-2">ITH Tag</th>
                                            <th class="p-2">Non Vatable Tag</th>
                                            <th class="p-2">Zero-rated Tag</th>
                                            <th class="p-2">Vatable Sales</th>
                                            <th class="p-2">Zero Rated Sales</th>
                                            <th class="p-2">Zero Rated EcoZones Sales</th>
                                            <th class="p-2">Vat On Sales</th>
                                            <th class="p-2">EWT</th>
                                            <th class="p-2">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2" align="center" style="background: #fff;">
                                                <div class="btn-group mb-0">
                                                    <a style="color:#fff" onclick="add_details_BS('','')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"></span>
                                                    </a>
                                                </div>
                                                <!-- <div class="btn-group mb-0">
                                                    <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                        <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                    </a>
                                                </div> -->
                                            </td>
                                            <td class="p-2"><center></center></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2"></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="center">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right">></td>
                                            <td class="p-2" align="right"></td>
                                            <td class="p-2" align="right">></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


                
                                       
         