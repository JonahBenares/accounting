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
                                    <h4>Summary of Purchases Total Amount Variance - Main</h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <!-- <td width="3%">
                                                    <b>Date From:</b>
                                                </td> -->
                                                <td width="15%">
                                                    <input placeholder="Billing Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <!-- <td width="5%"></td> -->
                                                <!-- <td width="3%">
                                                    <b>Date To:</b>
                                                </td> -->
                                                <td width="15%">
                                                    <input placeholder="Billing Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterPurchasesMainTotal();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <?php if(!empty($from) || !empty($to)){ ?>
                            <table class="table-bordesred" width="100%">
                                <tr>
                                    <td width="5%"></td>
                                    <td width="3%"><b>Date From:</b></td>
                                    <td width="5%"><?php echo $from; ?></td>
                                    <td width="5%"></td>
                                    <td width="3%"><b>Date To.:</b></td>
                                    <td width="5%"><?php echo $to; ?></td>
                                    <td width="10%"></td>
                                </tr>
                            </table>
                            <br>
                            <div >
                                <table class="table table-bordered table-hover mb-0" id="table-1"  style="width:100%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Due Date</td>
                                            <td style="vertical-align:middle!important;" class="1" align="center">Billing Date</td>
                                            <td style="vertical-align:middle!important;" class="2" align="center">Transaction No</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Billing ID</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Total Amount</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Overall Total Amount</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Total Collected Amount</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Overall Collected Amount</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Variance</td>
                                            <td style="vertical-align:middle!important;" class="3" align="center">Total Variance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($purchasesmain_total)){
                                            $data2 = array();
                                            foreach($purchasesmain_total as $sse) {
                                                $key = $sse['tin'];
                                                if(!isset($data2[$key])) {
                                                    $data2[$key] = array(
                                                        'billing_from'=>array(),
                                                        'billing_to'=>array(),
                                                        'due_date'=>array(),
                                                        'transaction_no'=>array(),
                                                        'billing_id'=>array(),
                                                        'total_amount'=>array(),
                                                        'overall_total_amount'=>$sse['overall_total_amount'],
                                                        'amount_collected'=>array(),
                                                        'overall_total_amount_collected'=>$sse['overall_total_amount_collected'],
                                                        'variance'=>array(),
                                                        'total_variance'=>$sse['total_variance'],
                                                    );
                                                }
                                                $data2[$key]['billing_date'][] = date("M. d, Y",strtotime($sse['billing_from']))." - ".date("M. d, Y",strtotime($sse['billing_to']));
                                                $data2[$key]['due_date'][] = date("M. d, Y",strtotime($sse['due_date']));
                                                $data2[$key]['transaction_no'][] = $sse['transaction_no'];
                                                $data2[$key]['billing_id'][] = $sse['billing_id'];
                                                $data2[$key]['total_amount'][] = number_format($sse['total_amount'],2);
                                                $data2[$key]['amount_collected'][] = number_format($sse['amount_collected'],2);
                                                $data2[$key]['variance'][] = number_format($sse['variance'],2);
                                            }
                                            $o_total_amount=array();
                                            $o_total_amount_collected=array();
                                            $o_total_variance=array();
                                            foreach($data2 AS $sa){
                                                $o_total_amount[]=$sa['overall_total_amount'];
                                                $o_total_amount_collected[]=$sa['overall_total_amount_collected'];
                                                $o_total_variance[]=$sa['total_variance'];
                                        ?>
                                        <tr>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['due_date']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['billing_date']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['transaction_no']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['billing_id']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['total_amount']);?></td>
                                            <td align="center" class=""><?php echo $sa['overall_total_amount']; ?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['amount_collected']);?></td>
                                            <td align="center" class=""><?php echo $sa['overall_total_amount_collected']; ?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['variance']);?></td>
                                            <td align="center" class=""
                                             <?php if($sa['total_variance'] == 0){
                                                echo "style='color:green'";
                                            } else if($sa['overall_total_amount'] < $sa['overall_total_amount_collected']) {
                                                echo "style='color:blue'";
                                            } else if($sa['overall_total_amount'] > $sa['overall_total_amount_collected']) {
                                                echo "style='color:red'";
                                            } ?>>
                                                <?php echo number_format($sa['total_variance'],2); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr>
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="4">TOTAL</td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($b_total_amount,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($o_total_amount),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($b_total_amount_collected,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($o_total_amount_collected),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($b_total_variance,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($o_total_variance),2); ?></td>
                                        </tr>
                                    </tfoot>
                                    <?php } } ?>
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
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='baseurl1' id='baseurl1' value="<?php echo base_url(); ?>">
                    <input type='button' class="btn btn-primary"  onclick="exportPurchasesMainTotal()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>