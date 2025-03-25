<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>   
<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>   
<script type="text/javascript">
/*function onClick() {
  var pdf = new jsPDF('p', 'pt', 'letter');
  pdf.canvas.height = 72 * 11;
  pdf.canvas.width = 72 * 8.5;

  pdf.fromHTML(output);
  //pdf.fromHTML(document.body);

  pdf.save('test.pdf');
};

var element = document.getElementById("clickbind");
element.addEventListener("click", onClick);*/
</script>
<style>
    table#table-6 tr td {
        vertical-align: top!important;
        padding-top: 5px!important;
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header ">
                                <div class="d-flex justify-content-start">
                                    <span class="badge badge-primary badge-sm" style="margin-right:10px">MERGE</span><h4>WESM Transaction - Purchases Adjustment</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table-borderded" width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value=''>-- Select Participant --</option>
                                                        <option value=""></option>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                    <input type='text' class="form-control" name="billing_from" id="billing_from" placeholder="Billing From">
                                                </td>
                                                <td width="20%">
                                                    <input type='text' class="form-control" name="billing_to" id="billing_to" placeholder="Billing To">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="ref_no" id="ref_no">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <option value=""></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Select Due Date --</option>
                                                        <option value=""></option>
                                                    </select>
                                                </td>
                                                <td  width="1%"><button type="button" onclick="filterPurchase();" class="btn btn-primary btn-block">Filter</button></td>
                                                <input name="baseurl" id="baseurl" value="" class="form-control" type="hidden" >
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <table class="table-bordesred" width="100%">
                                    <tr>
                                        <td>Participant Name</td>
                                        <td width="45%">: Participant name here</td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: Billing Billing Period (From) here</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: Reference Number here</td>
                                        <td>Billing Period (To)</td>
                                        <td>: Billing Period here</td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: Date here</td>
                                        <td>Due Date</td>
                                        <td>: Due Here</td>
                                    </tr>                                    
                                    <tr>
                                        <td class="pt-2"  colspan="4" align="center">
                                            <a href='<?php echo base_url(); ?>purchases/download_bulk/<?php echo $ref_no; ?>/<?php echo $due_date; ?>/<?php echo 'null'; ?>/<?php echo $billfrom; ?>/<?php echo $billto; ?>/<?php echo $participants; ?>' target="_blank" class="btn btn-link ">Download Bulk 2307</a>
                                            <a href='<?php echo base_url(); ?>purchases/download_bulk_zoomed/<?php echo $ref_no; ?>/<?php echo $due_date; ?>/<?php echo 'null'; ?>/<?php echo $billfrom; ?>/<?php echo $billto; ?>/<?php echo $participants; ?>' target="_blank" class="btn btn-link ">Download Bulk 2307 (Zoomed)</a>
                                            <a href='<?php echo base_url(); ?>purchases/export_not_download_purchase_wesm/<?php echo $ref_no; ?>/<?php echo $due_date; ?>/<?php echo $billfrom; ?>/<?php echo $billto; ?>/<?php echo $participants; ?>' target="_blank" class="btn btn-link ">Quick Scan Here. If downloaded files are complete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="append"></td>
                                    </tr>
                                </table>
                                <hr class="mt-0">
                                <table class="table-bosrdered" width="100%">
                                    <tr>
                                        <td width="30%">
                                            <select name="or_no" class="form-control select2" id="or_no" style="padding:2px 2px!important;">
                                                <option value="^">Select OR Number</option>
                                            </select>
                                        </td>
                                        <td class="p-t-5 p-b-5" align="center">
                                            <p class="m-0 p-t-2" style="line-height: 20px;">Original Copy</p>
                                            <div class="m-t-1">
                                                <label for="" class="d-inline-flex mr-3">
                                                        <span class="mr-1">Yes</span>
                                                        <input type="radio" class="" name="original_copy" id="original_yes" value="1" >
                                                </label>
                                                <label for="" class="d-inline-flex">
                                                        <span class="mr-1">No</span>
                                                        <input type="radio" class="" name="original_copy" id="original_no" value="0" >
                                                </label>
                                            </div>
                                        </td>
                                        <td class="p-t-5 p-b-5" align="center">
                                            <p class="m-0 p-t-2" style="line-height: 20px;">Scanned Copy</p>
                                            <label for="" class="d-inline-flex mr-3">
                                                <span class="mr-1">Yes</span>
                                                <input type="radio" class="" name="scanned_copy" id="scanned_yes" value="1" >
                                            </label>
                                            <label for="" class="d-inline-flex">
                                                    <span class="mr-1" >No</span>
                                                    <input type="radio" class="" name="scanned_copy" id="scanned_no" value="0" >
                                            </label>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="filterPurchases()">Filter</button>
                                            <a href="<?php echo base_url(); ?>purchases/purchases_wesm/" class="btn btn-warning btn-sm">Remove Filter</a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>purchases/export_purchasetrans/" class="btn btn-success btn-sm pull-right m-l-20">Export</a>
                                        </td>
                                    </tr>
                                </table>
                                <hr class="mb-0">
                                <style type="text/css">
                                    table#table-6 tr td{
                                        border: 1px solid #efefef;
                                        padding:0px 5px;
                                    }
                                </style>
                                <br>
                                <div class="table-responsive">
                                    <table class="table-bordered table table-hover " id="table-6" style="width:300%;">
                                        <thead>
                                            <tr>
                                                <th width="3%" align="center" style="background:rgb(245 245 245);max-width:10px">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th style="max-width:5px">Item No.</th>
                                                <th style="max-width:30px">STL ID / TPShort Name</th>
                                                <th style="position:sticky;max-width: 20px!important; left:0; z-index: 10;background: rgb(240 240 240);">Billing ID</th>
                                                <th style="position:sticky; left:105px;max-width: 60px!important; z-index: 10;background: rgb(240 240 240);">Trading Participant Name</th>
                                                <th style="position:sticky; left:280px;max-width: 15px!important; z-index: 10;background: rgb(240 240 240);">Billing Period</th>
                                                <th style="position:sticky; left:380px;max-width: 10px!important; z-index: 10;background: rgb(240 240 240);">Reference No</th>
                                                <th style="max-width:20px">Facility Type </th>
                                                <th style="max-width:20px">WHT Agent Tag</th>
                                                <th style="max-width:20px">ITH Tag</th>
                                                <th style="max-width:20px">Non Vatable Tag</th>
                                                <th style="max-width:20px">Zero-rated Tag</th>
                                                <th style="max-width:20px">Vatable Purchases</th>
                                                <th style="max-width:20px">Zero Rated Purchases</th>
                                                <th style="max-width:40px">Zero Rated EcoZones Purchases </th>
                                                <th style="max-width:20px">Vat On Purchases</th>
                                                <th style="max-width:10px">EWT</th>
                                                <th style="max-width:10px">Total Amount</th>
                                                <th style="max-width:20px">OR Number</th>
                                                <th style="max-width:20px">Total Amount</th>
                                                <th style="max-width:30px">Original Copy</th>
                                                <th style="max-width:30px">Scanned Copy</th>
                                                <!-- <th width="2%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th> -->

                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center" style="background: #fff;">                                                 
                                                    <div class="btn-group mb-0">
                                                        <a href="<?php echo base_url(); ?>purchases/print_2307/" target="_blank" class="btn btn-success btn-sm"title="Print BIR Form No.2307">
                                                            <span class="m-0 fas fa-print"></span><span id="clicksBS" class="badge badge-transparent">1</span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>2</td>
                                                <td>HDYFA</td>

                                                <td style="position:sticky;max-width: 20px!important; left:0; z-index: 10;background: #fff">
                                                    <div style="width:90px;word-wrap: break-word;font-size:11px!important;">
                                                        2331
                                                    </div>
                                                </td>
                                                <td style="position:sticky; left:105px;max-width: 60px!important; z-index: 10;background: #fff; ">
                                                    <div style="font-size:11px!important;width: 160px!important;padding:1px;">
                                                        1233
                                                    </div>
                                                </td>
                                                <td style="position:sticky; left:280px;max-width: 15px!important; z-index: 10;background: #fff">
                                                    <div style="font-size:11px!important;width: 80px!important;padding:1px;">
                                                        3423
                                                    </div>
                                                </td>
                                                <td style="position:sticky; left:380px;max-width: 10px!important; z-index: 10;background: #fff">
                                                    <div style="font-size:11px!important;width: 80px!important;padding:1px;">
                                                        45334
                                                    </div>  
                                                </td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="center"></td>
                                                <td align="right">()</td>
                                                <td align="right">()</td>
                                                <td align="right">()</td>
                                                <td align="right">()</td>
                                                <td align="right"></td>
                                                <td align="right">()</td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control"  name="or_no" id="or_no" value="" >
                                                </td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control"  name="total_update" id="total_update" value="" >
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="" name="orig_copy" id="orig_yes" value='1'>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="" name="orig_copy" id="orig_no" value='2'>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="" name="scanned_copy" id="scanned_yes" value='1'>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="" name="scanned_copy" id="scanned_no>" value='2'>
                                                    </label>
                                                </td>
                                                <!-- <td align="center">
                                                    <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit">
                                                            <span class="m-0 fas fa-pen"></span>
                                                        </a>
                                                </td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <div><center><b>No Available Data...</b></center></div>
                                <?php if(isset($or_nos) && isset($original_copy) && isset($scanned_copy)){ ?>
                                    <a href="<?php echo base_url(); ?>purchases/purchases_wesm" class="btn btn-warning btn-block">Remove Filter</a>
                                <?php } ?> -->
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

            
         