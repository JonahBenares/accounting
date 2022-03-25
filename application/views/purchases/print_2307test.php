<style type="text/css">
    @media print{
        label{
            font-size: 12px;
        }
    }
    
</style>
<a href="#" class="btn btn-success " onclick="printDiv('printableArea')">Print</a>
<div id="printableArea" >
    <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
    <label style="top: 183px;position: absolute;left: 361px; letter-spacing: 0.62em;">20022002</label>
</div>

<script type="text/javascript">
    function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>
