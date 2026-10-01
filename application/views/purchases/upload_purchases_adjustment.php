<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<!-- <?php

if(!empty($sales_id)){
    $readonly = 'readonly';
} else {
    $readonly='';
}
?> -->
<style>
#adjust-<?php echo $x; ?> th,
#adjust-<?php echo $x; ?> td {
    padding: 4px 6px !important;
    white-space: nowrap;
    font-size: 13px;
}

/* Keep frozen columns compact */
#adjust-<?php echo $x; ?> th:nth-child(2),
#adjust-<?php echo $x; ?> td:nth-child(2) {
    min-width: 105px;
    width: 105px;
}

#adjust-<?php echo $x; ?> th:nth-child(3),
#adjust-<?php echo $x; ?> td:nth-child(3) {
    min-width: 85px;
    width: 85px;
}

#adjust-<?php echo $x; ?> th:nth-child(4),
#adjust-<?php echo $x; ?> td:nth-child(4) {
    min-width: 105px;
    width: 105px;
}

/* Make the remaining columns compact */
#adjust-<?php echo $x; ?> th:nth-child(1),
#adjust-<?php echo $x; ?> td:nth-child(1) {
    width: 45px;
    min-width: 45px;
}

#adjust-<?php echo $x; ?> th:nth-child(5),
#adjust-<?php echo $x; ?> td:nth-child(5) {
    min-width: 150px;
}

#adjust-<?php echo $x; ?> th:nth-child(6),
#adjust-<?php echo $x; ?> td:nth-child(6) {
    min-width: 90px;
}

#adjust-<?php echo $x; ?> th:nth-child(7),
#adjust-<?php echo $x; ?> td:nth-child(7),
#adjust-<?php echo $x; ?> th:nth-child(8),
#adjust-<?php echo $x; ?> td:nth-child(8),
#adjust-<?php echo $x; ?> th:nth-child(9),
#adjust-<?php echo $x; ?> td:nth-child(9),
#adjust-<?php echo $x; ?> th:nth-child(10),
#adjust-<?php echo $x; ?> td:nth-child(10) {
    width: 65px;
    min-width: 65px;
    text-align: center;
}

