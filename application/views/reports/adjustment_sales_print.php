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
    .hr{
        margin:3px 0px;
        border-top:0px solid #000;
        border-bottom: 0px solid rgb(0 0 0 / 5%);
        border-right :0px solid rgb(0 0 0 / 5%);
        border-left :0px solid rgb(0 0 0 / 5%);
    }
</style>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <!-- <button href="#" class="btn btn-success " onclick="window.print()">Save & Print</button> -->
        <br>
        <br>
    </center>
</div>
<page size="Legal" >
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
                <td colspan="10" align="center" class="font-11" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?>, 
                    <?php echo TIN;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="4"></td>           
                <td colspan="1"></td>           
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
                <td colspan="9" class="bor-btm"><span><?php echo date('F d,Y'); ?></span></td>
                <td colspan="3"></td>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="3">Invoice Date:</td>
                <td colspan="9" class="bor-btm"><span><?php echo $invoice_date; ?></span></td>
                <td colspan="3"></td>
                <td colspan="5"></td>
            </tr>
            <!-- <tr>
                <td colspan="3"></td>
                <td colspan="9"><i><span>Note: Advisory was emailed on <?php echo date("F d, Y",strtotime($invoice_date)); ?></span></i></td>
                <td colspan="3"></td>
                <td colspan="5"></td>
            </tr> -->
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <table class="table-bordesred table-hover mb-0" style="width:100%;font-size: 12px;">
                        <tr>
                            <td class="td-green p-t-5 p-b-5" colspan="9" align="center">
                                <b>SUMMARY OF ADJUSTMENT BILLING STATEMENT - SALES
                                <br>For the Month of <?php echo date("F Y",strtotime($due_date));?></b>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-10 td-gray p-l-5 p-r-5" align="left" width="16%"><b>Particular</b></td>
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="22%"><b>Reference Number</b></td>  
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="25%"><b>Billing Period</b></td> 
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="8%"><b>Vatable Amount</b></td> 
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Zero Rated Amount</b></td>     
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Net Sale (Php)</b></td>     
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Vat on Energy </b></td> 
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="5%"><b>EWT</b></td>
                            <td class="font-10 td-gray p-l-5 p-r-5" align="center" width="9%"><b>Total Amount Due (Php)</b></td>
                        </tr>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5" colspan="9" style="border-bottom:0px solid #fff!important">
                                <b>RECEIVABLES</b>
                            </td>
                        </tr>
                            <?php 
                                        if(!empty($adjustment)){
                                        $data2 = array();
                                        foreach($adjustment as $ads) {
                                            $key = date("Y",strtotime($ads['billing_to']));
                                            if(!isset($data2[$key])) {
                                                $data2[$key] = array(
                                                    'particular'=>array(),
                                                    'reference_number'=>array(),
                                                    'billing_from'=>array(),
                                                    'billing_from_single'=>$ads['billing_from'],
                                                    'billing_to'=>array(),
                                                    'billing_to_single'=>$ads['billing_to'],
                                                    'vatable_sales'=>array(),
                                                    'vat_on_sales'=>array(),
                                                    'ewt'=>array(),
                                                    'zero_rated'=>array(),
                                                    'net'=>array(),
                                                    'total'=>array(),
                                                    'total_single'=>$ads['total'],
                                                );
                                            }
                                            $data2[$key]['particular'][] = $ads['particular'];
                                            $data2[$key]['reference_number'][] = $ads['reference_number'];
                                            $data2[$key]['billing_from'][] = date("F d",strtotime($ads['billing_from']));
                                            $data2[$key]['billing_to'][] = date("F d",strtotime($ads['billing_to']));
                                            $data2[$key]['billing_fromto'][] = date("M. d,Y",strtotime($ads['billing_from']))." - ".date("M. d,Y",strtotime($ads['billing_to']));
                                            $data2[$key]['vatable_sales'][] = number_format($ads['vatable_sales'],2);
                                            $data2[$key]['vat_on_sales'][] = number_format($ads['vat_on_sales'],2);
                                            $data2[$key]['ewt'][] = "(".number_format($ads['ewt'],2).")";
                                            $data2[$key]['zero_rated'][] = number_format($ads['zero_rated'],2);
                                            $data2[$key]['net'][] = number_format($ads['net'],2);
                                            $data2[$key]['total'][] = number_format($ads['total'],2);
                                        }
                                        foreach($data2 AS $ad){ 
                                ?>
                        <tr>
                            <td class="font-12 p-l-5 p-r-5" colspan="9">
                                <br>
                                <u>Year <?php echo date("Y",strtotime($ad['billing_to_single'])); ?></u>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-10 p-l-0 p-r-0"><?php echo implode("<hr class='hr'>",$ad['particular'])."<br>"; ?></td>
                            <td class="font-10 p-l-0 p-r-0 bor-btm" align="center"><?php echo implode("<hr class='hr'>",$ad['reference_number']);?></td>
                            <td class="font-9 p-l-0 p-r-0" align="center"><?php echo implode("<hr class='hr'>",$ad['billing_fromto']);?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['vatable_sales']);?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['zero_rated']);?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['net']);?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['vat_on_sales']);?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['ewt']); ?></td>
                            <td class="font-10 p-l-0 p-r-0" align="right"><?php echo implode("<hr class='hr'>",$ad['total']);?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="9"><br></td>
                        </tr>
                        <tr>
                            <td class="font-10 td-yellow p-l-5 p-r-5" colspan="3" align="right">Sub Total</td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right"><b><?php echo number_format($total_vatable_sales,2);?></b></td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right"><b><?php echo number_format($total_zero_rated,2);?></b></td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right"><b><?php echo number_format($total_net,2);?></b></td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right"><b><?php echo number_format($total_vat_on_sales,2);?></b></td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right"><b>(<?php echo number_format($total_ewt,2);?>)</b></td>
                            <td class="font-10 td-yellow p-l-5 p-r-5" align="right" style="border-top: 1px solid #000;"><b><?php echo number_format($total_sum,2);?></b></td>
                        </tr>
                        <tr>
                            <td colspan="9"><br></td>
                        </tr>
                        <tr>
                            <td class="font-10 p-l-5 p-r-5 td-blue" colspan="8" align="left">TOTAL AMOUNT RECEIVABLE on or before, <?php echo date('F d,Y',strtotime($due_date))?> &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                            <td class="font-10 p-l-5 p-r-5 td-blue" align="right"><b><?php echo number_format($total_sum,2);?></b></td>
                        </tr>
                        <?php } ?> 
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                    <br>
                </td>
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
                            <td width="19%" align="center" class="bor-btm font-11 "><?php echo strtoupper($_SESSION['fullname']);?></td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-11 ">JEOMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-11 ">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-11 ">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-11 ">MILA ARANA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td width="19%" align="center" class="font-11">Billing</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">EMG</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">Accounting</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">Finance</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">General Manager</td>
                            <td width="1%"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                    <br>
                    <br>
                </td>
            </tr>

        </table>
    </div>
</page>
</html>