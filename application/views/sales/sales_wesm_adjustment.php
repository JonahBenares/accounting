<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                            <div class="card-header">
                                <h4>WESM Transaction - Sales Adjustment</h4>
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
                                                    <td>
                                                        <select class="form-control select2" name="due_date" id="due_date">
                                                            <option value="">-- Select Due Date --</option>
                                                            <?php foreach($date AS $d){ ?>
                                                                <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="in_ex_sub" id="in_ex_sub">
                                                            <option value="">-- Select Include or Exlcude Sub-participant--</option>
                                                                <option value="0">Include Sub-participant</option>
                                                                <option value="1">Exclude Sub-participant</option>
                                                        </select>
                                                    </td>
                                                    <td  width="1%"><button type="button" onclick="filterSalesAdjustment();" class="btn btn-primary btn-block">Filter</button></td>
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                               <?php if(!empty($details) && (!empty($ref_no) || !empty($due_date))){ ?>
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
                                <?php if(!empty($details)){ ?>
                                <div class="table-responsive">
                                    <form method="POST" id="print_mult">
                                        <table class="table-bordered table table-hover " id="table-2" style="width:200%;">
                                            <thead>
                                                <tr>
                                                    <th>Item No</th>
                                                    <th>BS No.</th>
                                                    <th>OR No.</th>
                                                    <th>STL ID / TPShort Name</th>
                                                    <th width="7%" style="position: sticky;left:0;background:#f3f3f3;z-index: 999;">Billing ID</th>
                                                    <th width="15%" style="position: sticky;left:165px;background:#f3f3f3;z-index: 999;">Trading Participant Name</th>
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
                                                    <th>EWT Amount</th>
                                                    <th>Original Copy</th>
                                                    <th>Scanned Copy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $data2 = array();
                                                    foreach($details AS $s){ 
                                                    $key = $s['serial_no'].$s['due_date'];
                                                    if(!isset($data2[$key])) {
                                                            $data2[$key] = array(
                                                                'sales_detail_id' => $s['sales_detail_id'], 
                                                                'old_series_no' => $s['old_series_no'], 
                                                                'serial_no' => $s['serial_no'], 
                                                                'item_no' => array(),
                                                                'item_no_single'=>$s['item_no'],
                                                                'short_name' => array(),
                                                                'short_name_single'=>$s['short_name'], 
                                                                'billing_id' => array(),
                                                                'billing_id_single'=>$s['billing_id'], 
                                                                'company_name' => array(),
                                                                'company_single'=>$s['company_name'], 
                                                                'facility_type' => array(),
                                                                'facility_type_single'=>$s['facility_type'], 
                                                                'wht_agent' => array(),
                                                                'wht_agent_single'=>$s['wht_agent'],
                                                                'ith_tag' => array(),
                                                                'ith_tag_single'=>$s['ith_tag'], 
                                                                'non_vatable' => array(),
                                                                'non_vatable_single'=>$s['non_vatable'], 
                                                                'zero_rated' => array(),
                                                                'zero_rated_single'=>$s['zero_rated'], 
                                                                'vatable_sales' => array(),
                                                                'vatable_sales_single'=>$s['vatable_sales'],
                                                                'zero_rated_sales' => array(),
                                                                'zero_rated_sales_single'=>$s['zero_rated_sales'],
                                                                'zero_rated_ecozones' => array(),
                                                                'zero_rated_ecozones_single'=>$s['zero_rated_ecozones'],
                                                                'vat_on_sales' => array(),
                                                                'vat_on_sales_single'=>$s['vat_on_sales'],
                                                                'ewt' => array(),
                                                                'ewt_single'=>$s['ewt'],
                                                                'total_amount' => array(),
                                                                'total_amount_single'=>$s['total_amount'], 
                                                                /*'total' => array(),
                                                                'total_single'=>$value['total'],
                                                                'defint_single'=>$value['defint'],
                                                                'or_date_single'=>$value['or_date'],  
                                                                'or_no_remarks_single'=>$value['or_no_remarks'],  
                                                                'count_series'=>$value['count_series'],
                                                                'overall_total'=>$value['overall_total'],*/
                                                            );
                                                        }
                                                        $data2[$key]['item_no'][] = $s['item_no'];
                                                        $data2[$key]['short_name'][] = $s['short_name'];
                                                        $data2[$key]['billing_id'][] = $s['billing_id'];
                                                        $data2[$key]['company_name'][] = $s['company_name'];
                                                        $data2[$key]['facility_type'][] = $s['facility_type'];
                                                        $data2[$key]['wht_agent'][] = $s['wht_agent'];
                                                        $data2[$key]['ith_tag'][] = $s['ith_tag'];
                                                        $data2[$key]['non_vatable'][] = $s['non_vatable'];
                                                        $data2[$key]['zero_rated'][] = $s['zero_rated'];
                                                        $data2[$key]['vatable_sales'][] = $s['vatable_sales'];
                                                        $data2[$key]['zero_rated_sales'][] = $s['zero_rated_sales'];
                                                        $data2[$key]['zero_rated_ecozones'][] = $s['zero_rated_ecozones'];
                                                        $data2[$key]['vat_on_sales'][] = $s['vat_on_sales'];
                                                        $data2[$key]['ewt'][] = "(".$s['ewt'].")";
                                                        $data2[$key]['total_amount'][] = $s['total_amount'];
                                                    }
                                                    $x=1;
                                                    foreach($data2 as $log) {
                                                ?>
                                                <tr>
                                                    <td><center><?php echo implode("<br /><br />",$log['item_no']); ?></center></td>
                                                    <!-- <td><?php echo $s['serial_no'];?></td> -->
                                                    <?php if(!empty($log['old_series_no'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="BSNo" data-target="#olSeries" data-bs="<?php echo $log['serial_no']; ?>" data-old-bs="<?php echo $log['old_series_no'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $log['serial_no'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $log['serial_no'];?></td>
                                                    <?php } ?>
                                                    <?php if(!empty($s['old_series_no_col'])) {?>
                                                    <td width="7%"><a href="" data-toggle="modal" id="ORNo" data-target="#oldOR" data-series-col="<?php echo $s['series_number']; ?>" data-old-series-col="<?php echo $s['old_series_no_col'];?>" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"><?php echo $s['series_number'];?></a></td>
                                                    <?php }else{ ?>
                                                    <td><?php echo $log['serial_no'];?></td>
                                                    <?php } ?>
                                                    <td><?php echo implode("<br /><br />",$log['short_name']); ?></td>
                                                    <td style="position: sticky;left:0;background:#fff;z-index: 999;"><?php echo implode("<br /><br />",$log['billing_id']); ?></td>
                                                    <td style="position: sticky;left:165px;background:#fff;z-index: 999;"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td align="center"><?php echo implode("<br /><br />",$log['facility_type']); ?></td>
                                                    <td align="center"><?php echo implode("<br /><br />",$log['wht_agent']); ?></td>
                                                    <td align="center"><?php echo implode("<br /><br />",$log['ith_tag']); ?></td>
                                                    <td align="center"><?php echo implode("<br /><br />",$log['non_vatable']); ?></td>
                                                    <td align="center"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td align="right"><?php echo implode("<br /><br />",$log['vatable_sales']); ?></td>
                                                    <td align="right"><?php echo implode("<br /><br />",$log['zero_rated_sales']); ?></td>
                                                    <td align="right"><?php echo implode("<br /><br />",$log['zero_rated_ecozones']); ?></td>
                                                    <td align="right"><?php echo implode("<br /><br />",$log['vat_on_sales']); ?></td>
                                                    <td align="right"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td align="right" style="padding:0px"><?php echo implode("<br /><br />",$log['total_amount']); ?></td>
                                                    <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updateSalesAdjustment('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['sales_detail_id']; ?>','<?php echo $s['sales_adjustment_id']; ?>','<?php echo $s['billing_id']; ?>')" name="ewt_amount" id="ewt_amount<?php echo $x; ?>" value="<?php echo $s['ewt_amount']; ?>">
                                                    </td>
                                                    <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updateSalesAdjustment('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['sales_detail_id']; ?>','<?php echo $s['sales_adjustment_id']; ?>','<?php echo $s['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_yes<?php echo $x; ?>" value='1' <?php echo ($s['original_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updateSalesAdjustment('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['sales_detail_id']; ?>','<?php echo $s['sales_adjustment_id']; ?>','<?php echo $s['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_no<?php echo $x; ?>" value='2' <?php echo ($s['original_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                    <span class="m-b-10">Yes</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio"  onchange="updateSalesAdjustment('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['sales_detail_id']; ?>','<?php echo $s['sales_adjustment_id']; ?>','<?php echo $s['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_yes<?php echo $x; ?>" value='1' <?php echo ($s['scanned_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <span class="m-b-10">No</span>
                                                    <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updateSalesAdjustment('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $s['sales_detail_id']; ?>','<?php echo $s['sales_adjustment_id']; ?>','<?php echo $s['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_no<?php echo $x; ?>" value='2' <?php echo ($s['scanned_copy']=='0') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                </tr>
                                                <?php $x++; } } ?>
                                            </tbody>
                                        </table>
                                    </form>
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
