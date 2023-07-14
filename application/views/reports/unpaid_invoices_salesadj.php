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
                                    <h4>Summary of All Unpaid Invoices - Adjustment</h4>
                                </div>
                                <div class="col-8">
                                    <!-- <button class="btn btn-primary btn-sm pull-right"><span class="fas fa-print"></span> Print</button> -->
                                    <?php if(!empty($unpaid_sales)){ ?>
                                        <a href = "<?php echo base_url();?>reports/export_unpaid_invoices_salesadj/<?php echo $year; ?>/<?php echo $due; ?>/" class = "btn btn-success pull-right">Export to Excel</a>
                                    <?php }else{ ?>
                                        <a href = "<?php echo base_url();?>reports/export_unpaid_invoices_salesadj/" class = "btn btn-success pull-right">Export to Excel</a>
                                    <?php } ?>  
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                            <td width="20%">
                                                <select id="year" class="form-control select2" name="year">
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
                                                <select class="form-control select2" name="due_date" id="due_date">
                                                    <option value="">-- Select Due Date --</option>
                                                    <?php foreach($due_date AS $dd){ ?>
                                                        <option value="<?php echo $dd->due_date;?>"><?php echo $dd->due_date;?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                                <td width="10%">
                                                    <input type="hidden" id="baseurl" value="<?php echo base_url();?>">
                                                    <button type="button" onclick="filterUnpaidSalesAdj();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width="10%"></td>
                                    <td width="4%"><b>Year:</b></td>
                                    <td width="15%"><?php echo $year ?></td>
                                    <td width="7%"><b>Due Date.:</b></td>
                                    <td width="41%"><?php echo $due ?></td>
                                    <td width="10%"></td>
                                </tr>
                            </table>
                            <br>
                            <?php 
                             if(!empty($unpaid_sales)){
                                ?>
                            <div style="overflow-x:scroll; min-height: 500px; height:550px">
                                <table class="table table-bordered table-hover mb-0" style="width:100%;font-size: 13px;">
                                    <thead class="header">
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="1"align="center">Invoice Date</td>
                                            <td style="vertical-align:middle!important;" class="2"align="center">Invoice Number</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Due Date</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Transaction No</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">STL No</td>
                                            <td style="vertical-align:middle!important;" class="3"align="center">Billing ID</td>

                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Vatable Sales</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Zero Rated Ecozones Sales</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">VAT</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Overdue</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $sum_vatable_sales[]=0;  
                                            $zero_rated_sales[]=0; 
                                            $sum_vat_on_sales[]=0;
                                            $sum_total[]=0;
                                            
                                            foreach($unpaid_sales AS $us){
                                            $sum_vatable_sales[]=$us['vatable_sales'];
                                            $sum_zero_rated_ecozone[]=$us['zero_rated_sales']; 
                                            $sum_vat_on_sales[]=$us['vat_on_sales'];
                                            $sum_total[]=$us['total'];
                                        ?>
                                        <tr>
                                            <td align="center" class=""><?php echo $us['date']; ?></td>
                                            <td align="center" class=""><?php echo $us['invoice_no']; ?></td>
                                            <td align="center" class=""><?php echo $us['due_date']; ?></td>
                                            <td align="center" class=""><?php echo $us['reference_number']; ?></td>
                                            <td align="center" class=""><?php echo $us['stl_id']; ?></td>
                                            <td align="center" class=""><?php echo $us['billing_id']; ?></td>
                                            <td align="right"><?php echo number_format($us['vatable_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($us['zero_rated_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($us['vat_on_sales'],2); ?></td>
                                            <td align="right"><?php echo number_format($us['total'],2); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot class="header">
                                        <tr >
                                            <td align="right" style="vertical-align:middle!important;" class="td-sticky left-col-1 td-yellow" colspan="6">TOTAL</td>

                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_vatable_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_zero_rated_ecozone),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_vat_on_sales),2); ?></td>
                                            <td class="td-30 td-yellow" align="right" style="vertical-align:middle!important;"><?php echo number_format(array_sum($sum_total),2); ?></td>
                                        </tr>
                                    </tfoot>
                                     <?php }else{ ?>
                                            <div><center><b>No Available Data...</b></center></div>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

