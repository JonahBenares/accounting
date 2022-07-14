
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
                                    <h4>Summary of Adjustment Billing Statement - <b>Purchases</b></h4>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo base_url(); ?>reports/adjustment_purchases_print" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%">
                                        <tr>
                                            <td width="20%">
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Month --</option>
                                                </select>
                                            </td>
                                            <td width="20%">
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Year --</option>
                                                </select>
                                            </td>
                                            <td width="50%">
                                                <!-- <input placeholder="Reference Number" class="form-control" type="text" id="ref_no" name="ref_no"> -->
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Reference Number --</option>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  onclick="filter_sales()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <table class="table-bordsered" width="100%">
                                <tr>
                                    <td width="3%"></td>
                                    <td width="13%"><b>Month & Year:</b></td>
                                    <td width="25%"></td>
                                    <td width="13%"><b>Reference Number:</b></td>
                                    <td width="41%"></td>
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
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="20%">Participant's Name</td>  
                                        <td class="td-head pt-2 pb-2" align="left" style="vertical-align: bottom;" width="18%">Billing Period</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vatable Amount</td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Zero Rated Amount</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Net Purchase (Php)</td>     
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >Vat on Energy </td> 
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" >EWT</td>
                                        <td class="td-head pt-2 pb-2" align="center" style="vertical-align: bottom;font-size: 12px;" width="10%">Total Amount Due (Php)</td>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <td class="pt-1 pb-1">Addcom - MOT </td>
                                        <td class="pt-1 pb-1">TS-WAC-181F10-0000001</td>
                                        <td class="pt-1 pb-1">June 26 - Jul 25, 2021</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">7,145.85</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">609.41</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">28.88</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">(4.81)</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">264.69</td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1">Addcom - AP </td>
                                        <td class="pt-1 pb-1">TS-WAC-186F9-0000001</td>
                                        <td class="pt-1 pb-1">Nov 26 - Dec 25, 2021</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">7,145.85</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">609.41</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">28.88</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">(4.81)</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">264.69</td>
                                    </tr>
                                    <tr>
                                        <td class="pt-1 pb-1">Addcom - SEC </td>
                                        <td class="pt-1 pb-1">TS-WAC-186F9-0000001</td>
                                        <td class="pt-1 pb-1">Nov 26 - Dec 25, 2021</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">7,145.85</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">609.41</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">240.62</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">28.88</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">(4.81)</td>
                                        <td class="pt-1 pb-1" align="right" style="font-size: 12px;">264.69</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="pt-2 pb-2 td-yellow" colspan="3" align="right">Sub Total</td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>7,145.85</b></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>609.41</b></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>240.62</b></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>28.88</b></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>(4.81)</b></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right" style="font-size: 12px;"><b>264.69</b></td>
                                    </tr>
                                    <tr>
                                        <td class="pt-2 pb-2 td-yellow" colspan="8" align="left">TOTAL AMOUNT PAYABLE on or before, JUNE 25, 2022 &nbsp; &nbsp;&nbsp;        ------------------------------->>>></td>
                                        <td class="pt-2 pb-2 td-yellow" align="right"><b>(6,228.42)</b></td>
                                    </tr>
                                </tfoot>   
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
