<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script>
    function goBack() {
        window.close();
      window.history.back();
    }
</script>
<style>
    body{
        margin:0px;
        overflow: hidden;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/pdf_or_bulk.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>
<body>
    <div style="background:rgba(0,0,0,0.5);;height:100%;width: 100%;z-index: 99; position: absolute; display: none;padding-top: 20%;margin: 0px!important;" id="loading">
        <div style="display: flex; align-items: center;justify-content: center;font-size: 20px; font-weight: 600;">
            <p style="color: white;">Please wait, Downloading PDF Files...</p>
        </div>
    </div>
    <div class="" id="printbutton">
        <center>
            
            <button class="btn btn-warning " onclick="goBack()">Back</button>
            <!-- <button class="btn btn-success " id="counter_print" onclick="printDiv('printableArea')">Print</button>
            <button class="btn btn-success " onclick="">Save as PDF</button> -->
        </center>
        <br>
    </div>
    <center>
    <?php 
        $x=1;
        foreach($details AS $d){
            $total_sales = $d['total_vs'] + $d['total_zra'] + $d['total_vos'];
            $net_of_vat = $d['total_vs'] + $d['total_zra'];
            $total_amount_due = $d['total_vs'] + $d['total_zra'] + $d['total_vos'] + $d['total_ewt'];

             ?>
    <div style="padding-bottom:90px;">
        <div id="contentPDF" >
        <page size="Long" id="printableArea" class="canvas_div_pdf<?php echo $x; ?>" >
            <img class="img2307" src="<?php echo base_url(); ?>assets/img/OR.jpg" style="width: 100%;">
            <div class="">
                <label class="date_1"><?php echo date("F j, Y", strtotime($d['date'])); ?></label>
                <label class="ornumber_1"><?php echo $d['or_no']; ?></label>
                <label class="cusname_1"><?php echo $d['buyer']; ?> </label>
                <label class="address_1"><?php echo $d['address']; ?> </label>
                <label class="tin_1"><?php echo $d['tin']; ?> </label>
                <label class="desc_1"><?php echo $d['ref_no']; ?></label>
                <label class="defint_1">Vatable Sales</label>
                <label class="defint_value_1"><?php echo number_format($d['total_vs'],2); ?></label>
                <label class="energy_1">Zero Rated Ecozones Sales</label>
                <label class="energy_value_1"><?php echo number_format($d['total_zra'],2); ?></label>
                <label class="vat_1">VAT</label>
                <label class="vat_value_1"><?php echo number_format($d['total_vos'],2); ?></label>
                <label class="total_sales_1"><?php echo number_format($total_sales,2); ?></label>
                <label class="net_vat_1"><?php echo number_format($net_of_vat,2); ?></label>
                <label class="add_vat_1"><?php echo number_format($d['total_vos'],2); ?></label>
                <label class="total_1"><?php echo number_format($total_sales,2); ?></label>
                <label class="less_withholding_1"><?php echo number_format($d['total_ewt'],2); ?></label>
                <label class="total_amount_1"><?php echo number_format($total_amount_due,2); ?></label>
                <label class="vatable_1"><?php echo number_format($d['total_vs'],2); ?></label>
                <label class="vat_exempt_1">0.00</label>
                <label class="zero_rated_1"><?php echo number_format($d['total_zra'],2); ?></label>
                <label class="vat_percent_1"><?php echo number_format($d['total_vos'],2); ?></label>
                <label class="grand_total_1"><?php echo number_format($total_sales,2); ?></label>
                <!-- <label class="claim">This Document is Not Valid for Claiming Input Taxes</label> -->
                <label class="signature_1">
                    <img src="<?php echo base_url()."assets/img/sign_DeLosSantos.png" ?>" width="180px">
                </label>
            </div>
        </page>
        </div>
    </div>
    <input type="hidden" class="stl_id<?php echo $x; ?>" value="<?php echo $d['stl_id']; ?>" id="stl_id<?php echo $x; ?>">
    <input type="hidden" class="serial_no<?php echo $x; ?>" value="<?php echo $d['or_no']; ?>" id="serial_no<?php echo $x; ?>">
    <input type="hidden" class="ref_no<?php echo $x; ?>" id="ref_no<?php echo $x; ?>" value="<?php echo $d['refno']; ?>">
    <input type="hidden" class="reference_no<?php echo $x; ?>" id="reference_no<?php echo $x; ?>" value="<?php echo $d['ref_no']; ?>">
    <input type="hidden" class="sales_id<?php echo $x; ?>" id="sales_id<?php echo $x; ?>" value="<?php echo $d['sales_id']; ?>">
    <!-- <input type="hidden" class="collection_details_id<?php echo $x; ?>" id="collection_details_id<?php echo $x; ?>" value="<?php echo $d['collection_details_id']; ?>"> -->
    <input type="hidden" class="billing_month<?php echo $x; ?>" id="billing_month<?php echo $x; ?>" value="<?php echo $d['billing_month']; ?>">
    <input type="hidden" class="date_uploaded<?php echo $x; ?>" id="date_uploaded<?php echo $x; ?>" value="<?php echo $d['date_uploaded']; ?>">
    <!-- <input type="hidden" class="timestamp"  id="timestamp" value="<?php echo $timestamp; ?>"> -->
    <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
    <?php $x++; } ?>
    <input type="hidden"  id="count" value="<?php echo $x; ?>">
    </center>
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
             
            var counter=document.getElementById('count').value;
            // var timestamp=document.getElementById('timestamp').value;
            var loc= document.getElementById("baseurl").value;
            var redirect = loc+"sales/update_sales_wesm_adjustment_flag";


            for(let a=1;a<counter;a++){
               
            
                var billing_month=document.getElementById('billing_month'+a).value;
                var refno=document.getElementById('ref_no'+a).value;
                var shortname=document.getElementById('stl_id'+a).value;
                var dateuploaded=document.getElementById('date_uploaded'+a).value;
              
                var HTML_Width = $(".canvas_div_pdf"+a).width();
                var HTML_Height = $(".canvas_div_pdf"+a).height();
               
                var top_left_margin = 10;
                var PDF_Width = HTML_Width+(top_left_margin*2);
                var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
                var canvas_image_width = HTML_Width;
                var canvas_image_height = HTML_Height;

                var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
              // alert(a);
                html2canvas($(".canvas_div_pdf"+a)[0],{
                    allowTaint:true, 
                    useCORS: true,
                    logging: false,
                    // height: window.outerHeight + window.innerHeight,
                    // windowHeight: window.outerHeight + window.innerHeight,
                    height: window.outerHeight,
                    windowHeight: window.outerHeight,
            
                }).then(function(canvas) {
                     var serial_no= document.getElementById("serial_no"+a).value;
                     var reference_no= document.getElementById("reference_no"+a).value;
                     var sales_id= document.getElementById("sales_id"+a).value;

                        canvas.getContext('2d');   
                        var imgData = canvas.toDataURL("image/jpeg", 1.0);
                        var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
                        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);

                        var stl_id= $(".stl_id"+a).val();
                        var billing_month= $(".billing_month"+a).val();
                        var ref_no= $(".ref_no"+a).val();

                            //pdf.addPage(PDF_Width, PDF_Height);
                            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*a)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                           
                            var fname = "SI_CENPRI_"+stl_id+"_"+ref_no+"_"+billing_month+"_"+dateuploaded+".pdf";
                            pdf.save("SI_CENPRI_"+stl_id+"_"+ref_no+"_"+billing_month+"_"+dateuploaded+".pdf");
                           
                           
                                    $.ajax({
                                        data: '&serial_no='+serial_no+'&sales_id='+sales_id+'&filename='+fname,
                                        type: "POST",
                                        url: redirect,
                                        beforeSend: function(){
                                            document.getElementById("loading").style.display = 'block';  
                                        },
                                        success: function(output){
                                            //console.log(output);
                                            setTimeout(() => {
                                                document.getElementById("loading").style.display = 'none'; 
                                            }, 3000);
                                        }
                                    });
                            
                  });
            }
       });
    </script>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

       
    </script>
</body>
</html>