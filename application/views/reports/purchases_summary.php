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
                                    <h4>Purchases Summary</h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export Monthly IEMOP Purchases
                                    </button>
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%">
                                        <tr>
                                            <td width="15%">
                                                <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="15%">
                                                <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="30%">
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
                                                    <input type='button' class="btn btn-primary"  onclick="filter_purchases()" value="Filter"></td>
                                            <td width="5%"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="13%"><b>Date From:</b></td>
                                    <td width="25%"><?php echo $from ?></td>
                                    <td width="13%"><b>Reference Number:</b></td>
                                    <td width="41%"><?php echo $ref_no ?></td>
                                    <td width="3%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><b>Date To:</b></td>
                                    <td><?php echo $to ?></td>
                                    <td><b>Participant Name:</b></td>
                                    <td><?php echo $part ?></td>
                                    <td></td>
                                </tr>
                            </table>
                            <hr class="m-b-0">
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>Date</td>
                                        <td>TIN</td> 
                                        <td>Trading Participants (Registered Name)</td>  
                                        <td>Address</td> 
                                        <td>Description</td>     
                                        <td>Vatable Purchases</td>  
                                        <td>Zero Rated Purchases</td>    
                                        <td>Zero Rated Ecozones</td>    
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
                                        <td><?php echo date("F d,Y",strtotime($s['billing_from']))." - ".date("F d,Y",strtotime($s['billing_to']));?></td>
                                        <td><?php echo number_format($s['vatable_purchases'],2); ?></td>
                                        <td><?php echo number_format($s['zero_rated_purchases'],2); ?></td>
                                        <td><?php echo number_format($s['zero_rated_ecozones'],2); ?></td>
                                        <td><?php echo number_format($s['vat_on_purchases'],2); ?></td>
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
<div class="modal fade" id="basicModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label>Reference No</label>
                            <select name="reference" class="form-control select2" id="reference">
                            <option value="">-- Select Reference Number --</option>
                            <?php foreach($reference AS $r){ ?>
                                <option value="<?php echo $r->reference_number;?>"><?php echo $r->reference_number;?></option>
                            <?php } ?>
                            </select>
                        </div>
                         <div class="form-group col-lg-12">
                            <label>Due Date</label>
                            <select name="due_date" class="form-control select2" id="due_date">
                            <option value="">-- Select Due Date --</option>
                            <?php foreach($due_dates AS $dd){ ?>
                                <option value="<?php echo $dd->due_date;?>"><?php echo $dd->due_date; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    
                    </div>
                 
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='baseurl1' id='baseurl1' value="<?php echo base_url(); ?>">
                    <input type='button' class="btn btn-primary"  onclick="export_monthlyIEMOP_purchases()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

