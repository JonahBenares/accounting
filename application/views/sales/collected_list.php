<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<!-- Modal -->
<div class="modal fade" id="updateSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Series Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Series Number</label>
                        <input type="text" id="series_number" name="series_number" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="hidden" id="ref_no" name="ref_no" value="<?php echo $ref_no; ?>">
                    <input type="hidden" id="old_series_no" name="old_series_no" class="form-control">
                    <input type="hidden" id="collection_id" name="collection_id" class="form-control">
                    <input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
                    <button type="button" class="btn btn-primary" onclick="saveSeries()">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Collected</h4>
                                    </div>
                                    <!-- <div class="col-lg-6 col-md-6">
                                        <div class="input-group">
                                            <select class="custom-select" id="inputGroupSelect04">
                                                <option selected="">Choose Participant</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary m-0" type="button" style="border-radius: 0 .25rem .25rem 0;">Search</button>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%" class="table-borsdered">
                                            <tr>
                                                <td width="5%"></td>
                                                <td width="45%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value="">-- Select Participant --</option>
                                                        <?php 
                                                            foreach($participant_list AS $p){
                                                        ?>
                                                        <option value="<?php echo $p->settlement_id; ?>"><?php echo $p->participant_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="44%">
                                                    <select class="form-control select2" name="ref_number" id="ref_number">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="collected_filter()" value="Filter"></td>
                                                <td width="5%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($ref_no)){ ?>
                                <!-- <div class="row">
                                    <div class="col-lg-12">
                                        <?php foreach($sales_head AS $sh){ ?>
                                        <table width="100%" class="table-borsdered">
                                            <tr>
                                                <td width="13%">Reference Number:</td>
                                                <td><b><?php echo $sh->reference_number; ?></b></td>
                                                <td width="13%">Transaction Date:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->transaction_date)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Billing Period:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->billing_from)); ?> - <?php echo date("F j, Y", strtotime($sh->billing_to)); ?></td>
                                                <td>Due Date:</td>
                                                <td><?php echo date("F j, Y", strtotime($sh->due_date)); ?></td>
                                            </tr>
                                        </table>   
                                        <?php } ?>                                     
                                    </div>
                                </div> -->
                                
                                
                                <div id="collection-list">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th width="5%" align="center">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th width="20%">Date Collected</th>
                                                <th width="20%">Series No.</th>
                                                <th width="20%">Company Name</th>
                                                <!-- <th width="15%">Billing ID</th> -->
                                                <th width="15%">Settlement ID</th>
                                                <th width="10%">Amount</th>
                                                <th width="10%">Vat</th>
                                                <th width="10%">Zero Rated</th>
                                                <th width="10%">Zero Rated Ecozone</th>
                                                <th width="10%">EWT</th>
                                                <th width="15%">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                             foreach($sales AS $s){ 
                                                ?>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <button title="Edit Series Number" type="button" class="btn btn-info btn-sm" id="seriesupdate" data-toggle="modal" data-target="#updateSeries" data-name="<?php echo $s['series_number']; ?>" data-id='<?php echo $s['collection_details_id']; ?>'>
                                                            <span class="m-0 fas fa-edit"></span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group mb-0">
                                                        <a href="<?php echo base_url(); ?>sales/print_collected_OR/<?php echo $s['collection_details_id'];?>" class="btn btn-success btn-sm" target="_blank"  style="color:#fff">
                                                            <span class="m-0 fas fa-print"></span>
                                                        </a>
                                                    </div>
                                                  <!--   <a id="clicksOR"></a> -->
                                                </td>
                                                <td><?php echo date("F d,Y",strtotime($s['collection_date'])); ?></td>
                                                <td><?php echo $s['series_number']; ?></td>
                                                <td><?php echo $s['company_name']; ?></td>
                                                <!-- <td><?php echo $s['billing_id']; ?></td> -->
                                                <td><?php echo $s['short_name']; ?></td>
                                                <td><?php echo number_format($s['amount'],2); ?></td>
                                                <td align="right"><?php echo number_format($s['vat'],2); ?></td>
                                                <td align="right"><?php echo $s['zero_rated']; ?></td>
                                                <td align="right"><?php echo number_format($s['zero_rated_ecozone'],2); ?></td>
                                                <td align="right"><?php echo number_format($s['ewt'],2); ?></td>
                                                <td align="right"><?php echo number_format($s['total'],2); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         