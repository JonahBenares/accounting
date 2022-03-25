<style type="text/css">
    /*body {
        background: rgb(204,204,204); 
        color: #000;
        font-family: sans-serif, Arial;
    }
    h1,h2,h3,h4,h5,h6{color: #000}*/
    page {
        background: white;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    page[size="A4"] {  
        width: 21cm;
        height: 29.7cm; 
    }
    page[size="Long"] {
        background: white;
        width: 8.5in;
        height: 13in; 
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    page[size="A4"][layout="landscape"] {
        width: 29.7cm;
        height: 21cm;  
    }
    page[size="A3"] {
        width: 29.7cm;
        height: 42cm;
    }
    page[size="A3"][layout="landscape"] {
        width: 42cm;
        height: 29.7cm;  
    }
    page[size="A5"] {
        width: 14.8cm;
        height: 21cm;
    }
    page[size="A5"][layout="landscape"] {
        width: 21cm;
        height: 14.8cm;  
    }
    .period_from{
        left: 464px; 
        top: 175px;
        position: absolute;
        letter-spacing: 0.799em;
    }
    .period_to{
        left: 807px; 
        top: 175px;
        position: absolute;
        letter-spacing: 0.66em;
    }
    .payee{
        left: 307px; 
        top: 259px;
        position: absolute;
        font-size: 12px;
    }
    .address1{
        left: 307px; 
        top: 298px;
        position: absolute;
        font-size: 12px;
    }
    @media print {
        body,page {
            margin: 0;
            box-shadow: 0;
        }
        body{
            background: #fff;
        }
        page[size="Long"] {
            background: white;
            width: 8.5in;
            height: 13in; 
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        /*table td{ border:1px solid #fff!important; }*/
        .bor-btm{border-bottom:1px solid #000!important;}
        .bor-all{
            border: 1px solid #000;
        }
        #printbutton, #br, #br1{display: none}
        table{border:1px solid #000!important;}
        .backback{background:#d2cdc9}
        td{
            padding: 3px
        }
        .period_from{
            left: 250px; 
            top: 165px;
            position: absolute;
            letter-spacing: 0.799em;
            font-size: 20px!important;
        }
        .period_to{
            left: 680px; 
            top: 165px;
            position: absolute;
            letter-spacing: 0.69em;
            font-size: 20px!important;
        }
        .payee{
            left:40px; 
            top: 270px;
            position: absolute;
            font-size: 14px;
        }
        .address1{
            left: 40px; 
            top: 320px;
            position: absolute;
            font-size: 14px;
        }
    }
    
</style>

<div class="" id="printbutton">
    <center>
        <a href="#" class="btn btn-warning ">Back</a>
        <a href="#" class="btn btn-success " onclick="printDiv('printableArea')">Print</a>
    </center>
    <br>
</div>
<div style="padding-bottom:90px">
    <page size="Long" id="printableArea" >
        <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
        <label class="period_from ">20022002</label>
        <label class="period_to">20022002</label>
        <label class="payee">MESTRE, ERIC, VILLAVICENCIO (ANE ELECTRONIC AND AIRCONDITIONING TECHNOLOGY)</label>
        <label class="address1">TRIVI BLDG., 8 SAN SEBASTIAN AVE., BRGY. 14, BACOLOD CITY</label>
    </page>
</div>

</html>
<script type="text/javascript">
    function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>