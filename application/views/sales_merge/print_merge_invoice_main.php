<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<script>
    function goBack() {
        window.close();
      window.history.back();
    }
</script>

<style type="text/css">
    .first-main{
            position: relative;
        }
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
    .cusname{
        position: absolute;
        left:350px;
        top:180px;
        width: 340px;
/*        background: #7fff7f8c;*/
        font-size: 12px;
        line-height: 14px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px;
    }
    .date{
        position: absolute;
        top:150px;
        left:650px;
        width: 200px;
/*        background: #7fcf7f8c;*/
        font-size: 12px;
        line-height: 14px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;    
    }
    .address{
        position: absolute;
        top:180px;
        left:700px;
        width: 270px;
/*        background: #7fef7f8c;*/
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
        top:220px;
        left:700px;
        width: 270px;
/*        background: #7fef7f8c;*/
        font-size: 12px;
        line-height: 14px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px;
    }
    .ref{
        position: absolute;
        left:300px;
        top:235px;
/*            background: #7fcf7f8c;*/
        font-size: 12px;
        line-height: 15px;
        width: 200px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px; 
    }
    .desc{
        position: absolute;
        left:300px;
        top:250px;
/*        background: #7fef7f8c;*/
        font-size: 12px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px; 
    }
    .amount{
        position: absolute;
        left:650px;
        top:250px;
/*        background: #7fef7f8c;*/
        font-size: 12px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px; 
        text-align: right;
    }
    .sales{
        position: absolute;
        left:980px;
        top:250px;
/*        background: #7fef7f8c;*/
        font-size: 11px;
        font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        font-weight: 400;
        font-style: normal; 
        font-variant: normal;
        letter-spacing: 1px; 
    }
    .padd-5{
        padding: 5px;
    }
    @media print{
        .first-main{
            position: relative;
        }
        .main{
            position: absolute;
            top:30px;
        }
        .cusname{
            position: absolute;
            left:90px;
            top:79px;
            width: 340px;
/*            background: #7fcf7f8c;*/
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
            top:50px;
            left:400px;
            width: 160px;
/*            background: #7fcf7f8c;*/
            font-size: 12px;
            line-height: 14px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;    
            letter-spacing: 1px;
        }
        .address{
            position: absolute;
            top:70px;
            left:400px;
            width: 310px;
            /* background: #7fef7f8c; */
            font-size: 11px;
            line-height: 12px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
        }
        .tin{
            position: absolute;
            top:105px;
            left:470px;
            padding-right: 10px;
            width: 250px;
/*            background: #7fef7f8c;*/
            font-size: 11px;
            line-height: 14px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px;
            text-align: right;
        }
        .ref{
            position: absolute;
            left:0px;
            top:165px;
/*            background: #7fcf7f8c;*/
            font-size: 12px;
            line-height: 15px;
            width: 200px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .desc{
            position: absolute;
            left:10px;
            top:185px;
           /* background: #7fcf7f8c; */
            font-size: 12px;
            line-height: 15px;
            width: 200px;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            font-weight: 400;
            font-style: normal; 
            font-variant: normal;
            letter-spacing: 1px; 
            text-align: right;
        }
        .amount{
            position: absolute;
            left:410px;
            top:185px;
           /* background: #7fcf7f8c; */
            font-size: 12px;
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
            left:623px;
            top:135px;
/*            background: #7fcf7f8c;*/
            font-size: 11px;
            line-height: 10px;
            width: 100px;
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
            top:270px!important;
            left: 560px!important;
        }
    }   
    .esig{
        position: absolute;
        top:385px;
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
    <div class="main">
        <img class="esig" src="<?php echo base_url()."assets/img/sign_DeLosSantos.png" ?>" style="width: 180px;">
        <div class="cusname"><?php echo $company_name[$x]; ?></div>
        <div class="date"><?php echo date("M d,Y",strtotime($transaction_date[$x])); ?></div>
        <div class="tin"><?php echo $tin[$x]; ?></div>
        <div class="address"><?php echo $address[$x];?></div>

        <div class="ref px-5"><?php echo "Energy Sales for the month of ".date("F Y",strtotime($billing_to[$x])) ?></div>
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
        <?php
            $total_sales = $total_vs[$x] + $total_zra[$x] + $total_vos[$x];
            $net_of_vat = $total_vs[$x] + $total_zra[$x];
            $total_amount_due = ($total_vs[$x] + $total_zra[$x] + $total_vos[$x]) - $total_ewt[$x];
        ?>
        <div class="sales">
            <div class="pb-10 pt-2"> <!--TOTAL SALES (VAT INCLUSIVE) --><?php echo number_format($total_sales,2); ?></div>
            <div class="pb-10"> <!--AMOUNT: NET OF VAT --><?php echo number_format($net_of_vat,2); ?></div>
            <div class="pb-10"> <!--ADD: VAT --><?php echo number_format($total_vos[$x],2); ?></div>
            <div class="pb-10"> <!--TOTAL --><?php echo number_format($total_sales,2); ?></div>
            <div class="pb-10"> <!--LESS WITHHOLDING -->(<?php echo number_format($total_ewt[$x],2); ?>)</div>
            <div class="pb-5"> <!--TOTAL AMOUNT DUE --><?php echo number_format($total_amount_due,2); ?></div>
            <span class="font-10"> <!--VATABLE (V)  --><?php echo number_format($total_vs[$x],2); ?></span><br>
            <span class="font-10"> <!--VAT EXEMPT (E) --> 0.00</span><br>
            <span class="font-10"> <!--ZERO-RATED (Z)  --><?php echo number_format($total_zra[$x],2); ?></span><br>
            <span class="font-10"> <!--VAT (12%)  --><?php echo number_format($total_vos[$x],2); ?></span><br>
            <span class="font-10"> <!--TOTAL  --><?php echo number_format($total_sales,2); ?></span><br>
        </div>

    </div>
</page>
<?php } ?>

                
                                       
         