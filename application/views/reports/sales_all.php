
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
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <table width="100%">
                                        <tr>
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
<!-- <<<<<<< HEAD
                            <br>
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-s table-bordered table-hover" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <th>Billing Period</th>
                                            <th width="15%" style="position:sticky; left:0; z-index: 10;background: rgb(245 245 245);">BIlling ID</th> 
                                            <th width="15%" style="position:sticky; left:231px; z-index: 10;background: rgb(245 245 245);">Company Name</th>  
                                            <th>Vatable Sales</th> 
                                            <th>Zero-Rated Ecozones</th>     
                                            <th>VAT on Sales</th>   
                                            <th>EWT Sales</th>    
                                            <th>Total</th> 
                                            <th width="2%">Original Copy</th>
                                            <th width="2%">Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff;">BIlling ID</td> 
                                            <td style="position:sticky; left:231px; z-index: 10;background: #fff;">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td> 
                                            <td>Yes</td> 
                                            <td>No</td> 
                                        </tr>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff;">BIlling ID</td> 
                                            <td style="position:sticky; left:231px; z-index: 10;background: #fff;">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td> 
                                            <td>No</td> 
                                            <td>Yes</td> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
======= -->
                            <br>
                            <!-- s -->
                            
                            <!-- <hr class="m-b-0"> -->
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-bordered table-hover" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td width="15%" style="position:sticky; left:0; z-index: 10;background: rgb(245 245 245);">BIlling ID</td> 
                                            <td width="15%" style="position:sticky; left:231px; z-index: 10;background: rgb(245 245 245);">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td>
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
                                            }
                                            foreach($data2 AS $sa){
                                        ?>
                                        <tr>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo $sa['billing'];?></td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff;" class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['billing_id']);?></td>
                                            <td style="position:sticky; left:231px; z-index: 10;background: #fff;" class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['participant_name']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vatable_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['zero_rated']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['vat_on_sales']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['ewt']); ?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="right" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$sa['total']);?></td>
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

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?php echo base_url(); ?>masterfile/insert_employee" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>Billing Date From</label>
                            <input type="date" name="signature" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Billing Date To</label>
                            <input type="date" name="signature" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control select2" name="ref_no" id="ref_no">
                            <option value="">-- Select Company --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="submit" class="btn btn-success" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

