<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
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
                            <h4 class="p-0">Upload WESM Transaction - Sales 
                                <!-- <a href="<?php echo base_url(); ?>sales/upload_sales_adjustment" class="btn btn-info btn-md pull-right">Adjustment</a> -->
                            </h4>
                        </div>
                        <div class="card-body">
                            <form  id='saleshead'>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name='transaction_date' id="transaction_date" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (From)</label>
                                            <input type="date" class="form-control" name='billing_from' id="billing_from" value="" >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (To)</label>
                                            <input type="date" class="form-control" name='billing_to' id="billing_to" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="text" class="form-control" name="reference_number" id="reference_number"  value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control" name='due_date' id="due_date" value="" >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_btn()" value="Proceed" style="width: 100%;">
                                            <input type='button' class="btn  btn-danger" id="cancel" onclick="cancelSales()" value="Cancel Transaction" style='display: none;width: 100%;'>
                                        </div>
                                    </div>
                                </div>
                            </form>   
                            <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert" id="alert_error" style="display:none">
                                <center>
                                    <strong>Excel file incorrect format, kindly check excel file format.</strong> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </center>
                            </div>  
                            <form method="POST" id="upload_wesm">        
                                <div id="upload">
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" placeholder="" id="WESM_sales">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_sales" onclick="upload_btn()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </form>
                            <center><span id="alt" style="display:none"><b>Please wait, Saving Data...</b></span></center>
                            <div class="table-responsive"  id="table-wesm">
                            <hr>
                            <form method="POST">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                        <table class="table-borderded" width="100%">
                                            <tr>
                                                <td>
                                                    <select class="form-control" name="in_ex_sub" id="in_ex_sub">
                                                        <option value="">-- Select Include or Exlcude Sub-participant--</option>
                                                            <option value="0">Include Sub-participant</option>
                                                            <option value="1">Exclude Sub-participant</option>
                                                    </select>
                                                </td>
                                                <td  width="1%"><button type="button" onclick="filterUploadSales();" class="btn btn-primary btn-block">Filter</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </form>
                                <br>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <center>
                                        <strong><?php echo $count_empty_actual; ?> </strong> 
                                        <span>non-existing participant/s in masterfile.</span>
                                    </center>
                                </div> 
                                <form method="POST" id="print_mult">
                                    <table class="table-bordered table table-hover " id="tables" style="width:200%;">
                                        <thead>
                                            <tr>
                                                <th width="3%" align="center" style="background:rgb(245 245 245)">
                                                    <center>
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printMultiple()">
                                                            <span class="fas fa-print mr-1 mt-1 mb-1"></span>
                                                        </button>
                                                    </center><br>
                                                    <input class="form-control" type="checkbox" id="select-all">
                                                    <input type='hidden'class="form-control" type="checkbox" id="select-all">
                                                </th>    
                                                <th width="1%">Item No</th>                                        
                                                <th>Series No.</th>
                                                <th>STL ID / TPShort Name</th>
                                                <th>Billing ID</th>
                                                <th>Unique Billing ID</th>
                                                <th style="position: sticky;left:0;background:#f3f3f3;z-index: 999;" width="15%">Trading Participant Name</th>
                                                <th>Facility Type </th>
                                                <th width="3%">WHT Agent Tag</th>
                                                <th width="3%">ITH Tag</th>
                                                <th width="3%">Non Vatable Tag</th>
                                                <th width="3%">Zero-rated Tag</th>
                                                <th>Vatable Sales</th>
                                                <th>Zero Rated Sales</th>
                                                <th>Zero Rated EcoZones Sales</th>
                                                <th>Vat On Sales</th>
                                                <th>EWT</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center" >
                                                    <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="">
                                                    <!-- <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_details_BS('<?php echo base_url(); ?>','<?php echo $d['sales_detail_id']; ?>')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                            <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div> -->
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/" target="_blank" onclick = "countPrint('')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                            <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><center></center></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!-- <td <?php echo ($d['billing_id']=='') ? 'style="position: sticky;left:0;z-index: 999;"' : 'style="position: sticky;left:0;background:#fff;z-index: 999;"'?>><?php echo $d['company_name'];?></td> -->
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="right"></td>
                                                <td align="right"></td>
                                                <td align="right"></td>
                                                <td align="right"></td>
                                                <td align="right">(90)</td>
                                                <td align="right"></td> 
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                            <!-- <center><div id='alt1' style="font-weight:bold; display:none"><b>Please wait, Saving Data...</b></div></center>
                            <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save"> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
    })
});
</script>


                
                                       
         