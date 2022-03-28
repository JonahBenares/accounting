
<style type="text/css">
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
    .payor{
        left: 307px; 
        top: 417px;
        position: absolute;
        font-size: 12px;
    }
    .tin1{
        left: 541px;
        top: 222px;
        position: absolute;
        letter-spacing: 0.86em;
    }
    .tin2{
        left: 541px;
        top: 382px;
        position: absolute;
        letter-spacing: 0.86em;
    }
    .last1{
        margin-left:5px ;
        letter-spacing: 1em;
    }
    .address1{
        left: 307px; 
        top: 298px;
        position: absolute;
        font-size: 12px;
    }
    .address2{
        left: 307px; 
        top: 337px;
        position: absolute;
        font-size: 12px;
    }
    .address3{
        left: 307px; 
        top: 457px;
        position: absolute;
        font-size: 12px;
    }
    .zip1{
        left: 991px; 
        top: 296px;
        position: absolute;
        letter-spacing: 0.53em;
    }
    .zip2{
        left: 991px; 
        top: 457px;
        position: absolute;
        letter-spacing: 0.53em;
    }
    .address3{
        left: 307px; 
        top: 457px;
        position: absolute;
        font-size: 12px;
    }
    .row1-col1{
        left: 280px;
        top: 533px;
        position: absolute;
        width: 205px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
    }
    .row1-col2{
        left: 495px;
        top: 533px;
        position: absolute;
        width: 55px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: center;
    }

    .row1-col3{
        left: 555px;
        top: 533px;
        position: absolute;
        width: 95px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row1-col4{
        left: 655px;
        top: 533px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row1-col5{
        left: 755px;
        top: 533px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row1-col6{
        left: 855px;
        top: 533px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row1-col7{
        left: 955px;
        top: 533px;
        position: absolute;
        width: 115px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row2-col3{
        left: 555px;
        top: 722px;
        position: absolute;
        width: 95px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row2-col4{
        left: 655px;
        top: 722px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row2-col5{
        left: 755px;
        top: 722px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row2-col6{
        left: 855px;
        top: 722px;
        position: absolute;
        width: 99px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .row2-col7{
        left: 955px;
        top: 722px;
        position: absolute;
        width: 115px;
        line-height: 19px;
        font-size: 11px;
        font-family: arial;
        text-align: right;
    }
    .btn-success, .btn-success.disabled {
        box-shadow: 0 2px 6px #8edc9c;
        background-color: #54ca68;
        border-color: #54ca68;
        color: #fff;
    }
    .btn-warning, .btn-warning.disabled {
        box-shadow: 0 2px 6px #ffc473;
        background-color: #ffa426;
        border-color: #ffa426;
        color: #fff;
    }

    .btn {
        font-weight: 600;
        font-size: 12px;
        line-height: 24px;
        padding: 0.1rem 0.5rem;
        letter-spacing: 0.5px;
        border-radius: 5px;
    }
    #printbutton{
        position: fixed;
        width: 100%;
        align-content: center;
        z-index: 999;
    }

    @media print {
        body,page {
            margin: 0;
            box-shadow: 0;
            font-size: auto;
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
         page {
        background: white;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        .period_from{
            left: 257px; 
            top: 170px;
            position: absolute;
            letter-spacing: 0.799em;
            font-size: 21px;
        }
        .period_to{
            left: 708px; 
            top: 170px;
            position: absolute;
            letter-spacing: 0.66em;
            font-size: 21px;
        }
        .payee{
            left:40px; 
            top: 280px;
            position: absolute;
            font-size: 15px;
        }
        .payor{
            left:40px; 
            top: 417px;
            position: absolute;
            font-size: 12px;
        }
        .tin1{
            left: 358px;
            top: 232px;
            position: absolute;
            font-size: 21px;
            letter-spacing: 0.86em;
        }
        .tin2{
            left: 358px;
            top: 440px;
            position: absolute;
            font-size: 21px;
            letter-spacing: 0.86em;
        }
        .last1{
            margin-left:5px ;
            letter-spacing: 1em;
        }
        .address1{
            left:40px; 
            top: 335px;
            position: absolute;
            font-size: 15px;
        }
        .address2{
            left:40px; 
            top: 385px;
            position: absolute;
            font-size: 12px;
        }
        .address3{
            left:40px; 
            top: 457px;
            position: absolute;
            font-size: 12px;
        }
        .zip1{
            left: 991px; 
            top: 296px;
            position: absolute;
            letter-spacing: 0.53em;
        }
        .zip2{
            left: 991px; 
            top: 457px;
            position: absolute;
            letter-spacing: 0.53em;
        }
        .address3{
            left:40px; 
            top: 457px;
            position: absolute;
            font-size: 12px;
        }
        .row1-col1{
            left: 280px;
            top: 533px;
            position: absolute;
            width: 205px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
        }
        .row1-col2{
            left: 495px;
            top: 533px;
            position: absolute;
            width: 55px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: center;
        }

        .row1-col3{
            left: 555px;
            top: 533px;
            position: absolute;
            width: 95px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row1-col4{
            left: 655px;
            top: 533px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row1-col5{
            left: 755px;
            top: 533px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row1-col6{
            left: 855px;
            top: 533px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row1-col7{
            left: 955px;
            top: 533px;
            position: absolute;
            width: 115px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row2-col3{
            left: 555px;
            top: 722px;
            position: absolute;
            width: 95px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row2-col4{
            left: 655px;
            top: 722px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row2-col5{
            left: 755px;
            top: 722px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row2-col6{
            left: 855px;
            top: 722px;
            position: absolute;
            width: 99px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }
        .row2-col7{
            left: 955px;
            top: 722px;
            position: absolute;
            width: 115px;
            line-height: 19px;
            font-size: 11px;
            font-family: arial;
            text-align: right;
        }

       
    }
    
</style>

<div class="" id="printbutton">
    <center>
        <button class="btn btn-warning " onclick="history.back()">Back</button>
        <button class="btn btn-success " onclick="printDiv('printableArea')">Print</button>
    </center>
    <br>
</div>
<br>
<br>
<div style="padding-bottom:90px">
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
        <label class="payee">MESTRE, ERIC, VILLAVICENCIO (ANE ELECTRONIC AND AIRCONDITIONING TECHNOLOGY)</label>
        <label class="address1">TRIVI BLDG., 8 SAN SEBASTIAN AVE., BRGY. 14, BACOLOD CITY</label>
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
        <label class="row1-col3">3,225.67</label>
        <label class="row1-col4">3,225.67</label>
        <label class="row1-col5">3,225.67</label>
        <label class="row1-col6">3,225.67</label>
        <label class="row1-col7">3,225.67</label>

        <label class="row2-col3">3,225.67</label>
        <label class="row2-col4">3,225.67</label>
        <label class="row2-col5">3,225.67</label>
        <label class="row2-col6">3,225.67</label>
        <label class="row2-col7">3,225.67</label>
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