
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<style type="text/css">
    .table_td{border:1px solid #ddd!important}
</style>
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
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="15%">
                                                    <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="30%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->tin;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="15%">
                                                <select class="form-control" name="og_copy" id="og_copy">
                                                    <option value="">Original Copy</option>
                                                    <option value="1">YES</option>
                                                    <option value="0">NO</option>
                                                </select>
                                                </td>
                                                <td  width="15%">
                                                <select class="form-control" name="s_copy" id="s_copy">
                                                    <option value="">Scanned Copy</option>
                                                    <option value="1">YES</option>
                                                    <option value="0">NO</option>
                                                </select>
                                                </td>
                                                <td  width="10%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_purchases_all()" value="Filter">
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
                            <table class="table-bordesred" width="100%">
                                <tr>
                                    <td></td>
                                    <td><b>Participant Name:</b></td>
                                    <td colspan="4"><?php echo $part ?></td>
                                </tr>
                                <tr>
                                    <td width="3%"></td>
                                    <td width="13%"><b>Date From:</b></td>
                                    <td width="25%"><?php echo $from ?></td>
                                    <td><b>Original Copy:</b></td>
                                    <?php if($original != 'null'){ ?>
                                        <td><?php echo ($original=='0') ? 'NO' : 'YES'; ?></td>
                                    <?php }else{ ?>
                                        <td><?php echo $original; ?></td>
                                    <?php } ?>
                                    <td width="41%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><b>Date To:</b></td>
                                    <td><?php echo $to ?></td>
                                    <td><b>Scanned Copy:</b></td>
                                     <?php if($scanned != 'null'){ ?>
                                        <td><?php echo ($scanned=='0') ? 'NO' : 'YES'; ?></td>
                                    <?php }else{ ?>
                                        <td><?php echo $scanned; ?></td>
                                    <?php } ?>
                                    <td></td>
                                </tr>
                            </table>
                            <br>

                            <!-- <br>
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-bordered table-hover" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <th>Billing Period</th>
                                            <th width="15%" style="position:sticky; left:0; z-index: 10;background: rgb(245 245 245);">BIlling ID</th> 
                                            <th width="15%" style="position:sticky; left:231px; z-index: 10;background: rgb(245 245 245);">Company Name</th>  
                                            <th>Vatable Purchases</th> 
                                            <th>VAT on Purchases</th>    
                                            <th>EWT</th>    
                                            <th>Total</th> 
                                            <th width="2%">Original Copy</th>
                                            <th width="2%">Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td width="15%" style="position:sticky; left:0; z-index: 10;background: #fff;">BIlling ID</td> 
                                            <td width="15%" style="position:sticky; left:231px; z-index: 10;background: #fff;">Company Name</td>  
                                            <td>Vatable Purchases</td> 
                                            <td>VAT on Purchases</td>    
                                            <td>EWT</td>    
                                            <td>Total</td> 
                                            <td>No</td> 
                                            <td>Yes</td> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                            <br> -->
                            <!-- s -->
                            
                            <!-- <hr class="m-b-0"> -->
                            <br>
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-bordered2" id="save-stage" style="width:120%;">
                                    <thead>
                                        <tr>
                                            <th class="table_td p-2" style="font-size: 12px;" width="10%">BIlling ID</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="10%">Transaction Reference Number</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="30%">Company Name</th>
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Vatable Purchases</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Zero-rated Purchases</th>    
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Zero-rated Ecozones Purchases</th>    
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">VAT on Purchases</th>    
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">EWT Purchases</th>    
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Total</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">OR Number</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Total Amount</th> 
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Original Copy</th>
                                            <th class="table_td p-2" style="font-size: 12px;" width="5%">Scanned Copy</th>
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
                                                        'reference_number'=>array(),
                                                        'billing'=>date("M. d, Y",strtotime($pal['billing_from']))." - ".date("M. d, Y",strtotime($pal['billing_to'])),
                                                        'vatables_purchases'=>array(),
                                                        'zero_rated_ecozones'=>array(),
                                                        'zero_rated_ecozones'=>array(),
                                                        'vat_on_purchases'=>array(),
                                                        'ewt'=>array(),
                                                        'total'=>array(),
                                                        'or_no'=>array(),
                                                        'total_update'=>array(),
                                                        'original_copy'=>array(),
                                                        'scanned_copy'=>array(),
                                                    );
                                                }
                                                $data2[$key]['participant_name'][] = ($pal['participant_name'] != '') ? $pal['participant_name'] : '<span style="background-color:#ffafaf;width:100%;display:block;color:#ffafaf">No Company Name</span>';
                                                $data2[$key]['billing_id'][] = $pal['billing_id'];
                                                $data2[$key]['reference_number'][] = $pal['reference_number'];
                                                $data2[$key]['vatables_purchases'][] = "(".number_format($pal['vatables_purchases'],2).")";
                                                $data2[$key]['zero_rated_purchases'][] = "(".number_format($pal['zero_rated_purchases'],2).")";
                                                $data2[$key]['zero_rated_ecozones'][] = "(".number_format($pal['zero_rated_ecozones'],2).")";
                                                $data2[$key]['vat_on_purchases'][] = "(".number_format($pal['vat_on_purchases'],2).")";
                                                $data2[$key]['ewt'][] = number_format($pal['ewt'],2);
                                                $data2[$key]['total'][] = "(".number_format($pal['total'],2).")";
                                                $data2[$key]['or_no'][] = ($pal['or_no']!='') ? $pal['or_no'] : '<br>' ;
                                                $data2[$key]['total_update'][] = "(".number_format($pal['total_update'],2).")";
                                                $data2[$key]['original_copy'][] =($pal['original_copy']=='0') ? 'NO' : 'YES';
                                                $data2[$key]['scanned_copy'][] = ($pal['scanned_copy']=='0') ? 'NO' : 'YES';
                                            }
                                            foreach($data2 AS $pa){
                                        ?>
                                        <tr>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-2" colspan="13" style="font-size: 12px; background: #e8f5ff;">
                                                <b><?php echo $pa['billing']; ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="left" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['billing_id']);?>
                                            </td>
                                             <td class="pt-1 table_td pb-1 pr-0 pl-0" align="left" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['reference_number']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="left" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['participant_name']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['vatables_purchases']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['zero_rated_purchases']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['zero_rated_ecozones']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['vat_on_purchases']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['ewt']); ?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['total']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['or_no']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['total_update']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['original_copy']);?>
                                            </td>
                                            <td class="pt-1 table_td pb-1 pr-0 pl-0" align="center" style="font-size: 12px;vertical-align: top;">
                                                <?php echo implode("<hr style='margin: 0px'>",$pa['scanned_copy']);?>
                                            </td>
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

<div class="modal fade" id="basicModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- <form method="POST" action="<?php echo base_url(); ?>masterfile/insert_employee" enctype="multipart/form-data"> -->
        <form method="POST">
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
                            <input placeholder="Date From" class="form-control" id="export_from" name="export_from" type="text" onfocus="(this.type='date')" id="date">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Billing Date to</label>
                            <input placeholder="Date To" class="form-control" id="export_to" name="export_to" type="text" onfocus="(this.type='date')" id="date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control select2" name="participant1" id="participant1">
                                <option value="">-- Select Participant --</option>
                            <?php foreach($participant AS $p){ ?>
                                <option value="<?php echo $p->tin;?>"><?php echo $p->participant_name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='baseurl1' id='baseurl1' value="<?php echo base_url(); ?>">
                    <input type='button' class="btn btn-primary"  onclick="export_purchasesall()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>