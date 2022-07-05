<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
  
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
        position: absolute;
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
    .paper-long {
        background: white;
        position: absolute;
        width: 8.5in;
        height: 13in; 
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
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
        /*body{
            background: #fff;
        }*/
        body,section,page {
            margin: 0;
            box-shadow: 0;
            padding: 0;
        }
        .main-sidebar ,.sidebar-style-2, .settingPanelToggle{
            display: none;
        }
        
        .paper-long {
            background: white;
            position: absolute;
            width: 100%;
            height: 100%; 
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        #printbutton, #br, #br1{display: none}
        .main-content {
            padding-left: 0px;
            padding-right: 0px;
            padding-top: 0px;
            width: 100%;
            position: relative;
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
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="" id="printbutton">
                        <center>
                            <a href="#" class="btn btn-warning ">Back</a>
                            <a href="#" class="btn btn-success " onclick="printDiv('printableArea')">Print</a>
                        </center>
                        <br>
                    </div>
                    <div class="paper-long"  id="printableArea" >
                        <img class="img2307" src="<?php echo base_url(); ?>assets/img/form2307.jpg" style="width: 100%;">
                        <label class="period_from ">20022002</label>
                        <label class="period_to">20022002</label>
                        <label class="payee">MESTRE, ERIC, VILLAVICENCIO (ANE ELECTRONIC AND AIRCONDITIONING TECHNOLOGY)</label>
                        <label class="address1">TRIVI BLDG., 8 SAN SEBASTIAN AVE., BRGY. 14, BACOLOD CITY</label>
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
                    <div class="mb-5"><br></div>
                </div>  
            </div>
        </div>
    </section>
</div>