#adjust-<?php echo $x; ?> th:nth-child(n+11),
#adjust-<?php echo $x; ?> td:nth-child(n+11) {
    min-width: 100px;
}
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 style="line-height: 1.3;" class="p-0">Upload WESM Transaction - Purchases <br><small style="letter-spacing:2px">ADJUSTMENT</small></h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert" id="alert_error" style="display:none">
                                <center>
                                    <strong>Excel file incorrect format, kindly check excel file format.</strong> 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </center>
                            </div>  
                            <form id='uploadadjust'>
                                <!-- <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 offset-lg-1 offset-md-1 offset-sm-1">
                                        <label>File</label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <label>Remarks</label>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1">
                                    </div>
                                </div> -->
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
                                            <!-- <input type='hidden' name='count' id='count' value='1'> -->
                                            <?php if(empty($identifier)){ ?>
                                            <!-- <button type="button" id="button_adjust" class="btn btn-primary btn-block btn-md" onclick="upload_adjust_btn()">Upload</button> -->
                                            <?php 
                                                } else{ 
                                                    if($saved==0){
                                            ?>
                                            <input type='button' class="btn btn-danger" id="cancel" onclick="cancelmultiplePurchase()" value="Cancel Transaction" style="width:100%">
                                            <?php } } ?>
                                            <center><span id="alt" style="display:none"><b>Please wait, Saving Data...</b></span></center>
                                        </div>
                                    </div>
                                </div>
                            </form> 
                            <?php if(!empty($identifier) && !empty($details)){ ?>  
                            <div class="table-responssive" >
                                <?php $x=1; 
                                    usort($head, function($a, $b) {
                                        preg_match('/F(\d+)-/', $a->reference_number, $matchA);
                                        preg_match('/F(\d+)-/', $b->reference_number, $matchB);

                                        return (int)$matchA[1] - (int)$matchB[1];
                                    });

                                    foreach($head AS $h){
                                    ?>
                                <hr>
                                <table width="100%">
                                    <tr>
                                        <td><label class="m-0"><b>Reference Number</b>: <?php echo $h->reference_number; ?></label></td>
                                        <td><label class="m-0"><b>Billing Period</b>: <?php echo date("F d",strtotime($h->billing_from)); ?> - <?php echo date("F d,Y",strtotime($h->billing_to)); ?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Date</b>: <?php echo date("F d,Y",strtotime($h->transaction_date)); ?> </label></td>
                                        <td><label class="m-0"><b>Due Date</b>: <?php echo date("F d,Y",strtotime($h->due_date)); ?> </label></td>
                                    </tr>
                                    <tr>
                                        <td><label class="m-0"><b>Remarks</b>: <?php echo $h->adjustment_remarks; ?> </label></td>
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
                                <table class="table-bordered table table-hover" id="adjust-<?php echo $x; ?>" style="width:100%; table-layout:auto;">
                                    <thead>
                                        <tr>
                                            <th width="5%" align="center" style="background:rgb(245 245 245); padding:4px 5px;"> <center><span class="fas fa-bars"></span></center> </th>
                                            <th style="padding:4px 5px; width:55px; min-width:55px;">Item No.</th>
                                            <th style=" position:sticky; left:55px; background:#f3f3f3; z-index:1000; width:105px; min-width:105px; padding:4px 5px; "> STL ID / TPShort Name</th>
                                            <th style=" position:sticky; left:160px; background:#f3f3f3; z-index:1000; width:85px; min-width:85px; padding:4px 5px; "> Billing ID</th>
                                            <th style=" position:sticky; left:245px; background:#f3f3f3; z-index:1000; width:105px; min-width:105px; padding:4px 5px; "> Unique Billing ID</th>
                                            <th style="padding:4px 5px; min-width:75px;">Facility Type</th>
                                            <th style="padding:4px 5px; min-width:65px;">WHT Agent Tag</th>
                                            <th style="padding:4px 5px; min-width:55px;">ITH Tag</th>
                                            <th style="padding:4px 5px; min-width:75px;">Non Vatable Tag</th>
                                            <th style="padding:4px 5px; min-width:70px;">Zero-rated Tag</th>
                                            <th style="padding:4px 5px; min-width:90px;">Vatable Purchases</th>
                                            <th style="padding:4px 5px; min-width:90px;">Zero Rated Purchases</th>
                                            <th style="padding:4px 5px; min-width:110px;">Zero Rated EcoZones Purchases</th>
                                            <th style="padding:4px 5px; min-width:85px;">Vat On Purchases</th>
                                            <th style="padding:4px 5px; min-width:70px;">EWT</th>
                                            <th style="padding:4px 5px; min-width:90px;">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($details AS $d){ 
                                            if($d['reference_number']==$h->reference_number){
                                        ?>
                                        <tr <?php echo ($d['billing_id']=='') ? 'class="bg-red"' : ''; ?>>
                                            <td align="center"
                                                <?php echo ($d['billing_id']=='') ? '' : 'style="background:#fff;padding:4px 5px;"'; ?>>
                                                <span hidden><?php echo $d['billing_id']; ?></span>
                                                <?php if($saved==1){ ?>
                                                    <div class="btn-group mb-0">
                                                        <a href="<?php echo base_url(); ?>purchases/print_2307/<?php echo $h->purchase_id; ?>/<?php echo $d['purchase_detail_id']; ?>" target="_blank" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307"> <span class="m-0 fas fa-print"></span> <span id="clicksBS" class="badge badge-transparent"> <?php echo "".$d['print_counter'].""; ?> </span> </a>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td style="padding:4px 5px;"> <?php echo $d['item_no'];?></td>
                                            <td style=" position:sticky; left:55px; background:#fff; z-index:100; width:105px; min-width:105px; padding:4px 5px; "> <?php echo $d['short_name'];?></td>
                                            <td style=" position:sticky; left:160px; background:#fff; z-index:100; width:85px; min-width:85px; padding:4px 5px; "> <?php echo $d['actual_billing_id']; ?></td>
                                            <td align="center" style=" position:sticky; left:245px; background:#fff; z-index:100; width:105px; min-width:105px; padding:4px 5px; "> <?php echo $d['billing_id']; ?></td>
                                            <td align="center" style="padding:4px 5px;"> <?php echo $d['facility_type']; ?></td>
                                            <td align="center" style="padding:4px 5px;"> <?php echo $d['wht_agent']; ?></td>
                                            <td align="center" style="padding:4px 5px;"> <?php echo $d['ith_tag']; ?></td>
                                            <td align="center" style="padding:4px 5px;"> <?php echo $d['non_vatable']; ?></td>
                                            <td align="center" style="padding:4px 5px;"> <?php echo $d['zero_rated']; ?></td>
                                            <td align="right" style="padding:4px 5px;"> (<?php echo number_format($d['vatables_purchases'],2); ?>)</td>
                                            <td align="right" style="padding:4px 5px;"> (<?php echo number_format($d['zero_rated_purchases'],2); ?>)</td>
                                            <td align="right" style="padding:4px 5px;"> (<?php echo number_format($d['zero_rated_ecozones'],2); ?>)</td>
                                            <td align="right" style="padding:4px 5px;"> (<?php echo number_format($d['vat_on_purchases'],2); ?>)</td>
                                            <td align="right" style="padding:4px 5px;"> <?php echo number_format($d['ewt'],2); ?></td>
                                            <td align="right" style="padding:4px 5px;"> (<?php echo number_format($d['total_amount'],2); ?>)</td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                                <?php $x++; } ?>
                            </div>
                            <input type="hidden" id="counter" value="<?php echo $x; ?>">
                            <?php } ?>
                        </div>
                        <?php if(!empty($identifier)){ if($saved==0){ ?>
                        <center><div id='alt1' style="font-weight:bold;display:none"><b>Please wait, Saving Data...</b></div></center>
                        <input type="hidden" name="saveadjust_identifier" id="saveadjust_identifier" value="<?php echo $identifier;?>">
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAlladjust();" value="Save" <?php echo ($count_empty_actual==0) ? '' : 'disabled';?>>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#ddArea").on("dragover", function() {
        $(this).addClass("drag_over");
        return false;
    });

    $("#ddArea").on("dragleave", function() {
        $(this).removeClass("drag_over");
        return false;
    });

    $("#ddArea").on("click", function(e) {
        file_explorer();
    });

    $("#ddArea").on("drop", function(e) {
        e.preventDefault();
        $(this).removeClass("drag_over");
        var formData = new FormData();
        var files = e.originalEvent.dataTransfer.files;
        adjust_identifier = document.getElementById("adjust_identifier").value;
        for (var i = 0; i < files.length; i++) {
            formData.append("file[]", files[i]);
            formData.append('adjust_identifier', adjust_identifier);
        }
        uploadmultipleFiles(formData); 
    });

    function file_explorer() {
        document.getElementById("selectfile").click();
        document.getElementById("selectfile").onchange = function() {
            files = document.getElementById("selectfile").files;
            adjust_identifier = document.getElementById("adjust_identifier").value;
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) {
                formData.append("file[]", files[i]);
                formData.append('adjust_identifier', adjust_identifier);
            }
            uploadmultipleFiles(formData); 
        };
    }

    function uploadmultipleFiles(form_data) {
        $(".loading").removeClass("d-none").addClass("d-block");
        var loc= document.getElementById("baseurl").value;
        var identifier= document.getElementById("adjust_identifier").value;
        var redirect = loc+"purchases/display_upload_purchase_adjust";
        $.ajax({
            url: redirect,
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(){
                // document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt").style.display = 'block';  
                document.getElementById("alert_error").style.display = 'none';  
                document.getElementById("selectfile").disabled = true;
            },
            success: function(output) {
                if(output=='error'){
                    $(".loading").removeClass("d-block").addClass("d-none");
                    document.getElementById("selectfile").disabled = false;
                    document.getElementById("alt").style.display = 'none';  
                    document.getElementById("selectfile").value = '';
                    document.getElementById("alert_error").style.display = 'block';  
                }else{
                    $(".loading").removeClass("d-block").addClass("d-none");
                    $("#alt").hide();
                    window.location=loc+'purchases/upload_purchases_adjustment/'+output;
                    //$("#showThumb").append(data);
                    //alert(output);
                }
            }
        });
    }
});

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
        var counterElement = document.getElementById("counter");
        if (counterElement) {
            var counter = parseInt(counterElement.value);
            for (var a = 1; a < counter; a++) {
                $("#adjust-" + a).DataTable({
                    order: [],
                    scrollX: true
                });
            }
            document.getElementById("selectfile").disabled = true;
        }
    };
    </script>      
                                       
         