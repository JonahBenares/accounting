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
                                    <h4>Expanded Withholding Tax Summary</h4>
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
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Reference Number --</option>
                                                    <?php foreach($reference_no AS $r){ ?>
                                                        <option value="<?php echo $r->reference_number;?>"><?php echo $r->reference_number;?></option>
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
                                            <td><button type="button" class="btn btn-primary btn-block" onclick="filterEwt();">Filter</button></td>
                                             <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="13%"><b>Reference Number:</b></td>
                                    <td width="33%"><?php echo $ref_no ?></td>
                                    <td width="13%"><b>Participant Name:</b></td>
                                    <td width="33%"><?php echo $part ?></td>
                                    <td width="3%"></td>
                                </tr>
                            </table>
                            <hr class="m-b-0">
                            <table class="table-borsdered" width="100%" style="background-color:#fffaf4">
                                <tr>
                                    <td class="p-t-10 p-b-10" width="40%"></td>
                                    <td class="p-t-10 p-b-10" width="5%">Total</td>
                                    <td class="p-t-10 p-b-10 font-blue" width="10%">:&nbsp;<b><?php echo ($total!=0)? number_format($total,2) : '0.00'; ?></b></td>
                                    <td class="p-t-10 p-b-10" width="45%"></td>
                                </tr>
                            </table>
                            <hr class="m-t-0">
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
                                    <?php if(!empty($purchase)){ foreach($purchase AS $pu){ ?>
                                    <tr>                                        
                                        <td><?php echo $pu['transaction_date'];?></td>
                                        <td><?php echo $pu['tin'];?></td>
                                        <td><?php echo $pu['participant_name'];?></td>
                                        <td><?php echo $pu['address'];?></td>
                                        <td><?php echo ($pu['billing_from']!='' && $pu['billing_to']!='') ? date("F d,Y",strtotime($pu['billing_from']))." - ".date("F d,Y",strtotime($pu['billing_to'])) : '';?></td>
                                        <td align="center"><?php echo number_format($pu['ewt'],2);?></td>
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

