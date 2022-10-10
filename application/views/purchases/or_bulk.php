<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk OR Update - Purchases</h4>
                        </div>
                        <div class="card-body">
                            <form id='orbulk'> 
                                <div class="row">
                                    <?php if($saved==0){ ?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 offset-lg-2" >
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <?php if(empty($purchase_id)){ ?>
                                            <select class="form-control select2" name="purchase_id" id="purchase_id">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->purchase_id; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                            </select>
                                            <?php } else { ?>
                                            <input type="text" class="form-control" name='ref_number' id="ref_number" value="<?php echo $refno ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($purchase_id) && empty($identifier)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_or_bulk' type="button" onclick="proceed_or_bulk()" value="Proceed" style="width:100%">
                                                 <input type='button' class="btn btn-danger" id="cancel_or" onclick="cancelBulkor()" value="Cancel Transaction" style='display: none;width:100%'>
                                             <?php } elseif ($saved==0){ ?>
                                                 <input type='button' class="btn btn-danger" id="cancel_or" onclick="cancelBulkor()" value="Cancel Transaction" style="width:100%">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } else {?>
                                    <div class="col-lg-4">
                                        <span>Reference Number:</span> <?php echo $refno ?>
                                    </div> 
                                <?php } ?>  
                                </div> 
                            </form>
                            <?php if(!empty($purchase_id)){ if($saved==0){ ?>
                            <form method="POST" id="upload_bulkor">
                                <div id="upload_or_bulk">
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" name="doc" placeholder="" id="or_bulk">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_or" onclick="upload_or()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                 <input type='hidden' name='purchase_id' id='purchase_id'  value="<?php echo (!empty($purchase_id) ? $purchase_id : ''); ?>">
                                 <input type="hidden" name="identifier" id="identifier" value="<?php echo $identifier_code;?>">
                                  <input type="hidden" name="or_identifier" id="or_identifier" value="<?php echo $identifier;?>">
                            </form>
                             <center><span id="alt"></span></center>
                         <?php } } ?>
                         <?php if(!empty($identifier) && !empty($details)){ ?>
                            <div class="table-responsive" id="table-or" >
                                <hr>
                                <table class="table-bordered table table-hover " id="table-2" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Billing ID</th>
                                            <th>OR Number</th>
                                            <th width="15%">Total Amount</th>
                                            <th width="10%">Original Copy</th>
                                            <th width="10%">Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach($details AS $d){ 
                                                if(!empty($d['purchase_id'])){ 
                                        ?>
                                    	<tr>
                                    		<td><?php echo $d['billing_id']; ?></td>
                                    		<td><?php echo $d['or_no']; ?></td>
                                    		<td align="right"><?php echo number_format($d['total_update'],2); ?></td>
                                            <?php if ($d['original_copy'] == 1) { ?>
                                                <td align="center"><span class="fas fa-check" style="color:green"></span></td>
                                            <?php }else{ ?>
                                                <td align="center"><span class="fas fa-times" style="color:red"></td>
                                            <?php } ?>
                                    		 <?php if ($d['scanned_copy'] == 1) { ?>
                                                <td align="center"><span class="fas fa-check" style="color:green"></span></td>
                                            <?php }else{ ?>
                                                <td align="center"><span class="fas fa-times" style="color:red"></td>
                                            <?php } ?>
                                    	</tr>
                                     <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(!empty($details)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitor" class="btn btn-success btn-md btn-block" onclick="saveOR();" value="Save">
                        <?php } } }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>