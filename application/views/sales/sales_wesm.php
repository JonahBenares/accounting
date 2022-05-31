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
                                                    <!-- <td>
                                                        <input placeholder="Reference Number" name="ref_no" id="ref_no" class="form-control" type="text" >
                                                    </td> -->
                                                    <td>
                                                        <select class="form-control" name="ref_no" id="ref_no">
                                                            <option value=''>-- Select Reference No --</option>
                                                            <?php 
                                                                foreach($reference AS $r){
                                                            ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <!-- <td>
                                                        <select class="form-control" name="participant" id="participant">
                                                            <option value=''>-- Select Participant --</option>
                                                            <?php 
                                                                foreach($participants AS $p){
                                                            ?>
                                                            <option value="<?php echo $p->billing_id; ?>"><?php echo $p->billing_id." - ".$p->participant_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td> -->
                                                    <td><button type="button" onclick="filterSales();" class="btn btn-primary btn-block">Filter</button></td>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <?php if(!empty($details) && !empty($ref_no)){ ?>
                                <table class="table-bsordered" width="100%">
                                    <?php 
                                        foreach($details AS $d){ 
                                            $reference_number=$d['reference_number'];
                                            $transaction_date=date("F d,Y",strtotime($d['transaction_date']));
                                            $billing_from=date("F d,Y",strtotime($d['billing_from']));
                                            $billing_to=date("F d,Y",strtotime($d['billing_to']));
                                            $due_date=date("F d,Y",strtotime($d['due_date']));
                                        }

                                    ?>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: <?php echo (!empty($reference_number)) ? $reference_number : ''; ?></td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: <?php echo (!empty($billing_from)) ? $billing_from : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: <?php echo (!empty($transaction_date)) ? $transaction_date : ''; ?></td>
                                        <td>Billing Period (To)</td>
                                        <td>: <?php echo (!empty($billing_to)) ? $billing_to : ''; ?></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Due Date</td>
                                        <td>: <?php echo (!empty($due_date)) ? $due_date : ''; ?></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="table-responsive">
                                    <hr>
                                    <table class="table-bordered table table-hover " id="table-2" style="width:200%;">
                                        <thead>
                                            <tr>    
                                                <th width="5%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>                                            
                                                <th>Item No</th>
                                                <th>Series No.</th>
                                                <th>STL ID / TPShort Name</th>
                                                <th>Billing ID</th>
                                                <th>Trading Participant Name</th>
                                                <th>Facility Type </th>
                                                <th>WHT Agent Tag</th>
                                                <th>ITH Tag</th>
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
                                                $x=1;
                                                if(!empty($details)){
                                                foreach($details AS $s){ 
                                            ?>
                                            <tr>
                                                <td align="center" style="background: #fff;">
                                                    <?php 
                                                        if($s['serial_no']=='' && $s['print_counter']==0){
                                                    ?>
                                                        <div class="btn-group mb-0">
                                                            <a style="color:#fff" onclick="add_details_BS('<?php echo base_url(); ?>','<?php echo $s['sales_detail_id']; ?>')"  class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                                <span class="m-0 fas fa-indent"></span><span class="badge badge-transparent" id="clicksBS"><?php echo $s['print_counter']; ?></span>
                                                            </a>
                                                        </div>
                                                    <?php 
                                                        }else{
                                                    ?>
                                                        <div class="btn-group mb-0">
                                                            <a style="color:#fff" href="<?php echo base_url(); ?>sales/print_BS/<?php echo $s['sales_detail_id']; ?>" target='_blank' onclick = "countPrint('<?php echo base_url(); ?>','<?php echo $s['sales_detail_id']; ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details">
                                                                <span class="m-0 fas fa-indent"></span><span class="badge badge-transparent" id="clicksBS"><?php echo $s['print_counter']; ?></span>
                                                            </a>
                                                        </div>+
                                                    <?php } ?>
                                                </td>
                                                <td><center><?php echo $x;?></center></td>
                                                <td><?php echo $s['serial_no'];?></td>
                                                <td><?php echo $s['short_name'];?></td>
                                                <td><?php echo $s['billing_id'];?></td>
                                                <td><?php echo $s['company_name'];?></td>
                                                <td><?php echo $s['facility_type'];?></td>
                                                <td><?php echo $s['wht_agent'];?></td>
                                                <td><?php echo $s['ith_tag'];?></td>
                                                <td><?php echo $s['non_vatable'];?></td>
                                                <td><?php echo $s['zero_rated'];?></td>
                                                <td><?php echo $s['vatable_sales'];?></td>
                                                <td><?php echo $s['zero_rated_sales'];?></td>
                                                <td><?php echo $s['zero_rated_ecozones'];?></td>
                                                <td><?php echo $s['vat_on_sales'];?></td>
                                                <td><?php echo $s['ewt'];?></td>
                                                <td><?php echo $s['total_amount'];?></td>
                                            </tr>
                                            <?php $x++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php }else{ ?>
                                    <div><center><b>No Available Data...</b></center></div>
                                <?php } ?>
                            </div>
                       <!--  </form> -->
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         