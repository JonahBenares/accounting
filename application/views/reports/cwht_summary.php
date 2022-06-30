<script src="<?php echo base_url(); ?>assets/js/report.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-4">
                                    <h4>Creditable Withholding Tax Summary</h4>
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
                                            <td width="45%">
                                                <!-- <input placeholder="Reference Number" class="form-control" type="text" id=""> -->
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Reference Number --</option>
                                                    <?php foreach($reference_no AS $r){ ?>
                                                        <option value="<?php echo $r->reference_no;?>"><?php echo $r->reference_no;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="45%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td><button type="button" class="btn btn-primary btn-block" onclick="filterCreditable();">Filter</button></td>
                                             <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr class="m-bs-0">
                            <table class="table-borsdered" width="100%">
                                <tr>
                                    <td width="40%"></td>
                                    <td width="5%">Total</td>
                                    <td width="10%" class="font-blue">:&nbsp;<b><?php echo ($total!=0)? number_format($total,2) : '0.00'; ?></b></td>
                                    <td width="45%"></td>
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
                                        <td>Withholding Tax</td>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php if(!empty($sales)){ foreach($sales AS $sa){ ?>
                                    <tr>                                        
                                        <td><?php echo $sa['transaction_date'];?></td>
                                        <td><?php echo $sa['tin'];?></td>
                                        <td><?php echo $sa['participant_name'];?></td>
                                        <td><?php echo $sa['address'];?></td>
                                        <td><?php echo ($sa['billing_from']!='' && $sa['billing_to']!='') ? date("F d,Y",strtotime($sa['billing_from']))." - ".date("F d,Y",strtotime($sa['billing_to'])) : '';?></td>
                                        <td align="center"><?php echo number_format($sa['ewt'],2);?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

