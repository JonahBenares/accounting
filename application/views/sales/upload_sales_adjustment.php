<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<!-- <?php

if(!empty($sales_id)){
    $readonly = 'readonly';
} else {
    $readonly='';
}
?> -->
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
                            <!-- <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <label>File</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <label>Remarks</label>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                </div>
                            </div> -->
                            <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert" id="alert_error" style="display:none">
                                <center>
                                    <strong>Excel file incorrect format, kindly check excel file format.</strong> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </center>
                            </div>  
                            <form id='uploadsalesadjust'>
                                <div class="row append">
                                    <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                        <div id="ddArea">
                                            Drag and Drop Files Here or
                                            <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                                                Select File(s)
                                            </a>
                                        </div>
                                        <div id="showThumb"></div>
                                        <input type="file" class="d-none" id="selectfile" multiple />
                                            <!-- <input type="file" class="form-control fileupload" name='fileupload[]' id="fileupload1" required> -->
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name='remarks[]' id="remarks1">
                                        </div>
                                    </div> -->
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-5 addmoreupload">
                                            <input type="hidden" name="adjust_identifier" id="adjust_identifier" value="<?php echo $identifier_code;?>">
                                            <input type="hidden" name="count_name" id="count_name" value="<?php echo $count_name;?>">
                                            <!-- <button type="button" class="btn btn-primary btn-sm addUpload"><span class="fas fa-plus"></span></button> -->
                                            <!-- <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button> -->
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4  offset-lg-4 offset-md-4 offset-sm-4">
                                        <div class="form-group">
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <!-- <input type="hidden" name="identifier1" id="identifier1" value="<?php echo $identifier;?>"> -->
                                            <!-- <input type='hidden' name='count' id='count' value='1'> -->
                                            <?php if(empty($identifier)){ ?>
                                            <!-- <button type="button" id="button_adjust" class="btn btn-primary btn-block btn-md" onclick="upload_sales_adjust_btn()">Upload</button> -->
                                            <?php 
                                                } else{ 
                                                    if($saved==0){
                                            ?>
                                            <input type='button' class="btn btn-danger" id="cancel" onclick="cancelmultipleSales()" value="Cancel Transaction" style="width: 100%;">
                                            <?php } } ?>
                                            <center><span id="alt" style="display:none"><b>Please wait, Saving Data...</b></span></center>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php if(!empty($identifier) && !empty($details)){ ?>      
                            <div class="table-responsive" >
                                <hr>
                                <?php $x=1; foreach($head AS $h){ ?>
                               <table width="100%">
                                    <tr>
                                        <td><label class="m-0"><b>Reference Number</b>: <?php echo $h->reference_number;?></label></td>
                                        <td><label class="m-0"><b>Billing Period</b>: <?php echo date("F d,Y",strtotime($h->billing_from)); ?> - <?php echo date("F d,Y",strtotime($h->billing_to)); ?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Date</b>: <?php echo date("F d,Y",strtotime($h->transaction_date));?> </label></td>
                                        <td><label class="m-0"><b>Due Date</b>: <?php echo date("F d,Y",strtotime($h->due_date));?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Remarks</b>: <?php echo $h->remarks;?> </label></td>
                                        <td></td>
                                    </tr>
                                </table>
                                <br>
                                <?php if($count_empty_actual!=0){ ?>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <center>
                                        <strong><?php echo $count_empty_actual; ?> </strong> 
                                        <span>non-existing participant/s in masterfile.</span>
                                    </center>
                                </div> 
                                <?php } ?>
                                <table class="table-bordered table table-hover " id="adjust-<?php echo $x; ?>" style="width:190%;">
                                    <thead>
                                        <tr>    
                                            <!-- <th width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>  -->   
                                            <th >Item #</th>                                        
                                            <!-- <th >Series No.</th> -->
                                            <th >STL ID / TPShort Name</th>
                                            <th style="position: sticky;left:0;background:#f3f3f3;z-index: 999;">Billing ID</th>
                                            <th style="position: sticky;left:0;background:#f3f3f3;z-index: 999;">Unique Billing ID</th>
                                            <th style="position: sticky;left:125px;background:#f3f3f3;z-index: 999;">Trading Participant Name</th>
                                            <th >Facility Type </th>
                                            <th >WHT Agent Tag</th>
                                            <th >ITH Tag</th>
                                            <th >Non Vatable Tag</th>
                                            <th >Zero-rated Tag</th>
                                            <th >Vatable Sales</th>
                                            <th >Zero Rated Sales</th>
                                            <th >Zero Rated EcoZones Sales</th>
                                            <th >Vat On Sales</th>
                                            <th >EWT</th>
                                            <th >Total Amount</th>
                                            <!-- <th width="20%">Original Copy</th>
                                            <th width="20%">Scanned Copy</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($details AS $d){ 
                                            if($d['reference_number'] == $h->reference_number) {?>
                                        <tr <?php echo ($d['billing_id']=='') ? 'class="bg-red"' : ''; ?>>
                                            <!-- <td class="p-2" align="center" style="background: #fff;">
                                                <?php 
                                                    if($saved==1){ 
                                                        if($d['serial_no']=='' && $d['print_counter']==0){
                                                ?>
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_adjust_details_BS('<?php echo base_url(); ?>','<?php echo $d['adjustment_detail_id']; ?>')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                            <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div>
                                                <?php 
                                                    }else{
                                                ?>
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/<?php echo $d['adjustment_detail_id']; ?>" target="_blank" onclick = "countPrint('<?php echo base_url(); ?>','<?php echo $d['adjustment_detail_id']; ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                            <span class="m-0 fas fa-indent"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div>
                                                <?php       
                                                        } 
                                                    }
                                                ?>
                                            </td> -->
                                            <!-- <span hidden><?php echo $d['billing_id'];?></span> -->
                                            <td class="p-2">
                                                <center><?php echo $d['item_no'];?></center>
                                            </td>
                                            <!-- <td class="p-2"><?php echo $d['serial_no'];?></td> -->
                                            <td class="p-2"><?php echo $d['short_name'];?></td>
                                            <td class="p-2" <?php echo ($d['billing_id']=='') ? 'style="position: sticky;left:0;z-index: 999;"' : 'style="position: sticky;left:0;background:#fff;z-index: 999;"'?>><?php echo $d['actual_billing_id'];?></td>
                                            <td class="p-2" <?php echo ($d['billing_id']=='') ? 'style="position: sticky;left:0;z-index: 999;"' : 'style="position: sticky;left:0;background:#fff;z-index: 999;"'?>><?php echo $d['billing_id'];?></td>
                                            <td class="p-2" <?php echo ($d['billing_id']=='') ? 'style="position: sticky;left:125;z-index: 999;"' : 'style="position: sticky;left:125;background:#fff;z-index: 999;"'?>><?php echo $d['company_name'];?></td>
                                            <td class="p-2" align="center"><?php echo $d['facility_type'];?></td>
                                            <td class="p-2" align="center"><?php echo $d['wht_agent'];?></td>
                                            <td class="p-2" align="center"><?php echo $d['ith_tag'];?></td>
                                            <td class="p-2" align="center"><?php echo $d['non_vatable'];?></td>
                                            <td class="p-2" align="center"><?php echo $d['zero_rated'];?></td>
                                            <td class="p-2" align="right"><?php echo $d['vatable_sales'];?></td>
                                            <td class="p-2" align="right"><?php echo $d['zero_rated_sales'];?></td>
                                            <td class="p-2" align="right"><?php echo $d['zero_rated_ecozones'];?></td>
                                            <td class="p-2" align="right"><?php echo $d['vat_on_sales'];?></td>
                                            <td class="p-2" align="right">(<?php echo $d['ewt'];?>)</td>
                                            <td class="p-2" align="right"><?php echo $d['total_amount'];?></td>
                                            <!-- <td align="right" style="padding:0px">
                                                <input type="text" class="form-control"  name="total_update" id="total_update" value="<?php echo $d['total_amount'];?>" readonly>
                                            </td>
                                            <td align="right" >
                                                <div>
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" class="" name="orig_copy" id="orig_yes" value='1' checked>
                                                    </label>
                                                </div>  
                                                <div>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" name="orig_copy" id="orig_no" value='2'>
                                                    </label>
                                                </div>
                                            </td>
                                            <td align="right">
                                                <div>   
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" class="" name="scanned_copy" id="scanned_yes" value='1'>
                                                    </label>
                                                </div>
                                                <div>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  name="scanned_copy" id="scanned_no" value='2' checked>
                                                    </label>
                                                </div>
                                            </td> -->
                                        </tr>
                                        <?php } }?>
                                    </tbody>
                                </table>
                                <hr>
                                <?php  $x++; } ?>
                            </div>
                            <input type="hidden" id="counter" value="<?php echo $x; ?>">
                            <?php } ?>
                        </div>
                        <?php if(!empty($identifier)){ if($saved==0){ ?>
                        <div id='alt1' style="font-weight:bold"></div>
                        <input type="hidden" name="save_sales_adjustment" id="save_sales_adjustment" value="<?php echo $identifier;?>">
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAllAdjust();" value="Save"  <?php echo ($count_empty_actual==0) ? '' : 'disabled';?>>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var  z = 1;
    $("body").on("click", ".addUpload", function() {
        z++;
        var $append = $(this).parents('.append');
        var nextHtml = $append.clone().find("input").val("").end();
        //nextHtml.children(':first').find("input").attr('id', 'remarks' + z).val('');
        nextHtml.attr('id', 'append' + z);
        document.getElementById('count').value=z;
        var hasRmBtn = $('.remUpload', nextHtml).length > 0;
        if (!hasRmBtn) {
            var rm = "<button type='button' class='btn btn-danger btn-sm remUpload'><span class='fas fa-times'></span></button>"
            $('.addmoreupload', nextHtml).append(rm);
        }
        $append.after(nextHtml); 
    });

    $("body").on("click", ".remUpload", function() {
        $(this).parents('.append').remove();
    });

    window.onload = function(){
        var counter = document.getElementById("counter").value;
        for(var a=1;a<=counter;a++){
            $("#adjust-"+a).dataTable({
                "scrollX": true,
                // "scrollX": true,
            });
        }
        document.getElementById("selectfile").disabled = true;
    }
</script>

                
                                       
         