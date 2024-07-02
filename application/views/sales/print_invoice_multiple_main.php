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
            left:490px;
            width: 250px;
/*            background: #7fef7f8c;*/
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
            left:490px;
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
            left:0px;
            top:180px;
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
        .amount{
            position: absolute;
            left:380px;
            top:180px;
/*            background: #7fcf7f8c;*/
            font-size: 12px;
            line-height: 15px;
            width: 100px;
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
<page size="A4" style="">
    <div style="margin-left: 20px;margin-right: 80px;">
        <?php 
            if(!empty($client)){ foreach($client AS $c){ 
        ?>
            <div class="cusname"><?php echo $c->participant_name; ?></div>
            <div class="date"><?php echo date("F j, Y", strtotime($date)); ?></div>
            <div class="address" style="padding-left: 5px"><?php echo $c->registered_address; ?></div>
            <div class="tin"><?php echo $c->tin; ?></div>
        <?php } } else { ?>
            <div class="cusname"><br></div>
            <div class="date"><br></div>
            <div class="address"><br></div>
        <?php }?>
        <div class="ref px-5"><?php echo $ref_no; ?></div>
        <div class="desc">
            <div class="marg-5">DEF. INTEREST</div>
            <div class="marg-5">ENERGY</div>
            <div class="marg-5">VAT</div>
        </div>
        <div class="amount">
            <div class="marg-5"><?php echo number_format($defint,2); ?></div>
            <div class="marg-5"><?php echo number_format($sum_amount,2); ?></div>
            <div class="marg-5"><?php echo number_format($sum_vat,2); ?></div>
        </div>
        <?php
            $zero_rated = $sum_zero_rated + $sum_zero_rated_ecozone; 
            $total = $sum_amount +$zero_rated + $sum_vat; 
            $total_due = $total - $sum_ewt;
        ?>
        <div class="sales">
            <div class="pb-10 pt-2"> <!--TOTAL SALES (VAT INCLUSIVE) --><?php echo number_format($total,2); ?></div>
            <div class="pb-10"> <!--AMOUNT: NET OF VAT --><?php echo number_format($sum_amount,2); ?></div>
            <div class="pb-10"> <!--ADD: VAT --><?php echo number_format($sum_vat,2); ?></div>
            <div class="pb-10"> <!--TOTAL --><?php echo number_format($total,2); ?></div>
            <div class="pb-10"> <!--LESS WITHHOLDING --><?php echo number_format($sum_ewt,2); ?></div>
            <div class="pb-5"> <!--TOTAL AMOUNT DUE --><?php echo number_format($total_due,2); ?></div>
            <span class="font-10"> <!--VATABLE (V)  --><?php echo number_format($sum_amount,2); ?></span><br>
            <span class="font-10"> <!--VAT EXEMPT (E) --> 0.00</span><br>
            <span class="font-10"> <!--ZERO-RATED (Z)  --><?php echo number_format($zero_rated,2); ?></span><br>
            <span class="font-10"> <!--VAT (12%)  --><?php echo number_format($sum_vat,2); ?></span><br>
            <span class="font-10"> <!--TOTAL  --><?php echo number_format($total,2); ?></span><br>
        </div>
        <?php  ?>
    </div>
</page>

                
                                       
         