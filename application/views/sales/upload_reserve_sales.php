<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<?php

if(!empty($res_sales_id)){
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
                            <h4 class="p-0">Upload WESM Transaction - Reserve Sales
                            </h4>
                        </div>
                        <div class="card-body">
                            <form  id='reservesaleshead'>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name='res_transaction_date' id="res_transaction_date" value="<?php echo (!empty($res_sales_id) ? $res_transaction_date : ''); ?>" required <?php echo $readonly; ?> >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (From)</label>
                                            <input type="date" class="form-control" name='res_billing_from' id="res_billing_from" value="<?php echo (!empty($res_sales_id) ? $res_billing_from : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (To)</label>
                                            <input type="date" class="form-control" name='res_billing_to' id="res_billing_to" value="<?php echo (!empty($res_sales_id) ? $res_billing_to : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="text" class="form-control" name="res_reference_number" id="res_reference_number"  value="<?php echo (!empty($res_sales_id) ? $res_reference_number : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control" name='res_due_date' id="res_due_date" value="<?php echo (!empty($res_sales_id) ? $res_due_date : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                             <?php if(empty($res_sales_id)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_res_btn()" value="Proceed" style="width: 100%;">
                                                <input type='button' class="btn  btn-danger" id="cancel" onclick="cancelResSales()" value="Cancel Transaction" style='display: none;width: 100%;'>
                                                <?php } else { ?>
                                                <input type='button' class="btn btn-danger" id="cancel" onclick="cancelResSales()" value="Cancel Transaction" style="width: 100%;">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form method="POST" id="upload_reserve_wesm">        
                                <div id="upload_res" <?php echo (empty($res_sales_id) ? 'style="display:none"' : ''); ?>>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                                <div class="form-group mb-0">
                                                    <div class="input-group mb-0">
                                                        <input type="file" class="form-control" placeholder="" id="WESM_reserve_sales">

                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" id="proceed_reserve_sales" onclick="upload_res_btn()"  type="button">Upload</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <input type='hidden' name='reserve_sales_id' id='reserve_sales_id'  value="<?php echo (!empty($res_sales_id) ? $res_sales_id : ''); ?>">
                                <input type='hidden' name='count_name' id='count_name'  value="<?php echo (!empty($count_name) ? $count_name : ''); ?>">
                            </form>
                            <center><span id="alt"></span></center>
                            <?php if(!empty($details)){ ?>
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
                                                    <input type='hidden' name='res_sales_id' id='res_sales_id'  value="<?php echo (!empty($res_sales_id) ? $res_sales_id : ''); ?>">
                                                    <td  width="1%"><button type="button" onclick="filterUploadResSales();" class="btn btn-primary btn-block">Filter</button></td>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" id="print_mult">
                                    <table class="table-bordered table table-hover " id="table-2" style="width:200%;">
                                        <thead>
                                            <tr>
                                                <th width="3%" align="center" style="background:rgb(245 245 245)">
                                                    <?php if($res_saved==1){ ?>
                                                    <center><button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printReserveMultiple()"><span class="fas fa-print mr-1 mt-1 mb-1"></span></button></center><br>
                                                    <input class="form-control" type="checkbox" id="select-all">
                                                    <input type='hidden'class="form-control" type="checkbox" id="select-all">
                                                    <?php } ?>
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
                                            <?php 
                                                foreach($details AS $d){ 
                                                    if(!empty($d['reserve_sales_id'])){ 
                                            ?>
                                            <tr>
                                                
                                                <td align="center" >
                                                    <?php if($res_saved==1){ ?>
                                                    <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="<?php echo $identifier_code.','.$d['reserve_sales_detail_id'].','.$res_reference_number; ?>">
                                                    <?php } ?>
                                                </td>
                                                <td><center><?php echo $d['res_item_no'];?></center></td>
                                                <td><?php echo $d['res_serial_no'];?></td>
                                                <td><?php echo $d['res_short_name'];?></td>
                                                <td><?php echo $d['res_actual_billing_id'];?></td>
                                                <td><?php echo $d['res_billing_id'];?></td>
                                                <td style="position: sticky;left:0;background:#fff;z-index: 999;"><?php echo $d['res_company_name'];?></td>
                                                <td align="center"><?php echo $d['res_facility_type'];?></td>
                                                <td align="center"><?php echo $d['res_wht_agent'];?></td>
                                                <td align="center"><?php echo $d['res_ith_tag'];?></td>
                                                <td align="center"><?php echo $d['res_non_vatable'];?></td>
                                                <td align="center"><?php echo $d['res_zero_rated'];?></td>
                                                <td align="right"><?php echo $d['res_vatable_sales'];?></td>
                                                <td align="right"><?php echo $d['res_zero_rated_sales'];?></td>
                                                <td align="right"><?php echo $d['res_zero_rated_ecozones'];?></td>
                                                <td align="right"><?php echo $d['res_vat_on_sales'];?></td>
                                                <td align="right">(<?php echo $d['res_ewt'];?>)</td>
                                                <td align="right"><?php echo $d['res_total_amount'];?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($details)){ if($res_saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAllReserve();" value="Save">
                        <?php } } ?>
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


                
                                       
         