<script>
    function goBack() {
        window.history.back();
        window.close() ;
    }
</script>
<style type="text/css">
    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-calendar-picker-indicator {
        display: none;
        -webkit-appearance: none;
    }
</style>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Save & Print</button>
        <br>
        <br>
    </center>
</div>
<page size="Legal" >
    <div style="padding:30px">
        <table width="100%" class="table-bor table-bordsered" style="border-collapse: collapse;">
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
                <td colspan="11" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo TIN;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="4"></td>           
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center"> 
                    <h3 style="margin:0px">BILLING & SETTLEMENT</h3>
                </td>
            </tr>
            <tr>
                <td colspan="3">Date Prepared:</td>
                <td colspan="9" class="bor-btm"><input type="date" name="" style="width: 100%;border: 0px;"></td>
                <td colspan="3"></td>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="3">Invoice Date:</td>
                <td colspan="9" class="bor-btm"><input type="date" name="" style="width: 100%;border: 0px;"></td>
                <td colspan="3"></td>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <table class="table-boredered table-hover mb-0" style="width:100%;font-size: 12px;">
                        <tr>
                            <td class="td-green p-t-5 p-b-5" colspan="9" align="center">
                                <b>SUMMARY OF ADJUSTMENT BILLING STATEMENT - PURCHASES
                                <br>For the Month of June 2022</b> 
                            </td>
                        </tr>
                        <tr>
                            <td class="font-11 td-gray p-l-5 p-r-5" align="left" width="12%"><b>Particular</b></td>
                            <td class="font-11 td-gray p-l-5 p-r-5" align="left" width="19%"><b>Participant's Name</b></td>  
                            <td class="font-11 td-gray p-l-5 p-r-5" align="left" width="15%"><b>Billing Period</b></td> 
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="8%"><b>Vatable Amount</b></td> 
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Zero Rated Amount</b></td>     
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Net Purchase (Php)</b></td>     
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Vat on Energy </b></td> 
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="5%"><b>EWT</b></td>
                            <td class="font-11 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Total Amount Due (Php)</b></td>
                        </tr>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5" colspan="9" style="border-bottom:0px solid #fff!important">
                                <b>PAYABLES</b>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5" colspan="9">
                                <br>
                                <u>Year 2016-2017</u>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Adjustment</td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAD-170F5-0000050</td>
                            <td class="font-11 p-l-5 p-r-5">Jul 26 - Aug 25, 2020</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">-</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - MOT </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-181F10-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">June 26 - Jul 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - AP </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-186F9-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">Nov 26 - Dec 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - SEC </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-186F9-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">Nov 26 - Dec 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5" colspan="9">
                                <br>
                                <u>Year 2016-2017</u>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Adjustment</td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAD-170F5-0000050</td>
                            <td class="font-11 p-l-5 p-r-5">Jul 26 - Aug 25, 2020</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">-</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - MOT </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-181F10-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">June 26 - Jul 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - AP </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-186F9-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">Nov 26 - Dec 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>
                        <tr>
                            <td class="font-11 p-l-5 p-r-5">Addcom - SEC </td>
                            <td class="font-11 p-l-5 p-r-5 bor-btm" align="center">TS-WAC-186F9-0000001</td>
                            <td class="font-11 p-l-5 p-r-5">Nov 26 - Dec 25, 2021</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">7,145.85</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">609.41</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">240.62</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">28.88</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">(4.81)</td>
                            <td class="font-11 p-l-5 p-r-5" align="right" style="">264.69</td>
                        </tr>

                        <tr>
                            <td colspan="9"><br></td>
                        </tr>
                        <tr>
                            <td class="font-11 td-yellow p-l-5 p-r-5" colspan="3" align="right">Sub Total</td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style=""><b>7,145.85</b></td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style=""><b>609.41</b></td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style=""><b>240.62</b></td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style=""><b>28.88</b></td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style=""><b>(4.81)</b></td>
                            <td class="font-11 td-yellow p-l-5 p-r-5" align="right" style="border-top: 1px solid #000;"><b>264.69</b></td>
                        </tr>
                        <tr>
                            <td colspan="9"><br></td>
                        </tr>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5 td-blue" colspan="8" align="left">TOTAL AMOUNT PAYABLE on or before, JUNE 25, 2022 &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                            <td class="font-12 p-l-5 p-r-5 td-blue" align="right"><b>(6,228.42)</b></td>
                        </tr> 
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br><br><br></td>
            </tr>
            <tr>
                <td colspan="20">
                    <table width="100%">
                        <tr>
                            <td colspan="2" style="background:#e5e5e5; border-right:5px solid #fff"><b>Prepared by:</b></td>
                            <td colspan="6" style="background:#e5e5e5; border-right:5px solid #fff"><b>Checked by:</b></td>
                            <td colspan="2" style="background:#e5e5e5;"><b>Noted by:</b></td>
                        </tr>
                        <tr>
                            <td colspan="20"><br><br></td>
                        </tr>
                        <tr>
                            <td width="19" align="center" class="bor-btm font-12 "><?php echo strtoupper($_SESSION['fullname']);?></td>
                            <td width="1%"></td>
                            <td width="19" align="center" class="bor-btm font-12 ">JEOMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td width="19" align="center" class="bor-btm font-12 ">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td width="19" align="center" class="bor-btm font-12 ">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                            <td width="19" align="center" class="bor-btm font-12 ">MILA ARANA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td width="19" align="center">Billing</td>
                            <td width="1%"></td>
                            <td width="19" align="center">EMG Supervisor</td>
                            <td width="1%"></td>
                            <td width="19" align="center">Accounting</td>
                            <td width="1%"></td>
                            <td width="19" align="center">Finance</td>
                            <td width="1%"></td>
                            <td width="19" align="center">General Manager</td>
                            <td width="1%"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br><br><br></td>
            </tr>
        </table>
    </div>
</page>
</html>
