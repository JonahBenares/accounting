
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-8">
                                    <h4>Consolidation/Summary of all Sales Transaction</h4>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <table width="100%">
                                        <tr>
                                            <td width="30%">
                                                <input type="date" class="form-control" name="">
                                            </td>
                                            <td width="30%">
                                                <input type="date" class="form-control" name="">
                                            </td>
                                            <td width="30%">
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Company --</option>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-bordered" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <td>BIlling ID</td> 
                                            <td width="20%">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td>
                                            <td>EWT Amount</td>
                                            <td>Original Copy</td>
                                            <td>Scanned Copy</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($salesall)){
                                            $data2 = array();
                                            foreach($salesall as $sal) {
                                                $key = date("M. d,Y",strtotime($sal['billing_to'])).date("M. d,Y",strtotime($sal['billing_from']));
                                                if(!isset($data2[$key])) {
                                                    $data2[$key] = array(
                                                        'particular'=>array(),
                                                        'participant_name'=>array(),
                                                        'billing_id'=>array(),
                                                        'billing'=>date("M. d, Y",strtotime($sal['billing_from']))." - ".date("M. d, Y",strtotime($sal['billing_to'])),
                                                        'vatable_sales'=>array(),
                                                        'vat_on_sales'=>array(),
                                                        'ewt'=>array(),
                                                        'zero_rated'=>array(),
                                                        'total'=>array(),
                                                        'ewt_amount'=>array(),
                                                        'original_copy'=>array(),
                                                        'scanned_copy'=>array(),
                                                        'sales_detail_id'=>array(),
                                                    );
                                                }
                                                $data2[$key]['participant_name'][] = $sal['participant_name'];
                                                $data2[$key]['billing_id'][] = $sal['billing_id'];
                                                $data2[$key]['sales_detail_id'][] = $sal['sales_detail_id'];
                                                $data2[$key]['vatable_sales'][] = number_format($sal['vatable_sales'],2);
                                                $data2[$key]['vat_on_sales'][] = number_format($sal['vat_on_sales'],2);
                                                $data2[$key]['ewt'][] = "(".number_format($sal['ewt'],2).")";
                                                $data2[$key]['zero_rated'][] = number_format($sal['zero_rated'],2);
                                                $data2[$key]['total'][] = number_format($sal['total'],2);
                                                $data2[$key]['ewt_amount'][] = number_format($sal['ewt_amount'],2);
                                                $data2[$key]['original_copy'][] =($sal['original_copy']=='0') ? 'NO' : 'YES';
                                                $data2[$key]['scanned_copy'][] = ($sal['scanned_copy']=='0') ? 'NO' : 'YES';
                                            }
                                            foreach($data2 AS $sa){
                                        ?>
                                        <tr>
                                            <td class="pt-3 pb-1 pr-0 pl-0" colspan="9">
                                                <u><?php echo $sa['billing']; ?></u>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['billing_id']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['participant_name']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vatable_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['zero_rated']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vat_on_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['ewt']); ?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['total']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['ewt_amount']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['original_copy']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['scanned_copy']);?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
