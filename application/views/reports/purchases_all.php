
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
                                    <h4>Consolidation/Summary of all Purchases Transaction</h4>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%">
                                        <tr>
                                            <td width="30%">
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Company --</option>
                                                </select>
                                            </td>
                                            <td width="30%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_sales()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br> -->
                            <!-- s -->
                            
                            <!-- <hr class="m-b-0"> -->
                            
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>Billing Period</td>
                                        <td>BIlling ID</td> 
                                        <td>Company Name</td>  
                                        <td>Vatable Purchases</td> 
                                        <td>VAT on Purchases</td>    
                                        <td>EWT</td>    
                                        <td>Total</td> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if(!empty($purchaseall)){
                                        $data2 = array();
                                        foreach($purchaseall as $pal) {
                                            $key = date("M. d,Y",strtotime($pal['billing_to'])).date("M. d,Y",strtotime($pal['billing_from']));
                                            if(!isset($data2[$key])) {
                                                $data2[$key] = array(
                                                    'particular'=>array(),
                                                    'participant_name'=>array(),
                                                    'billing_id'=>array(),
                                                    'billing'=>date("M. d, Y",strtotime($pal['billing_from']))." - ".date("M. d, Y",strtotime($pal['billing_to'])),
                                                    'vatables_purchases'=>array(),
                                                    'vat_on_purchases'=>array(),
                                                    'ewt'=>array(),
                                                    'total'=>array(),
                                                );
                                            }
                                            $data2[$key]['participant_name'][] = $pal['participant_name'];
                                            $data2[$key]['billing_id'][] = $pal['billing_id'];
                                            $data2[$key]['vatables_purchases'][] = number_format($pal['vatables_purchases'],2);
                                            $data2[$key]['vat_on_purchases'][] = number_format($pal['vat_on_purchases'],2);
                                            $data2[$key]['ewt'][] = "(".number_format($pal['ewt'],2).")";
                                            $data2[$key]['total'][] = number_format($pal['total'],2);
                                        }
                                        foreach($data2 AS $pa){
                                    ?>
                                    <tr>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo $pa['billing'];?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['billing_id']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['participant_name']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['vatables_purchases']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['vat_on_purchases']);?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['ewt']); ?></td>
                                        <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['total']);?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

