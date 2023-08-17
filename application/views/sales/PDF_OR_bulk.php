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
        <!-- <button class="btn btn-success " id="counter_print" onclick="printDiv('printableArea')">Print</button>
        <button class="btn btn-success " onclick="">Save as PDF</button> -->
    </center>
    <br>
</div>
<center>
<?php 
    $x=1;
    foreach($details AS $d){
        $zero_rated = $d['sum_zero_rated'] + $d['sum_zero_rated_ecozone']; 
        $total = $d['sum_amount'] +$zero_rated + $d['sum_vat']; 
        $total_due = $total - $d['sum_ewt'];

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
            <label class="defint_1">DEF INTEREST</label>
            <label class="defint_value_1"><?php echo number_format($d['defint'],2); ?></label>
            <label class="energy_1">ENERGY</label>
            <label class="energy_value_1"><?php echo number_format($d['sum_amount'],2); ?></label>
            <label class="vat_1">VAT</label>
            <label class="vat_value_1"><?php echo number_format($d['sum_vat'],2); ?></label>
            <label class="total_sales_1"><?php echo number_format($total,2); ?></label>
            <label class="net_vat_1"><?php echo number_format($d['sum_amount'],2); ?></label>
            <label class="add_vat_1"><?php echo number_format($d['sum_vat'],2); ?></label>
            <label class="total_1"><?php echo number_format($total,2); ?></label>
            <label class="less_withholding_1"><?php echo number_format($d['sum_ewt'],2); ?></label>
            <label class="total_amount_1"><?php echo number_format($total_due,2); ?></label>
            <label class="vatable_1"><?php echo number_format($d['sum_amount'],2); ?></label>
            <label class="vat_exempt_1">0.00</label>
            <label class="zero_rated_1"><?php echo number_format($zero_rated,2); ?></label>
            <label class="vat_percent_1"><?php echo number_format($d['sum_vat'],2); ?></label>
            <label class="grand_total_1"><?php echo number_format($total,2); ?></label>
            <label class="signature_1">
                <img src="<?php echo base_url()."uploads/".$user_signature; ?>" width="100px">
            </label>



        </div>
        <!-- <div class="" style="position:absolute;top: 420px;left:-5px">
            <label class="date_1">December 10, 2023</label>
            <label class="ornumber_1">8951</label>
            <label class="cusname_1">1590 Energy Corporation 1590 Energy Corporation 1590 Energy Corporation 1590 </label>
            <label class="address_1">907-908 Ayala Life FGU Ctr. Cebu Business Park Luz Cebu City, Cebu City (Capital) Philippines </label>
            <label class="tin_1">007-099-179-000 </label>
            <label class="desc_1">TS-WAD-1b5F'2-00000</label>
            <label class="defint_1">DEF INTEREST</label>
            <label class="defint_value_1">0.00</label>
            <label class="energy_1">ENERGY</label>
            <label class="energy_value_1">2.23</label>
            <label class="vat_1">VAT</label>
            <label class="vat_value_1">2.23</label>
            <label class="total_sales_1">019.23</label>
            <label class="net_vat_1">019.23</label>
            <label class="add_vat_1">019.23</label>
            <label class="total_1">019.23</label>
            <label class="less_withholding_1">500.23</label>
            <label class="total_amount_1">500.23</label>
            <label class="vatable_1">500.23</label>
            <label class="vat_exempt_1">500.23</label>
            <label class="zero_rated_1">500.23</label>
            <label class="vat_percent_1">500.23</label>
            <label class="grand_total_1">500.23</label>
            <label class="signature_1">
                <img src="<?php echo base_url(); ?>assets/img/signature.png" width="100px">
            </label>
        </div> -->
    </page>
    </div>
</div>
<input type="hidden" class="stl_id<?php echo $x; ?>" value="<?php echo $d['stl_id']; ?>" id="stl_id<?php echo $x; ?>">
<input type="hidden" class="series_no<?php echo $x; ?>" value="<?php echo $d['or_no']; ?>" id="series_no<?php echo $x; ?>">
<input type="hidden" class="ref_no<?php echo $x; ?>" id="ref_no<?php echo $x; ?>" value="<?php echo $d['refno']; ?>">
<input type="hidden" class="reference_no<?php echo $x; ?>" id="reference_no<?php echo $x; ?>" value="<?php echo $d['ref_no']; ?>">
<input type="hidden" class="collection_id<?php echo $x; ?>" id="collection_id<?php echo $x; ?>" value="<?php echo $d['collection_id']; ?>">
<input type="hidden" class="billing_month<?php echo $x; ?>" id="billing_month<?php echo $x; ?>" value="<?php echo $d['billing_month']; ?>">
<input type="hidden" class="timestamp"  id="timestamp" value="<?php echo $timestamp; ?>">
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
        var timestamp=document.getElementById('timestamp').value;
        var loc= document.getElementById("baseurl").value;
        var redirect = loc+"sales/update_flag";


        for(let a=1;a<counter;a++){
           
        
            //var billing_month=document.getElementById('billing_month'+a).value;
            //var refno=document.getElementById('ref_no'+a).value;
            // var shortname=document.getElementById('stl_id'+a).value;
          
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
                 var series_no= document.getElementById("series_no"+a).value;
                 var reference_no= document.getElementById("reference_no"+a).value;
                 var collection_id= document.getElementById("collection_id"+a).value;

                    canvas.getContext('2d');   
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);

                    var stl_id= $(".stl_id"+a).val();
                    var billing_month= $(".billing_month"+a).val();
                    var ref_no= $(".ref_no"+a).val();

                        //pdf.addPage(PDF_Width, PDF_Height);
                        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*a)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                       
                       if(pdf.save("OR_CENPRI_"+stl_id+"_"+ref_no+"_"+billing_month+"_"+timestamp+"_"+series_no+".pdf")){
                        alert(series_no);
                       }
                       
                                $.ajax({
                                    data: 'series_no='+series_no+'&stl_id='+stl_id+'&reference_no='+reference_no+'&collection_id='+collection_id,
                                    type: "POST",
                                    url: redirect,
                                    success: function(output){
                                        //console.log(output);
                                    
                                       
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
</html>