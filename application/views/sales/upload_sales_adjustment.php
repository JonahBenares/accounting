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
                            <form id='uploadsalesadjust'>
                                <div class="row append">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <div class="form-group">
                                            <label>File</label>
                                            <input type="file" class="form-control fileupload" name='fileupload[]' id="fileupload1" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <input type="text" class="form-control" name='remarks[]' id="remarks1">
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                        <div class="form-group m-t-35 addmoreupload">
                                            <button class="btn btn-primary btn-sm addUpload"><span class="fas fa-plus"></span></button>
                                            <!-- <button class="btn btn-danger btn-sm"><span class="fas fa-times"></span></button> -->
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4  offset-lg-4 offset-md-4 offset-sm-4">
                                        <div class="form-group">
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <input type='text' name='count' id='count' value='1'>
                                            <button type="button" id="button_adjust" class="btn btn-primary btn-block btn-md" onclick="upload_sales_adjust_btn()">Upload</button>
                                            <center><span id="alt"></span></center>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php 
                                foreach($details AS $d){ 
                                    if(!empty($details)){ 
                            ?>      
                            <div class="table-responsive" >
                                <hr>
                               <table width="100%">
                                    <tr>
                                        <td><label class="m-0"><b>Reference Number</b>: <?php echo $d['reference_number'];?></label></td>
                                        <td><label class="m-0"><b>Billing Period</b>: <?php echo $d['billing_from'];?> - <?php echo $d['billing_to'];?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Date</b>: <?php echo $d['transaction_date'];?> </label></td>
                                        <td><label class="m-0"><b>Due Date</b>: <?php echo $d['due_date'];?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Remarks</b>: <?php echo $d['remarks'];?> </label></td>
                                        <td></td>
                                    </tr>
                                </table>
                                <br>
                                
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
                                    </tbody>
                                </table>
                            </div>
                            <?php } } ?>
                        </div>
                        <?php if(!empty($details)){ if($saved==0){ ?>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save">
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
</script>

                
                                       
         