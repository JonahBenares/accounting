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
                                    <h4>Summary of Adjustment Billing Statement - <b>Sales</b></h4>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo base_url(); ?>reports/adjustment_sales_print" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <td width="22%">
                                                <select class="form-control select2" name="date" id="date">
                                                    <option value="">-- Select Transaction Date --</option>
                                                    <?php foreach($date AS $d){ ?>
                                                        <option value="<?php echo $d->transaction_date;?>"><?php echo date("F d,Y",strtotime($d->transaction_date));?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_adjusted_sales()" value="Filter">
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
                                    <td width="13%"><b>Transaction Date:</b></td>
                                    <td width="41%"><?php echo $date1 ?></td>
                                    <td width="3%"></td>
                                </tr>
                                <!-- <tr>
                                    <td></td>
                                    <td><b>Date Prepared:</b></td>
                                    <td></td>
                                    <td><b>Invoice Date:</b></td>
                                    <td></td>
                                    <td></td>
                                </tr> -->
                            </table>
                            <hr class="m-b-0">
                            <table class="table table-bordered table-hover mb-0" style="width:100%;font-size: 13px;">
                                <thead>
                                    <tr>
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="12%">Particular</td>
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="20%">Reference Number</td>  
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="18%">Billing Period</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vatable Amount</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Zero Rated Amount</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Net Sale (Php)</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vat on Energy </td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >EWT</td>
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" width="10%">Total Amount Due (Php)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="pt-1 pb-1" colspan="9" style="border-bottom:0px solid #fff">
                                            <b>RECEIVABLES</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1" colspan="9">
                                            <br>
                                            <u>Year 2016-2017</u>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1">Adjustment</td>
                                        <td class="pt-1 pb-1">TS-WAD-170F5-0000050</td>
                                        <td class="pt-1 pb-1">Jul 26 - Aug 25, 2020</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">-</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">28.88</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">(4.81)</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">264.69</td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1" colspan="9">
                                            <br>
                                            <u>Year 2016-2017</u>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1">Adjustment</td>
                                        <td class="pt-1 pb-1">TS-WAD-170F5-0000050</td>
                                        <td class="pt-1 pb-1">Jul 26 - Aug 25, 2020</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">-</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">28.88</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">(4.81)</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">264.69</td>
                                    </tr>
                                    <tr>
                                        <td class="pt-2 pb-2 td-yellow" colspan="8" align="left">TOTAL AMOUNT RECEIVABLE on or before, JUNE 25, 2022 &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right"><b>(6,228.42)</b></td>
                                    </tr>
                                </tbody>   
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
