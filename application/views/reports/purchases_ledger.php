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
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Reference Number --</option>
                                                    <?php foreach($reference_no AS $r){ ?>
                                                        <option value="<?php echo $r->reference_number;?>"><?php echo $r->reference_number;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="15%">
                                                <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="15%">
                                                <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
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
                                        <td class="td-30 td-head">Billing</td>  
                                        <td class="td-30 td-head">Collection</td>  
                                        <td class="td-30 td-head">Vatable Purchases</td> 
                                        <td class="td-30 td-head">Non-Vat Purchases</td>    
                                        <td class="td-30 td-head">Input Vat</td>
                                        <td class="td-30 td-head">Balance</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($purchases)){
                                        foreach($purchases AS $s) {

                                        $total_vatable_purchases[]=$s['vatable_purchases'];
                                        $total_input_vat[]=$s['vat_on_purchases'];
                                        ?>
                                    <tr>                                        
                                        <td class="td-30"><?php echo $s['transaction_date']; ?></td>
                                        <td class="td-30"><?php echo $s['participant_name']; ?></td>
                                        <td class="td-30"><?php echo $s['billing_from']." - ".$s['billing_from']; ?></td>
                                        <td class="td-30">Billing</td>
                                        <td class="td-30">Collection</td>
                                        <td class="td-30" align="right"><?php echo number_format($s['vatable_purchases']); ?></td>
                                        <td class="td-30" align="right"></td>
                                        <td class="td-30" align="right"><?php echo number_format($s['vat_on_purchases']); ?></td>
                                        <td class="td-30" align="right">  </td>
                                    </tr>
                                     <?php } }
                                     $vatable_purchases=array_sum($total_vatable_purchases);
                                     $vatable_on_sales=array_sum($total_input_vat);
                                     //$balance=array_sum($total_balance);
                                     ?>
                                    <tr>
                                        <td class="td-30 td-yellow" colspan="5">Total</td>
                                        <td class="td-30 td-yellow" align="right"><?php echo number_format(array_sum($total_vatable_purchases),2);?></td>
                                        <td class="td-30 td-yellow" align="right"> </td>
                                        <td class="td-30 td-yellow" align="right"><?php echo number_format(array_sum($total_input_vat),2);?></td>
                                        <td class="td-30 td-yellow" align="right"> <!-- <?php echo number_format(array_sum($total_balance),2);?> --> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

