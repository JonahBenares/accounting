
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<style type="text/css">
    .hr{
        margin:2px 0px;
        border-top:1px solid rgb(0 0 0 / 5%);
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-8">
                                    <h4>Summary of Adjustment Billing Statement - <b>Purchases</b></h4>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo base_url(); ?>reports/adjustment_purchases_print/<?php echo $due_date; ?>" target="_blank" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</a>
                                     <a class="btn btn-success btn-sm pull-right"  href="<?php echo base_url(); ?>reports/export_monthly_IEMOP_adjustment_purchases/<?php echo $due_date; ?>">
                                        <span class="fas fa-file-export"></span> Export Monthly IEMOP Purchases
                                                </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <!-- <td width="99%">
                                                <input type="month" name="transaction_date" id="transaction_date" class="form-control">
                                            </td> -->
                                            <td width="22%">
                                                <select class="form-control select2" name="due_date" id="due_date">
                                                    <option value="">-- Select Due Date --</option>
                                                    <?php foreach($date AS $d){ ?>
                                                        <option value="<?php echo $d->due_date;?>"><?php echo date("F d,Y",strtotime($d->due_date));?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="adjustment_purchases_filter()" value="Filter">
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
                                    <td width="13%"><b>Due Date:</b></td>
                                    <?php if(!empty($due_date)){ ?>
                                      <td width="41%"><?php echo date("F d,Y",strtotime($due_date)); ?></td>  
                                    <?php } else ?>
                                    <td width="41%"></td>
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
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="20%">Reference No.</td>  
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="23%">Billing Period</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vatable Amount</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Zero Rated Amount</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Net Purchase (Php)</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vat on Energy </td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >EWT</td>
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" width="5%">Total Amount Due (Php)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pt-1 pb-1 pl-0" colspan="9" style="border-bottom:0px solid #fff">
                                            <b>PAYABLES</b>
                                        </td>
                                    </tr>
                                    <?php 
                                        if(!empty($adjust)){
                                        $data2 = array();
                                        foreach($adjust as $ads) {
                                            $key = date("Y",strtotime($ads['billing_to']));
                                            if(!isset($data2[$key])) {
                                                $data2[$key] = array(
                                                    'particular'=>array(),
                                                    'participant'=>array(),
                                                    'billing_from'=>array(),
                                                    'billing_from_single'=>$ads['billing_from'],
                                                    'billing_to'=>array(),
                                                    'billing_fromto'=>array(),
                                                    'billing_to_single'=>$ads['billing_to'],
                                                    'vatables_purchases'=>array(),
                                                    'vat_on_purchases'=>array(),
                                                    'ewt'=>array(),
                                                    'zero_rated'=>array(),
                                                    'net'=>array(),
                                                    'total'=>array(),
                                                    'total_single'=>$ads['total'],
                                                );
                                            }
                                            $data2[$key]['particular'][] = $ads['particular'];
                                            $data2[$key]['participant'][] = $ads['participant'];
                                            $data2[$key]['billing_from'][] = date("F d",strtotime($ads['billing_from']));
                                            $data2[$key]['billing_to'][] = date("F d, Y",strtotime($ads['billing_to']));
                                            $data2[$key]['billing_fromto'][] = date("M. d, Y",strtotime($ads['billing_from']))." - ".date("M. d, Y",strtotime($ads['billing_to']));
                                            $data2[$key]['vatables_purchases'][] = "(".number_format($ads['vatables_purchases'],2).")";
                                            $data2[$key]['vat_on_purchases'][] = "(".number_format($ads['vat_on_purchases'],2).")";
                                            $data2[$key]['ewt'][] = number_format($ads['ewt'],2);
                                            $data2[$key]['zero_rated'][] = "(".number_format($ads['zero_rated'],2).")";
                                            $data2[$key]['net'][] = "(".number_format($ads['net'],2).")";
                                            $data2[$key]['total'][] = "(".number_format($ads['total'],2).")";
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
                                        <td class="pt-1 pb-1 pr-0 pl-0" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['particular'])."<br>"; ?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['participant']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px"><?php echo implode("<hr class='hr'>",$ad['billing_fromto']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['vatables_purchases']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['zero_rated']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['net']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['vat_on_purchases']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['ewt']); ?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$ad['total']);?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="pt-2 pb-2 td-yellow" colspan="8" align="left" style="font-size:12px">TOTAL AMOUNT PAYABLE on or before, <?php echo date('F d,Y',strtotime($due_date))?> &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size:12px"><b>(<?php echo number_format($total_sum,2);?>)</b></td>
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
