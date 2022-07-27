<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
      window.close();
      window.history.back();
    }
</script>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <!-- <a href='<?php echo base_url(); ?>sales/print_invoice/<?php echo $sales_detail_id ?>' class="btn btn-primary button">Invoice</a>  -->
        <br>
        <br>
    </center>
</div>
<?php for($x=0;$x<$count;$x++){ ?>
<page size="BS">
    <table class="table-bordersed" width="100%" style="font-size:13px">
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
            <td colspan="3">Billed to:</td>
            <td colspan="12" class="bor-btm pl-2"><?php echo $company_name[$x]; ?></td>
            <td></td>
            <td>Date:</td>
            <td colspan="3" class="bor-btm  pl-2"><?php echo date("F d,Y");?></td>
        </tr>
        <tr>
            <td colspan="3">Business Style:</td>
            <td colspan="9" class="bor-btm pl-2"></td>
            <td>TIN:</td>
            <td colspan="3" class="bor-btm pl-2"><?php echo $tin[$x]; ?></td>
        </tr>
        <tr>
            <td colspan="3">Address:</td>
            <td colspan="13" class="bor-btm pl-2"><?php echo $address[$x];?></td>
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
                        <td colspan="15" align="center"><?php echo "Billing Charges for ".date("M d,Y",strtotime($billing_from[$x]))." to ".date("M d,Y",strtotime($billing_to[$x]))?></td>
                        <td colspan="4"></td>
                        <td colspan="1"></td>
                    </tr>

                    <tr>
                        <td colspan="15" align="right">Vatable Sales</td>
                        <?php if($participant_id[$x]==$participant_id_sub[$x]){ ?>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($vat_sales_peso_sub[$x],0); ?></td>
                        <td colspan="1"><?php echo $vat_sales_cents_sub[$x]; ?></td>
                        <?php }else{ ?>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($vat_sales_peso[$x],0); ?></td>
                        <td colspan="1"><?php echo $vat_sales_cents[$x]; ?></td>
                        <?php } ?>
                    </tr>

                    <?php 
                        if($zero_rated_peso[$x]!=0 || $zero_rated_cents[$x] != 0) { 
                            if($participant_id[$x]!=$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">Zero Rated</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($zero_rated_peso[$x],0); ?></td>
                        <td colspan="1"><?php echo $zero_rated_cents[$x]; ?></td>
                    </tr>
                    <?php } } if($zero_rated_peso_sub[$x]!=0 || $zero_rated_cents_sub[$x] != 0){ 
                        if($participant_id[$x]==$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">Zero Rated</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($zero_rated_peso_sub[$x],0); ?></td>
                        <td colspan="1"><?php echo $zero_rated_cents_sub[$x]; ?></td>
                    </tr>
                    <?php } } ?>
                    <?php 
                        if($zero_rated_ecozones_peso[$x]!=0 || $zero_rated_ecozones_cents[$x] != 0) { 
                            if($participant_id[$x]!=$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">Zero Rated Ecozones Sales</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($zero_rated_ecozones_peso[$x],0); ?></td>
                        <td colspan="1"><?php echo $zero_rated_ecozones_cents[$x]; ?></td>
                    </tr>
                    <?php } } if($zero_rated_ecozones_peso_sub[$x]!=0 || $zero_rated_ecozones_cents_sub[$x] != 0){ 
                        if($participant_id[$x]==$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">Zero Rated Ecozones Sales</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($zero_rated_ecozones_peso_sub[$x],0); ?></td>
                        <td colspan="1"><?php echo $zero_rated_ecozones_cents_sub[$x]; ?></td>
                    </tr>
                    <?php 
                        } }
                    ?>
                    <?php
                        if($vat_peso[$x]!=0  || $vat_cents[$x]!=0) { 
                            if($participant_id[$x]!=$participant_id_sub[$x]){
                    ?>
                     <tr>
                        <td colspan="15" align="right">VAT</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($vat_peso[$x],0); ?></td>
                        <td colspan="1"><?php echo $vat_cents[$x]; ?></td>
                    </tr>
                    <?php } } if($vat_peso_sub[$x]!=0  || $vat_cents_sub[$x] != 0){ 
                        if($participant_id[$x]==$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">VAT</td>
                        <td colspan="4" align="center"><?php echo "₱ ".number_format($vat_peso_sub[$x],0); ?></td>
                        <td colspan="1"><?php echo $vat_cents_sub[$x]; ?></td>
                    </tr>
                    <?php } } ?>
                    <?php 
                        if($ewt_peso[$x]!=0 || $ewt_cents[$x] != 0) { 
                            if($participant_id[$x]!=$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">EWT</td>
                        <td colspan="4" align="center"><?php echo "₱ (".number_format($ewt_peso[$x],0).")"; ?></td>
                        <td colspan="1"><?php echo "(".$ewt_cents[$x].")"; ?></td>
                    </tr>
                    <?php } } if($ewt_peso_sub[$x]!=0 || $ewt_cents_sub[$x] != 0){ 
                        if($participant_id[$x]==$participant_id_sub[$x]){
                    ?>
                    <tr>
                        <td colspan="15" align="right">EWT</td>
                        <td colspan="4" align="center"><?php echo "₱ (".number_format($ewt_peso_sub[$x],0).")"; ?></td>
                        <td colspan="1"><?php echo "(".$ewt_cents_sub[$x].")"; ?></td>
                    </tr>
                    <?php } } ?>
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
                    <?php if($total_cents_sub[$x] < 10 || $total_cents[$x] < 10){
                        $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_LEFT);
                        $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_LEFT);
                    } else {
                        $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_RIGHT);
                        $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_RIGHT);
                    } ?>
                    <tr>
                        <td colspan="15" align="right" class="pr-2">
                            <b>TOTAL AMOUNT DUE</b>
                        </td>
                        <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
                            <td colspan="4" align="center"><?php echo "₱ ".number_format($total_peso[$x],0); ?></td>
                            <td colspan="1"><?php echo $cents; ?></td>
                        <?php } else{ ?>
                            <td colspan="4" align="center"><?php echo "₱ ".number_format($total_peso_sub[$x],0); ?></td>
                            <td colspan="1"><?php echo $cents_sub; ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4"><b>AMOUNT IN WORDS:</b></td>
            <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
            <td colspan="16" class="bor-btm"><?php echo ($total_amount[$x]!=0) ? $amount_words[$x] : ''; ?></td>
            <?php } else { ?>
            <td colspan="16" class="bor-btm"><?php echo ($total_amount_sub[$x]!=0) ? $amount_words_sub[$x] : ''; ?></td>
            <?php } ?>
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
<?php } ?>