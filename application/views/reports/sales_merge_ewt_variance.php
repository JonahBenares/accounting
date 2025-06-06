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
                                    <h4>Summary of Sales Total EWT Variance - Merge</h4>
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
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <input placeholder="Billing Date From" class="form-control" id="from" name="from" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="15%">
                                                    <input placeholder="Billing Date To" class="form-control" id="to" name="to" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterSalesMergeEWT();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <br>
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
                                <table class="table table-bordered table-hover mb-0" id="table-1" style="width:100%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="1"align="center">Billing Date</td>
                                            <td style="vertical-align:middle!important;" class="2"align="center">Transaction No</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Billing ID</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">EWT Total Amount</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Overall Total Amount</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">EWT Amount Collected</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Overall Amount Collected</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Variance</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Total Variance</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($salesmain_ewt)){
                                            $data2 = array();
                                            foreach($salesmain_ewt as $sse) {
                                                $key = $sse['tin'];
                                                if(!isset($data2[$key])) {
                                                    $data2[$key] = array(
                                                        'billing_from'=>array(),
                                                        'billing_to'=>array(),
                                                        'billing_to'=>array(),
                                                        'transaction_no'=>array(),
                                                        'billing_id'=>array(),
                                                        'ewt_amount'=>array(),
                                                        'overall_ewt_amount'=>$sse['overall_ewt_amount'],
                                                        'ewt_collected'=>array(),
                                                        'overall_ewt_collected'=>$sse['overall_ewt_collected'],
                                                        'variance'=>array(),
                                                        'total_variance'=>$sse['total_variance'],
                                                    );
                                                }
                                                $data2[$key]['billing_date'][] = date("M. d, Y",strtotime($sse['billing_from']))." - ".date("M. d, Y",strtotime($sse['billing_to']));
                                                $data2[$key]['transaction_no'][] = $sse['transaction_no'];
                                                $data2[$key]['billing_id'][] = $sse['billing_id'];
                                                $data2[$key]['ewt_amount'][] = number_format($sse['ewt_amount'],2);
                                                $data2[$key]['ewt_collected'][] = number_format($sse['ewt_collected'],2);
                                                $data2[$key]['variance'][] = number_format($sse['variance'],2);
                                            }
                                            $overall_total_ewt=array();
                                            $o_total_ewt_collected=array();
                                            $o_total_variance=array();
                                            foreach($data2 AS $sa){
                                                $o_total_ewt_amount[]=$sa['overall_ewt_amount'];
                                                $o_total_ewt_collected[]=$sa['overall_ewt_collected'];
                                                $o_total_variance[]=$sa['total_variance'];
                                        ?>
                                        <tr>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['billing_date']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['transaction_no']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['billing_id']);?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['ewt_amount']);?></td>
                                            <td align="center" class=""><?php echo number_format($sa['overall_ewt_amount'],2); ?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['ewt_collected']);?></td>
                                            <td align="center" class=""><?php echo number_format($sa['overall_ewt_collected'],2); ?></td>
                                            <td align="center" class=""><?php echo implode("<hr style='margin:0px'>",$sa['variance']);?></td>
                                            <td align="center" class=""
                                             <?php if($sa['total_variance'] == 0){
                                                echo "style='color:green'";
                                            } else if($sa['overall_ewt_amount'] < $sa['overall_ewt_collected']) {
                                                echo "style='color:blue'";
                                            } else if($sa['overall_ewt_amount'] > $sa['overall_ewt_collected']) {
                                                echo "style='color:red'";
                                            } ?>>
                                                <?php echo number_format($sa['total_variance'],2); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr>
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="3">TOTAL</td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($b_total_ewt,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($o_total_ewt_amount),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format($b_total_ewt_amount,2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($o_total_ewt_collected),2); ?></td>
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
                    <input type='button' class="btn btn-primary"  onclick="exportSalesMergeEWT()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
