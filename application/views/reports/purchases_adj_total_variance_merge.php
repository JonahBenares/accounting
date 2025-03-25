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
                                    <div class="d-flex justify-content-start">
                                        <span class="badge badge-primary badge-sm" style="margin-right:10px">MERGE</span><h4>Summary of Purchases Total Amount Variance - Adjustment</h4>
                                    </div>
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
                                                    <button type="button" onclick="filterPurchasesAdjTotal();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <table class="table-bordesred" width="100%">
                                <tr>
                                    <td width="5%"></td>
                                    <td width="3%"><b>Date From:</b></td>
                                    <td width="5%">01/05/21</td>
                                    <td width="5%"></td>
                                    <td width="3%"><b>Date To.:</b></td>
                                    <td width="5%">01/01/25</td>
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
                                        <tr>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                            <td align="center" class=""></td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr>
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="4">TOTAL</td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"></td>
                                        </tr>
                                    </tfoot>
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
                    <input type='button' class="btn btn-primary"  onclick="exportPurchasesAdjTotal()" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>