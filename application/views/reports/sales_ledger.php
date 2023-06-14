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
                                <div class="col-4">
                                    <h4>Sales Ledger</h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-primary btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                    <?php if(!empty($bill)){ ?>
                                        <a href = "<?php echo base_url();?>/reports/export_sales_ledger/<?php echo $refno; ?>/<?php echo $year; ?>/<?php echo $date_from; ?>/<?php echo $date_to; ?>" class = "btn btn-success pull-right">Export to Excel</a>
                                    <?php }else{ ?>
                                        <a href = "<?php echo base_url();?>/reports/export_sales_ledger/" class = "btn btn-success pull-right">Export to Excel</a>
                                    <?php } ?>  
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                            <td width="20%">
                                                <select id="year" class="form-control select2" name="year" onchange='getSalesLedgerRef()'>
                                                    <option value="">--Select Year--</option>
                                                    <?php 
                                                        $years=date('Y');
                                                        for($x=2020;$x<=$years;$x++){
                                                    ?>
                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="30%">
                                                    <!-- <select class="form-control select2" id="ref_no" multiple>
                                                        <option value="">-- Select Transaction No --</option>
                                                        <?php foreach($reference_no AS $r){ ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select> -->
                                                    <select name="ref_no" id='ref_no' class="form-control select2" multiple>
                                                    </select>
                                            </td>
                                            <td width="20%">
                                                <select name="month_from" id='month_from' class="form-control select2">
                                                    <option value="" selected>--Select Month From--</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="month_to" id='month_to' class="form-control select2">
                                                    <option value="" selected>--Select Month To--</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </td>
                                                <!-- <td width="20%">
                                                    <input placeholder="Date From" id="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date To" id="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td> -->
                                                <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterLedger();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            if(!empty($refno) || !empty($year) || !empty($date_from) || !empty($date_to)){
                                $month_from   = DateTime::createFromFormat('!m', $date_from);
                                $mnth_from = ($month_from!='') ? $month_from->format('F') : ''; // March
                                $month_to   = DateTime::createFromFormat('!m', $date_to);
                                $mnth_to = ($month_to!='') ? $month_to->format('F') : ''; // March
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="7%"><b>Transaction No.:</b></td>
                                    <td width="41%"><?php echo $refno ?></td>
                                    <td width="4%"><b>Year:</b></td>
                                    <td width="10%"><?php echo $year ?></td>
                                    <td width="8%"><b>Month From - To:</b></td>
                                    <td width="10%"><?php echo $mnth_from ?> - <?php echo $mnth_to ?></td>
                                    <td width="10%"></td>
                                </tr>
                            </table>
                            <br>
                            <div style="overflow-x:scroll; min-height: 500px; height:550px">
                                <table class="table table-bordered table-hover mb-0" style="width:200%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-1" rowspan="2" align="center">Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-2 width300" rowspan="2" align="center">Participant Name</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-3 width300" rowspan="2" align="center">Description</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-4 width200" rowspan="2" align="center">Reference Number</td> 

                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vatable Sales</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Sales</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Ecozone</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">VAT</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">EWT</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($bill)){
                                            foreach($bill AS $b){
                                            $sum_vatable_sales[]=$b['vatable_sales']; 
                                            $sum_zero_rated_sales[]=$b['zero_rated_sales']; 
                                            $sum_zero_rated_ecozone[]=$b['zero_rated_ecozones']; 
                                            $sum_vat_on_sales[]=$b['vat_on_sales']; 
                                            $sum_ewt[]=$b['ewt'];

                                            $sum_cvatable_sales[]=$b['cvatable_sales']; 
                                            $sum_czero_rated_sales[]=$b['czero_rated_sales']; 
                                            $sum_czero_rated_ecozone[]=$b['czero_rated_ecozone']; 
                                            $sum_cvat_on_sales[]=$b['cvat_on_sales']; 
                                            $sum_cewt[]=$b['cewt']; 
                                        ?>
                                        <tr>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $b['date']; ?></td>
                                            <td align="left" class="td-sticky left-col-2 sticky-back"><?php echo $b['company_name']; ?></td>
                                            <td align="center" class="td-sticky left-col-3 sticky-back"><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>
                                            <td align="center" class="td-sticky left-col-4 sticky-back"><?php echo $b['reference_number']; ?></td>
                                            <td align="right"><?php echo number_format($b['vatable_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['cvatable_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vatablebalance'],2); ?></td>

                                            <td align="right"><?php echo number_format($b['zero_rated_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['czero_rated_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zerobalance'],2); ?></td>

                                            <td align="right"><?php echo number_format($b['zero_rated_ecozones'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['czero_rated_ecozone'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zeroecobalance'],2); ?></td>

                                            <td align="right"><?php echo number_format($b['vat_on_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['cvat_on_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vatbalance'],2); ?></td>

                                            <td align="right"><?php echo number_format($b['ewt'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['cewt'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['ewtbalance'],2); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr >
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="4">TOTAL</td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_vatable_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_cvatable_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vatable_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_zero_rated_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_czero_rated_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_rated_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_zero_rated_ecozone),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_czero_rated_ecozone),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_ecozones_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_vat_on_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_cvat_on_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vat_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_ewt),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_cewt),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_ewt_balance,2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                     <?php }else{ ?>
                            <div><center><b>No Available Data...</b></center></div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

