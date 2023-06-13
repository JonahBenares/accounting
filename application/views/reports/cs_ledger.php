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
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <table width="100%">
                                        <tr>
                                            <!-- <td>
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td> -->
                                            <td width="20%">
                                                <select id="year" class="form-control" name="year">
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
                                                <select name="month" id='month' class="form-control select2" onchange='getReference()'>
                                                    <option value="" selected>--Select Month--</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select name="reference_no" id='reference_no' class="form-control select2" multiple>
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
                                $months   = DateTime::createFromFormat('!m', $month);
                                $mnth = ($months!='') ? $months->format('F') : ''; // March
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width=""></td>
                                    <td width=""><b>Reference Number:</b></td>
                                    <td width=""><?php echo $refno ?></td>

                                    <td width=""><b>Year:</b></td>
                                    <td width=""><?php echo ($year!='null') ? $year : '' ?></td>
                                    
                                    <td width=""><b>Month:</b></td>
                                    <td width=""><?php echo $mnth ?></td>
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
                                            $total_val_sal=array();
                                            $total_cval_sal=array();
                                            $diff_total_val_sal=array();

                                            $total_zerorated_sal=array();
                                            $total_czerorated_sal=array();
                                            $total_zeroratedeco_sal=array();

                                            $total_czeroratedeco_sal=array();
                                            $diff_total_zerorated_sal=array();
                                            $diff_total_zeroratedeco_sal=array();

                                            $total_vat_on_sal=array();
                                            $total_cvat_on_sal=array();
                                            $diff_total_vat_on_sal=array();

                                            $total_ewt_sal=array();
                                            $total_cewt_sal=array();
                                            $diff_total_ewt_sal=array();
                                            if(!empty($csledger)){
                                                //$csledger = array_map("unserialize", array_unique(array_map("serialize", $csledger)));
                                                $data2 = array();
                                                $amount=0;
                                                $shortnamelast='';
                                                foreach($csledger as $sal) {
                                                    $key = $sal['date'];
                                                    //echo $sal['short_name']."<br>";
                                                    if(!isset($data2[$key])) {
                                                        $data2[$key] = array(
                                                            'date'=>$sal['date'],
                                                            'due_date'=>$sal['due_date'],
                                                            'company_name'=>array(),
                                                            'short_name'=>array(),
                                                            'reference_no'=>array(),
                                                            'billing_from'=>$sal['billing_from'],
                                                            'billing_to'=>$sal['billing_to'],
                                                            'vatable_sales'=>array(),
                                                            'vatable_sales_sum'=>$sal['vatable_sales_sum'],
                                                            'cvatsal_amount'=>array(),
                                                            'zero_rated_sales'=>array(),
                                                            'zero_rated_sales_sum'=>$sal['zero_rated_sales_sum'],
                                                            'czerorated_amount'=>array(),
                                                            'zero_rated_ecozones'=>array(),
                                                            'zero_rated_ecozones_sum'=>$sal['zero_rated_ecozones_sum'],
                                                            'czeroratedeco_amount'=>array(),
                                                            'vat_on_sales'=>array(),
                                                            'vat_on_sales_sum'=>$sal['vat_on_sales_sum'],
                                                            'cvatonsal_amount'=>array(),
                                                            'ewt'=>array(),
                                                            'ewt_sum'=>$sal['ewt_sum'],
                                                            'cewt_amount'=>array()
                                                            // 'vatablebalance'=>array(),
                                                            // 'zerobalance'=>array(),
                                                            // 'zeroecobalance'=>array(),
                                                            // 'vatbalance'=>array(),
                                                            // 'ewtbalance'=>array(),
                                                            // 'cvatable_sales'=>array(),
                                                            // 'czero_rated_sales'=>array(),
                                                            // 'czero_rated_ecozone'=>array(),
                                                            // 'cvat_on_sales'=>array(),
                                                            // 'cewt'=>array()
                                                        );
                                                    }
                                                    //$amount.=$ci->collection_display($sal['short_name'],$sal['reference_no'],'amount')." <br><span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($sal['short_name'],$sal['reference_no'],'amount'),2)."</span><br>";
                                                    // $amount.=$ci->collection_display($sal['item_no'],$sal['short_name'],$sal['reference_no'],'amount')." <br>";
                                                    $data2[$key]['reference_no'][] = "'".$sal['reference_no']."'";
                                                    $data2[$key]['company_name'][] = $sal['company_name'];
                                                    $data2[$key]['short_name'][] = "'".$sal['short_name']."'";
                                                    $shortname=$sal['short_name'];
                                                    if($shortname!=$shortnamelast){
                                                        $data2[$key]['vatable_sales'][] = $sal['vatable_sales'];
                                                        $data2[$key]['cvatsal_amount'][] = $sal['cvatsal_amount'];
                                                        $data2[$key]['zero_rated_sales'][] = $sal['zero_rated_sales'];
                                                        $data2[$key]['czerorated_amount'][] = $sal['czerorated_amount'];
                                                        $data2[$key]['zero_rated_ecozones'][] = $sal['zero_rated_ecozones'];
                                                        $data2[$key]['czeroratedeco_amount'][] = $sal['czeroratedeco_amount'];
                                                        $data2[$key]['vat_on_sales'][] = $sal['vat_on_sales'];
                                                        $data2[$key]['cvatonsal_amount'][] = $sal['cvatonsal_amount'];
                                                        $data2[$key]['ewt'][] = $sal['ewt'];
                                                        $data2[$key]['cewt_amount'][] = $sal['cewt_amount'];
                                                    }
                                                    $shortnamelast=$shortname;
                                                    
                                                    // $data2[$key]['vatablebalance'][] = number_format($sal['vatablebalance'],2);
                                                    // $data2[$key]['zerobalance'][] = number_format($sal['zerobalance'],2);
                                                    // $data2[$key]['zeroecobalance'][] = number_format($sal['zeroecobalance'],2);
                                                    // $data2[$key]['vatbalance'][] =number_format($sal['vatbalance'],2);
                                                    // $data2[$key]['ewtbalance'][] = number_format($sal['ewtbalance'],2);
                                                    // $data2[$key]['cvatable_sales'][] = $sal['cvatable_sales'];
                                                    // $data2[$key]['czero_rated_sales'][] = number_format($sal['czero_rated_sales'],2);
                                                    // $data2[$key]['czero_rated_ecozone'][] = number_format($sal['czero_rated_ecozone'],2);
                                                    // $data2[$key]['cvat_on_sales'][] = number_format($sal['cvat_on_sales'],2);
                                                    // $data2[$key]['cewt'][] = number_format($sal['cewt'],2);
                                                }
                                                $shortname_last='';
                                                $referenceno_last='';
                                                foreach($csledger as $b) {
                                                    $short_name=$b['short_name'];
                                                    $reference_number=$b['reference_no'];
                                                    // $total_val_sal[]=$b['vatable_sales_sum'];
                                                    // $total_cval_sal[]=$ci->collection_sum($s_name,$ref_no,'amount');
                                                    // $diff_val_sal=$b['vatable_sales_sum']-$ci->collection_sum($s_name,$ref_no,'amount');
                                                    // $diff_total_val_sal[]=$b['vatable_sales_sum']-$ci->collection_sum($s_name,$ref_no,'amount');

                                                    // $total_zerorated_sal[]=$b['zero_rated_sales_sum'];
                                                    // $total_czerorated_sal[]=$ci->collection_sum($s_name,$ref_no,'zero_rated');
                                                    // $diff_zerorated_sal=$b['zero_rated_sales_sum']-$ci->collection_sum($s_name,$ref_no,'zero_rated');
                                                    // $diff_total_zerorated_sal[]=$b['zero_rated_sales_sum']-$ci->collection_sum($s_name,$ref_no,'zero_rated');

                                                    // $total_zeroratedeco_sal[]=$b['zero_rated_ecozones_sum'];
                                                    // $total_czeroratedeco_sal[]=$ci->collection_sum($s_name,$ref_no,'zero_rated_ecozone');
                                                    // $diff_zeroratedeco_sal=$b['zero_rated_ecozones_sum']-$ci->collection_sum($s_name,$ref_no,'zero_rated_ecozone');
                                                    // $diff_total_zeroratedeco_sal[]=$b['zero_rated_ecozones_sum']-$ci->collection_sum($s_name,$ref_no,'zero_rated_ecozone');

                                                    // $total_vat_on_sal[]=$b['vat_on_sales_sum'];
                                                    // $total_cvat_on_sal[]=$ci->collection_sum($s_name,$ref_no,'vat');
                                                    // $diff_vat_on_sal=$b['vat_on_sales_sum']-$ci->collection_sum($s_name,$ref_no,'vat');
                                                    // $diff_total_vat_on_sal[]=$b['vat_on_sales_sum']-$ci->collection_sum($s_name,$ref_no,'vat');

                                                    // $total_ewt_sal[]=$b['ewt_sum'];
                                                    // $total_cewt_sal[]=$ci->collection_sum($s_name,$ref_no,'ewt');
                                                    // $diff_ewt_sal=$b['ewt_sum']-$ci->collection_sum($s_name,$ref_no,'ewt');
                                                    // $diff_total_ewt_sal[]=$b['ewt_sum']-$ci->collection_sum($s_name,$ref_no,'ewt');
                                        ?>
                                        <tr>
                                            <?php if($short_name!=$shortname_last){ ?>
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
                                                    // $amount=$ci->collection_display($s_name,$ref_no,'amount')." <span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($s_name,$ref_no,'amount'),2)."</span>";
                                                    //echo $b['cvatsal_amount'];
                                                    echo (strpos($b['cvatsal_amount'], "Total: 0.00") == false) ? $b['cvatsal_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_vatsal'];?></td>

                                            <td align="right">
                                                <?php 
                                                    //echo $b['zero_rated_sales']; 
                                                    echo (strpos($b['zero_rated_sales'], "Total: 0.00") == false) ? $b['zero_rated_sales'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    // $zero_rated_sales=$ci->collection_display($s_name,$ref_no,'zero_rated')." <span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($s_name,$ref_no,'zero_rated'),2)."</span>";
                                                    //echo $b['czerorated_amount'];
                                                    echo (strpos($b['czerorated_amount'], "Total: 0.00") == false) ? $b['czerorated_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_zerorated'];?></td>

                                            <td align="right">
                                                <?php 
                                                    //echo $b['zero_rated_ecozones']; 
                                                    echo (strpos($b['zero_rated_ecozones'], "Total: 0.00") == false) ? $b['zero_rated_ecozones'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    // $zero_rated_ecozone=$ci->collection_display($s_name,$ref_no,'zero_rated_ecozone')." <span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($s_name,$ref_no,'zero_rated_ecozone'),2)."</span>";
                                                    //echo $b['czeroratedeco_amount'];
                                                    echo (strpos($b['czeroratedeco_amount'], "Total: 0.00") == false) ? $b['czeroratedeco_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_zeroratedeco'];?></td>

                                            <td align="right">
                                                <?php 
                                                    //echo $b['vat_on_sales']; 
                                                    echo (strpos($b['vat_on_sales'], "Total: 0.00") == false) ? $b['vat_on_sales'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    // $vat_on_sales=$ci->collection_display($s_name,$ref_no,'vat')." <span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($s_name,$ref_no,'vat'),2)."</span>";
                                                    //echo $b['cvatonsal_amount'];
                                                    echo (strpos($b['cvatonsal_amount'], "Total: 0.00") == false) ? $b['cvatonsal_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_vatonsales'];?></td>

                                            <td align="right">
                                                <?php 
                                                    //echo $b['ewt']; 
                                                    echo (strpos($b['ewt'], "Total: 0.00") == false) ? $b['ewt'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php 
                                                    // $ewt=$ci->collection_display($s_name,$ref_no,'ewt')." <span class='td-30 td-yellow'> Total: ".number_format($ci->collection_sum($s_name,$ref_no,'ewt'),2)."</span>";
                                                    //echo $b['cewt_amount'];
                                                    echo (strpos($b['cewt_amount'], "Total: 0.00") == false) ? $b['cewt_amount'] : "0.00<br><span class='td-30 td-yellow'>Total: 0.00</span>"; 
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $b['balance_ewt'];?></td>
                                            <?php } ?>
                                        </tr>
                                        <?php 
                                            $shortname_last=$short_name; 
                                            $referenceno_last=$reference_number; 
                                            } } 
                                        ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr >
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="5">TOTAL</td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_amountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_camountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($diff_total_val_sal),2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_zeroratedarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_czerorated_amountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($diff_total_zerorated_sal),2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_zeroratedecoarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_czeroratedeco_amountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($diff_total_zeroratedeco_sal),2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_vatonsalesarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_cvatonsal_amountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($diff_total_vat_on_sal),2); ?></td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_ewtarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($bal_cewt_amountarr,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($diff_total_ewt_sal),2); ?></td>
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

