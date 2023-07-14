<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<?php $ci =& get_instance(); ?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-4">
                                    <h4>Customer Subsidiary Ledger </h4>
                                </div>
                                <div class="col-8">
                                    <!-- <button class="btn btn-primary btn-sm pull-right"><span class="fas fa-print"></span> Print</button> -->
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <table width="100%">
                                        <tr>
                                            <td width="20%">
                                                <select id="year" class="form-control" name="year" onchange='getReference()'>
                                                    <option value="">--Select Year--</option>
                                                    <?php 
                                                        $years=date('Y');
                                                        for($x=2020;$x<=$years;$x++){
                                                    ?>
                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="month" id='month' class="form-control select2" style="color:black!important" onchange='getReference()' multiple>
                                                    <!-- <option value="">--Select Month--</option> -->
                                                    <option value="'1'">January</option>
                                                    <option value="'2'">February</option>
                                                    <option value="'3'">March</option>
                                                    <option value="'4'">April</option>
                                                    <option value="'5'">May</option>
                                                    <option value="'6'">June</option>
                                                    <option value="'7'">July</option>
                                                    <option value="'8'">August</option>
                                                    <option value="'9'">September</option>
                                                    <option value="'10'">October</option>
                                                    <option value="'11'">November</option>
                                                    <option value="'12'">December</option>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="reference_no" id='reference_no' class="form-control select2" multiple>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="participant" id='participant' class="form-control select2">
                                                    <option value="">--Select Participant--</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                    <option value="<?php echo $p->tin; ?>"><?php echo $p->participant_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <!-- <td width="20%">
                                                    <input placeholder="Date From" id="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                            </td>
                                            <td width="20%">
                                                    <input placeholder="Date To" id="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                            </td> -->
                                            <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterCSLedger();" class="btn btn-primary btn-block">Filters</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            if(!empty($part) || !empty($year) || !empty($month)){
                                $remqt=str_replace("'","",$month);
                                $exp=explode(',',$remqt);
                                $mnth=array();
                                foreach($exp AS $e){
                                    $months   = DateTime::createFromFormat('!m', $e);
                                    $mnth[]= ($months!='') ? $months->format('F') : ''; // March
                                }
                                $mnth_imp=implode(', ',$mnth);
                                // $months   = DateTime::createFromFormat('!m', $month);
                                // $mnth = ($months!='') ? $months->format('F') : ''; // March
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width=""></td>
                                    <td width=""><b>Reference Number:</b></td>
                                    <td width=""><?php echo $refno ?></td>

                                    <td width=""><b>Year:</b></td>
                                    <td width=""><?php echo ($year!='null') ? $year : '' ?></td>
                                    
                                    <td width=""><b>Month:</b></td>
                                    <td width=""><?php echo $mnth_imp ?></td>
                                    <td width=""></td>

                                    <td width=""><b>Participant:</b></td>
                                    <td width=""><?php echo $part ?></td>
                                    <td width=""></td>
                                </tr>
                            </table>
                            <br>
                            <div style="overflow-x:scroll; min-height: 500px; height:550px">
                                <table class="table table-bordered table-hover mb-0" style="width:200%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-1" rowspan="2" align="center">Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-1" style="width:6%!important" rowspan="2" align="center">Due Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-1" rowspan="2" align="center">Transaction No.</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-2" rowspan="2" align="center">Participant Name</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head td-sticky-hd left-col-3" rowspan="2" align="center">Description</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vatable Sales</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Sales</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Zero-Rated Ecozone</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vat</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">EWT</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Collection</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            //$csledger = array_map("unserialize", array_unique(array_map("serialize", $csledger)));
                                            //sales
                                            $sum_amount=array();
                                            $sum_zerorated=array();
                                            $sum_zeroratedeco=array();
                                            $sum_vat_on_sales=array();
                                            $sum_ewt=array();
                                            //collection
                                            $sum_camountarr=array();
                                            $sum_czerorated_amountarr=array();
                                            $sum_czeroratedeco_amountarr=array();
                                            $sum_cvatonsal_amountarr=array();
                                            $sum_cewt_amountarr=array();
                                            if(!empty($csledger)){
                                                $shortname_last='';
                                                $referenceno_last='';
                                                foreach($csledger as $b) {
                                                    $short_name=$b['short_name'];
                                                    $reference_number=$b['reference_no'];
                                                    //echo $b['count_collection']."-".$b['short_name']."-".$b['reference_no']."<br>";
                                        ?>
                                        <tr>
                                            <?php 
                                                //if($short_name!=$shortname_last){ 
                                                //sales sum
                                                $sum_amount[]=$ci->sales_sum($b['short_name'],$b['reference_no'],'vatable_sales');
                                                $sum_zerorated[]=$ci->sales_sum($b['short_name'],$b['reference_no'],'zero_rated_sales');
                                                $sum_zeroratedeco[]=$ci->sales_sum($b['short_name'],$b['reference_no'],'zero_rated_ecozones');
                                                $sum_vat_on_sales[]=$ci->sales_sum($b['short_name'],$b['reference_no'],'vat_on_sales');
                                                $sum_ewt[]=$ci->sales_sum($b['short_name'],$b['reference_no'],'ewt');
                                                //collection sum
                                                $sum_camountarr[]=$ci->collection_sum($b['short_name'],$b['reference_no'],'amount');
                                                $sum_czerorated_amountarr[]=$ci->collection_sum($b['short_name'],$b['reference_no'],'zero_rated');
                                                $sum_czeroratedeco_amountarr[]=$ci->collection_sum($b['short_name'],$b['reference_no'],'zero_rated_ecozone');
                                                $sum_cvatonsal_amountarr[]=$ci->collection_sum($b['short_name'],$b['reference_no'],'vat');
                                                $sum_cewt_amountarr[]=$ci->collection_sum($b['short_name'],$b['reference_no'],'ewt');
                                            ?>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" align="center"><?php echo $b['date']; ?></td>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" align="center"><?php echo $b['due_date']; ?></td>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" style="width:6%!important" align="center"><?php echo $b['reference_no']; ?></td>
                                            <td align="left" class="td-sticky left-col-2 sticky-back" style="width:18%!important">
                                                <?php echo $b['company_name']; ?>
                                            </td>
                                            <td align="left" class="td-sticky left-col-3 sticky-back"><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>

                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['vatable_sales'], "Total: 0.00") == false) ? $b['vatable_sales'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['cvatsal_amount'], "Total: 0.00") == false) ? $b['cvatsal_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_vatsal'];?></td>

                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['zero_rated_sales'], "Total: 0.00") == false) ? $b['zero_rated_sales'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['czerorated_amount'], "Total: 0.00") == false) ? $b['czerorated_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_zerorated'];?></td>

                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['zero_rated_ecozones'], "Total: 0.00") == false) ? $b['zero_rated_ecozones'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['czeroratedeco_amount'], "Total: 0.00") == false) ? $b['czeroratedeco_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_zeroratedeco'];?></td>

                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['vat_on_sales'], "Total: 0.00") == false) ? $b['vat_on_sales'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['cvatonsal_amount'], "Total: 0.00") == false) ? $b['cvatonsal_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_vatonsales'];?></td>

                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['ewt'], "Total: 0.00") == false) ? $b['ewt'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    echo (strpos($b['cewt_amount'], "Total: 0.00") == false) ? $b['cewt_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_ewt'];?></td>
                                            <?php //} ?>
                                        </tr>
                                        <?php 
                                            // $shortname_last=$short_name; 
                                            // $referenceno_last=$reference_number; 
                                            } } 
                                        ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr >
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="5">TOTAL</td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_amount),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_camountarr),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($balance_vatsalarr,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_zerorated),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_czerorated_amountarr),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($balance_zeroratedarr,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_zeroratedeco),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_czeroratedeco_amountarr),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($balance_zeroratedecoarr,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php  echo number_format(array_sum($sum_vat_on_sales),2); //echo number_format($bal_vatonsalesarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_cvatonsal_amountarr),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($balance_vatonsalesarr,2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_ewt),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_cewt_amountarr),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($balance_ewtarr,2); ?></td>
                                        </tr>
                                    </tfoot>
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
                    <div class="form-group">
                        <label>Year:</label>
                        <select name="year_export" id='year_export' class="form-control select2" onchange='getReferenceExport()'>
                            <option value="">--Select Year--</option>
                            <?php 
                                $years=date('Y');
                                for($x=2020;$x<=$years;$x++){
                            ?>
                                <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Month:</label>
                        <select name="month_export" id='month_export' class="form-control select2" onchange='getReferenceExport()' multiple>
                            <option value="'1'">January</option>
                            <option value="'2'">February</option>
                            <option value="'3'">March</option>
                            <option value="'4'">April</option>
                            <option value="'5'">May</option>
                            <option value="'6'">June</option>
                            <option value="'7'">July</option>
                            <option value="'8'">August</option>
                            <option value="'9'">September</option>
                            <option value="'10'">October</option>
                            <option value="'11'">November</option>
                            <option value="'12'">December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reference No:</label>
                        <select name="reference_no_export" id='reference_no_export' class="form-control select2" multiple></select>
                    </div>
                    <div>
                        <select name="participant_export" id='participant_export' class="form-control select2">
                            <option value="">--Select Participant--</option>
                            <?php foreach($participant AS $p){ ?>
                            <option value="<?php echo $p->tin; ?>"><?php echo $p->participant_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                    <input type='button' class="btn btn-primary"  onclick="export_cs_ledger()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
