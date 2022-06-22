<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print2307-style.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>
<div class="" id="printbutton">
    <center>
        <button class="btn btn-warning " onclick="document.location='upload_purchases'">Back</button>
        <button class="btn btn-success " id="counter_print" onclick="countPrint('<?php echo base_url(); ?>','<?php echo $purchase_detail_id; ?>'); printDiv('printableArea')">Print</button>
        <button class="btn btn-primary " onclick="saveDiv('printableArea','Title')">Save as PDF</button>
    </center>
    <br>
</div>
<center>
<div style="padding-bottom:90px;">
    <div id="contentPDF">
    <page size="Long" id="printableArea" >
        <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
        <label class="period_from "><?php echo $period_from; ?></label>
        <label class="period_to"><?php echo $period_to; ?></label>
        <?php $tin=explode("-",$tin); ?>
        <div class="tin1">
           <label class=""><?php echo $tin[0]; ?></label> 
           <label class=""><?php echo $tin[1]; ?></label> 
           <label class=""><?php echo $tin[2]; ?></label> 
           <label class="last1">0000</label> 
        </div>
        <label class="payee"><?php echo $name; ?></label>
        <label class="address1"><?php echo $address; ?></label>
        <label class="zip1"><?php echo $zip; ?></label>
        <label class="address2"></label>
        <div class="tin2">
           <label class="">008</label> 
           <label class="">691</label> 
           <label class="">287</label> 
           <label class="last1">0000</label> 
        </div>
        <label class="payor">CENTRAL NEGROS POWER RELIABILITY, INC.</label>
        <label class="address3">COR. RIZAL - MABINI STREETS, BACOLOD CITY</label>
        <label class="zip2">6100</label>
        <label class="row1-col1">Income payment made by top withholding agents to their local/resident supplier of goods other than those covered by other rates of withholding tax</label>
        <label class="row1-col2">WC158</label>
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
        <label class="row2-col8"> Reference Number: <b><?php echo $reference_no; ?></b></label>
        <label class="row2-col9"> Item Number: <b><?php echo $item_no; ?></b></label>
    </page>
    </div>
</div>
</center>
<input type="hidden" name="baseurl" id="baseurl" value="<?php echo base_url(); ?>">
<input type="hidden" name="purchase_detail_id" id="purchase_detail_id" value="<?php echo $purchase_detail_id; ?>">
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }

    var doc = new jsPDF();

    function saveDiv(divId, title) {
        doc.fromHTML(`<html><head><title>${title}</title></head><body>` + document.getElementById(divId).innerHTML + `</body></html>`);
        doc.save('FORM_2307.pdf');
    }
</script>