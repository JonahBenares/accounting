<script src="<?php echo base_url(); ?>assets/js/reserve.js"></script>
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print2307-new.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>
<div class="" id="printbutton">
    <center>
        <?php foreach($prev_reserve_details_id AS $prev){ ?>
        <a href="<?php echo base_url(); ?>reserve/print_2307_reserve/<?php echo $reserve_id; ?>/<?php echo $prev->reserve_detail_id; ?>" class="btn btn-info">Previous</a>
        <?php } ?>
        <button class="btn btn-warning " onclick="goBack()">Back</button>
        <button class="btn btn-success " id="counter_print" onclick="countPrint('<?php echo base_url(); ?>','<?php echo $reserve_detail_id; ?>'); printDiv('printableArea')">Print</button>
        <button class="btn btn-success " onclick="getPDF('<?php echo $short_name; ?>', '<?php echo $refno; ?>','<?php echo $billing_month; ?>','<?php echo date("Ymd"); ?>')">Save as PDF</button>
        <button class="btn btn-success " onclick="getPDFZoomed('<?php echo $short_name; ?>', '<?php echo $refno; ?>','<?php echo $billing_month; ?>','<?php echo date("Ymd"); ?>')">Save as PDF (zoomed)</button>
        <?php foreach($next_reserve_details_id AS $next){ ?>
        <a href="<?php echo base_url(); ?>reserve/print_2307_reserve/<?php echo $reserve_id; ?>/<?php echo $next->reserve_detail_id; ?>" class="btn btn-primary">Next</a>
        <?php } ?>
    </center>
    <br>
</div>
<center>
<div style="padding-bottom:90px;">
    <div id="contentPDF" >
    <page size="Long" id="printableArea" class="canvas_div_pdf" >
        <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
        <label class="period_from "><?php echo $period_from; ?></label>
        <label class="period_to"><?php echo $period_to; ?></label>
        <?php $tin=explode("-",$tin); ?>
        <div class="tin1">
           <label class=""><?php echo (!empty($tin[0])) ? $tin[0] : ''; ?></label> 
           <label class=""><?php echo (!empty($tin[1])) ? $tin[1] : ''; ?></label> 
           <label class=""><?php echo (!empty($tin[2])) ? $tin[2] : ''; ?></label> 
           <label class="last1"><?php echo (!empty($tin[3])) ? $tin[3] : ''; ?></label> 
        </div>
        <label class="payee"><?php echo $name; ?></label>
        <label class="address1"><?php echo $address; ?></label>
        <label class="zip1"><?php echo $zip; ?></label>
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
        <label class="row1-col3"><?php echo (($firstmonth=="-") ? "-" : number_format($firstmonth,2)); ?></label>
        <label class="row1-col4"><?php echo (($secondmonth=="-") ? "-" : number_format($secondmonth,2)); ?></label>
        <label class="row1-col5"><?php echo (($thirdmonth=="-") ? "-" : number_format($thirdmonth,2)); ?></label>
        <label class="row1-col6"><?php echo number_format($total,2); ?></label>
        <label class="row1-col7"><?php echo number_format($ewt,2); ?> <span class="hey">&nbsp;&nbsp;</span></label>

        <label class="row2-col3"><?php echo (($firstmonth=="-") ? "-" : number_format($firstmonth,2)); ?></label>
        <label class="row2-col4"><?php echo (($secondmonth=="-") ? "-" : number_format($secondmonth,2)); ?></label>
        <label class="row2-col5"><?php echo (($thirdmonth=="-") ? "-" : number_format($thirdmonth,2)); ?></label>
        <label class="row2-col6"><?php echo number_format($total,2); ?></label>
        <label class="row2-col7"><?php echo number_format($ewt,2); ?> <span>&nbsp;&nbsp;</span></label>
        <img src="<?php echo base_url(); ?>assets/img/sign_lacambra.png" class="sign_lacambra">
        <label class="row2-col8"> Reference Number: <b><?php echo $reference_no; ?></b></label>
        <label class="row2-col9"> Item Number: <b><?php echo $item_no; ?></b></label>
    </page>
    </div>
</div>
</center>
<input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
<input type="hidden" name="reserve_detail_id" id="reserve_detail_id" value="<?php echo $reserve_detail_id; ?>">
</html>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>
<script type="text/javascript">
    function getPDF(shortname, refno, billing_month, timestamp) {
        html2canvas($(".canvas_div_pdf")[0], {
            allowTaint: true,
            useCORS: true,
            logging: false,
            scale: 2
        }).then(function (canvas) {
            var imgData = canvas.toDataURL("image/png", 0.7);

            // PDF size (Long bond: 210mm × 330mm)
            var pdf = new jsPDF('p', 'mm', [210, 330]);

            var margin = 5; // mm
            var pageWidth = 210 - margin * 2;
            var pageHeight = 330 - margin * 2;

            var imgWidth = pageWidth;
            var imgHeight = (canvas.height * imgWidth) / canvas.width;

            var heightLeft = imgHeight;
            var position = margin;

            // First page
            pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            // Extra pages if content exceeds one page
            while (heightLeft > 0) {
                position = heightLeft - imgHeight + margin;
                pdf.addPage(210, 330);
                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            // Filename
            var fname = "BIR2307_CENPRI_" + shortname + "_" + refno + "_" + billing_month + "_" + timestamp + ".pdf";
            pdf.save(fname);

            // Optional: Send filename to server
            $.ajax({
                data: 'purchase_detail_id=' + $("#purchase_detail_id").val() + '&filename=' + fname,
                type: "POST",
                url: $("#baseurl").val() + "purchases/save_pdf_filename",
                beforeSend: function () {
                    document.getElementById("loading").style.display = 'block';
                },
                success: function (output) {
                    setTimeout(() => {
                        document.getElementById("loading").style.display = 'none';
                    }, 3000);
                }
            });
        });
    }




    function getPDFZoomed(shortname, refno, billing_month, timestamp) {
        html2canvas($(".canvas_div_pdf")[0], {
            allowTaint: true,
            useCORS: true,
            logging: false,
            scale: 2
        }).then(function (canvas) {
            var imgData = canvas.toDataURL("image/png", 0.7);

            // PDF size (Long bond: 210mm × 330mm)
            var pdf = new jsPDF('p', 'mm', [210, 330]);

            var margin = 5; // mm
            var pageWidth = 210 - margin * 2;
            var pageHeight = 330 - margin * 2;

            var imgWidth = pageWidth;
            var imgHeight = (canvas.height * imgWidth) / canvas.width;

            var heightLeft = imgHeight;
            var position = margin;

            // First page
            pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            // Extra pages if content exceeds one page
            while (heightLeft > 0) {
                position = heightLeft - imgHeight + margin;
                pdf.addPage(210, 330);
                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            // Filename
            var fname = "BIR2307_CENPRI_" + shortname + "_" + refno + "_" + billing_month + "_" + timestamp + ".pdf";
            pdf.save(fname);

            // Optional: Send filename to server
            $.ajax({
                data: 'purchase_detail_id=' + $("#purchase_detail_id").val() + '&filename=' + fname,
                type: "POST",
                url: $("#baseurl").val() + "purchases/save_pdf_filename",
                beforeSend: function () {
                    document.getElementById("loading").style.display = 'block';
                },
                success: function (output) {
                    setTimeout(() => {
                        document.getElementById("loading").style.display = 'none';
                    }, 3000);
                }
            });
        });
}

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