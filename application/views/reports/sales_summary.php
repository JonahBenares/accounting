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
                                    <h4>Sales Summary</h4>
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
                                                <!-- <input placeholder="Reference Number" class="form-control" type="text" id="ref_no" name="ref_no"> -->
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Reference Number --</option>
                                                    <?php foreach($reference_no AS $r){ ?>
                                                        <option value="<?php echo $r->reference_number;?>"><?php echo $r->reference_number;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="30%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->settlement_id." - ".$p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="filter_sales()" value="Filter"></td>
                                            <td width="5%"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr class="m-bs-0">
                            <table class="table-borsdered" width="100%">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="10%">Total Amount</td>
                                    <td class="font-blue">:&nbsp;<b><?php echo ($total_amount!=0) ? number_format($total_amount,2) : '0.00'; ?></b></td>
                                     <td width="10%">Total Collected</td>
                                    <td class="font-blue">:&nbsp;<b><?php echo ($total_collection!=0) ? number_format($total_collection,2) : '0.00'; ?></b></td>
                                    <td width="10%">Balance</td>
                                    <td class="font-blue">:&nbsp;<b><?php echo ($total_balance!=0) ? number_format($total_balance,2) : '0.00'; ?></b></td> 
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
                                        <td>Vatable Sales</td>   
                                        <td>Zero-Rated Sales</td>    
                                        <td>Vat on sales </td> 
                                        <td>Withholding Tax</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($sales)){
                                        foreach($sales AS $s) {?>
                                    <tr>
                                        <td><?php echo $s['transaction_date']; ?></td>
                                        <td><?php echo $s['tin']; ?></td>
                                        <td><?php echo $s['participant_name']; ?></td>
                                        <td><?php echo $s['address']; ?></td>
                                        <td><?php echo date("F d,Y",strtotime($s['billing_from']))." - ".date("F d,Y",strtotime($s['billing_to']));?></td>
                                        <td><?php echo number_format($s['vatable_sales'],2); ?></td>
                                        <td><?php echo number_format($s['zero_rated_sales'],2); ?></td>
                                        <td><?php echo number_format($s['vat_on_sales'],2); ?></td>
                                        <td><?php echo number_format($s['ewt'],2);?></td>
                                    </tr>
                                    <?php } }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

