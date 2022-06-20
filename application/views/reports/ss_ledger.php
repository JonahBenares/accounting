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
                                    <h4>Supplier Subsidiary Ledger</h4>
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
                                                        <option value="<?php echo $p->billing_id;?>"><?php echo $p->billing_id." - ".$p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                             <td width="20%">
                                                    <input placeholder="Date From" id="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="20%">
                                                    <input placeholder="Date To" id="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterSSLedger();" class="btn btn-primary btn-block">Filter</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            if(!empty($part) || !empty($date_from) || !empty($date_to)){
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width="7%"></td>
                                    <td width="13%"><b>Participant Name:</b></td>
                                    <td width="32%">: <?php echo $part ?></td>
                                    <td width="12%"><b>Date From - To:</b></td>
                                    <td width="33%">: <?php echo $date_from ?> - <?php echo $date_to ?></td>
                                    <td width="3%"></td>
                                </tr>
                            </table>
                            <br>
                            <div style="overflow-x:scroll; min-height: 500px; height:550px">
                                <table class="table table-bordered table-hover mb-0" style="width:170%;font-size: 13px;">
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
                                            if(!empty($ssledger)){
                                            foreach($ssledger AS $b){ 
                                        ?>
                                        <tr>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $b['date']; ?></td>
                                            <td align="left" class="td-sticky left-col-2 sticky-back"><?php echo $b['company_name']; ?></td>
                                            <td align="center" class="td-sticky left-col-3 sticky-back"><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>
                                            <td align="right"><?php echo number_format($b['vatables_purchases'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['purchase_amount'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vatable_balance'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zero_rated_purchases'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zero_rated'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zerorated_balance'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['zero_rated_ecozones'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['rated_ecozones'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['ratedecozones_balance'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vat_on_purchases'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vat'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['vat_balance'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['ewt'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['p_ewt'],2); ?></td>
                                            <td align="right"><?php echo number_format($b['ewt_balance'],2); ?></td>
                                        </tr>
                                    </tbody>
                                         <?php  } } ?>
                                    <tfoot class="header">
                                        <tr >
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="3">TOTAL</td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vatable_purchases,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_purchase_amount,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vatable_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_rated,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_p_zero_rated,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_rated_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_ecozones,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_p_zero_ecozones,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_zero_ecozones_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vat,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_p_vat,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_vat_balance,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_ewt,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($total_p_ewt,2); ?></td>
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

