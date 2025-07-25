<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print2307-style.css">
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
        

        </center>
        <br>
    </div>
    <center>


        <?php 
        $x=1;
        foreach($details AS $d){ ?>
        <div style="padding-bottom:90px;">
        <div id="contentPDF" >
        <page size="Long"  class="canvas_div_pdf<?php echo $x; ?>" >
             <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;"> 
            <label class="period_from "><?php echo $period_from; ?></label>
            <label class="period_to"><?php echo $period_to; ?></label>
            <?php 
           
                $tin=explode("-",$d['tin']);
                $tin1 = $tin[0] ?? '';
                $tin2 = $tin[1] ?? '';
                $tin3 = $tin[2] ?? '';
           ?>
            <div class="tin1">
               <label class=""><?php echo $tin1; ?></label> 
               <label class=""><?php echo $tin2; ?></label> 
               <label class=""><?php echo $tin3; ?></label> 
               <label class="last1">0000</label> 
            </div>
            <label class="payee"><?php echo $d['name']; ?></label>
            <label class="address1"><?php echo $d['address']; ?></label>
            <label class="zip1"><?php echo $d['zip']; ?></label>
            <label class="address2"></label>
            <div class="tin2">
               <label class=""><?php echo COMPANY_TIN1 ?></label> 
               <label class=""><?php echo COMPANY_TIN2 ?></label> 
               <label class=""><?php echo COMPANY_TIN3 ?></label> 
               <label class="last1"><?php echo COMPANY_TIN4 ?></label> 
            </div>
            <label class="payor"><?php echo COMPANY_NAME2307 ?></label>
            <label class="address3"><?php echo COMPANY_ADDRESS ?></label>
            <label class="zip2"><?php echo COMPANY_ZIP ?></label>
            <label class="row1-col1">Income payment made by top withholding agents to their local/resident supplier of services other than those covered by other rates of withholding tax</label>
            <label class="row1-col2">WC160</label>
            <label class="row1-col3"><?php echo (($d['firstmonth']=="-") ? "-" : number_format($d['firstmonth'],2)); ?></label>
            <label class="row1-col4"><?php echo (($d['secondmonth']=="-") ? "-" : number_format($d['secondmonth'],2)); ?></label>
            <label class="row1-col5"><?php echo (($d['thirdmonth']=="-") ? "-" : number_format($d['thirdmonth'],2)); ?></label>
            <label class="row1-col6"><?php echo number_format($d['total'],2); ?></label>
            <label class="row1-col7"><?php echo number_format($d['ewt'],2); ?> <span class="hey">&nbsp;&nbsp;</span></label>

            <label class="row2-col3"><?php echo (($d['firstmonth']=="-") ? "-" : number_format($d['firstmonth'],2)); ?></label>
            <label class="row2-col4"><?php echo (($d['secondmonth']=="-") ? "-" : number_format($d['secondmonth'],2)); ?></label>
            <label class="row2-col5"><?php echo (($d['thirdmonth']=="-") ? "-" : number_format($d['thirdmonth'],2)); ?></label>
            <label class="row2-col6"><?php echo number_format($d['total'],2); ?></label>
            <label class="row2-col7"><?php echo number_format($d['ewt'],2); ?> <span>&nbsp;&nbsp;</span></label>
            <img src="<?php echo base_url(); ?>assets/img/sign_lacambra.png" class="sign_lacambra">
            <label class="row2-col8"> Reference Number: <b><?php echo $d['reference_no']; ?></b></label>
            <label class="row2-col9"> Item Number: <b><?php echo $d['item_no']; ?></b></label>
           
                
        </page>
        </div>
     
        </div>
         <input type="hidden" class="shortname<?php echo $x; ?>" value="<?php echo $d['shortname']; ?>" id="shortname<?php echo $x; ?>">   
        <input type="hidden" class="ref_no" id="ref_no<?php echo $x; ?>" value="<?php echo $d['ref_no']; ?>">
        <input type="text" class="purchase_detail_id" id="purchasedetailid<?php echo $x; ?>" value="<?php echo $d['purchase_detail_id']; ?>">
        <input type="hidden" class="billing_month" id="billing_month" value="<?php echo ($d['due_date']=='') ? $billing_month : $d['due_date']; ?>">
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
            var billing_month=document.getElementById('billing_month').value;
            var timestamp=document.getElementById('timestamp').value;
            var loc= document.getElementById("baseurl").value;
            var redirect = loc+"purchases/update_filename";

            for(let a=1;a<counter;a++){
              
                var HTML_Width = $(".canvas_div_pdf"+a).width();

                
                var HTML_Height = $(".canvas_div_pdf"+a).height();
               

                var top_left_margin = 10;
                var PDF_Width = HTML_Width+(top_left_margin*2);
                var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
                var canvas_image_width = HTML_Width;
                var canvas_image_height = HTML_Height;
                
                var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
              
                html2canvas($(".canvas_div_pdf"+a)[0],{
                    allowTaint:true, 
                    useCORS: true,
                    logging: false,
                    height: window.outerHeight + window.innerHeight,
                    windowHeight: window.outerHeight + window.innerHeight,

                }).then(function(canvas) {
                        var purchase_detail_id= document.getElementById("purchasedetailid"+a).value;
                        var refno=document.getElementById('ref_no'+a).value;
                        canvas.getContext('2d');   
                        var imgData = canvas.toDataURL("image/jpeg", 1.0);
                        var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
                        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);

                        var shortname= $(".shortname"+a).val();
                       
                        //for (var i = 1; i <= totalPDFPages; i++) { 
                            pdf.addPage(PDF_Width, PDF_Height);
                            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*a)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                        //}
                        // let rno = refno.split("-");
                        // let newref = rno[2] + rno[3].slice(0, -1);
                        var fname = "BIR2307_CENPRI_"+shortname+"_"+refno+"_"+billing_month+"_"+timestamp+".pdf";
                         pdf.save("BIR2307_CENPRI_"+shortname+"_"+refno+"_"+billing_month+"_"+timestamp+".pdf");
                        
                         $.ajax({
                                data: 'purchase_detail_id='+purchase_detail_id+'&filename='+fname,
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
    <!-- <script src="<?php echo base_url(); ?>assets/js/jspdf.umd.min.js"></script> -->
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

