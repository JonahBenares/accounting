<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/report.js"></script>
<style type="text/css">
    .hr{
        margin:2px 0px;
        border-top:1px solid rgb(0 0 0 / 5%);
    }
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
                                    <h4>Payment Report</h4>
                                </div>
                                <div class="col-4">
                                    <a href="" target="_blank" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <td width="22%">
                                                <select class="form-control select2" name="payment_date" id="payment_date">
                                                    <option value="">-- Select Date of Payment --</option>
                                                    <?php foreach($date AS $d){ ?>
                                                        <option value="<?php echo $d->payment_date;?>"><?php echo date("F d,Y",strtotime($d->payment_date));?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_payment_form()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="25%"></td>
                                    <td width="13%"><b>Payment Date:</b></td>
                                    <?php if(!empty($payment_date)){ ?>
                                    <td width="41%"><?php echo date("F d,Y",strtotime($payment_date)); ?></td>  
                                    <?php } else ?>
                                    <td width="41%"></td>
                                    <td width="3%"></td>
                                </tr>
                            </table>
                            <?php if(!empty($payment)){?>
                            <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                <thead>
                                    <tr>
                                        <td>Date of Payment</td>  
                                        <td>TOTAL</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php foreach($payment AS $pay){ ?>
                                    <tr>                                        
                                        <td><?php echo date("F d,Y",strtotime($pay['transaction_date']));?></td>
                                        <td>(<?php echo number_format($pay['total_amount'],2);?>)</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" title="View" data-original-title="View" target="_blank" href="<?php echo base_url(); ?>reports/payment_form/<?php echo $pay['payment_identifier'];?>"><span class="fas fa-eye m-0"></span></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
