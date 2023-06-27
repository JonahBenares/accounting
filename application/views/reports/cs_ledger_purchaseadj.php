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
                                    <h4>Customer Subsidiary Ledger (Purchase Adjustment)</h4>
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
                                            <td width="20%">
                                                <select class="form-control select2" name="participant" id="participant" onchange='getReferencePurchAdj()'>
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->tin;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="15%">
                                                <select id="year" class="form-control" name="year" onchange='getReferencePurchAdj()'>
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
                                                    <input placeholder="Due Date From" id="date_from" class="form-control" type="text" onfocus="(this.type='date')" onchange='getReferencePurchAdj()'>
                                            </td>
                                            <td width="20%">
                                                    <input placeholder="Due Date To" id="date_to" class="form-control" type="text" onfocus="(this.type='date')" onchange='getReferencePurchAdj()'>
                                            </td>
                                            <td width="20%">
                                                <select class="form-control select2" name="reference_no" id="reference_no"></select>
                                            </td>
                                            <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterCSLedgerpurchaseadj();" class="btn btn-primary btn-block">Filters</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php 
                                if(!empty($participants) || !empty($referenceno) || !empty($year) || !empty($from) || !empty($to)){
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width=""><b>Participant Name:</b></td>
                                    <td width=""><?php echo $participants ?></td>
                                    <td width=""></td>

                                    <td width=""></td>
                                    <td width=""><b>Reference Number:</b></td>
                                    <td width=""><?php echo $referenceno ?></td>

                                    <td width=""><b>Year:</b></td>
                                    <td width=""><?php echo ($year!='null') ? $year : '' ?></td>
                                    
                                    <td width=""><b>Due Date From / Due Date To:</b></td>
                                    <td width=""><?php echo $from."-".$to ?></td>
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
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vatable Purchase</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">Vat</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" colspan="3" align="center">EWT</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Payment</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Payment</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Billing</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Payment</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Balance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            //$csledger = array_map("unserialize", array_unique(array_map("serialize", $csledger)));
                                             //sales
                                            $sum_amount=array();
                                            $sum_vat_on_sales=array();
                                            $sum_ewt=array();
                                            //collection
                                            $sum_camountarr=array();
                                            $sum_cvatonsal_amountarr=array();
                                            $sum_cewt_amountarr=array();
                                            if(!empty($csledger)){  
                                                $shortname_last='';
                                                $referenceno_last='';
                                                foreach($csledger as $b) {
                                                    $short_name=$b['short_name'];
                                                    $reference_number=$b['reference_no'];
                                                    //echo $b['count_payment']."-".$b['short_name']."-".$b['reference_no']."<br>";
                                        ?>
                                        <tr>
                                            <?php 
                                                //if($short_name!=$shortname_last){ 
                                                //purchase sum
                                                $sum_amount[]=$ci->purchase_adjustment_sum($b['short_name'],$b['reference_no'],'vatables_purchases');
                                                $sum_vat_on_sales[]=$ci->purchase_adjustment_sum($b['short_name'],$b['reference_no'],'vat_on_purchases');
                                                $sum_ewt[]=$ci->purchase_adjustment_sum($b['short_name'],$b['reference_no'],'ewt');
                                                //payment sum
                                                $sum_camountarr[]=$ci->payment_sum($b['short_name'],$b['payment_id'],'purchase_amount');
                                                $sum_cvatonsal_amountarr[]=$ci->payment_sum($b['short_name'],$b['payment_id'],'vat');
                                                $sum_cewt_amountarr[]=$ci->payment_sum($b['short_name'],$b['payment_id'],'ewt');
                                            ?>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" align="center"><?php echo $b['date']; ?></td>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" align="center"><?php echo $b['due_date']; ?></td>
                                            <td align="left" class="td-sticky left-col-1 sticky-back" style="width:6%!important" align="center"><?php echo $b['reference_no']; ?></td>
                                            <td align="left" class="td-sticky left-col-2 sticky-back" style="width:18%!important">
                                                <?php echo $b['company_name']; ?>
                                            </td>
                                            <td align="left" class="td-sticky left-col-3 sticky-back" style='width: 9%!important;'><?php echo ($b['billing_from']!='' && $b['billing_to']!='') ? date("F d,Y",strtotime($b['billing_from']))." - ".date("F d,Y",strtotime($b['billing_to'])) : ''; ?></td>

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

