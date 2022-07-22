<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-8">
                                    <h4>Summary of Adjustment Billing Statement - <b>Sales</b></h4>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo base_url(); ?>reports/adjustment_sales_print/<?php echo $transaction_date; ?>" target="_blank" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <!-- <td width="22%">
                                                <select class="form-control select2" name="date" id="date">
                                                    <option value="">-- Select Transaction Date --</option>
                                                    <?php foreach($date AS $d){ ?>
                                                        <option value="<?php echo $d->transaction_date;?>"><?php echo date("F d,Y",strtotime($d->transaction_date));?></option>
                                                    <?php } ?>
                                                </select>
                                            </td> -->
                                             <td width="99%">
                                            <input type="date" name="transaction_date" id="transaction_date" class="form-control">
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_adjusted_sales()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="25%"></td>
                                    <td width="13%"><b>Transaction Date:</b></td>
                                    <td width="41%"><?php echo $transaction_date ?></td>
                                    <td width="3%"></td>
                                </tr>
                                <!-- <tr>
                                    <td></td>
                                    <td><b>Date Prepared:</b></td>
                                    <td></td>
                                    <td><b>Invoice Date:</b></td>
                                    <td></td>
                                    <td></td>
                                </tr> -->
                            </table>
                            <hr class="m-b-0">
                            <table class="table table-bordered table-hover mb-0" style="width:100%;font-size: 13px;">
                                <thead>
                                    <tr>
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="12%">Particular</td>
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="20%">Reference Number</td>  
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="18%">Billing Period</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vatable Amount</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Zero Rated Amount</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Net Sale (Php)</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vat on Energy </td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >EWT</td>
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" width="10%">Total Amount Due (Php)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pt-1 pb-1" colspan="9" style="border-bottom:0px solid #fff">
                                            <b>RECEIVABLES</b>
                                        </td>
                                    </tr>
                                    <?php 
                                        if(!empty($adjustment)){
                                        $data2 = array();
                                        foreach($adjustment as $ads) {
                                            $key = date("Y",strtotime($ads['billing_to'])).date("Y",strtotime($ads['billing_from']));
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
                                            $data2[$key]['billing_from'][] = $ads['billing_from'];
                                            $data2[$key]['billing_to'][] = $ads['billing_to'];
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
                                        <td class="pt-1 pb-1" colspan="9">
                                            <br>
                                            <u>Year <?php echo date("Y",strtotime($ad['billing_to_single'])); ?></u>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1"><?php echo implode("<br /><br />",$ad['particular']); ?></td>
                                        <td class="pt-1 pb-1"><?php echo implode("<br /><br />",$ad['reference_number']);?></td>
                                        <td class="pt-1 pb-1"><?php echo date("F d",strtotime($ad['billing_from_single']));?> - <?php echo date("F d,Y",strtotime($ad['billing_to_single']));?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['vatable_sales']);?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['zero_rated']);?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['net']);?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['vat_on_sales']);?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['ewt']); ?></td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;"><?php echo implode("<br /><br />",$ad['total']);?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="pt-2 pb-2 td-yellow" colspan="8" align="left">TOTAL AMOUNT RECEIVABLE on or before, <?php echo date('F d,Y',strtotime($due_date))?> &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right"><b>(<?php echo number_format($total_sum,2);?>)</b></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>   
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
