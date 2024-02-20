<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/report.js"></script>

<style type="text/css">
    /*.divblock{
        display: block;
    }*/
    .disnone{
        display: none!important;
    }
    @media print{
        .disnone{
            display: block!important;
        }
        .divblock{
            display: none!important;
        }
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-4">
                                    <h4>Collection Report </h4>
                                </div>
                                <div class="col-8">
                                    <!-- <button onclick="printDiv('printableArea')" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button> -->
                                    <button type="button" onclick="printDiv('printableArea')" class="btn btn-primary btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                    <a href="<?php echo base_url(); ?>reports/export_collection_report/<?php echo $date; ?>/<?php echo $ref_no; ?>/<?php echo $stl_id; ?>" class="btn btn-success btn-sm pull-right"><span class="fas fa-file-export"></span> Export</a>
                                    <a href="<?php echo base_url(); ?>reports/export_iemop/<?php echo $date; ?>/<?php echo $ref_no; ?>/<?php echo $stl_id; ?>" class="btn btn-success btn-sm pull-right"><span class="fas fa-file-export"></span> IEMOP Export</a>
                                </div>
                            </div>
                        </div>
                            <div class="card-body">
                               <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <td width="22%">
                                                <select class="form-control select2" name="collection_date" id="collection_date">
                                                    <option value="">-- Select Collection Date --</option>
                                                    <?php foreach($collection_date AS $cd){ ?>
                                                        <option value="<?php echo $cd->collection_date;?>"><?php echo date("F d, Y",strtotime($cd->collection_date));?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="22%">
                                                <select class="form-control select2" name="reference_no" id="reference_no">
                                                    <option value="">-- Select Statement No --</option>
                                                    <?php foreach($reference_no AS $rn){ ?>
                                                        <option value="<?php echo $rn->reference_no;?>"><?php echo $rn->reference_no;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="22%">
                                                <select class="form-control select2" name="settlement_id" id="settlement_id">
                                                    <option value="">-- Select Buyer --</option>
                                                    <?php foreach($buyer AS $b){ ?>
                                                        <option value="<?php echo $b->settlement_id;?>"><?php echo $b->buyer_fullname;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_collection()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                                <hr>
                                <?php if(!empty($collection)){ ?>
                                <div style="overflow-x:scroll;">
                                    <table class="table-bordered table table-hosver divblock" id="table-3" width="200%"> 
                                        <thead>
                                            <tr>
                                                <th width="1%"  hidden="">OR#</th>
                                                <th width="1%">OR#</th>
                                                <th width="2%">Billing Remarks</th>
                                                <th width="1%">Date</th>
                                                <th width="2%">Particulars</th>
                                                <th width="2%">STL ID</th>
                                                <th width="5%">Participant Name</th>
                                                <th width="3%">Reference No</th>
                                                <th width="2%" align="center">Def Int</th>
                                                <th width="2%" align="center">Vatable Sales</th>
                                                <th width="2%" align="center">Zero Rated Sales</th>
                                                <th width="2%" align="center">Zero Rated Ecozone</th>
                                                <th width="2%" align="center">VAT</th>
                                                <th width="2%" align="center">EWT</th>
                                                <th width="2%" align="center">Total</th>
                                                <th width="2%" align="center">OR Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $data2 = array();
                                                foreach($collection as $value) {
                                                    //$key = $value['series_number'].$value['settlement_id'].$value['reference_no'];
                                                    $key = $value['settlement_id'].$value['reference_no'].$value['collection_date'];
                                                    if(!isset($data2[$key])) {
                                                        $data2[$key] = array(
                                                            'collection_id' => $value['collection_id'], 
                                                            'collection_details_id' => $value['collection_details_id'], 
                                                            'series_number_single' => $value['series_number'], 
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
                                                            'collection_date' => array(),
                                                            'collection_date_single'=>$value['collection_date'],
                                                            //'defint' => array(),
                                                            'defint_single'=>$value['defint'],
                                                            'or_no_remarks_single'=>$value['or_no_remarks'],
                                                            'series_number_single' => $value['series_number'],
                                                            'count_series'=>$value['count_series'],
                                                            'overall_total' => array(),
                                                            'overall_total'=>$value['overall_total'],
                                                            'sum_amount' => array(),
                                                            'sum_amount_single'=>$value['sum_amount'],
                                                            'sum_zero_rated' => array(),
                                                            'sum_zero_rated_single'=>$value['sum_zero_rated'],
                                                            'sum_zero_rated_ecozone' => array(),
                                                            'sum_zero_rated_ecozone_single'=>$value['sum_zero_rated_ecozone'],
                                                            'sum_vat' => array(),
                                                            'sum_vat_single'=>$value['sum_vat'],
                                                            'sum_ewt' => array(),
                                                            'sum_ewt_single'=>$value['sum_ewt']
                                                        );
                                                    }
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
                                                    //$data2[$key]['overall_total'][] = $value['overall_total'];
                                                    //$data2[$key]['collection_date'][] = $value['collection_date'];
                                                    //$data2[$key]['defint'][] = $value['defint'];
                                                }
                                                $x=1;
                                                foreach($data2 as $log) {
                                            ?>
                                            <tr>
                                                
                                                <?php if($log['count_series']>=1){ ?>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                    <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                <?php }else if($log['count_series']<=2){ ?>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                     <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                <?php }else{ ?>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['billing_remarks_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['particular_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                     <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['amount_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_ecozone_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['vat_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['ewt_single']; ?></td>
                                                    <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['total_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" hidden=""></td>
                                                <td class="td-btm pt-1 pb-1"><?php echo $log['series_number_single']; ?></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"><?php echo date('M d, Y', strtotime($log['collection_date_single'])); ?></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                 <td class="td-btm td-blue pt-1 pb-1"><?php echo $log['defint_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_amount_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_zero_rated_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_zero_rated_ecozone_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_vat_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_ewt_single']; ?></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['overall_total']; ?></td> 
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['or_no_remarks_single']; ?></td> 
                                            </tr>
                                            <?php $x++; } ?>
                                        </tbody>
                                    </table>
                                    <div id="printableArea">
                                        
                                        <table class="table-bordered table table-hosver disnone"  width="200%" style="font-size: 11px" > 
                                            <thead>
                                                <tr>
                                                    <th width="1%"  hidden="">OR#</th>
                                                    <th width="1%">OR#</th>
                                                    <th width="2%">Billing Remarks</th>
                                                    <th width="1%">Date</th>
                                                    <th width="2%">Particulars</th>
                                                    <th width="2%">STL ID</th>
                                                    <th width="5%">Participant Name</th>
                                                    <th width="3%">Reference No</th>
                                                    <th width="2%" align="center">Def Int</th>
                                                    <th width="2%" align="center">Vatable Sales</th>
                                                    <th width="2%" align="center">Zero Rated Sales</th>
                                                    <th width="2%" align="center">Zero Rated Ecozone</th>
                                                    <th width="2%" align="center">VAT</th>
                                                    <th width="2%" align="center">EWT</th>
                                                    <th width="2%" align="center">Total</th>
                                                    <th width="2%" align="center">OR Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $data2 = array();
                                                    foreach($collection as $value) {
                                                        //$key = $value['series_number'].$value['settlement_id'].$value['reference_no'];
                                                        $key = $value['settlement_id'].$value['reference_no'];
                                                        if(!isset($data2[$key])) {
                                                            $data2[$key] = array(
                                                                'collection_id' => $value['collection_id'], 
                                                                'collection_details_id' => $value['collection_details_id'], 
                                                                'series_number_single' => $value['series_number'], 
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
                                                                'collection_date' => array(),
                                                                'collection_date_single'=>$value['collection_date'],
                                                                //'defint' => array(),
                                                                'defint_single'=>$value['defint'],
                                                                'or_no_remarks_single'=>$value['or_no_remarks'],
                                                                'series_number_single' => $value['series_number'],
                                                                'count_series'=>$value['count_series'],
                                                                'overall_total' => array(),
                                                                'overall_total'=>$value['overall_total'],
                                                                'sum_amount' => array(),
                                                                'sum_amount_single'=>$value['sum_amount'],
                                                                'sum_zero_rated' => array(),
                                                                'sum_zero_rated_single'=>$value['sum_zero_rated'],
                                                                'sum_zero_rated_ecozone' => array(),
                                                                'sum_zero_rated_ecozone_single'=>$value['sum_zero_rated_ecozone'],
                                                                'sum_vat' => array(),
                                                                'sum_vat_single'=>$value['sum_vat'],
                                                                'sum_ewt' => array(),
                                                                'sum_ewt_single'=>$value['sum_ewt']
                                                            );
                                                        }
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
                                                        //$data2[$key]['overall_total'][] = $value['overall_total'];
                                                        //$data2[$key]['collection_date'][] = $value['collection_date'];
                                                        //$data2[$key]['defint'][] = $value['defint'];
                                                    }
                                                    $x=1;
                                                    foreach($data2 as $log) {
                                                ?>
                                                <tr>
                                                    
                                                    <?php if($log['count_series']>=1){ ?>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                        <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <?php }else if($log['count_series']<=2){ ?>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                         <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <?php }else{ ?>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['billing_remarks_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['particular_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                         <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['amount_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_ecozone_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['vat_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['ewt_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['total_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <td class="td-btm pt-1 pb-1" hidden=""></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['series_number_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo date('M d, Y', strtotime($log['collection_date_single'])); ?></td>
                                                    <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                     <td class="td-btm td-blue pt-1 pb-1"><?php echo $log['defint_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_amount_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_zero_rated_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_zero_rated_ecozone_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_vat_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['sum_ewt_single']; ?></td>
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['overall_total']; ?></td> 
                                                    <td align="center" class="td-btm td-blue pt-1 pb-1"><?php echo $log['or_no_remarks_single']; ?></td> 
                                                </tr>
                                                <?php $x++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
</script>
