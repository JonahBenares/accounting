<script src="<?php echo base_url(); ?>assets/js/reserve.js"></script>
<?php
    if(!empty($reserve_id)){
        $readonly = 'readonly';
    } else {
        $readonly='';
    }
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Upload WESM Transaction - Reserve</h4>
                        </div>
                        <div class="card-body">
                            <form id='reservehead'>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" name='transaction_date' id="transaction_date" value="<?php echo (!empty($reserve_id) ? $transaction_date : ''); ?>" required <?php echo $readonly; ?> >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (From)</label>
                                            <input type="date" class="form-control" name='billing_from' id="billing_from" value="<?php echo (!empty($reserve_id) ? $billing_from : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Billing Period (To)</label>
                                            <input type="date" class="form-control" name='billing_to' id="billing_to" value="<?php echo (!empty($reserve_id) ? $billing_to : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <input type="text" class="form-control" name="reference_number" id="reference_number"  value="<?php echo (!empty($reserve_id) ? $reference_number : ''); ?>" required <?php echo $readonly; ?>>
                                            <span id="ref_error" style="color:red;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label>Due Date</label>
                                            <input type="date" class="form-control" name='due_date' id="due_date" value="<?php echo (!empty($reserve_id) ? $due_date : ''); ?>" required <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($reserve_id)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_btn()" value="Proceed" style="width:100%">
                                                 <input type='button' class="btn btn-danger" id="cancel" onclick="cancelReserve()" value="Cancel Transaction" style='display: none;width:100%'>
                                             <?php } else { ?>
                                                 <input type='button' class="btn btn-danger" id="cancel" onclick="cancelReserve()" value="Cancel Transaction" style="width:100%">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                            <form method="POST" id="upload_wesm">
                                <div id="upload" <?php echo (empty($reserve_id) ? 'style="display:none"' : ''); ?>>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" placeholder="" id="WESM_reserve">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_reserve" onclick="upload_btn()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <input type='hidden' name='reserve_id' id='reserve_id'  value="<?php echo (!empty($reserve_id) ? $reserve_id : ''); ?>">
                            </form>
                            <center><span id="alt"></span></center>
                            <style type="text/css">
                                table#table-8 tr td{
                                    border: 1px solid #efefef;
                                    padding:0px 5px;
                                }
                            </style>
                            <?php if(!empty($details)){ ?>
                            <div class="table-responsive" id="table-wesm" >
                                <hr>
                                <table class="table-bordered table table-hover" id="table-8" style="width:300%;">
                                    <thead>
                                        <tr>
                                            <th width="5%" align="center" style="background:rgb(245 245 245)">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>
                                            <th>Item No.</th>
                                            <th  style="position:sticky; left:0; z-index: 10;background: rgb(240 240 240);">STL ID / TPShort Name</th>
                                            <th style="position:sticky; left:166px; z-index: 10;background: rgb(240 240 240);">Billing ID</th>
                                            <th>Unique Billing ID</th>
                                            <th>Facility Type </th>
                                            <th>WHT Agent Tag</th>
                                            <th>ITH Tag</th>
                                            <th>Non Vatable Tag</th>
                                            <th>Zero-rated Tag</th>
                                            <th>Vatable Purchases</th>
                                            <th>Zero Rated Purchases</th>
                                            <th>Zero Rated EcoZones Purchases </th>
                                            <th>Vat On Purchases</th>
                                            <th>EWT</th>
                                            <th>Total Amount</th>
                                            <!-- <th>OR Number</th>
                                            <th>Total Amount</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $x=1;
                                            foreach($details AS $d){ 
                                                if(!empty($d['reserve_id'])){ 
                                        ?>
                                        <tr>
                                            <td align="center" style="background: #fff;">
                                                <?php if($saved==1){ ?>
                                               <div class="btn-group mb-0">
                                                    <a href="<?php echo base_url(); ?>reserve/print_2307_reserve/<?php echo $d['reserve_id']; ?>/<?php echo $d['reserve_detail_id']; ?>" target="_blank" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                        <span class="m-0 fas fa-print"></span><span id="clicksBS" class="badge badge-transparent"><?php echo "".$d['print_counter'].""; ?></span>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                            </td>
                                            <td><?php echo $d['item_no'];?></td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff"><?php echo $d['short_name'];?></td>
                                            <td style="position:sticky; left:166px; z-index: 10;background: #fff;"><?php echo $d['actual_billing_id']; ?></td>
                                            <td align="center"><?php echo $d['billing_id']; ?></td>
                                            <td align="center"><?php echo $d['facility_type']; ?></td>
                                            <td align="center"><?php echo $d['wht_agent']; ?></td>
                                            <td align="center"><?php echo $d['ith_tag']; ?></td>
                                            <td align="center"><?php echo $d['non_vatable']; ?></td>
                                            <td align="center"><?php echo $d['zero_rated']; ?></td>
                                            <td align="right">(<?php echo number_format($d['vatables_purchases'],2); ?>)</td>
                                            <td align="right">(<?php echo number_format($d['zero_rated_purchases'],2); ?>)</td>
                                            <td align="right">(<?php echo number_format($d['zero_rated_ecozones'],2); ?>)</td>
                                            <td align="right">(<?php echo number_format($d['vat_on_purchases'],2); ?>)</td>
                                            <td align="right"><?php echo number_format($d['ewt'],2); ?></td>
                                            <td align="right">(<?php echo number_format($d['total_amount'],2); ?>)</td>
                                        </tr>
                                        <?php } $x++; } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($details)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save">
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){

        $('#reference_number').on('blur', function(){

            var reference_number = $.trim($(this).val());
            var reserve_id = "<?php echo isset($reserve_id) ? $reserve_id : ''; ?>";

            if(reference_number === ''){
                $('#ref_error').text('');
                $('#reference_number').css('border','1px solid #ced4da');
                $('#save_head_button').prop('disabled', true);
                return;
            }

            $.ajax({
                url: "<?php echo base_url('reserve/check_reference_purchases_reserve'); ?>",
                type: "POST",
                data: { 
                    reference_number: reference_number,
                    reserve_id: reserve_id
                },
                success: function(response){

                    response = response.trim();

                    if(response === 'exists_saved'){
                        $('#ref_error').text('Reference number already exists!');
                        $('#reference_number').css('border','2px solid red');
                        $('#save_head_button').prop('disabled', true);

                    } else if(response === 'exists_unsaved'){
                        $('#ref_error').text('You have existing unsaved transaction.');
                        $('#reference_number').css('border','2px solid red');
                        $('#save_head_button').prop('disabled', true);

                    } else {
                        $('#ref_error').text('');
                        $('#reference_number').css('border','1px solid #ced4da');
                        $('#save_head_button').prop('disabled', false);
                    }
                }
            });

        });

    });
</script>

                
                                       
         