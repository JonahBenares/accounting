<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<style>
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
                            <div class="card-header">
                                <h4>WESM Transaction - Reserve Sales</h4>
                                 <?php if($count_unsaved != 0){?>
                                    <a href="<?php echo base_url(); ?>sales/sales_wesm_reserve_unsaved">Unsaved</a>
                                 <?php } ?>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table-borderded" width="100%">
                                                <tr>
                                                    <td>
                                                        <input type='text' class="form-control" name="billing_from" id="billing_from" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Billing From">
                                                    </td>
                                                    <td>
                                                        <input type='text' class="form-control" name="billing_to" id="billing_to" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Billing To">
                                                    </td>
                                                    <td>
                                                        <select class="form-control select2" name="participant" id="participant">
                                                            <option value=''>-- Select Participant --</option>
                                                            <?php 
                                                                foreach($participant AS $p){
                                                            ?>
                                                            <option value="<?php echo $p->res_tin;?>"><?php echo $p->res_participant_name;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td  width="1%" rowspan="2" class="text-center align-middle">
                                                        <button type="button" onclick="filterReserveSales();" class="btn btn-primary btn-block mb-2">Filter</button>
                                                        <button type="button" onclick="resetBulkReserve('<?php echo $ref_no; ?>');" class="btn btn-secondary btn-block" <?php echo (!empty($ref_no) && $ref_no != 'null') ? '' : 'disabled'; ?>>Reset</button>
                                                    </td>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                     <?php if(!empty($details)) {?>
                                                         <td  rowspan="2" class="text-center align-middle">
                                                            <a href="<?php echo base_url();?>sales/reserve_sales_wesm_pdf_or_bulk/<?php echo $ref_no;?>/<?php echo $due_date;?>/<?php echo $in_ex_sub;?>/<?php echo $billingfrom;?>/<?php echo $billingto;?>/<?php echo $part_name;?>" target='_blank' class="btn btn-success btn-block">Bulk OR PDF </a>   
                                                            <a href="<?php echo base_url();?>sales/reserve_sales_wesm_pdf_si_bulk/<?php echo $ref_no;?>/<?php echo $due_date;?>/<?php echo $in_ex_sub;?>/<?php echo $billingfrom;?>/<?php echo $billingto;?>/<?php echo $part_name;?>" target='_blank' class="btn btn-warning btn-block">Bulk SI PDF</a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    
                                                    <td>
                                                        <select class="form-control select2" name="ref_no" id="ref_no">
                                                            <option value=''>-- Select Reference No --</option>
                                                            <?php 
                                                                foreach($reference AS $r){
                                                            ?>
                                                            <option value="<?php echo $r->res_reference_number; ?>"><?php echo $r->res_reference_number; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select2" name="due_date" id="due_date">
                                                            <option value="">-- Select Due Date --</option>
                                                            <?php foreach($date AS $d){ ?>
                                                                <option value="<?php echo $d->res_due_date; ?>"><?php echo $d->res_due_date; ?></option>
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
                                                         
                                                    
                                                    
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                 <?php if(!empty($details)){ ?>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <strong>Quick Scan!</strong> 
                                    <a href="<?php echo base_url(); ?>sales/export_not_download_reserve_sales_wesm/<?php echo $ref_no;?>/<?php echo $due_date;?>/<?php echo $in_ex_sub;?>/<?php echo $billingfrom;?>/<?php echo $billingto;?>/<?php echo $part_name;?>" target="_blank"><u>Click here</u></a> to check if downloaded files are complete.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>  
                                <?php } ?>
                                <hr>
                               <?php if(!empty($details) && (!empty($ref_no) || !empty($due_date))){ ?>
                                <table class="table-bsordered" width="100%">
                                    <?php 
                                        foreach($details AS $d){ 
                                            $reference_number=$d['reference_number'];
                                            $transaction_date=date("F d,Y",strtotime($d['transaction_date']));
                                            $billing_from=date("F d,Y",strtotime($d['billing_from']));
                                            $billing_to=date("F d,Y",strtotime($d['billing_to']));
                                            $duedate=date("F d,Y",strtotime($d['due_date']));
                                        }
                                        if(!empty($participant_name)){
                                    ?>
                                    <tr>
                                        <td>Participant Name</td>
                                        <td>: <?php echo (!empty($participant_name)) ? $participant_name : ''; ?></td>
                                    </tr>
                                    <?php } ?>
                                     <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: <?php echo (!empty($ref_no) && $ref_no != 'null') ? $ref_no : ''; ?></td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td> 
                                            <div class="d-flex justify-content-between align-items-center">
                                            <?php if(!empty($ref_no) && $ref_no != 'null'){ ?>
                                                <span>: <?php echo (!empty($billing_from)) ? $billing_from : ''; ?></span>
                                            <?php }else{ ?>
                                                <span>: <?php echo (!empty($billingfrom) && $billingfrom != 'null') ? date("F d,Y",strtotime($billingfrom)) : ''; ?></span>
                                            <?php } ?>
                                                <a href="" class="btn btn-danger btn-sm text-white">
                                                    <span class="fas fa-trash m-0"></span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: <?php echo (!empty($ref_no) && $ref_no != 'null') ? $transaction_date : ''; ?></td>
                                        <td>Billing Period (To)</td>
                                        <?php if(!empty($ref_no) && $ref_no != 'null'){ ?>
                                            <td>: <?php echo (!empty($billing_to)) ? $billing_to : ''; ?></td>
                                        <?php }else{ ?>
                                            <td>: <?php echo (!empty($billingto) && $billingto != 'null') ? date("F d,Y",strtotime($billingto)) : ''; ?></td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td>Due Date</td>
                                        <?php if(!empty($ref_no) && $ref_no != 'null'){ ?>
                                            <td>: <?php echo (!empty($duedate)) ? $duedate : ''; ?></td>
                                        <?php }else{ ?>
                                            <td>: <?php echo (!empty($due_date) && $due_date != 'null') ? date("F d,Y",strtotime($due_date)) : ''; ?></td>
                                        <?php } ?>
                                    </tr>
                                
                                </table>
                                <br>
                                <div class="table-responsive">
                                    <form method="POST" id="print_mult">
                                        <table class="table-bordered table table-hover " id="table-5" style="width:200%;">
                                            <thead>
                                                <tr>         
                                                    <th width="2%"><input class="form-control" type="checkbox" id="select-all"></th>
                                                    <th width="2%" hidden=""><input class="form-control" type="checkbox" id="select-all"></th>
                                                    <th width="2%" align="center" style="background:rgb(245 245 245)">
                                                        <!-- <a href="" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple"><span class="fas fa-print mr-1 mt-1 mb-1"></span></a> -->
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printReserveMultiple()"><span class="fas fa-print mr-1 mt-1 mb-1"></span></button>
                                                    </th>
                                                    <th>PDF</th>
                                                    <th>Item No</th>
                                                    <th>BS No.</th>
                                                    <th>OR No.</th>
                                                    <th>STL ID / TPShort Name</th>
                                                    <th style="position:sticky; left:0;  z-index: 10;background: rgb(240 240 240);">Billing ID</th>
                                                    <th style="position:sticky; left:99px; z-index: 10;background: rgb(240 240 240);">Trading Participant Name</th>
                                                    <th style="position:sticky; left:283px; z-index: 10;background: rgb(240 240 240);">Billing Period</th>
                                                    <th style="position:sticky; left:462px; z-index: 10;background: rgb(240 240 240);">Reference No</th>
                                                    <th>Facility Type </th>
                                                    <th>WHT Agent Tag</th>
                                                    <th>ITH Tag</th>
                                                    <th>Non Vatable Tag</th>
                                                    <th>Zero-rated Tag</th>
                                                    <th>Vatable Sales</th>
                                                    <th>Zero Rated Sales</th>
                                                    <th>Zero Rated EcoZones Sales</th>
                                                    <th>Vat On Sales</th>
                                                    <th>EWT</th>
                                                    <th>Total Amount</th>
                                                    <th>EWT Amount</th>
                                                    <th>Original Copy</th>
                                                    <th>Scanned Copy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    
                                                    if(!empty($details)){
                                                    $x=1;
                                                    foreach($details AS $s){ 
                                                ?>
                                                <tr>
                                                    <td align="center">
                                                        <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="<?php echo $identifier_code.','.$s['reserve_sales_detail_id'].','.$ref_no; ?>">
                                                            
                                                    </td>
                                                    <td hidden=""></td>
                                                    <td style="width:100px;margin: 0px 6px;">
                                                        <input type="text" class="form-control" onblur="saveResBseries('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['serial_no']; ?>')" name="series_number" id="series_number<?php echo $x; ?>" value="<?php echo $s['serial_no']; ?>">
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url();?>sales/reserve_sales_wesm_pdf_or/<?php echo $s['reserve_sales_detail_id']; ?>" title="Export PDF" target='_blank' class="btn btn-success btn-sm text-white"><span class="fas fa-file-export" style="margin:0px"></span></a>
                                                        <a href="<?php echo base_url();?>sales/reserve_sales_wesm_pdf_si/<?php echo $s['reserve_sales_detail_id']; ?>" title="Export PDF" target='_blank' class="btn btn-warning btn-sm text-white"><span class="fas fa-file-export" style="margin:0px"></span></a>
                                                    </td>
                                                    <td><center><?php echo $s['item_no'];?></center></td>
                                                    <?php if(!empty($s['old_series_no'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="BSNo" data-target="#olSeries" data-bs="<?php echo $s['serial_no']; ?>" data-old-bs="<?php echo $s['old_series_no'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $s['serial_no'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $s['serial_no'];?></td>
                                                    <?php } ?>
                                                    <?php if(!empty($s['old_series_no_col'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="ORNo" data-target="#oldOR" data-series-col="<?php echo $s['series_number']; ?>" data-old-series-col="<?php echo $s['old_series_no_col'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $s['series_number'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $s['series_number'];?></td>
                                                    <?php } ?>
                                                    <td><?php echo $s['short_name'];?></td>
                                                    <td style="position: sticky;left:0;background:#fff;z-index: 999;"><?php echo $s['actual_billing_id'];?></td>
                                                    <td style="position: sticky;left:99px;background:#fff;z-index: 999;"><?php echo $s['company_name'];?></td>
                                                    <td style="position: sticky;left:283px;background:#fff;z-index: 999;"><?php echo date("M. d, Y",strtotime($s['billing_from']))." - ".date("M. d, Y",strtotime($s['billing_to']));?></td>
                                                    <td style="position: sticky;left:462px;background:#fff;z-index: 999;"><?php echo $s['reference_number'];?></td>
                                                    <td align="center"><?php echo $s['facility_type'];?></td>
                                                    <td align="center"><?php echo $s['wht_agent'];?></td>
                                                    <td align="center"><?php echo $s['ith_tag'];?></td>
                                                    <td align="center"><?php echo $s['non_vatable'];?></td>
                                                    <td align="center"><?php echo $s['zero_rated'];?></td>
                                                    <td align="right"><?php echo $s['vatable_sales'];?></td>
                                                    <td align="right"><?php echo $s['zero_rated_sales'];?></td>
                                                    <td align="right"><?php echo $s['zero_rated_ecozones'];?></td>
                                                    <td align="right"><?php echo $s['vat_on_sales'];?></td>
                                                    <td align="right">(<?php echo $s['ewt'];?>)</td>
                                                    <td align="right"><?php echo $s['total_amount'];?></td>
                                                   <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updateReserveSales('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['reserve_sales_id']; ?>','<?php echo $s['billing_id']; ?>')" name="ewt_amount" id="ewt_amount<?php echo $x; ?>" value="<?php echo $s['ewt_amount']; ?>">
                                                    </td>
                                                    <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updateReserveSales('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['reserve_sales_id']; ?>','<?php echo $s['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_yes<?php echo $x; ?>" value='1' <?php echo ($s['original_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updateReserveSales('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['reserve_sales_id']; ?>','<?php echo $s['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_no<?php echo $x; ?>" value='2' <?php echo ($s['original_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updateReserveSales('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['reserve_sales_id']; ?>','<?php echo $s['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_yes<?php echo $x; ?>" value='1' <?php echo ($s['scanned_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updateReserveSales('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['reserve_sales_detail_id']; ?>','<?php echo $s['reserve_sales_id']; ?>','<?php echo $s['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_no<?php echo $x; ?>" value='2' <?php echo ($s['scanned_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                </tr>
                                                <?php $x++; } } ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <?php }else{ ?>
                                    <div><center><b>No Available Data...</b></center></div>
                                <?php } ?>
                       <!--  </form> -->
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="updateSerial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Billing Statement Series</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Series Number</label>
                        <input type="text" id="series_number" name="series_number" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="hidden" id="ref_no" name="ref_no" class="form-control" value="<?php echo $ref_no; ?>">
                    <input type="hidden" id="old_series_no" name="old_series_no" class="form-control">
                    <input type="hidden" id="sales_detail_id" name="sales_detail_id" class="form-control">
                    <input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
                    <button type="button" class="btn btn-primary" onclick="saveBseries()">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
                
          
<div class="modal fade" id="oldOR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #6777ef;color:#fff">
                <h4 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current OR</small>
                    <br><!-- <input type="text" id="series_no" class="form-control"> --><span id="series_no"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_series_no_disp"></span></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>                             
<div class="modal fade" id="olSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #ffa426;color:#fff">
                <h5 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current Series</small>
                    <br><span id="bs_no"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_bs_no_disp"></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
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

        function resetBulkReserve(reference_no) {
        var loc= document.getElementById("baseurl").value;
        var redirect = loc+"sales/reset_bulk_sales_reserve";

        var conf = confirm('Do you really want to reset ' + reference_no + ' to be available for bulk download again?');
        if (conf) {
             $.ajax({
                data: "reference_no="+reference_no,
                type: "POST",
                url: redirect,
                success: function(response){
                    location.reload();
                }
            });
        }
    }
</script>                             
