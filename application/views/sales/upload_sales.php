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
                                            <input type="date" class="form-control" name='transaction_date' id="transaction_date" value="<?php echo (!empty($sales_id) ? $transaction_date : ''); ?>" required <?php echo $readonly; ?> >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (From)</label>
                                            <input type="date" class="form-control" name='billing_from' id="billing_from" value="<?php echo (!empty($sales_id) ? $billing_from : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (To)</label>
                                            <input type="date" class="form-control" name='billing_to' id="billing_to" value="<?php echo (!empty($sales_id) ? $billing_to : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="text" class="form-control" name="reference_number" id="reference_number"  value="<?php echo (!empty($sales_id) ? $reference_number : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control" name='due_date' id="due_date" value="<?php echo (!empty($sales_id) ? $due_date : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-2 col-md-2 col-sm-2">
                                        <label><br></label>
                                        <div class="form-group mb-2">
                                            <div class="pretty p-icon p-curve p-jelly">
                                                <input type="checkbox" name="adjustment" value="1" <?php echo (!empty($sales_id)) ? (($adjustment==1) ? 'checked' : '') : ''; ?>>
                                                <div class="state p-warning">
                                                    <i class="icon material-icons">done</i>
                                                    <label>Adjustment</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($sales_id)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_btn()" value="Proceed" style="width: 100%;">
                                                <input type='button' class="btn  btn-danger" id="cancel" onclick="cancelSales()" value="Cancel Transaction" style='display: none;width: 100%;'>
                                             <?php } else { ?>
                                                <input type='button' class="btn btn-danger" id="cancel" onclick="cancelSales()" value="Cancel Transaction" style="width: 100%;">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </form>   
                            <form method="POST" id="upload_wesm">        
                                <div id="upload" <?php echo (empty($sales_id) ? 'style="display:none"' : ''); ?>>
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
                                <input type='hidden' name='sales_id' id='sales_id'  value="<?php echo (!empty($sales_id) ? $sales_id : ''); ?>">
                            </form>
                            <center><span id="alt"></span></center>
                            <?php if(!empty($details)){ ?>
                            <div class="table-responsive"  id="table-wesm">
                                <hr>
                                <form method="POST" id="print_mult">
                                    <table class="table-bordered table table-hover " id="table-2" style="width:200%;">
                                        <thead>
                                            <tr>    
                                                <th width="3%" align="center" style="background:rgb(245 245 245)">
                                                    <!-- <a href="" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple"><span class="fas fa-print mr-1 mt-1 mb-1"></span></a> -->
                                                    <?php if($saved==1){ ?>
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printMultiple()"><span class="fas fa-print mr-1 mt-1 mb-1"></span></button>
                                                    <?php } ?>
                                                </th>    
                                                <th width="1%">Item No</th>                                        
                                                <th>Series No.</th>
                                                <th>STL ID / TPShort Name</th>
                                                <th>Billing ID</th>
                                                <th width="15%">Trading Participant Name</th>
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
                                            //$x=1;
                                                foreach($details AS $d){ 
                                                    if(!empty($d['sales_id'])){ 
                                            ?>
                                            <tr>
                                                
                                                <td align="center" >
                                                    <?php if($saved==1){ ?>
                                                    <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="<?php echo $identifier_code.','.$d['sales_detail_id'].','.$reference_number; ?>">
                                                    <?php } ?>
                                                    <!-- <?php 
                                                        if($saved==1){ 
                                                            if($d['serial_no']=='' && $d['print_counter']==0){
                                                    ?>
                                                        <div class="btn-group mb-0">
                                                            <a style="color:#fff" onclick="add_details_BS('<?php echo base_url(); ?>','<?php echo $d['sales_detail_id']; ?>')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                                <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                            </a>
                                                        </div>
                                                    <?php 
                                                        }else{
                                                    ?>
                                                        <div class="btn-group mb-0">
                                                            <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/<?php echo $d['sales_detail_id']; ?>" target="_blank" onclick = "countPrint('<?php echo base_url(); ?>','<?php echo $d['sales_detail_id']; ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                                <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                            </a>
                                                        </div>
                                                    <?php       
                                                            } 
                                                        }
                                                    ?> -->
                                                </td>
                                                <td><center><?php echo $d['item_no'];?></center></td>
                                                <td><?php echo $d['serial_no'];?></td>
                                                <td><?php echo $d['short_name'];?></td>
                                                <td><?php echo $d['billing_id'];?></td>
                                                <td><?php echo $d['company_name'];?></td>
                                                <td align="center"><?php echo $d['facility_type'];?></td>
                                                <td align="center"><?php echo $d['wht_agent'];?></td>
                                                <td align="center"><?php echo $d['ith_tag'];?></td>
                                                <td align="center"><?php echo $d['non_vatable'];?></td>
                                                <td align="center"><?php echo $d['zero_rated'];?></td>
                                                <td align="right"><?php echo $d['vatable_sales'];?></td>
                                                <td align="right"><?php echo $d['zero_rated_sales'];?></td>
                                                <td align="right"><?php echo $d['zero_rated_ecozones'];?></td>
                                                <td align="right"><?php echo $d['vat_on_sales'];?></td>
                                                <td align="right">(<?php echo $d['ewt'];?>)</td>
                                                <td align="right"><?php echo $d['total_amount'];?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($details)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save">
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


                
                                       
         