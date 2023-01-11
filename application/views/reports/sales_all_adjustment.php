
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
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="30%">
                                                    <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="30%">
                                                     <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->settlement_id." - ".$p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_sales_adjustment_all()" value="Filter">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <br>

                        <?php 
                            if(!empty($part) || !empty($from) || !empty($to)){
                            ?>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="13%"><b>Date From:</b></td>
                                    <td width="25%"><?php echo $from ?></td>
                                    <td width="13%"></td>
                                    <td width="41%"></td>
                                    <td width="3%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><b>Date To:</b></td>
                                    <td><?php echo $to ?></td>
                                    <td><b>Participant Name:</b></td>
                                    <td><?php echo $part ?></td>
                                    <td></td>
                                </tr>
                            </table>
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
                                            if(!empty($salesad_all)){
                                            $data2 = array();
                                            foreach($salesad_all as $sal) {
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
                                                        'adjustment_detail_id'=>array(),
                                                    );
                                                }
                                                $data2[$key]['participant_name'][] = $sal['participant_name'];
                                                $data2[$key]['billing_id'][] = $sal['billing_id'];
                                                $data2[$key]['adjustment_detail_id'][] = $sal['adjustment_detail_id'];
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
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="left" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['billing_id']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="left" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['participant_name']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vatable_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['zero_rated']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vat_on_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['ewt']); ?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['total']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['ewt_amount']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['original_copy']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['scanned_copy']);?></td>
                                        </tr>
                                        <?php } } } ?>
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
