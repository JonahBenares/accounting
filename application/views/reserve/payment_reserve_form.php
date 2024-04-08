<script>
    function goBack() {
      window.history.back();
    }
</script>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <button class="btn btn-success " onclick="getPDF()">Save as PDF</button>
        <br>
        <br>
    </center>
</div>
    <page size="Legal" class="canvas_div_pdf">
        <div style="padding:20px">
            <table width="100%" class="table-bsor table-bordsered" style="border-collapse: collapse;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="8" align="center">
                        <img class="logo-print m-t-10" src="<?php echo base_url().LOGO_IEMOP;?>">   
                    </td>
                    <td colspan="6"></td>           
                </tr>
                <tr>
                    <td colspan="20" align="center"> 
                        <h3 style="margin:0px">PAYMENT FORM</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant Name:</td>
                    <td colspan="10" class="bor-btm" align="center">CENTRAL NEGROS POWER RELIABILITY, INC. (CENPRI)</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant ID No.:</td>
                    <td colspan="10" class="bor-btm" align="center">1580</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="table-bordered font10" width="100%" >
                            <tr>
                                <td style="vertical-align: center;" width="9%" align="center"><b>Date of Payment</b></td>
                                <td style="vertical-align: center;" width="19%" align="center"><b>Invoice No.</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>VAT on Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Market Fees</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding Tax</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding VAT</b></td>
                                <td style="vertical-align: center;" width="11%" align="center"><b>Others (Prudential Requirement, Default Interest, etc.)</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>TOTAL</b></td> 
                            </tr>
                            <tr>
                                <td ></td>
                                <td class="xl94"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">-</td>
                            </tr>
                            <?php 
                                $x=1;
                                foreach($payment AS $p){ 
                            ?>
                            <?php if($x <= 50){ ?>
                            <tr>
                                <td align="center"><?php echo $p['transaction_date'];?></td>
                                <td align="center"><?php echo $p['reference_number'];?></td>
                                <td align="right">(<?php echo number_format($p['energy'],2);?>)</td>
                                <td align="right">(<?php echo number_format($p['vat_on_purchases'],2);?>)</td>
                                <td align="right"></td>
                                <td align="right"><?php echo number_format($p['ewt'],2);?></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">(<?php echo number_format($p['total_amount'],2);?>)</td>
                            </tr>
                            <?php }  $x++; } ?>
                            <?php if($x <= 50){ ?>
                            <tr>
                                <td align="center" colspan="2"><b>TOTAL AMOUNT PAID</b></td>
                                <td align="right"><b>(<?php echo number_format($energy,2);?>)</b></td>
                                <td align="right"><b>(<?php echo number_format($vat_on_purchases,2);?>)</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b><?php echo number_format($ewt,2);?></b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>(<?php echo number_format($total,2);?>)</b></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <?php if($x <= 50){ ?>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5"><b>Certified Correct:</b></td>
                    <td colspan="8" align="center" class="bor-btm" style="text-transform: capitalize;"><?php echo $_SESSION['fullname'];?></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="5"></b></td>
                    <td colspan="8" align="center">Authorized Representative & Signature</td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Payment Guide:</b></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="font10" width="100%">
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Standard Chartered Bank</b>  
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="20%">Account Name:</td>
                                <td width="50%"><b>Independent Electricity Market Operator of the Philippines, Inc.</b> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Beneficiary Account No.:</td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Account No.:</td>
                                <td><b>01-99492385786</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Swift Code:</td>
                                <td><b>SCBLPHMM</b></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Banco De Oro</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Company Name:</td>
                                <td><b>SCB FAO IEMOP</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Name.:</td>
                                <td><b>Your Company Name</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Account No.:  </td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">- For check payment, payee should be in the name of  <u><b>"SCB FAO IEMOP"</b></u></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Send this form together with transaction slip at <span style="color:red">accounts.management@iemop.ph</span></b></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </page>
    <?php if($x >= 50){ ?>
        <page size="Legal" class="canvas_div_pdf">
        <div style="padding:20px">
            <table width="100%" class="table-bsor table-bordsered" style="border-collapse: collapse;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                </tr>
                <!-- <tr>
                    <td colspan="6"></td>
                    <td colspan="8" align="center">
                        <img class="logo-print m-t-10" src="<?php echo base_url().LOGO_IEMOP;?>">   
                    </td>
                    <td colspan="6"></td>           
                </tr>
                <tr>
                    <td colspan="20" align="center"> 
                        <h3 style="margin:0px">PAYMENT FORM</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant Name:</td>
                    <td colspan="10" class="bor-btm" align="center">CENTRAL NEGROS POWER RELIABILITY, INC. (CENPRI)</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant ID No.:</td>
                    <td colspan="10" class="bor-btm" align="center">1580</td>
                    <td colspan="5"></td>
                </tr>-->
                <tr>
                    <td colspan="20"><br></td>
                </tr> 
                <tr>
                    <td colspan="20">
                        <table class="table-bordered font10" width="100%" >
                            <tr>
                                <td style="vertical-align: center;" width="9%" align="center"><b>Date of Payment</b></td>
                                <td style="vertical-align: center;" width="19%" align="center"><b>Invoice No.</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>VAT on Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Market Fees</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding Tax</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding VAT</b></td>
                                <td style="vertical-align: center;" width="11%" align="center"><b>Others (Prudential Requirement, Default Interest, etc.)</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>TOTAL</b></td> 
                            </tr>
                            <tr>
                                <td ></td>
                                <td class="xl94"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">-</td>
                            </tr>
                            <?php 
                                $y=1;
                                foreach($payment AS $p){
                            ?>
                            <?php if($y >= 51){ ?>
                            <tr>
                                <td align="center"><?php echo $p['transaction_date'];?></td>
                                <td align="center"><?php echo $p['reference_number'];?></td>
                                <td align="right">(<?php echo number_format($p['energy'],2);?>)</td>
                                <td align="right">(<?php echo number_format($p['vat_on_purchases'],2);?>)</td>
                                <td align="right"></td>
                                <td align="right"><?php echo number_format($p['ewt'],2);?></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">(<?php echo number_format($p['total_amount'],2);?>)</td>
                            </tr>
                            <?php } $y++; } ?>
                            <?php if($x >= 51){ ?>
                            <tr>
                                <td align="center" colspan="2"><b>TOTAL AMOUNT PAID</b></td>
                                <td align="right"><b>(<?php echo number_format($energy,2);?>)</b></td>
                                <td align="right"><b>(<?php echo number_format($vat_on_purchases,2);?>)</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b><?php echo number_format($ewt,2);?></b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>(<?php echo number_format($total,2);?>)</b></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <?php if($y >= 51){ ?>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5"><b>Certified Correct:</b></td>
                    <td colspan="8" align="center" class="bor-btm" style="text-transform: capitalize;"><?php echo $_SESSION['fullname'];?></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="5"></b></td>
                    <td colspan="8" align="center">Authorized Representative & Signature</td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Payment Guide:</b></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="font10" width="100%">
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Standard Chartered Bank</b>  
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="20%">Account Name:</td>
                                <td width="50%"><b>Independent Electricity Market Operator of the Philippines, Inc.</b> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Beneficiary Account No.:</td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Account No.:</td>
                                <td><b>01-99492385786</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Swift Code:</td>
                                <td><b>SCBLPHMM</b></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Banco De Oro</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Company Name:</td>
                                <td><b>SCB FAO IEMOP</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Name.:</td>
                                <td><b>Your Company Name</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Account No.:  </td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">- For check payment, payee should be in the name of  <u><b>"SCB FAO IEMOP"</b></u></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Send this form together with transaction slip at <span style="color:red">accounts.management@iemop.ph</span></b></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </page>
    <?php } ?>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>
<script type="text/javascript">
    function getPDF(){

        var HTML_Width = $(".canvas_div_pdf").width();
        var HTML_Height = $(".canvas_div_pdf").height();
        var top_left_margin = 10;
        var PDF_Width = HTML_Width+(top_left_margin*2);
        var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;
        var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
        html2canvas($(".canvas_div_pdf")[0],{allowTaint:true, 
            useCORS: true,
            logging: false,
            height: window.outerHeight + window.innerHeight,
            windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
            canvas.getContext('2d');
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            var check = document.getElementsByClassName('canvas_div_pdf');
            if (check.length > 1) {
                html2canvas($(".canvas_div_pdf")[1],{allowTaint:true, 
                    useCORS: true,
                    logging: false,
                    height: window.outerHeight + window.innerHeight,
                    windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
                    canvas.getContext('2d');
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
                    for (var i = 1; i <= totalPDFPages; i++) { 
                        pdf.addPage(PDF_Width, PDF_Height);
                        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                    }
                    pdf.save("Payment Reserve Form"+".pdf");
                });
            }else{
                pdf.save("Payment Reserve Form"+".pdf");
            }
        });
    };
</script>
   
