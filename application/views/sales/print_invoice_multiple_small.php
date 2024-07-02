<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
      window.close();
      window.history.back();
    }
</script>
<style type="text/css">
	@media print{
        .company{
            position: absolute;
            left:20px;
            top:70px;
            width: 500px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            
        }
        .date{
            position: absolute;
            left:607px;
            top:70px;
            width: 100px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            
        }
        .tin{
            position: absolute;
            left:410px;
            top:98px;
            width: 125px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .address{
            position: absolute;
            left:20px;
            top:115px;
            width: 500px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            
        }
        .charges{
            position: absolute;
            left:0px;
            top:165px;
            width: 350px;
            line-height: 15px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
        }
        .vatable{
            position: absolute;
            left:43px;
            top:158px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .vatable_1{
            position: absolute;
            left:563px;
            top:158px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .vatable_2{
            position: absolute;
            left:669px;
            top:158px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
            
        }
        .zero_rated{
            position: absolute;
            left:43px;
            top:171px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;   
                 
        }
        .zero_rated_1{
            position: absolute;
            left:563px;
            top:171px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .zero_rated_2{
            position: absolute;
            left:669px;
            top:171px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
            
        }



        .zero_rated_sub{
            position: absolute;
            left:43px;
            top:171px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;   
             
        }
        .zero_rated_sub_1{
            position: absolute;
            left:563px;
            top:171px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .zero_rated_sub_2{
            position: absolute;
            left:669px;
            top:171px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
            
        }

        .ecozone{
            position: absolute;
            left:43px;
            top:184px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;   
             
        }
        .ecozone_1{
            position: absolute;
            left:563px;
            top:184px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .ecozone_2{
            position: absolute;
            left:669px;
            top:184px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
            
        }

        .ecozone_sub{
            position: absolute;
            left:43px;
            top:184px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;   
             
        }
        .ecozone_sub_1{
            position: absolute;
            left:563px;
            top:184px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
            
        }
        .ecozone_sub_2{
            position: absolute;
            left:669px;
            top:184px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
            
        }

        

        
        .vat{
            position: absolute;
            left:43px;
            top:197px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;  
        }
        .vat_1{
            position: absolute;
            left:563px;
            top:197px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .vat_2{
            position: absolute;
            left:669px;
            top:197px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
        }

        .vat_sub{
            position: absolute;
            left:43px;
            top:197px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;  
        }
        .vat_sub_1{
            position: absolute;
            left:563px;
            top:197px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .vat_sub_2{
            position: absolute;
            left:669px;
            top:197px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
        }

        .ewt{
            position: absolute;
            left:43px;
            top:210px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;  
        }
        .ewt_1{
            position: absolute;
            left:563px;
            top:210px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .ewt_2{
            position: absolute;
            left:669px;
            top:210px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
        }

        .ewt_sub{
            position: absolute;
            left:43px;
            top:210px;
            width: 500px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;  
        }
        .ewt_sub_1{
            position: absolute;
            left:563px;
            top:210px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .ewt_sub_2{
            position: absolute;
            left:669px;
            top:210px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;
        }

        .total_1{
            position: absolute;
            left:563px;
            top:224px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .total_2{
            position: absolute;
            left:673px;
            top:224px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;        
        }

        .total_sub_1{
            position: absolute;
            left:563px;
            top:224px;
            width: 100px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .total_sub_2{
            position: absolute;
            left:673px;
            top:224px;
            width: 30px;
            line-height: 13px;
            font-size: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: center;        
        }

        
        .amount_1{
            position: absolute;
            left:120px;
            top:250px;
            width: 570px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: left;
        }
        .amount_2{
            position: absolute;
            left:120px;
            top:250px;
            width: 570px;
            line-height: 13px;
            font-size: 13px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: left;
        }
        .esig{
            position:absolute;
            left:500px;
            top:240px;
        }
        .flex{
            display: flex;
            justify-content: space-between;
        }
    }
    .esig{
        position:absolute;
            left:500px;
            top:240px;
        }
    }
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
    <img class="esig" src="<?php echo base_url()."uploads/".$user_signature; ?>" style="width: 100px;">
    <div class="company"><?php echo $company_name[$x]; ?></div>
    <div class="date"><?php echo date("M d,Y",strtotime($transaction_date[$x])); ?></div>
    <div class="tin"><?php echo $tin[$x]; ?></div>
    <div class="address"><?php echo $address[$x];?></div>
    <div class="charges"><?php echo "Billing Charges for ".date("M d,Y",strtotime($billing_from[$x]))." to ".date("M d,Y",strtotime($billing_to[$x]))?></div>

    <!-- //vatable sales -->
    <div class="vatable">Vatable Sales</div>
    <?php if($participant_id[$x]==$participant_id_sub[$x]){ ?>
        <div class="vatable_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($vat_sales_peso_sub[$x],0); ?></span>
            </div>
        </div>
        <div class="vatable_2"><?php echo $vat_sales_cents_sub[$x]; ?></div>
    <?php }else{ ?>
        <div class="vatable_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($vat_sales_peso[$x],0); ?></span>
            </div>
        </div>
        <div class="vatable_2"><?php echo $vat_sales_cents[$x]; ?></div>
    <?php } ?>


    <!-- //zero_rated -->
    <!-- <div class="zero_rated_sub">Zero Rated</div>
    <div class="zero_rated_sub_1">
        <div class="flex">
            <span>₱</span>
            <span>0909</span>
        </div>
    </div>
    <div class="zero_rated_sub_2">09</div> -->
    <?php 
        if($zero_rated_peso[$x]!=0 || $zero_rated_cents[$x] != 0) { 
            if($participant_id[$x]!=$participant_id_sub[$x]){
    ?>
        <div class="zero_rated">Zero Rated</div>
        <div class="zero_rated_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($zero_rated_peso[$x],0); ?></span>
            </div>
        </div>
        <div class="zero_rated_2"><?php echo $zero_rated_cents[$x]; ?></div>
    <?php } } ?>

    <?php if($zero_rated_peso_sub[$x]!=0 || $zero_rated_cents_sub[$x] != 0){ 
        if($participant_id[$x]==$participant_id_sub[$x]){
    ?>
        <div class="zero_rated_sub">Zero Rated</div>
        <div class="zero_rated_sub_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($zero_rated_peso_sub[$x],0); ?></span>
            </div>
        </div>
        <div class="zero_rated_sub_2"><?php echo $zero_rated_cents_sub[$x]; ?></div>
    <?php } } ?>

    <!-- //Zero Rated Ecozones Sales -->
    <!-- <div class="ecozone_sub">Zero Rated Ecozones Sales</div>
    <div class="ecozone_sub_1">
        <div class="flex">
            <span>₱</span>
            <span>8988</span>
        </div>
    </div>
    <div class="ecozone_sub_2">21</div> -->
    <?php 
        if($zero_rated_ecozones_peso[$x]!=0 || $zero_rated_ecozones_cents[$x] != 0) { 
            if($participant_id[$x]!=$participant_id_sub[$x]){
    ?>
        <div class="ecozone">Zero Rated Ecozones Sales</div>
        <div class="ecozone_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($zero_rated_ecozones_peso[$x],0); ?></span>
            </div>
        </div>
        <div class="ecozone_2"><?php echo $zero_rated_ecozones_cents[$x]; ?></div>
    <?php } } ?>

    <?php if($zero_rated_ecozones_peso_sub[$x]!=0 || $zero_rated_ecozones_cents_sub[$x] != 0){ 
        if($participant_id[$x]==$participant_id_sub[$x]){
    ?>
        <div class="ecozone_sub">Zero Rated Ecozones Sales</div>
        <div class="ecozone_sub_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($zero_rated_ecozones_peso_sub[$x],0); ?></span>
            </div>
        </div>
        <div class="ecozone_sub_2"><?php echo $zero_rated_ecozones_cents_sub[$x]; ?></div>
    </tr>
    <?php  } } ?>
               
    <!-- //Vat  -->
    <?php
        if($vat_peso[$x]!=0  || $vat_cents[$x]!=0) { 
            if($participant_id[$x]!=$participant_id_sub[$x]){
    ?>
        <div class="vat">VAT</div>
        <div class="vat_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($vat_peso[$x],0); ?></span>
            </div>                
        </div>
        <div class="vat_2"><?php echo $vat_cents[$x]; ?></div>
    <?php } } if($vat_peso_sub[$x]!=0  || $vat_cents_sub[$x] != 0){ 
        if($participant_id[$x]==$participant_id_sub[$x]){
    ?>
        <div class="vat_sub">VAT</div>
        <div class="vat_sub_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($vat_peso_sub[$x],0); ?></span>
            </div>  
        </div>
        <div class="vat_sub_2"><?php echo $vat_cents_sub[$x]; ?></div>
    <?php } } ?> 

    
    <?php 
        if($ewt_peso[$x]!=0 || $ewt_cents[$x] != 0) { 
            if($participant_id[$x]!=$participant_id_sub[$x]){
    ?>
        <div class="ewt">EWT</div>
        <div class="ewt_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($ewt_peso[$x],0); ?></span>
            </div>         </div>
        <div class="ewt_2"><?php echo "(".$ewt_cents[$x].")"; ?></div>
    <?php } } if($ewt_peso_sub[$x]!=0 || $ewt_cents_sub[$x] != 0){ 
        if($participant_id[$x]==$participant_id_sub[$x]){
    ?>
        <div class="ewt_sub">EWT</div>
        <div class="ewt_sub_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($ewt_peso_sub[$x],0); ?></span>
            </div>
        </div>
        <div class="ewt_sub_2"><?php echo "(".$ewt_cents_sub[$x].")"; ?></div>
    <?php } } ?> 

    <?php  
        $cents_sub = str_pad($total_cents_sub[$x], '2', '0', STR_PAD_RIGHT);
        $cents = str_pad($total_cents[$x], '2', '0', STR_PAD_RIGHT);
    ?>

    <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
        <div class="total_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($total_peso[$x],0); ?></span>
            </div>
        </div>
        <div class="total_2"><?php echo $cents; ?></div>
    <?php } else{ ?>
        <div class="total_sub_1">
            <div class="flex">
                <span>₱</span>
                <span><?php echo number_format($total_peso_sub[$x],0); ?></span>
            </div>
        </div>
        <div class="total_sub_2"><?php echo $cents_sub; ?></div>
    <?php } ?>

    <?php if($participant_id[$x]!=$participant_id_sub[$x]){ ?>
        <div class="amount_1"><?php echo ($total_amount[$x]!=0) ? $amount_words[$x] : ''; ?></div>
    <?php } else { ?>
        <div class="amount_2"><?php echo ($total_amount_sub[$x]!=0) ? $amount_words_sub[$x] : ''; ?></div>
    <?php } ?>

    


</page>
<?php } ?>