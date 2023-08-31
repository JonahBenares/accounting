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
                                <div class="col-4">
                                    <h4>OR Summary</h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="50%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date From" id="date_from" name="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date To" id="date_to" name="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="10%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <button type="button" onclick="filterOR();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            $ors=array();
                            $consolidated=array();
                            $missing=array();
                            $series=array();
                            if(!empty($part) || !empty($date_from) || !empty($date_to)){
                            ?>
                            <table class="table" width="100%">
                                <tr>
                                    <td width="7%"></td>
                                    <td width="13%"><b>Participant Name:</b></td>
                                    <td width="32%">: <?php echo $part ?></td>
                                    <td width="12%"><b>Date From - To:</b></td>
                                    <td width="33%">: <?php echo $date_from ?> - <?php echo $date_to ?></td>
                                    <td width="3%"></td>
                                </tr>

                            </table>
                            <br>
                            <div>
                                <table class="table table-bordered table-hover" id='table-3' width="100%">
                                    <thead>
                                        <tr>
                                            <td style="vertical-align:middle!important; border-bottom:1px solid #000" class="td-30 td-head" align="center">Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">OR No</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">STL ID</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Company Name</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Amount</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Remarks</td>
                                            <td  style="vertical-align:middle!important;" class="td-30 td-head" align="center" >Action</td>
                                        </tr>
                                    </thead>
                                  
                                <?php

                                $count = count($or_summary);
                                if($count == 1){
                                    $max++;
                                }
                                
                                for($a=$min;$a<=$max;$a++){
                                    $series[] = $a;

                                }
                              
                                foreach($or_summary AS $o){
                                    $ors[] = $o['or_no'];
                                }
                               
                                $result= array_diff($series,$ors); 
                               

                                foreach($result AS $r){
                                    $missing[] = array(
                                        "date"=>"",
                                        "or_no"=>$r,
                                        "stl_id"=>"",
                                        "company_name"=>"",
                                        "amount"=>"",
                                        "remarks"=>$this->super_model->select_column_where("or_remarks","remarks","or_no",$r),
                                    );
                                }

                                if($count > 1){
                                    if(!empty($missing) && !empty($or_summary)){
                                    $all = array_merge($missing,$or_summary);
                                    } else {
                                    $all=array();
                                    }
                                }else{
                                    $all = $or_summary;
                                }
                                
                                $columns = array_column($all, 'or_no');
                                array_multisort($columns, SORT_ASC, $all);

                                ?>
                                <tbody>
                                    <?php 
                                     
                                    foreach($all AS $a){ 
                                        ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo $a['date']; ?></td>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo $a['or_no']; ?></td>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo str_replace("-", "<br>", $a['stl_id'] ?? ''); ?></td>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo str_replace("-", "<br>",$a['company_name'] ?? ''); ?></td>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo str_replace("-", "<br>", $a['amount'] ?? ''); ?></td>
                                            <?php if(empty($a['date'])){ ?>
                                            <td style="border-bottom: 1px solid #e5e5e5;"><?php echo str_replace("-", "<br>",$a['remarks'] ?? ''); ?></td>
                                            <?php } else { ?>
                                                <td style="border-bottom: 1px solid #e5e5e5;"></td>
                                            <?php } ?>
                                            <?php if(empty($a['date']) && empty($a['remarks'])){ ?>
                                                <td style="border-bottom: 1px solid #e5e5e5;" align="center" class="left-col-1 ">
                                                    <a href='#' onclick="ignoreOR('<?php echo base_url(); ?>','<?php echo $a['or_no']; ?>','<?php echo $date_from; ?>','<?php echo $date_to; ?>','<?php echo $a['stl_id']; ?>')" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to ignore this OR?')" data-toggle="tooltip" data-placement="bottom" title="Ignore" data-original-title="Ignore"><span class="fas fa-ban ml-0"></span></a>
                                                    <a href='#' onclick="cancelOR('<?php echo base_url(); ?>','<?php echo $a['or_no']; ?>','<?php echo $date_from; ?>','<?php echo $date_to; ?>','<?php echo $a['stl_id']; ?>')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Cancel" data-original-title="Cancel"><span class="fas fa-times ml-1 mr-1 "></span></a>
                                                </td>
                                            <?php } else { ?>
                                                <td style="border-bottom: 1px solid #e5e5e5;"></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                     <?php }else{ ?>
                            <div><center><b>No Available Data...</b></center></div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
