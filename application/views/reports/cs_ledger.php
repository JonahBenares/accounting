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
                                    <h4>Customer Subsidiary Ledger </h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->billing_id;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select id="year" class="form-control" name="year">
                                                    <option value="">--Select Year--</option>
                                                    <?php 
                                                        $years=date('Y');
                                                        for($x=2020;$x<=$years;$x++){
                                                    ?>
                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="month_from" id='month_from' class="form-control">
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
                                                <select name="month_to" id='month_to' class="form-control">
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
                                                    <button type="button" onclick="filterCSLedger();" class="btn btn-primary btn-block">Filter</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            if(!empty($part) || !empty($year) || !empty($date_from) || !empty($date_to)){
                                $from_date   = DateTime::createFromFormat('!m', $date_from);
                                $from = $from_date->format('F'); // March
                                $to_date   = DateTime::createFromFormat('!m', $date_to);
                                $to = $to_date->format('F'); // March
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width=""></td>
                                    <td width=""><b>Participant Name:</b></td>
                                    <td width=""><?php echo $part ?></td>

                                    <td width=""><b>Year:</b></td>
                                    <td width=""><?php echo ($year!='null') ? $year : '' ?></td>
                                    
                                    <td width=""><b>Month From - To:</b></td>
                                    <td width=""><?php echo $from ?> - <?php echo $to ?></td>
                                    <td width=""></td>
                                </tr>
                            </table>
                            <br>
                            <div style="overflow-x:scroll; min-height: 500px; height:550px">
                                <table class="table table-bordered table-hover mb-0" style="width:200%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-1" rowspan="2" align="center">Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-2" rowspan="2" align="center">Participant Name</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-3" rowspan="2" align="center">Description</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vatable Sales</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Sales</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Ecozone</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vat</td>
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
                                            if(!empty($csledger)){
                                                $csledger = array_map("unserialize", array_unique(array_map("serialize", $csledger)));
                                            foreach($csledger AS $b){ 
                                        ?>
                                        <tr>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" align="center"><?php echo $b['date']; ?></td>
                                            <td align="left" class="td-sticky left-col-2 sticky-back"><?php echo $b['company_name']; ?></td>
                                            <td align="left" class="td-sticky left-col-3 sticky-back"><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>

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
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="3">TOTAL</td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vatable_sales,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_amount,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vatable_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_rated,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_c_zero_rated,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_rated_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_ecozones,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_c_zero_ecozones,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_ecozones_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vat,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_c_vat,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vat_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_ewt,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_c_ewt,2); ?></td>
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

