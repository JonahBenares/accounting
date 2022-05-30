<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Otika - Admin Dashboard Template</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print2307-style.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/favicon.ico' />
</head>
<div class="" id="printbutton">
    <center>
        <button class="btn btn-warning " onclick="document.location='upload_purchases'">Back</button>
        <button class="btn btn-success " id="counter_print" onclick="countPrint('<?php echo base_url(); ?>','<?php echo $purchase_detail_id; ?>'); printDiv('printableArea')">Print</button>
        <button class="btn btn-primary " onclick="saveDiv('printableArea','Title')">Save as PDF</button>
    </center>
    <br>
</div>
<br>
<br>
<center>
<div style="padding-bottom:90px;">
    <div id="contentPDF">
    <page size="Long" id="printableArea" >
        <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
        <label class="period_from ">20022002</label>
        <label class="period_to">20022002</label>
        <div class="tin1">
           <label class="">112</label> 
           <label class="">112</label> 
           <label class="">112</label> 
           <label class="last1">0000</label> 
        </div>
        <label class="payee">NATIONAL IRRIGATION ADMINISTRATION MAGAT RIVER INTEGRATED IRRIGATION SYSTEM</label>
        <label class="address1">28th Floor The Orient Square, Don Francisco Ortigas Jr. Road, Ortigas Center, San Antonio 1605 City of Pasig NCR, Second District Philippines</label>
        <label class="zip1">6100</label>
        <label class="address2">X</label>
        <div class="tin2">
           <label class="">112</label> 
           <label class="">112</label> 
           <label class="">112</label> 
           <label class="last1">0000</label> 
        </div>
        <label class="payor">CENTRAL NEGROS POWER RELIABILITY, INC.</label>
        <label class="address3">COR. RIZAL - MABINI STREETS, BACOLOD CITY</label>
        <label class="zip2">6100</label>
        <label class="row1-col1">Income payment made by top withholding agents to their local/resident supplier of goods other than those covered by other rates of withholding tax</label>
        <label class="row1-col2">WC158</label>
        <label class="row1-col3">9.6700</label>
        <label class="row1-col4">9.6700</label>
        <label class="row1-col5">9.6700</label>
        <label class="row1-col6">3,225.67</label>
        <label class="row1-col7">9.6700 <span class="hey">&nbsp;&nbsp;</span></label>

        <label class="row2-col3">9.6700</label>
        <label class="row2-col4">9.6700</label>
        <label class="row2-col5">9.6700</label>
        <label class="row2-col6">9.6700</label>
        <label class="row2-col7">9.6700 <span>&nbsp;&nbsp;</span></label>
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