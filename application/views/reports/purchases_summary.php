<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/reports.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-4">
                                    <h4>Purchases Summary</h4>
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
                                            <td width="15%">
                                                <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="15%">
                                                <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="30%">
                                                <input placeholder="Reference Number" class="form-control" type="text" id="ref_no" name="ref_no">
                                            </td>
                                            <td width="30%">
                                                <select class="form-control" id="participant_id" name="participant_id">
                                                    <option value="">-Choose Participant-</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->participant_id; ?>"><?php echo $p->participant_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="filter_purchases()" value="Filter"></td>
                                            <td width="5%"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                             <?php if(!empty($ref_no) || !empty($participant_id)){ ?>
                            <hr class="m-bs-0">
                            <table class="table-borsdered" width="100%">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="10%">Total Amount</td>
                                    <td class="font-blue">:&nbsp;<b>19928.00</b></td>
                                    <td width="10%">Total Paid</td>
                                    <td class="font-blue">:&nbsp;<b>19928.00</b></td>
                                    <td width="10%">Balance</td>
                                    <td class="font-blue">:&nbsp;<b>19928.00</b></td>
                                </tr>
                            </table>
                            <hr>
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>Date</td>
                                        <td>TIN</td> 
                                        <td>Trading Participants (Registered Name)</td>  
                                        <td>Address</td> 
                                        <td>Description</td>     
                                        <td>Vatable Purchases</td>  
                                        <td>Non-Vatable Purchases</td>    
                                        <td>Vat on Purchases </td> 
                                        <td>Withholding Tax</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($purchases)){
                                        foreach($purchases AS $s) {?>
                                    <tr>
                                        <td><?php echo $s['transaction_date']; ?></td>
                                        <td><?php echo $s['tin']; ?></td>
                                        <td><?php echo $s['participant_name']; ?></td>
                                        <td><?php echo $s['address']; ?></td>
                                        <td><?php echo $s['billing_from']." - ".$s['billing_from']; ?></td>
                                        <td><?php echo $s['vatable_purchases']; ?></td>
                                        <td><?php echo $s['zero_rated_purchases']; ?></td>
                                        <td><?php echo $s['vat_on_purchases']; ?></td>
                                        <td><?php echo $s['wht_agent']; ?></td>
                                    </tr>
                                    <?php } }?>
                                </tbody>
                            </table>
                             <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

