<script>
    function goBack() {
      window.history.back();
    }
</script>
<div class="animated fadeInDown" style="margin-top:10px" id="printbutton">
    <center>
        <a onclick="goBack()" class="btn btn-warning text-white btn-w100 btn-round">Back</a>
        <a href="#" class="btn btn-success btn-w100 btn-round" onclick="window.print()">Print</a>
        <a href='<?php echo base_url(); ?>purchases/print_invoice/<?php echo $purchase_detail_id ?>' class="btn btn-primary btn-w100 btn-round">Invoice</a> 
    </center>
    <br>
</div>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-borsdered" style="border-collapse: collapse;">
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
                <td colspan="1"></td>
                <td colspan="4">
                    <img class="logo-print" src="<?php echo base_url().LOGO;?>">   
                </td>
                <td colspan="10" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="5"></td>           
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center"> 
                    <h3 style="margin:0px">BILLING STATEMENT</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">Customer:</td>
                <td colspan="9" class="bor-btm"><?php echo $company_name; ?></td>
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm">514</td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    <?php echo $address;?>
                </td>
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y");?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Billing Period:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($billing_from))." to ".date("M d,Y",strtotime($billing_to));?></td>
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm"><?php echo $tin; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($due_date));?></td>
            </tr>
            <tr>
                <td colspan="2">STL ID:</td>
                <td colspan="6" class="bor-btm"><?php echo $settlement; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Reference:</td>
                <td colspan="5" class="bor-btm"><?php echo $reference_number; ?></td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%"> 
                        <tr class="table-bor">
                            <td align="center" width="15%">ITEMS</td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC_SS</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="15%">TOTAL</td>
                        </tr>
                        <tr>
                            <td>Vatable Sales</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                        </tr>
                        <tr>
                            <td>Zero-Rated Sales</td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                        </tr>
                        <tr>
                            <td>Total Sales</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td>12% VAT on Sales</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">144.26</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">144.26</td>
                        </tr>
                        <tr>
                            <td>EWT</td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">(24.04)</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">(24.04)</td>
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td><b>Net Amount Due</b></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">1,322.42</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right"><b>1,322.42</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <b>Note:</b><br>
                    1. The scanned copies of the withholding tax certificate shall be submitted to IEMOP thru tax data facility no later than three (3) working days from the end of the calendar month.
                    <br>
                    <br

                    2. The original copy shall be submitted at the offices of the IEMOP at the 9th Floor Robinsons Equitable Tower ADB Avenue, Ortigas Center, Pasig City.
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td></td>
                            <td colspan="5"><b>Checked by:</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">JOEMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">EMG Supervisor</td>
                            <td></td>
                            <td align="center">Accounting</td>
                            <td></td>
                            <td align="center">Finance</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td width="1%"></td>
                            <td width="32%"><b>Prepared by:</b></td>
                            <td width="1%"></td>
                            <td width="32%"></td>
                            <td width="1%"></td>
                            <td width="32%"><b>Noted by:</b></td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center" class="bor-btm">CELINA TIGNERO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center" class="bor-btm">MILA ARANA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">Billing</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center">General Manager</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</page>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-borsdered" style="border-collapse: collapse;">
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
                <td colspan="1"></td>
                <td colspan="4">
                    <img class="logo-print" src="<?php echo base_url().LOGO;?>">   
                </td>
                <td colspan="10" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="5"></td>           
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center"> 
                    <h3 style="margin:0px">BILLING STATEMENT</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">Customer:</td>
                <td colspan="9" class="bor-btm">1590 Energy Corporation</td>
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm">514</td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    907-908 Ayala Life FGU Ctr. Cebu Business Park Luz Cebu City, Cebu City (Capital) Philippines 6000
                </td>
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm">18/04/2022</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Billing Period:</td>
                <td colspan="5" class="bor-btm">Feb 26 - March 25, 2022</td>
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm">007-833-205-000</td>
                <td colspan="4"></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm">25/04/2022</td>
            </tr>
            <tr>
                <td colspan="2">STL ID:</td>
                <td colspan="6" class="bor-btm">1590EC</td>
                <td colspan="4"></td>
                <td colspan="3">Reference:</td>
                <td colspan="5" class="bor-btm">TS-WF-189F-0000089</td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%"> 
                        <tr class="table-bor">
                            <td align="center" width="15%">ITEMS</td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC_SS</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="15%">TOTAL</td>
                        </tr>
                        <tr>
                            <td>Vatable Purchases</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                        </tr>
                        <tr>
                            <td>Zero-Rated Purchases</td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                        </tr>
                        <tr>
                            <td>Total Purchases</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">1,202.20</td>
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td>12% VAT on Purchases</td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">144.26</td>
                            <td></td>
                            <td align="right">-</td>
                            <td></td>
                            <td align="right">144.26</td>
                        </tr>
                        <tr>
                            <td>EWT</td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">(24.04)</td>
                            <td></td>
                            <td class="bor-btm" align="right">-</td>
                            <td></td>
                            <td class="bor-btm" align="right">(24.04)</td>
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td><b>Net Amount Due</b></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right">1,322.42</td>
                            <td></td>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <td class="bor-btm2" align="right"><b>1,322.42</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <b>Note:</b><br>
                    1. The scanned copies of the withholding tax certificate shall be submitted to IEMOP thru tax data facility no later than three (3) working days from the end of the calendar month.
                    <br>
                    <br>

                    2. The original copy shall be submitted at the offices of the IEMOP at the 9th Floor Robinsons Equitable Tower ADB Avenue, Ortigas Center, Pasig City.
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td></td>
                            <td colspan="5"><b>Checked by:</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">JOEMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">EMG Supervisor</td>
                            <td></td>
                            <td align="center">Accounting</td>
                            <td></td>
                            <td align="center">Finance</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td width="1%"></td>
                            <td width="32%"><b>Prepared by:</b></td>
                            <td width="1%"></td>
                            <td width="32%"></td>
                            <td width="1%"></td>
                            <td width="32%"><b>Noted by:</b></td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center" class="bor-btm">CELINA TIGNERO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center" class="bor-btm">MILA ARANA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">Billing</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center">General Manager</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</page>

<!-- <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<script src="assets/js/demo.js"></script>  -->
   

</html>
