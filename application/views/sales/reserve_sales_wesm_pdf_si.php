<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script>
    function goBack() {
        window.close();
      window.history.back();
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/pdf_or.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>

<div class="" id="printbutton">
    <center>
        
        <button class="btn btn-warning " onclick="goBack()">Back</button>
        <button class="btn btn-success " id="counter_print" onclick="printDiv('printableArea')">Print</button>
       <!--  <button class="btn btn-success " onclick="">Save as PDF</button> -->
    </center>
    <br>
</div>
<center>
<div style="padding-bottom:90px;">
    <div id="contentPDF" >
    <page size="Long" id="printableArea" class="canvas_div_pdf" >
        <img class="img2307" src="<?php echo base_url(); ?>assets/img/SI.png" style="width: 100%;">
        <div class="">
            <label class="date_1"><?php echo date("F j, Y", strtotime($transaction_date)); ?></label>
            <label class="ornumber_1"><?php echo $or_no; ?></label>
            <label class="cusname_1"><?php echo $company_name; ?> </label>
            <label class="address_1"><?php echo $address; ?> </label>
            <label class="tin_1"><?php echo $tin; ?> </label>
            <label class="desc_1"><?php echo $reference_number; ?></label>
            <label class="defint_1">Vatable Sales</label>
            <label class="defint_value_1"><?php echo number_format($total_vs,2); ?></label>
            <label class="energy_1">Zero Rated Ecozones Sales</label>
            <label class="energy_value_1"><?php echo number_format($total_zra,2); ?></label>
            <label class="vat_1">VAT</label>
            <label class="vat_value_1"><?php echo number_format($total_vos,2); ?></label>
            <?php
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;
            ?>
            <label class="total_sales_1"><?php echo number_format($total_sales,2); ?></label>
            <label class="net_vat_1"><?php echo number_format($net_of_vat,2); ?></label>
            <label class="add_vat_1"><?php echo number_format($total_vos,2); ?></label>
            <label class="total_1"><?php echo number_format($total_sales,2); ?></label>
            <label class="less_withholding_1">(<?php echo number_format($total_ewt,2); ?>)</label>
            <label class="total_amount_1"><?php echo number_format($total_amount_due,2); ?></label>
            <div style="position: absolute;top:5px">  
                <label class="vatable_1"><?php echo number_format($total_vs,2); ?></label>
                <label class="vat_exempt_1">0.00</label>
                <label class="zero_rated_1"><?php echo number_format($total_zra,2); ?></label>
                <label class="vat_percent_1"><?php echo number_format($total_vos,2); ?></label>
                <label class="grand_total_1"><?php echo number_format($total_sales,2); ?></label>
            </div>
            <!-- <label class="claim">This Document is Not Valid for Claiming Input Taxes</label> -->
            <label class="signature_1" >
                <img src="<?php echo base_url()."assets/img/sign_DeLosSantos.png" ?>" width="180px">
            </label>
        </div>
    </page>
    </div>
</div>
<input type="hidden" class="stl_id" value="<?php echo $stl_id; ?>" id="stl_id">
<input type="hidden" class="ref_no" id="ref_no" value="<?php echo $refno; ?>">
<input type="hidden" class="billing_month" id="billing_month" value="<?php echo $billing_month; ?>">
<input type="hidden" class="date_uploaded" id="date_uploaded" value="<?php echo $date_uploaded; ?>">
<input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
</center>
</html>

<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
         
            var stl_id=document.getElementById('stl_id').value;
            var billing_month=document.getElementById('billing_month').value;
            var refno=document.getElementById('ref_no').value;
            var dateuploaded=document.getElementById('date_uploaded').value;
          
            var HTML_Width = $(".canvas_div_pdf").width();

            var HTML_Height = $(".canvas_div_pdf").height();

            var top_left_margin = 10;
            var PDF_Width = HTML_Width+(top_left_margin*2);
            var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;
            
            var totalPDFPages = Math.ceil(HTML_Height/PDF_Height);
          
            html2canvas($(".canvas_div_pdf")[0],{
                allowTaint:true, 
                useCORS: true,
                logging: false,
                height: window.outerHeight,
                windowHeight: window.outerHeight,

            }).then(function(canvas) {
                    canvas.getContext('2d');
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);

                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
                    pdf.save("SI_CENPRI_"+stl_id+"_"+refno+"_"+billing_month+"_"+dateuploaded+".pdf");

              });
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