<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
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
                                                    <td>
                                                        <select class="form-control select2" name="ref_no" id="ref_no">
                                                            <option value=''>-- Select Reference No --</option>
                                                            <?php 
                                                                foreach($reference AS $r){
                                                            ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td  width="1%"><button type="button" onclick="filterSales();" class="btn btn-primary btn-block">Filter</button></td>
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
                                    <form method="POST" id="print_mult">
                                        <table class="table-bordered table table-hover " id="table-2" style="width:200%;">
                                            <thead>
                                                <tr>    
                                                    <th width="2%" align="center" style="background:rgb(245 245 245)">
                                                        <!-- <a href="" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple"><span class="fas fa-print mr-1 mt-1 mb-1"></span></a> -->
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printMultiple()"><span class="fas fa-print mr-1 mt-1 mb-1"></span></button>
                                                    </th>      
                                                    <th width="2%"></th>
                                                    <th>Item No</th>
                                                    <th>BS No.</th>
                                                    <th>OR No.</th>
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
                                                    //$x=1;
                                                    if(!empty($details)){
                                                    foreach($details AS $s){ 
                                                ?>
                                                <tr>
                                                    <td align="center">
                                                        <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="<?php echo $identifier_code.','.$s['sales_detail_id'].','.$ref_no; ?>">
                                                        <!-- <?php 
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
                                                            </div>
                                                        <?php } ?> -->
                                                            
                                                    </td>
                                                    <td>
                                                        <button title="Edit Series Number" type="button" class="btn btn-info btn-sm" id="BSupdate" data-toggle="modal" data-target="#updateSerial" data-series="<?php echo $s['serial_no']; ?>" data-id="<?php echo $s['sales_detail_id'];?>">
                                                            <span class="m-0 fas fa-edit"></span>
                                                        </button>
                                                    </td>
                                                    <td><center><?php echo $s['item_no'];?></center></td>
                                                    <?php if(!empty($s['old_series_no'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="BSNo" data-target="#olSeries" data-bs="<?php echo $s['serial_no']; ?>" data-old-bs="<?php echo $s['old_series_no'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $s['serial_no'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $s['serial_no'];?></td>
                                                    <?php } ?>
                                                    <?php if(!empty($s['old_series_no_col'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="ORNo" data-target="#oldOR" data-series-col="<?php echo $s['series_number']; ?>" data-old-series-col="<?php echo $s['old_series_no_col'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $s['series_number'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $s['series_number'];?></td>
                                                    <?php } ?>
                                                    <td><?php echo $s['short_name'];?></td>
                                                    <td><?php echo $s['billing_id'];?></td>
                                                    <td><?php echo $s['company_name'];?></td>
                                                    <td align="center"><?php echo $s['facility_type'];?></td>
                                                    <td align="center"><?php echo $s['wht_agent'];?></td>
                                                    <td align="center"><?php echo $s['ith_tag'];?></td>
                                                    <td align="center"><?php echo $s['non_vatable'];?></td>
                                                    <td align="center"><?php echo $s['zero_rated'];?></td>
                                                    <td align="right"><?php echo $s['vatable_sales'];?></td>
                                                    <td align="right"><?php echo $s['zero_rated_sales'];?></td>
                                                    <td align="right"><?php echo $s['zero_rated_ecozones'];?></td>
                                                    <td align="right"><?php echo $s['vat_on_sales'];?></td>
                                                    <td align="right">(<?php echo $s['ewt'];?>)</td>
                                                    <td align="right"><?php echo $s['total_amount'];?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                        </table>
                                    </form>
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

<div class="modal fade" id="updateSerial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Billing Statement Series</h5>
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
                    <input type="hidden" id="ref_no" name="ref_no" class="form-control" value="<?php echo $ref_no; ?>">
                    <input type="hidden" id="old_series_no" name="old_series_no" class="form-control">
                    <input type="hidden" id="sales_detail_id" name="sales_detail_id" class="form-control">
                    <input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
                    <button type="button" class="btn btn-primary" onclick="saveBseries()">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
                
          
<div class="modal fade" id="oldOR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #6777ef;color:#fff">
                <h4 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current OR</small>
                    <br><!-- <input type="text" id="series_no" class="form-control"> --><span id="series_no"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_series_no_disp"></span></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>                             
<div class="modal fade" id="olSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #ffa426;color:#fff">
                <h5 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current Series</small>
                    <br><span id="bs_no"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_bs_no_disp"></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>                             
