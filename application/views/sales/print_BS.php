
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <center>
                        <button class="btn btn-primary btn-md">Print</button>
                    </center>
                    <br>
                    <page size="BS">
                        <table class="page-BS table-borddered" width="100%">
                            <tr>
                                <td width="5%"><br></td>
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
                                <td colspan="20" align="center">                        
                                    <h3 class="mb-0" style="font-family: arial;font-stretch: condensed;font-size: 30px;font-weight: 700;">CENTRAL NEGROS POWER RELIABILTY, INC.</h3>
                                    <div style="font-size: 12px;margin-bottom: 10px;">
                                        #88 ELOISA Q'S BLDG., COR. RIZAL-MABINI STS., BRGY. 22, BACOLOD CITY <br>
                                        <b>VAT Reg. TIN: 008-691-287-00002</b><br>
                                        TEL. NO. (034) 435-1932
                                        <br>
                                    </div>  
                                    <b style="font-family: times new roman;font-size: 16px;">BILLING STATEMENT</b>
                                    <br>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td>Billed to:</td>
                                <td colspan="13" class="bor-btm pl-2"><?php echo $company_name; ?></td>
                                <td></td>
                                <td>Date:</td>
                                <td colspan="3" class="bor-btm  pl-2"><?php echo date("F d,Y");?></td>
                            </tr>
                            <tr>
                                <td colspan="2">Business Style:</td>
                                <td colspan="8" class="bor-btm pl-2"></td>
                                <td>TIN</td>
                                <td colspan="3" class="bor-btm pl-2"><?php echo $tin; ?></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td colspan="13" class="bor-btm pl-2"><?php echo $address;?></td>
                            </tr>
                            <tr>
                                <td colspan="20">
                                    <br>
                                    <table width="100%" class="table-bordered">
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
                                            <td colspan="15" align="center"><b>PARTICULARS</b></td>
                                            <td colspan="5" align="center"><b>AMOUNT</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="15" align="center"><?php echo "Billing Charges for ".date("M d,Y",strtotime($billing_from))." to ".date("M d,Y",strtotime($billing_to))?></td>
                                            <td colspan="4"></td>
                                            <td colspan="1"></td>
                                        </tr>

                                        <tr>
                                            <td colspan="15" align="right">Vatable Sales</td>
                                            <td colspan="4" align="center"><?php echo "₱ ".$vat_sales_peso; ?></td>
                                            <td colspan="1"><?php echo $vat_sales_cents; ?></td>
                                        </tr>

                                        <?php if($zero_rated_peso!=0) { ?>
                                         <tr>
                                            <td colspan="15" align="right">Zero Rated</td>
                                            <td colspan="4" align="center"><?php echo "₱ ".$zero_rated_peso; ?></td>
                                            <td colspan="1"><?php echo $zero_rated_cents; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if($zero_rated_ecozones_peso!=0) { ?>
                                        <tr>
                                            <td colspan="15" align="right">Zero Rated Ecozones Sales</td>
                                            <td colspan="4" align="center"><?php echo "₱ ".$zero_rated_ecozones_peso; ?></td>
                                            <td colspan="1"><?php echo $zero_rated_ecozones_cents; ?></td>
                                        </tr>
                                        <?php } 
                                        if($vat_peso!=0) { ?>
                                         <tr>
                                            <td colspan="15" align="right">VAT</td>
                                            <td colspan="4" align="center"><?php echo "₱ ".$vat_peso; ?></td>
                                            <td colspan="1"><?php echo $vat_cents; ?></td>
                                        </tr>
                                        <?php }
                                        if($ewt_peso!=0) { ?>
                                        <tr>
                                            <td colspan="15" align="right">EWT</td>
                                            <td colspan="4" align="center"><?php echo "₱ (".$ewt_peso.")"; ?></td>
                                            <td colspan="1"><?php echo "(".$ewt_cents.")"; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="15"><br></td>
                                            <td colspan="4"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="15"><br></td>
                                            <td colspan="4"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="15"><br></td>
                                            <td colspan="4"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="15"><br></td>
                                            <td colspan="4"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="15" align="right" class="pr-2">
                                                <b>TOTAL AMOUNT DUE</b>
                                            </td>
                                            <td colspan="4" align="center"><?php echo "₱ ".$total_peso; ?></td>
                                            <td colspan="1"><?php echo $total_cents; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4"><b>AMOUNT IN WORDS:</b></td>
                                <td colspan="16" class="bor-btm"><?php echo ($total_amount!=0) ? $amount_words : ''; ?></td>
                            </tr>
                            <tr>
                                <td colspan="14"></td>
                                <td colspan="6"><br></td>
                            </tr>
                            <tr>
                                <td colspan="14"></td>
                                <td colspan="6">Certified Correct:</td>
                            </tr>
                            <tr>
                                <td colspan="14"></td>
                                <td colspan="5" class="bor-btm"><br></td>
                                <td colspan="1"></td>
                            </tr>
                            <tr>
                                <td colspan="14"></td>
                                <td colspan="5" align="center">Authorized Signature</td>
                                <td colspan="1"></td>
                            </tr>
                        </table>
                    </page>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         