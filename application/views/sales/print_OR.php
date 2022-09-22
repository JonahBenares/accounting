<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
        window.close();
      window.history.back();
    }
</script>

<style type="text/css">
    .table-size td{
        font-size: 10px;
        padding: 0px;
        line-height:10px;
    }
    .table-size2 td{
        font-size: 11px;
        padding: 0px;
    }
    .bor-btm1{
        border: 1px solid #000;
    }
    /*table tr td{
        font-family: arial!important;
    }
    table tr td span{
        font-family: arial!important;
    }*/
</style>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <br>
        <br>
    </center>
</div>
<page size="A4" style="">
    <div style="margin-left: 20px;margin-right: 80px;">
        <table class="page-OR table-bordssered" width="100%">
            <tr>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
                <td style="padding:3px;" width="5%"></td>
            </tr>
            <tr>
                <td colspan="20" align="left" style="color: #fff;">                        
                    <h4 class="mb-0" style="font-family: arial;font-stretch: condensed;font-size: 25px;font-weight: 700;color: #fff;">CENTRAL NEGROS POWER RELIABILTY, INC.</h4>
                    <div style="font-size: 12px;margin-bottom: 0px;line-height: 15px;">
                        #88 ELOISA Q'S BLDG., COR. RIZAL-MABINI STS.<br>
                        BRGY. 22, BACOLOD CITY <br>
                        <b>VAT Reg. TIN: 008-691-287-00002</b>
                    </div>
                </td>
            </tr>
           <!--  <tr>
                <td colspan="10" style="font-size: 12px;">TEL. NO. (034) 435-1932</td>
                <td colspan="1">DATE:</td>
                <td colspan="4" class="bor-btm"> <?php echo date("F j, Y", strtotime($date)); ?></td>
                <td colspan="5"></td>
            </tr> -->
            <tr>
                <td colspan="20" style="padding-top: 0px;">
                    <table width="100%" class="table-bordsered" >
                        <tr>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                        </tr>
                        <?php if(!empty($client)){ foreach($client AS $c){ ?>
                        <tr>
                            <td colspan="3" rowspan="2" style="font-size:11px;color: #fff; vertical-align: text-top;">CUSTOMER NAME:</td>
                            <td colspan="7" rowspan="2" class="p-l-15" style="font-size:11px; vertical-align: text-top;">
                                <span ><?php echo $c->participant_name; ?></span>
                            </td>
                            <td colspan="10" style="font-size:11px;" class="p-l-30">
                                <span style="font-size:11px;color: #fff;">DATE:</span>
                                <span ><?php echo date("F j, Y", strtotime($date)); ?></span>
                            </td>
                        </tr>   
                        <tr>
                            
                            <td colspan="10" style="font-size:11px" >
                                <span style="font-size:11px;color: #fff;">ADDRESS:</span>
                                <span class="pl-2"><?php echo $c->registered_address; ?></span>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td colspan="10" rowspan="2" style="font-size:11px"><b>CUSTOMER NAME:<span class="pl-2"><?php echo $c->participant_name; ?></span></b></td>
                            <td colspan="10" style="font-size:11px"><b>DATE:<span class="pl-2"><?php echo date("F j, Y", strtotime($date)); ?></span></b> </td>
                        </tr>   
                        <tr>
                            
                            <td colspan="10" style="font-size:11px"><b>ADDRESS:<span class="pl-2"><?php echo $c->registered_address; ?></span></b> </td>
                        </tr> -->
                        <tr>
                            <td colspan="15" style="font-size:11px;color: #fff;"><b>BUSINESS STYLE:</b></td>
                            <td colspan="5" class="p-l-5">
                                <span style="color:#fff">TIN:</span>
                                <span style="font-size:11px;"><?php echo $c->tin; ?></span>
                            </td>
                        </tr>
                        <?php } }else{ ?>
                        <tr>
                            <td colspan="10" style="font-size:11px;color: #fff;"><b>CUSTOMER NAME:<span class="pl-2"></span></b></td>
                            <td colspan="10" style="font-size:11px;color: #fff;"><b>ADDRESS:<span class="pl-2"></span></b></td>
                        </tr>
                        <tr>
                            <td colspan="15" style="font-size:11px;color: #fff;"><b>BUSINESS STYLE:</b></td>
                            <td colspan="5" style="font-size:11px;color: #fff;"><b>TIN:<span class="pl-2" ></span></b></td>
                        </tr>
                        <?php }?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding-top:6px">
                    <table width="100%" class="table-borsdered">
                        <tr>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                            <td style="padding: 0px;" width="5%"></td>
                        </tr>
                        <tr>
                            <td class="bor-btm3" align="center" colspan="8" style="font-size:11px;color: #fff;"><b>IN PAYMENT OF THE FOLLOWING SERVICE/ TRANSACTION/DESCRIPTION</b></td>
                            <td class="bor-btm3" align="center" colspan="2" style="font-size:11px;color: #fff;"><b>QTY</b></td>
                            <td class="bor-btm3" align="center" colspan="3" style="font-size:11px;color: #fff;"><b>UNIT PRICE</b></td>
                            <td class="bor-btm3" align="center" colspan="2" style="font-size:11px;color: #fff;"><b>AMOUNT P</b></td>
                            <td class="bor-btm3" colspan="5" rowspan="7" class="p-0">
                                <?php //foreach($collection AS $c){ 
                                    /*$total = $c['amount'] + $c['vat']; 
                                    $total_due = $total - $c['ewt'];
                                    $zero_rated = $c['zero_rated'] + $c['zero_rated_ecozone']; */
                                    $zero_rated = $sum_zero_rated + $sum_zero_rated_ecozone; 
                                    $total = $sum_amount +$zero_rated + $sum_vat; 
                                    $total_due = $total - $sum_ewt;
                                    
                                    
                                    //$total_last = $sum_amount +$zero_rated+ $sum_vat; 
                                ?>
                                <table width="100%" class="table-size" style="border: 0px solid #fff;">
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;" width="60%">TOTAL SALES <br> (VAT INCLUSIVE)</td>
                                        <td style="border: 0px solid #000;font-size: 10px;" width="40%" align="right"><?php echo number_format($total,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">AMOUNT: NET OF VAT</td>
                                        <td style="border: 0px solid #000;font-size: 10px;" align="right"><?php echo number_format($sum_amount,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">ADD: VAT</td>
                                        <td style="border: 0px solid #000;padding-top:3px;font-size: 10px;" align="right"><?php echo number_format($sum_vat,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">TOTAL</td>
                                        <td style="border: 0px solid #000;padding-top:10px;font-size: 10px;" align="right"><?php echo number_format($total,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">LESS WITHHOLDING</td>
                                        <td style="border: 0px solid #000;padding-top:10px;font-size: 10px;" align="right"><?php echo number_format($sum_ewt,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">TOTAL AMOUNT DUE</td>
                                        <td style="border: 0px solid #000;padding-top:5px;font-size: 10px;" align="right"><?php echo number_format($total_due,2); ?></td>
                                    </tr>

                                      <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;" width="60%"> VATABLE (V) </td>
                                        <td width="40%" align="right" style="font-size: 11px;padding-top:5px;"><?php echo number_format($sum_amount,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 10px;">VAT EXEMPT (E)</td>
                                        <td align="right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 10px;">ZERO-RATED (Z)</td>
                                        <td align="right"><?php echo number_format($zero_rated,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 10px;">VAT (12%) </td>
                                        <td align="right"><?php echo number_format($sum_vat,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0px solid #000;color: #fff;font-size: 12px;">TOTAL</td>
                                        <td align="right" style="font-size: 11px;"><?php echo number_format($total,2); ?></td>
                                    </tr>


                                    
                                </table>
                            <?php //} ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bor-btm3" colspan="8" align="right" style="font-size:12px;padding-top: 10px"><?php echo $ref_no; ?></td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2"></td>
                        </tr>
                    
                        <tr>
                            <td class="bor-btm3" colspan="8" align="right" style="font-size:12px">DEF. INTEREST</td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2" style="font-size:12px"><?php echo number_format($defint,2); ?></td>
                        </tr>
                        <tr>
                            <td class="bor-btm3" colspan="8" align="right" style="font-size:12px">ENERGY</td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2" style="font-size:12px"><?php echo number_format($sum_amount,2); ?></td>
                        </tr>
                        <tr>
                            <td class="bor-btm3" colspan="8" align="right" style="font-size:12px">VAT</td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2" style="font-size:12px"><?php echo number_format($sum_vat,2); ?></td>
                        </tr>
                        <tr>
                            <td class="bor-btm3" colspan="8"><br></td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2"></td>
                        </tr>
                      
                         <tr>
                            <td class="bor-btm3" colspan="8"><br></td>
                            <td class="bor-btm3" colspan="2"></td>
                            <td class="bor-btm3" colspan="3"></td>
                            <td class="bor-btm3" colspan="2"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="15"></td>
                <td colspan="5"><br></td>
            </tr>
            <!-- <tr>
                <td colspan="15"></td>
                <td colspan="5" class="bor-btm"><br></td>
            </tr>
            <tr>
                <td colspan="15"></td>
                <td colspan="5" align="center" ><span style="font-size:10px"><b>CASHIER/AUTHORIZED PERSON</b></span></td>
            </tr> -->
        </table>
    </div>
</page>

                
                                       
         