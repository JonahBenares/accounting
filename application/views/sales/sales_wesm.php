<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                            <div class="card-header">
                                <h4>WESM Transaction - Sales</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-1">
                                            <table class="table-borderded" width="100%">
                                                <tr>
                                                    <td width="40%">
                                                        <input placeholder="Reference Number" name="ref_no" id="ref_no" class="form-control" type="text" >
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="participant" id="participant">
                                                            <option value=''>-- Select Participant --</option>
                                                            <?php 
                                                                foreach($participants AS $p){
                                                            ?>
                                                            <option value="<?php echo $p->billing_id; ?>"><?php echo $p->billing_id." - ".$p->participant_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><button type="button" onclick="filterSales();" class="btn btn-primary btn-block">Filter</button></td>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <?php if(!empty($ref_no) || !empty($participant)){ ?>
                                <table class="table-bsordered" width="100%">
                                    <?php 
                                        foreach($details AS $d){ 
                                            $ref_no=$d['reference_number'];
                                            $trasaction_date=$d['transaction_date'];
                                            $billing_from=date("F d,Y",strtotime($d['billing_from']));
                                            $billing_to=date("F d,Y",strtotime($d['billing_to']));
                                            $due_date=date("F d,Y",strtotime($d['due_date']));
                                        }

                                    ?>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: <?php echo $ref_no; ?></td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: <?php echo $billing_from; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: <?php echo $trasaction_date; ?></td>
                                        <td>Billing Period (To)</td>
                                        <td>: <?php echo $billing_to; ?></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Due Date</td>
                                        <td>: <?php echo $due_date; ?></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="table-responsive">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:200%;">
                                        <thead>
                                            <tr>    
                                                <th width="1%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>                                            
                                                <th>Trading Participant Name</th>
                                                <th>Facility Type </th>
                                                <th>WHT Agent Tag</th>
                                                <th>Non Vatable Tag</th>
                                                <th>Zero-rated Tag</th>
                                                <th>Vatable Sales</th>
                                                <th>Zero Rated Sales</th>
                                                <th>Zero Rated EcoZones Sales</th>
                                                <th>Vat On Sales</th>
                                                <th>EWT</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if(!empty($details)){
                                                foreach($details AS $s){ 
                                            ?>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_details_wesm('<?php echo base_url(); ?>')" class="btn btn-success btn-sm">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicks"></a>
                                                </td>
                                                <td><?php echo $s['company_name'];?></td>
                                                <td><?php echo $s['facility_type'];?></td>
                                                <td><?php echo $s['wht_agent'];?></td>
                                                <td><?php echo $s['non_vatable'];?></td>
                                                <td><?php echo $s['zero_rated'];?></td>
                                                <td><?php echo $s['vatable_sales'];?></td>
                                                <td><?php echo $s['zero_rated_sales'];?></td>
                                                <td><?php echo $s['zero_rated_ecozones'];?></td>
                                                <td><?php echo $s['vat_on_sales'];?></td>
                                                <td><?php echo $s['ewt'];?></td>
                                                <td><?php echo $s['total_amount'];?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php } ?>
                            </div>
                       <!--  </form> -->
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         