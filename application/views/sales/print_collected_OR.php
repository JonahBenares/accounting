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
    }
    .table-size2 td{
        font-size: 11px;
        padding: 0px;
    }
    .bor-btm2{
        border: 1px solid #000;
    }
</style>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <br>
        <br>
    </center>
</div>
<page size="OR">
    <table class="page-OR table-bordssered" width="100%" >
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
            <td colspan="20" align="left">                        
                <h4 class="mb-0" style="font-family: arial;font-stretch: condensed;font-size: 25px;font-weight: 700;">CENTRAL NEGROS POWER RELIABILTY, INC.</h4>
                <div style="font-size: 12px;margin-bottom: 0px;line-height: 15px;">
                    #88 ELOISA Q'S BLDG., COR. RIZAL-MABINI STS.<br>
                    BRGY. 22, BACOLOD CITY <br>
                    <b>VAT Reg. TIN: 008-691-287-00002</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="10" style="font-size: 12px;">TEL. NO. (034) 435-1932</td>
            <td colspan="1">DATE:</td>
            <td colspan="4" class="bor-btm"> <?php echo date("F j, Y", strtotime($date)); ?></td>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="20">
                <table width="100%" class="table-bordered table-size2">
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
                    <?php foreach($client AS $c){ ?>
                    <tr>
                        <td colspan="10"><b>CUSTOMER NAME:<span class="pl-2"><?php echo $c['client_name']; ?></span></b></td>
                        <td colspan="10"><b>ADDRESS:<span class="pl-2"><?php echo $c['address']; ?></span></b></td>
                    </tr>
                    <tr>
                        <td colspan="15"><b>BUSINESS STYLE:</b></td>
                        <td colspan="5"><b>TIN:<span class="pl-2"><?php echo $c['tin']; ?></span></b></td>
                    </tr>
                <?php } ?>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                <table width="100%" class="table-bosrdered " >
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
                        <td class="bor-btm2" align="center" colspan="8"><b>IN PAYMENT OF THE FOLLOWING SERVICE/ TRANSACTION/DESCRIPTION</b></td>
                        <td class="bor-btm2" align="center" colspan="2"><b>QTY</b></td>
                        <td class="bor-btm2" align="center" colspan="3"><b>UNIT PRICE</b></td>
                        <td class="bor-btm2" align="center" colspan="2"><b>AMOUNT P</b></td>
                        <td class="bor-btm2" colspan="5" rowspan="7" class="p-0">
                            <?php foreach($collection AS $c){ 
                                $total = $c->amount + $c->vat; 
                                $total_due = $total - $c->ewt;
                                $zero_rated = $c->zero_rated + $c->zero_rated_ecozone; ?>
                            <table width="100%" class="table-size" style="border: 0px solid #fff;">
                                <tr>
                                    <td style="border: 0px solid #000;" width="60%">TOTAL SALES <br> (VAT INCLUSIVE)</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" width="40%" align="right"><?php echo number_format($total,2); ?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px solid #000;">AMOUNT: NET OF VAT</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" align="right"><?php echo number_format($c->amount,2); ?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px solid #000;">ADD: VAT</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" align="right"><?php echo number_format($c->vat,2); ?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px solid #000;">TOTAL</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" align="right"><?php echo number_format($total,2); ?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px solid #000;">LESS WITHHOLDING</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" align="right"><?php echo number_format($c->ewt,2); ?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px solid #000;">TOTAL AMOUNT DUE</td>
                                    <td style="border: 0px solid #000;border-bottom: 1px solid #dee2e6;" align="right"><?php echo number_format($total_due,2); ?></td>
                                </tr>
                                 <tr>
                                    <td style="border: 0px solid #000;">
                                        VATABLE (V)<br>
                                        VAT EXEMPT (E) <br>
                                        ZERO-RATED (Z) <br>
                                        VAT (12%) <br>
                                        TOTAL
                                    </td>
                                    <td style="border: 0px solid #000;" align="right">
                                        <?php echo number_format($zero_rated,2); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                        </td>
                    </tr>
                      <tr>
                        <td class="bor-btm2" colspan="8" align="right"><?php echo $ref_no; ?></td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"></td>
                    </tr>
                
                    <tr>
                        <td class="bor-btm2" colspan="8" align="right">DEF. INTEREST</td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="bor-btm2" colspan="8" align="right">ENERGY</td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"><?php echo number_format($amount,2); ?></td>
                    </tr>
                    <tr>
                        <td class="bor-btm2" colspan="8" align="right">VAT</td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"><?php echo number_format($vat,2); ?></td>
                    </tr>
                    <tr>
                        <td class="bor-btm2" colspan="8"><br></td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"></td>
                    </tr>
                  
                     <tr>
                        <td class="bor-btm2" colspan="8"><br></td>
                        <td class="bor-btm2" colspan="2"></td>
                        <td class="bor-btm2" colspan="3"></td>
                        <td class="bor-btm2" colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="15"></td>
            <td colspan="5"><br></td>
        </tr>
        <tr>
            <td colspan="15"></td>
            <td colspan="5" class="bor-btm"><br></td>
        </tr>
        <tr>
            <td colspan="15"></td>
            <td colspan="5" align="center" ><span style="font-size:10px"><b>CASHIER/AUTHORIZED PERSON</b></span></td>
        </tr>
    </table>
</page>

                
                                       
         