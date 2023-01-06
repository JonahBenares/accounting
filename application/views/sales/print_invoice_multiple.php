<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
      window.close();
      window.history.back();
    }
</script>
<style type="text/css">
	
</style>
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
<page size="A4">
    <div style="margin-left: 20px;margin-right: 75px;">
        <table class="table-bordesred" width="100%" style="font-size:16px">
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
                    <h3 class="m-b-0" style="font-family: arial;font-stretch: condensed;font-size: 25px;font-weight: 700;color:#fff">CENTRAL NEGROS POWER RELIABILTY, INC.</h3>
                    <div style="font-size: 12px;margin-bottom: 5px;line-height: 1.4;color:#fff">
                        #88 ELOISA Q'S BLDG., COR. RIZAL-MABINI STS., BRGY. 22, BACOLOD CITY <br>
                        <b>VAT Reg. TIN: 008-691-287-00002</b><br>
                        TEL. NO. (034) 435-1932
                    </div>  
                    <b style="font-family: times new roman;font-size: 16px;color:#fff">BILLING STATEMENT</b>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="color:#fff">Billed to:</td>
                <td colspan="12" class=" pl-2" style="font-size:13px"><?php echo $company_name[$x]; ?></td>
                <td></td>
                <td></td>
                <td style="color:#fff">Date:</td>
                <!-- <td colspan="3" class="  pl-2"><?php echo date("M d,Y");?></td> -->
                <td colspan="3" class="  pl-2"><?php echo date("M d,Y",strtotime($transaction_date[$x])); ?></td>
            </tr>
            <tr>
                <td colspan="3" style="color:#fff">Business Style:</td>
                <td colspan="8" class=" pl-2"></td>
                <td style="color:#fff">TIN:</td>
                <td colspan="4" class=" pl-2" style="font-size:13px"><?php echo $tin[$x]; ?></td>
            </tr>
            <tr>
                <td colspan="3" style="color:#fff">Address:</td>
                <td colspan="13" class=" pl-2" style="font-size:13px;padding-top:10px"><?php echo $address[$x];?></td>
            </tr>
        </table>
        <div class="particulars">   
            <table width="140%" class="table-borddered" >
                <tr>
                    <td style="padding: 0px;" width="6%"></td>
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
                    <td style="padding: 0px;" width="4%"></td>
                </tr>
                <tr colspan="20"><br><br><br></tr>
                <tr>
                    <td colspan="15" align="center" style="color:#fff"><b>PARTICULARS</b></td>
                    <td colspan="5" align="center" style="color:#fff"><b>AMOUNT</b></td>
                </tr>
                <tr>
                    <td colspan="15" align="center" style="font-size:13px"><?php echo "Billing Charges for ".date("M d,Y",strtotime($billing_from[$x]))." to ".date("M d,Y",strtotime($billing_to[$x]))?></td>
                    <td colspan="4"></td>
                    <td colspan="1"></td>
                </tr>

                <tr>
                    <td colspan="15" align="right" style="font-size:13px">Vatable Sales</td>
                    <?php if($participant_id[$x]==$participant_id_sub[$x]){ ?>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($vat_sales_peso_sub[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $vat_sales_cents_sub[$x]; ?></td>
                    <?php }else{ ?>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($vat_sales_peso[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $vat_sales_cents[$x]; ?></td>
                    <?php } ?>
                </tr>

                <?php 
                    if($zero_rated_peso[$x]!=0 || $zero_rated_cents[$x] != 0) { 
                        if($participant_id[$x]!=$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">Zero Rated</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($zero_rated_peso[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $zero_rated_cents[$x]; ?></td>
                </tr>
                <?php } } if($zero_rated_peso_sub[$x]!=0 || $zero_rated_cents_sub[$x] != 0){ 
                    if($participant_id[$x]==$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">Zero Rated</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($zero_rated_peso_sub[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $zero_rated_cents_sub[$x]; ?></td>
                </tr>
                <?php } } ?>
                <?php 
                    if($zero_rated_ecozones_peso[$x]!=0 || $zero_rated_ecozones_cents[$x] != 0) { 
                        if($participant_id[$x]!=$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">Zero Rated Ecozones Sales</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($zero_rated_ecozones_peso[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $zero_rated_ecozones_cents[$x]; ?></td>
                </tr>
                <?php } } if($zero_rated_ecozones_peso_sub[$x]!=0 || $zero_rated_ecozones_cents_sub[$x] != 0){ 
                    if($participant_id[$x]==$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">Zero Rated Ecozones Sales</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($zero_rated_ecozones_peso_sub[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $zero_rated_ecozones_cents_sub[$x]; ?></td>
                </tr>
                <?php 
                    } }
                ?>
                <?php
                    if($vat_peso[$x]!=0  || $vat_cents[$x]!=0) { 
                        if($participant_id[$x]!=$participant_id_sub[$x]){
                ?>
                 <tr>
                    <td colspan="15" align="right" style="font-size:13px">VAT</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($vat_peso[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $vat_cents[$x]; ?></td>
                </tr>
                <?php } } if($vat_peso_sub[$x]!=0  || $vat_cents_sub[$x] != 0){ 
                    if($participant_id[$x]==$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">VAT</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($vat_peso_sub[$x],0); ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo $vat_cents_sub[$x]; ?></td>
                </tr>
                <?php } } ?>
                <?php 
                    if($ewt_peso[$x]!=0 || $ewt_cents[$x] != 0) { 
                        if($participant_id[$x]!=$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">EWT</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ (".number_format($ewt_peso[$x],0).")"; ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo "(".$ewt_cents[$x].")"; ?></td>
                </tr>
                <?php } } if($ewt_peso_sub[$x]!=0 || $ewt_cents_sub[$x] != 0){ 
                    if($participant_id[$x]==$participant_id_sub[$x]){
                ?>
                <tr>
                    <td colspan="15" align="right" style="font-size:13px">EWT</td>
                    <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ (".number_format($ewt_peso_sub[$x],0).")"; ?></td>
                    <td colspan="1" align="center" style="font-size:13px"><?php echo "(".$ewt_cents_sub[$x].")"; ?></td>
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
                <tr>
                    <td colspan="15"></td>
                    <td colspan="4"></td>
                    <td colspan="1"></td>
                </tr>
                <?php /*if($total_cents_sub[$x] < 10 || $total_cents[$x] < 10){
                    $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_LEFT);
                    $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_LEFT);
                } else {
                    $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_RIGHT);
                    $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_RIGHT);
                } */ 

                $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_RIGHT);
                    $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_RIGHT);
                    ?>
                <tr>
                    <td colspan="15" align="right"  class="pr-2" style="color:#fff"> 
                        <b>TOTAL AMOUNT DUE</b>
                    </td>
                    <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
                        <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($total_peso[$x],0); ?></td>
                        <td colspan="1"><?php echo $cents; ?></td>
                    <?php } else{ ?>
                        <td colspan="4" align="center" style="font-size:13px"><?php echo "₱ ".number_format($total_peso_sub[$x],0); ?></td>
                        <td colspan="1"><?php echo $cents_sub; ?></td>
                    <?php } ?>
                </tr>
            </table>
            <table width="100%" class="table-borddered">
                <tr>
                    <td colspan="5" style="color:#fff"><b>AMOUNT IN WORDS:</b></td>
                    <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
                    <td colspan="15" class="" style="font-size: 10px; padding-top: 5px;"><?php echo ($total_amount[$x]!=0) ? $amount_words[$x] : ''; ?></td>
                    <?php } else { ?>
                    <td colspan="15" class="" style="font-size: 10px; padding-top: 5px;"><?php echo ($total_amount_sub[$x]!=0) ? $amount_words_sub[$x] : ''; ?></td>
                    <?php } ?>
                </tr>
            </table>
        
            <?php //if(!empty($_SESSION['user_signature'])){ ?>
                <div class="esig">
                    <img src="<?php echo base_url()."uploads/".$user_signature; ?>" style="width:100px">
                </div>
            <?php //} ?>
        </div>
    </div>
</page>
<?php } ?>