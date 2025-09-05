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
    .card-header {
        display: flex!important ;
        justify-content: space-between!important;
        align-items: center!important;
        padding: 8px 12px;
        border-bottom: 1px solid #ccc;
    }
    .card-header h4 {
        margin: 0;
    }
    .card-header a {
        text-decoration: none;
        padding: 1px 10px;
        background: #ffc107;
        color: #fff;
        border-radius: 4px;
        font-size: 12px;
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <h4>WESM Transaction - Purchases Adjustment</h4>
                                <?php if($count_unsaved != 0){?>
                                  <a href="<?php echo base_url(); ?>purchases/purchases_wesm_adjustment_unsaved">Unsaved</a>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table-borderded" width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value=''>-- Select Participant --</option>
                                                        <?php 
                                                            foreach($participant AS $p){
                                                        ?>
                                                        <option value="<?php echo $p->tin;?>"><?php echo $p->participant_name;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                    <select class="form-control select2" name="due_date_from" id="due_date_from">
                                                        <option value="">-- Select Due Date From--</option>
                                                        <?php foreach($date AS $d){ ?>
                                                            <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                    <select class="form-control select2" name="due_date_to" id="due_date_to">
                                                        <option value="">-- Select Due Date To--</option>
                                                        <?php foreach($date AS $d){ ?>
                                                            <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="ref_no" id="ref_no">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="in_ex_sub" id="in_ex_sub">
                                                        <option value="">-- Select Include or Exlcude Sub-participant--</option>
                                                            <option value="0">Include Sub-participant</option>
                                                            <option value="1">Exclude Sub-participant</option>
                                                    </select>
                                                </td>
                                                <td >
                                                    <button type="button" onclick="filterPurchaseAdj();" class="btn btn-primary btn-block">Filter</button>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($details) && ((!empty($ref_no) || !empty($due_date)))){ ?>
                                <table class="table-bsordered" width="100%">
                                    <?php 
                                        foreach($details AS $d){ 
                                            $reference_number=$d['reference_number'];
                                            $transaction_date=date("F d,Y",strtotime($d['transaction_date']));
                                            $billing_from=date("F d,Y",strtotime($d['billing_from']));
                                            $billing_to=date("F d,Y",strtotime($d['billing_to']));
                                            $due_dates=date("F d,Y",strtotime($d['due_date']));
                                        }
                                        if(!empty($participant_name)){
                                    ?>
                                    <tr>
                                        <td>Participant Name</td>
                                        <td width="45%">: <?php echo (!empty($participant_name)) ? $participant_name : ''; ?></td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: <?php echo (!empty($billing_from)) ? $billing_from : ''; ?></td>
                                    </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <td>Participant Name</td>
                                            <td width="45%">: --</td>
                                            <td width="15%">Billing Period (From)</td>
                                            <td>: <?php echo (!empty($billing_from)) ? $billing_from : ''; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: <?php echo (!empty($reference_number)) ? $reference_number : ''; ?></td>
                                        <td>Billing Period (To)</td>
                                        <td>: <?php echo (!empty($billing_to)) ? $billing_to : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: <?php echo (!empty($transaction_date)) ? $transaction_date : ''; ?></td>
                                        <td>Due Date</td>
                                        <td>: <?php echo (!empty($due_dates)) ? $due_dates : ''; ?></td>
                                    </tr>    
                                    <tr>
                                        <td class="pt-2"  colspan="4" align="center">
                                            <a href='<?php echo base_url(); ?>purchases/download_bulk_adjustment/<?php echo $ref_no; ?>/<?php echo $due_date_from; ?>/<?php echo $due_date_to; ?>/<?php echo $in_ex_sub; ?>/<?php echo $participants; ?>'target="_blank" class="btn btn-link ">Download Bulk 2307</a>
                                            <a href='<?php echo base_url(); ?>purchases/download_bulk_zoomed_adjustment/<?php echo $ref_no; ?>/<?php echo $due_date_from; ?>/<?php echo $due_date_to; ?>/<?php echo $in_ex_sub; ?>/<?php echo $participants; ?>' target="_blank" class="btn btn-link ">Download Bulk 2307 (Zoomed)</a>
                                            <a href='<?php echo base_url(); ?>purchases/export_not_download_purchase_wesm_adjustment/' target="_blank" class="btn btn-link ">Quick Scan Here. If downloaded files are complete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="append"></td>
                                    </tr>
                                </table>
                                <hr class="mt-0">
                                <table width="100%">
                                    <tr>
                                        <td width="30%">
                                            <select name="or_no" class="form-control select2" id="or_no" style="padding:2px 2px!important;">
                                                <option value="^">Select OR Number</option>
                                                <?php foreach($or_no AS $o){ ?>
                                                    <option value="<?php echo ($o->or_no!='') ? $o->or_no : '-'; ?>"><?php echo ($o->or_no!='') ? $o->or_no : '-'; ?></option>
                                                <?php } ?>
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
                                            <input type="hidden" name="ref_no" id="reference_no" value="<?php echo $ref_no; ?>">
                                            <input type="hidden" name="due_date_from" id="due_datefilt_from" value="<?php echo $due_date_from; ?>">
                                            <input type="hidden" name="due_date_to" id="due_datefilt_to" value="<?php echo $due_date_to; ?>">
                                            <input type="hidden" name="in_ex_sub" id="in_ex_subfilt" value="<?php echo $in_ex_sub; ?>">
                                            <input name="baseurl" id="base_url" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                            <button type="button" class="btn btn-primary btn-sm" onclick="filterPurchasesAdj()">Filter</button>
                                            <a href="<?php echo base_url(); ?>purchases/purchases_wesm_adjustment/<?php echo $ref_no; ?>/<?php echo $due_date_from; ?>/<?php echo $due_date_to; ?>/<?php echo $in_ex_sub; ?>/<?php echo $participants; ?>" class="btn btn-warning btn-sm">Remove Filter</a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>purchases/export_purchasetransadjust/<?php echo $ref_no; ?>/<?php echo $due_date_from; ?>/<?php echo $due_date_to; ?>/<?php echo $in_ex_sub; ?>/<?php echo $or_nos; ?>/<?php echo $original_copy; ?>/<?php echo $scanned_copy; ?>/<?php echo $participants; ?>" class="btn btn-success btn-sm pull-right m-l-20">Export</a>
                                        </td>
                                    </tr>
                                </table>
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
                                                <th style="position:sticky; left:280px;max-width: 15px!important; z-index: 10;background: rgb(240 240 240);">Due Date</th>
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
                                            <?php 
                                                $x=1;
                                                foreach($details AS $d){ 
                                                    if(!empty($d['purchase_id'])){ 
                                            ?>
                                            <tr>
                                                <td align="center" style="background: #fff;">                                                 
                                                    <div class="btn-group mb-0">
                                                        <a href="<?php echo base_url(); ?>purchases/print_2307/<?php echo $d['purchase_id']; ?>/<?php echo $d['purchase_detail_id']; ?>" target="_blank" class="btn btn-success btn-sm"title="Print BIR Form No.2307">
                                                            <span class="m-0 fas fa-print"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?php echo $d['item_no'];?></td>
                                                <td ><?php echo $d['short_name'];?></td>
                                                <td style="position:sticky;max-width: 20px!important; left:0; z-index: 10;background: #fff">
                                                    <div style="width:90px;word-wrap: break-word;font-size:11px!important;">
                                                        <?php echo $d['actual_billing_id']; ?>
                                                    </div>
                                                </td>

                                                <td style="position:sticky; left:105px;max-width: 60px!important; z-index: 10;background: #fff; ">
                                                    <div style="font-size:11px!important;width: 160px!important;padding:1px;">
                                                        <?php echo $d['company_name']; ?>
                                                    </div>
                                                </td>
                                                <td style="position:sticky; left:280px;max-width: 15px!important; z-index: 10;background: #fff">
                                                    <div style="font-size:11px!important;width: 80px!important;padding:1px;">
                                                        <?php echo date("M. d, Y",strtotime($d['due_date'])); ?>
                                                    </div>
                                                </td>
                                                <td style="position:sticky; left:380px;max-width: 10px!important; z-index: 10;background: #fff">
                                                    <div style="font-size:11px!important;width: 80px!important;padding:1px;">
                                                        <?php echo $d['reference_number']; ?>
                                                    </div>  
                                                </td>
                                                <td align="center"><?php echo $d['facility_type']; ?></td>
                                                <td align="center"><?php echo $d['wht_agent']; ?></td>
                                                <td align="center"><?php echo $d['ith_tag']; ?></td>
                                                <td align="center"><?php echo $d['non_vatable']; ?></td>
                                                <td align="center"><?php echo $d['zero_rated']; ?></td>
                                                <td align="right">(<?php echo $d['vatables_purchases']; ?>)</td>
                                                <td align="right">(<?php echo $d['zero_rated_purchases']; ?>)</td>
                                                <td align="right">(<?php echo $d['zero_rated_ecozones']; ?>)</td>
                                                <td align="right">(<?php echo $d['vat_on_purchases']; ?>)</td>
                                                <td align="right"><?php echo $d['ewt']; ?></td>
                                                <td align="right">(<?php echo $d['total_amount']; ?>)</td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="or_no" id="or_no<?php echo $x; ?>" value="<?php echo $d['or_no'];?>">
                                                </td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="total_update" id="total_update<?php echo $x; ?>" value="<?php echo $d['total_update']; ?>">
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_yes<?php echo $x; ?>" value='1' <?php echo ($d['original_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_no<?php echo $x; ?>" value='2' <?php echo ($d['original_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_yes<?php echo $x; ?>" value='1' <?php echo ($d['scanned_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_no<?php echo $x; ?>" value='2' <?php echo ($d['scanned_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <!-- <td align="center">
                                                    <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit">
                                                            <span class="m-0 fas fa-pen"></span>
                                                        </a>
                                                </td> -->
                                            </tr>
                                            <?php } $x++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php }else{ ?>
                                    <div><center><b>No Available Data...</b></center></div>
                                    <?php if(isset($or_nos) && isset($original_copy) && isset($scanned_copy)){ ?>
                                        <a href="<?php echo base_url(); ?>purchases/purchases_wesm_adjustment" class="btn btn-warning btn-block">Remove Filter</a>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

            
         