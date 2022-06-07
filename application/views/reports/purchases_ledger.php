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
                                    <h4>Purchases Ledger</h4>
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
                                                <td width="50%">
                                                    <select class="form-control" id="ref_no">
                                                        <option value="">-- Select Transaction No --</option>
                                                        <?php foreach($reference_no AS $r){ ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date From" id="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date To" id="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                            <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="filter_purchasesledger()" value="Filter"></td>
                                            <td width="5%"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-bordered table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <td class="td-30 td-head">Date</td>
                                        <td class="td-30 td-head">Participant Name</td>
                                        <td class="td-30 td-head">Description</td>
                                        <td class="td-30 td-head">Method</td>  
                                        <!-- <td class="td-30 td-head">Collection</td>  --> 
                                        <td class="td-30 td-head">Vatable Purchases</td> 
                                        <td class="td-30 td-head">Non-Vat Purchases</td>    
                                        <td class="td-30 td-head">Input Vat</td>
                                        <td class="td-30 td-head">Balance</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if(!empty($bill)){
                                        foreach($bill AS $b){ 
                                            $vatable_sum[]=$b['vatable_total'];
                                            $zerorated_sum[]=$b['zerorated_total'];
                                            $vat_sum[]=$b['vat_total'];
                                            $total_sum=$b['total_sum'];
                                    ?>
                                    <tr>                                        
                                        <td class="td-30"><?php echo $b['date']; ?></td>
                                        <td class="td-30"><?php echo $b['company_name']; ?></td>
                                        <td class="td-30"><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>
                                        <td class="td-30"><?php echo ($b['method']=='Bill') ? 'Billing' : 'Payment'; ?></td>
                                        <td class="td-30" align="right"><?php echo number_format($b['vatables_purchases'],2); ?></td>
                                        <td class="td-30" align="right"><?php echo number_format($b['zero_rated_purchases'],2); ?></td>
                                        <td class="td-30" align="right"><?php echo number_format($b['vat_on_purchases'],2); ?></td>
                                        <td class="td-30" align="right"><?php echo number_format($b['total'],2); ?></td>
                                    </tr>
                                    <?php 
                                        } 
                                        $vatable_arraysum=array_sum($vatable_sum);
                                        $zerorated_arraysum=array_sum($zerorated_sum);
                                        $vat_arraysum=array_sum($vat_sum);
                                        /*$total_arraysum=array_sum($total_sum);*/
                                    ?>
                                    <tr>
                                        <td class="td-30 td-yellow" colspan="4">TOTAL</td>
                                        <td class="td-30 td-yellow" align="right"><b><?php echo number_format($vatable_arraysum,2); ?></b></td>
                                        <td class="td-30 td-yellow" align="right"><b><?php echo number_format($zerorated_arraysum,2); ?></b></td>
                                        <td class="td-30 td-yellow" align="right"><b><?php echo number_format($vat_arraysum,2); ?></b></td>
                                        <td class="td-30 td-yellow" align="right"><b><?php echo number_format($total_sum,2); ?></b></td>
                                    </tr>
                                    <?php  } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

