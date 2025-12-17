<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
        window.close();
      window.history.back();
    }
</script>

<style type="text/css">
    

    @media print{
        .first-main{
            position: relative;
        }
        .main{
            position: absolute;
            top:0px;
            left:-500px;
            page-break-after: always!important;
        }
        .cusname{
            position: absolute;
            left:560px;
            top:118px;
            width: 400px;
            height: 38px;
            /* background: #7fcf7f8c; */
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
            top:460px;
            left:680px;
            width: 130px;
            /* background: #7fcf7f8c; */
            font-size: 12px;
            line-height: 14px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;    
            letter-spacing: 1px;
            text-align: center;
        }
        .address{
            position: absolute;
            top:158px;
            left:560px;
            width: 400px;
            height: 38px;
            /* background: #7fef7f8c; */
            font-size: 12px;
            line-height: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
        }
        .tin{
            position: absolute;
            top:199px;
            left:842px;
            padding-right: 10px;
            width: 135px;
            height: 15px;
            /* background: #7fef7f8c; */
            font-size: 11px;
            line-height: 14px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: left;
        }
        .ref{
            position: absolute;
            left:670px;
            top:250px;
            /* background: #7fcf7f8c; */
            font-size: 12px;
            line-height: 15px;
            width: 140px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .desc{
            position: absolute;
            left:670px;
            top:280px;
            padding-left:3px;
            /* background: #7fcf7f8c; */
            font-size: 11px;
            line-height: 15px;
            width: 140px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: left;
        }
        .amount{
            position: absolute;
            left:840px;
            top:280px;
            /* background: #7fcf7f8c; */
            font-size: 11px;
            line-height: 15px;
            width: 120px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .totalamount{
            position: absolute;
            left:835px;
            top:420px;
            /* background: #7fcf7f8c; */
            font-size: 11px;
            line-height: 15px;
            width: 120px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .sales{
            position: absolute;
            left:600px;
            top:225px;
            /* background: #7fcf7f8c; */
            font-size: 11px;
            line-height: 10px;
            width: 65px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .marg-5{
            margin: 5px!important;
        }
        .px-5{
            padding: 0px 5px;
        }
        .pb-10{
            padding-bottom: 10px ;
        }
        .pb-5{
            padding-bottom: 5px ;
        }
        .pt-2{
            padding-top: 2px ;
        }
        .font-10{
            font-size: 9px!important;
        }
        .esig{
            position: absolute;
            top:410px!important;
            left: 780px!important;
        }

        .pb-1  { padding-bottom: 0.25rem; }
        .pb-1r  { padding-bottom: 0.38rem; }
        .pb-2  { padding-bottom: 0.5rem; }
        .pb-3  { padding-bottom: 0.75rem; }
        .pb-4  { padding-bottom: 1rem; }
        .pb-5  { padding-bottom: 1.25rem; }
        .pb-6  { padding-bottom: 1.5rem; }
        .pb-7  { padding-bottom: 1.75rem; }
        .pb-8  { padding-bottom: 2rem; }
        .pb-9  { padding-bottom: 2.25rem; }
        .pb-10 { padding-bottom: 2.5rem; }

        .pt-1  { padding-top: 0.25rem; }
        .pt-2  { padding-top: 0.5rem; }
        .pt-3  { padding-top: 0.75rem; }
        .pt-4  { padding-top: 1rem; }
        .pt-5  { padding-top: 1.25rem; }
        .pt-6  { padding-top: 1.5rem; }
        .pt-7  { padding-top: 1.75rem; }
        .pt-8  { padding-top: 2rem; }
        .pt-9  { padding-top: 2.25rem; }
        .pt-10 { padding-top: 2.5rem; }
    }   
    .esig{
        position: absolute;
        top:385px;
        /* top:390px; */
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
<?php for($x=0;$x<$count;$x++){ ?>
<page size="A4" class="first-main">
    <div class="main" >
        <img class="esig" src="<?php echo base_url()."assets/img/sign_DeLosSantos.png" ?>" style="width: 180px;">
        <div class="cusname"><?php echo $company_name[$x]; ?></div>
        <div class="date"><?php echo date("M d,Y",strtotime($transaction_date[$x])); ?></div>
        <div class="tin"><?php echo $tin[$x]; ?></div>
        <div class="address"><?php echo $address[$x];?></div>

        <div class="ref"><?php echo $reference_number[$x]; ?></div>
        <div class="desc">
            <div class="marg-5">Vatable Sales</div>
            <div class="marg-5">Zero Rated Ecozones Sales</div>
            <div class="marg-5">VAT</div>
        </div>
        <div class="amount">
            <div class="marg-5"><?php echo "₱ ".number_format($total_vs[$x],2); ?></div>
            <div class="marg-5"><?php echo "₱ ".number_format($total_zra[$x],2); ?></div>
            <div class="marg-5"><?php echo "₱ ".number_format($total_vos[$x],2); ?></div>
        </div>
        <div class="totalamount">₱ 123.00</div>
        <?php
            $total_sales = $total_vs[$x] + $total_zra[$x] + $total_vos[$x];
            $net_of_vat = $total_vs[$x] + $total_zra[$x];
            $total_amount_due = ($total_vs[$x] + $total_zra[$x] + $total_vos[$x]) - $total_ewt[$x];
            
        ?>
        <div class="sales">
            <div class="pt-2 pb-1r"> <!--Vatable Sales -->2,790.52</div>
            <div class="pt-2 pb-1r"> <!--VAT Exempt Sales -->2,491.4</div>
            <div class="pt-2 pb-1r"> <!--Zero-Rated Sales -->298.98</div>
            <div class="pt-2 pb-1r"> <!--Vat Amount -->2,790.52</div>
            <div class="pt-2 pb-1r"> <!--Total Sales -->2,740.69</div>
            <div class="pt-2 pb-1r"> <!--Less VAT -->(49.83)</div>
            <div class="pt-2 pb-1r"> <!-- Amount Net of VAT  -->2,491.104</div>
            <div class="pt-2 pb-1r"> <!-- Less Withholding Tax --> 0.00</div>
        </div>

    </div>
</page>
<?php } ?>
                
                                       
         