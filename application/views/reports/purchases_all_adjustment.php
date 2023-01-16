
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
                                    <h4>Consolidation/Summary of all Purchases Adjustment Transaction</h4>
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
                                <div class="col-lg-8 offset-lg-2">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input placeholder="Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Select Due Date --</option>
                                                        <?php foreach($date AS $d){ ?>
                                                            <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->settlement_id." - ".$p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="10%">
                                                <select class="form-control" name="og_copy" id="og_copy">
                                                    <option value="">Original Copy</option>
                                                    <option value="1">YES</option>
                                                    <option value="0">NO</option>
                                                </select>
                                                </td>
                                                <td  width="10%">
                                                <select class="form-control" name="s_copy" id="s_copy">
                                                    <option value="">Scanned Copy</option>
                                                    <option value="1">YES</option>
                                                    <option value="0">NO</option>
                                                </select>
                                                </td>
                                                <td  width="10%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_purchases_adjustment_all()" value="Filter">
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
                                    <td width="13%"><b>Original Copy:</b></td>
                                    <?php if($original != 'null'){ ?>
                                        <td><?php echo ($original=='0') ? 'NO' : 'YES'; ?></td>
                                    <?php }else{ ?>
                                        <td><?php echo $original; ?></td>
                                    <?php } ?>
                                    <td width="3%"></td>
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
                                <tr>
                                    <td></td>
                                    <td><b>Participant Name:</b></td>
                                    <td><?php echo $part ?></td>
                                    <td><b>Due Date:</b></td>
                                    <td><?php echo $due; ?></td>
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
                                <table class="table table-bordered table-hover" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <th width="15%" style="position:sticky; left:0; z-index: 10;background: rgb(245 245 245);">BIlling ID</th> 
                                            <th width="15%" style="position:sticky; left:231px; z-index: 10;background: rgb(245 245 245);">Company Name</th>  
                                            <th>Vatable Purchases</th> 
                                            <th>VAT on Purchases</th>    
                                            <th>EWT</th>    
                                            <th>Total</th> 
                                            <th>OR Number</th> 
                                            <th>Total Amount</th> 
                                            <th>Original Copy</th>
                                            <th>Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($purchasead_all)){
                                            $data2 = array();
                                            foreach($purchasead_all as $pal) {
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
                                                        'or_no'=>array(),
                                                        'total_update'=>array(),
                                                        'original_copy'=>array(),
                                                        'scanned_copy'=>array(),
                                                    );
                                                }
                                                $data2[$key]['participant_name'][] = $pal['participant_name'];
                                                $data2[$key]['billing_id'][] = $pal['billing_id'];
                                                $data2[$key]['vatables_purchases'][] = number_format($pal['vatables_purchases'],2);
                                                $data2[$key]['vat_on_purchases'][] = number_format($pal['vat_on_purchases'],2);
                                                $data2[$key]['ewt'][] = "(".number_format($pal['ewt'],2).")";
                                                $data2[$key]['total'][] = number_format($pal['total'],2);
                                                $data2[$key]['or_no'][] = $pal['or_no'];
                                                $data2[$key]['total_update'][] = number_format($pal['total_update'],2);
                                                $data2[$key]['original_copy'][] =($pal['original_copy']=='0') ? 'NO' : 'YES';
                                                $data2[$key]['scanned_copy'][] = ($pal['scanned_copy']=='0') ? 'NO' : 'YES';
                                            }
                                            foreach($data2 AS $pa){
                                        ?>
                                        <tr>
                                            <td class="pt-3 pb-1 pr-0 pl-0" colspan="10">
                                                <u><?php echo $pa['billing']; ?></u>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="left" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['billing_id']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="left" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['participant_name']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['vatables_purchases']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['vat_on_purchases']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['ewt']); ?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['total']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['or_no']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['total_update']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['original_copy']);?></td>
                                            <td class="pt-1 pb-1 pr-0 pl-0" align="center" style="font-size: 12px;"><?php echo implode("<hr class='hr'>",$pa['scanned_copy']);?></td>
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

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <label>Due Date</label>
                        <input placeholder="Due Date" class="form-control" id="due_date1" name="due_date1" type="text" onfocus="(this.type='date')" id="date">
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control select2" name="participant1" id="participant1">
                                <option value="">-- Select Participant --</option>
                            <?php foreach($participant AS $p){ ?>
                                <option value="<?php echo $p->settlement_id;?>"><?php echo $p->settlement_id." - ".$p->participant_name;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                    <input type='button' class="btn btn-primary"  onclick="export_purchasesall()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>