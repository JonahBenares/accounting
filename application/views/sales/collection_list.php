<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
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
                                        <h4>Collection</h4>
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
                                        <table width="100%">
                                            <tr>
                                                <td width="5%"></td>
                                                
                                                <!-- <td>
                                                    <input type="text" class="form-control" name="ref_number" id="ref_number" placeholder="Reference Number">
                                                </td> -->
                                                    <td>
                                                        <select class="form-control" name="ref_number" id="ref_number">
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
                                                    <input type='button' class="btn btn-primary"  onclick="collection_filter()" value="Filter"></td>
                                                <td width="5%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($ref_no)){ ?>
                                <div class="row">
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
                                </div>
                                
                                
                                <div id="collection-list">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th width="5%" align="center">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th width="20%">Company Name</th>
                                                <th width="15%">Billing ID</th>
                                                <th width="15%">Short Name</th>
                                                <th width="10%">Vatable Sales</th>
                                                <th width="15%">Total Amount Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                             foreach($sales AS $s){ 
                                                ?>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>', '<?php echo $s->sales_id; ?>','<?php echo $s->sales_detail_id; ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                  <!--   <a id="clicksOR"></a> -->
                                                </td>
                                                <td><?php echo $s->company_name; ?></td>
                                                <td><?php echo $s->billing_id; ?></td>
                                                <td><?php echo $s->short_name; ?></td>
                                                <td><?php echo number_format($s->vatable_sales,2); ?></td>
                                                <td align="right"><?php echo number_format($s->balance,2); ?></td>
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


                
                                       
         