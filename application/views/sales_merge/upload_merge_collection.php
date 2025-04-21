<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/salesmerge.js"></script>
<?php

if(!empty($collection_id)){
    $readonly = 'readonly';
} else {
    $readonly='';
}

if(!empty($saved)){
    $saved = $saved;
} else {
    $saved=0;
}
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk Merge Collection</h4>
                        </div>
                        <div class="card-body">
                          <?php if($saved==0){ ?>  
                            <form id='collection_bulk'>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 offset-lg-2" >
                                        <div class="form-group">
                                            <label>Collection Date:</label>
                                            <input type="date" name="collection_date" id="collection_date" value="<?php echo (!empty($collection_id) ? $collection_date : ''); ?>" required <?php echo $readonly; ?> class="form-control">
                                        </div>
                                    </div>

                                   <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                            <?php if(empty($collection_id)){ ?>
                                                <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_merge_collection()" value="Proceed" style="width: 100%;">
                                                <input type='button' class="btn  btn-danger" id="cancel" onclick="cancelMergeCollection()" value="Cancel Transaction" style='display: none;width: 100%;'>
                                             <?php } else { ?>
                                                <input type='button' class="btn btn-danger" id="cancel" onclick="cancelMergeCollection()" value="Cancel Transaction" style="width: 100%;">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> 
                                
                            </form>
                            <form method="POST" id="upload_bulkcollection">
                                 <div id="upload" <?php echo (empty($collection_id) ? 'style="display:none"' : ''); ?>>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" name="doc" placeholder="" id="bulk_collection">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_collection" onclick="upload_merge_collection()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                 <input type='hidden' name='collection_id' id='collection_id'  value="<?php echo (!empty($collection_id) ? $collection_id : ''); ?>">
                            </form>
                             <?php } else {?> 
                                <div class="col-lg-4">
                                        <h4><span>Collection Date:</span> <?php echo date('M d, Y', strtotime($collection_date)); ?></h4>
                                    </div> 
                             <?php } ?>
                             <center><span id="alt"></span></center>
                             <?php if(!empty($collection)){ ?>
                            <div class="table-responsive" id="table-collection" >
                                <hr>
                                <table class="table-bordered table table-hosver" id="table-3" width="200%"> 
                                        <thead>
                                            <tr>
                                                <th width="1%"><center><span class="fas fa-bars"></span></center></th>
                                                <th width="5%">OR#</th>
                                                <th width="2%">OR Date</th>
                                                <?php if(!empty($collection)){ if($saved!=0){ ?>
                                                <th width="5%">OR Remarks</th>
                                                <?php } } ?>
                                                <th width="2%" align="center">Def Int</th>
                                                <th width="2%">Billing Remarks</th>
                                                <th width="2%">Particulars</th>
                                                <th width="2%">STL ID</th>
                                                <th width="5%">Participant Name</th>
                                                <th width="3%">Reference No</th>
                                                <th width="3%" align="center">Vatable Sales</th>
                                                <th width="3%" align="center">Zero Rated Sales</th>
                                                <th width="3%" align="center">Zero Rated Ecozone</th>
                                                <th width="3%" align="center">VAT</th>
                                                <th width="3%" align="center">EWT</th>
                                                <th width="3%" align="center">Total</th>
                                                <th width="3%" align="center">Overall Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $data2 = array();
                                                foreach($collection as $value) {
                                                    $key = $value['series_number'].$value['settlement_id'].$value['reference_no'];
                                                    if(!isset($data2[$key])) {
                                                        $data2[$key] = array(
                                                            'collection_id' => $value['collection_id'], 
                                                            'collection_details_id' => $value['collection_details_id'], 
                                                            'item_no_array' => array(), 
                                                            'series_number' => $value['series_number'], 
                                                            'billing_remarks' => array(),
                                                            'billing_remarks_single'=>$value['billing_remarks'],
                                                            'particulars' => array(),
                                                            'particular_single'=>$value['particulars'], 
                                                            'settlement_id' => array(),
                                                            'settlement_id_single'=>$value['settlement_id'], 
                                                            'company_name' => array(),
                                                            'company_single'=>$value['company_name'], 
                                                            'reference_no' => array(),
                                                            'reference_no_single'=>$value['reference_no'], 
                                                            'amount' => array(),
                                                            'amount_single'=>$value['amount'],
                                                            'zero_rated' => array(),
                                                            'zero_rated_single'=>$value['zero_rated'], 
                                                            'zero_rated_ecozone' => array(),
                                                            'zero_rated_ecozone_single'=>$value['zero_rated_ecozone'], 
                                                            'vat' => array(),
                                                            'vat_single'=>$value['vat'], 
                                                            'ewt' => array(),
                                                            'ewt_single'=>$value['ewt'], 
                                                            'total' => array(),
                                                            'total_single'=>$value['total'],
                                                            //'defint' => array(),
                                                            'defint_single'=>$value['defint'],
                                                            'or_date_single'=>$value['or_date'],  
                                                            'or_no_remarks_single'=>$value['or_no_remarks'],  
                                                            'count_series'=>$value['count_series'],
                                                            'overall_total'=>$value['overall_total'],
                                                        );
                                                    }
                                                    $data2[$key]['item_no_array'][] = $value['item_no'];
                                                    $data2[$key]['billing_remarks'][] = $value['billing_remarks'];
                                                    $data2[$key]['particulars'][] = $value['particulars'];
                                                    $data2[$key]['settlement_id'][] = $value['settlement_id'];
                                                    $data2[$key]['company_name'][] = $value['company_name'];
                                                    $data2[$key]['reference_no'][] = $value['reference_no'];
                                                    $data2[$key]['amount'][] = $value['amount'];
                                                    $data2[$key]['zero_rated'][] = $value['zero_rated'];
                                                    $data2[$key]['zero_rated_ecozone'][] = $value['zero_rated_ecozone'];
                                                    $data2[$key]['vat'][] = $value['vat'];
                                                    $data2[$key]['ewt'][] = $value['ewt'];
                                                    $data2[$key]['total'][] = $value['total'];
                                                    //$data2[$key]['defint'][] = $value['defint'];
                                                }
                                                $x=1;
                                                foreach($data2 as $log) {
                                            ?>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" style="vertical-align: middle;" align="center">
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url(); ?>salesmerge/print_merge_OR/<?php echo $log['collection_id'];?>/<?php echo $log['settlement_id_single'];?>/<?php echo $log['reference_no_single'];?>" target='_blank' class="btn btn-primary btn-sm text-white"><span class="fas fa-print"></span></a>
                                                    </div>
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="series_number" id="series_number<?php echo $x; ?>" value="<?php echo $log['series_number'];?>" onchange="updateMergeSeries('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input Series Number'>
                                                    <span hidden><?php echo $log['series_number'];?></span>
                                                    <input type="hidden" name="old_series_no" id="old_series_no<?php echo $x; ?>" value='<?php echo $log['series_number'];?>'> 
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="date" name="or_date" id="or_date<?php echo $x; ?>" value="<?php echo $log['or_date_single'];?>" onchange="updateMergeORDate('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');">
                                                    <input type="hidden" name="old_or_date" id="old_or_date<?php echo $x; ?>" value='<?php echo $log['or_date_single'];?>'> 
                                                </td>
                                                <?php if(!empty($collection)){ if($saved!=0){ ?>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="or_no_remarks" id="or_no_remarks<?php echo $x; ?>" value="<?php echo $log['or_no_remarks_single'];?>" onchange="updateMergeorRemarks('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input OR Remarks'>
                                                    <span hidden><?php echo $log['or_no_remarks_single'];?></span>
                                                </td>
                                                <?php } } ?>
                                                 <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="def_int" id="def_int<?php echo $x; ?>" value="<?php echo $log['defint_single'];?>" onchange="updateMergeDefInt('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input Def Int'>
                                                    <span hidden><?php echo $log['defint_single'];?></span>
                                                </td>
                                                <?php if($log['count_series']>=1){ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php }else if($log['count_series']<=2){ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php }else{ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['billing_remarks_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['particular_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['amount_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_ecozone_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['vat_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['ewt_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['total_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php $x++; } ?>
                                        </tbody>
                                    </table>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if(!empty($collection)){ if($saved==0){ ?>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAllMergeCollection();" value="Save">
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>