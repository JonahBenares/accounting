<script>
    function goBack() {
        window.history.back();
        window.close() ;
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<form id='InsertBSAdjustment'>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <!-- <button href="#" class="btn btn-success " onclick="window.print()">Print</button> -->
       <!--  <button class="btn btn-success " oid="saved" name="submit" onclick="printbs_history()">Print</button> -->
        <input type="button" id="saved" name="submit" class="btn btn-success btn-block  btn-md" value = "Print" onclick="printbs_adjustment_history(this);return false;">
        <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
        <a href='<?php echo base_url(); ?>sales/print_invoice_adjustment_small/<?php echo $invoice_no; ?>/<?php echo $print_identifier; ?>/<?php echo $count; ?>' class="btn btn-primary button" target="_blank">Invoice (small)</a> 
        <a href='<?php echo base_url(); ?>sales/print_invoice_adjustment_half/<?php echo $invoice_no; ?>/<?php echo $print_identifier; ?>/<?php echo $count; ?>' class="btn btn-primary button" target="_blank">Invoice (half)</a> 
        <a href='<?php echo base_url(); ?>sales/print_invoice_adjustment_main/<?php echo $invoice_no; ?>/<?php echo $print_identifier; ?>/<?php echo $count; ?>' class="btn btn-primary button" target="_blank">Invoice New</a> 
        <!-- <button class="btn btn-info btn-fill" data-toggle="modal" data-target="#basicModal"></span> Update Serial No.</button><br> -->
        <br>
        <br>
    </center>
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Serial Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Serial Number">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-primary " style="color: #fff;">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    $y=0; 
    foreach($head AS $as){
?>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-bordsered" style="border-collapse: collapse;">
            <tr>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="4">
                    <img class="logo-print" src="<?php echo base_url().LOGO;?>">   
                </td>
                <td colspan="11" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo TIN;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="4"></td>           
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center"> 
                    <h3 style="margin:0px">BILLING STATEMENT</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">Customer:</td>
                <td colspan="9" class="bor-btm"><?php echo $company_name[$y];?></td>
                <input type="hidden" id="company_name" name="company_name[]" class="form-control" value="<?php echo $company_name[$y]; ?>">
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm"><?php echo $serial_no[$y];?></td>
                <input type="hidden" id="serial_no" name="serial_no[]" class="form-control" value="<?php echo $serial_no[$y]; ?>">
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    <?php echo $address[$y];?>
                </td>
                <input type="hidden" id="address" name="address[]" class="form-control" value="<?php echo $address[$y]; ?>">
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($transaction_date[$y]));?></td>
                <input type="hidden" id="transaction_date" name="transaction_date[]" class="form-control" value="<?php echo $transaction_date[$y]; ?>">
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($due_date[$y]));?></td>
                <input type="hidden" id="due_date" name="due_date[]" class="form-control" value="<?php echo $due_date[$y]; ?>">
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm"><?php echo $tin[$y]; ?></td>
                <input type="hidden" id="tin" name="tin[]" class="form-control" value="<?php echo $tin[$y]; ?>">
                <td colspan="4"></td>
                <td colspan="3">STL ID:</td>
                <td colspan="5" class="bor-btm"><?php echo $settlement[$y]; ?></td>
                <input type="hidden" id="settlement" name="settlement[]" class="form-control" value="<?php echo $settlement[$y]; ?>">
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%" class="table-bordereds">
                        <tr style="border:1px solid #000">
                            <td style="vertical-align:text-bottom;" width="19%" align="center" class="p-r-10 p-b-5">Transaction Reference</td>
                            <td style="vertical-align:text-bottom;" width="15%" align="center" class="p-r-10 p-b-5">Billing Period</td>
                            <td style="vertical-align:text-bottom;" width="20%" align="center" class="p-r-10 p-b-5">Billing ID</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">Vatable Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">Zero-Rated Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">12% VAT on Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">EWT</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">NET AMOUNT DUE</td>
                        </tr>
                        <?php
                            if(!empty($sub_part)){ 
                                $h=1;
                                $x=1;
                                $vatable=0;
                                $zero=0;
                                $total_rated_sales=0;
                                $total_rated_ecozones=0;
                                $total=0;
                                $vat=0;
                                $ewt_arr=0;
                                $overall_totals=0;
                                foreach($sub_part AS $sps){ 
                                    if($sps['serial_no']==$as['serial_no']){
                                        if($h <=10){ 
                                        if($bs_head_adjustment_id[$y] != ''){
                                            $vatable=$total_vatable_sales[$y];
                                            $zero=$total_zero_rated[$y];
                                            $total_rated_sales=$total_rated_sales[$y];
                                            $total_rated_ecozones=$total_rated_ecozones[$y];
                                            $vat=$total_vat[$y];
                                            $ewt_arr=$total_ewt[$y];
                                            $overall_totals=$total_net_amount[$y];
                                        }else{
                                            $vatable+=$sps['vatable_sales']; 
                                            $zero+=$sps['zero_rated_sales'];
                                            $total_rated_sales+=$sps['rated_sales'];
                                            $total_rated_ecozones+=$sps['zero_rated_ecozones'];
                                            $vat+=$sps['vat_on_sales']; 
                                            $ewt_arr+=$sps['ewt'];
                                            $overall_totals+=$sps['overall_total'];
                                        }
                        ?>
                        <tr class="bor-btm">
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo $sps['ref_no'];?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo date("Y-m-d",strtotime($sps['billing_from']))." <br> ".date("Y-m-d",strtotime($sps['billing_to']));?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo $sps['sub_participant'];?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['vatable_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['zero_rated_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['vat_on_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right">(<?php echo number_format($sps['ewt'],2); ?>)</td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><b><?php echo number_format($sps['overall_total'],2); ?></b></td>

                            <input type="hidden" id="ref_no" name="ref_no[]" class="form-control" value="<?php echo $sps['ref_no']; ?>">
                            <input type="hidden" id="billing_from" name="billing_from[]" class="form-control" value="<?php echo $sps['billing_from']; ?>">
                            <input type="hidden" id="billing_to" name="billing_to[]" class="form-control" value="<?php echo $sps['billing_to']; ?>">
                            <input type="hidden" id="sub_participant" name="sub_participant[]" class="form-control" value="<?php echo $sps['sub_participant']; ?>">
                            <input type="hidden" id="vatable_sales" name="vatable_sales[]" class="form-control" value="<?php echo $sps['vatable_sales']; ?>">
                            <input type="hidden" id="rated_sales" name="rated_sales[]" class="form-control" value="<?php echo $sps['rated_sales']; ?>">
                            <input type="hidden" id="rated_ecozones" name="rated_ecozones[]" class="form-control" value="<?php echo $sps['zero_rated_ecozones']; ?>">
                            <input type="hidden" id="zero_rated_sales" name="zero_rated_sales[]" class="form-control" value="<?php echo $sps['zero_rated_sales']; ?>">
                            <input type="hidden" id="vat_on_sales" name="vat_on_sales[]" class="form-control" value="<?php echo $sps['vat_on_sales']; ?>">
                            <input type="hidden" id="ewt" name="ewt[]" class="form-control" value="<?php echo $sps['ewt']; ?>">
                            <input type="hidden" id="net_amount" name="net_amount[]" class="form-control" value="<?php echo $sps['overall_total']; ?>">
                            <input type="hidden" id="invoice_no" name="invoice_no[]" class="form-control" value="<?php echo $serial_no[$y]; ?>">
                        </tr> 
                        <?php } $h++; } $x++; } } ?>
                        <?php if($total_sub <=10 && $total_sub_h <=10){ ?>
                        <tr>
                            <td class="p-r-10 p-b-5"><b>TOTAL AMOUNT</b></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($vatable,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($zero,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($vat,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">(<?php echo number_format($ewt_arr,2); ?>)</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><b><?php echo number_format($overall_totals,2); ?></b></td>

                            <input type="hidden" id="vatable" name="vatable[]" class="form-control" value="<?php echo $vatable; ?>">
                            <input type="hidden" id="total_rated_sales" name="total_rated_sales[]" class="form-control" value="<?php echo $total_rated_sales; ?>">
                            <input type="hidden" id="total_rated_ecozones" name="total_rated_ecozones[]" class="form-control" value="<?php echo $total_rated_ecozones; ?>">
                            <input type="hidden" id="zero" name="zero[]" class="form-control" value="<?php echo $zero; ?>">
                            <input type="hidden" id="vat" name="vat[]" class="form-control" value="<?php echo $vat; ?>">
                            <input type="hidden" id="ewt_arr" name="ewt_arr[]" class="form-control" value="<?php echo $ewt_arr; ?>">
                            <input type="hidden" id="overall_total" name="overall_total[]" class="form-control" value="<?php echo $overall_totals; ?>">
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"></td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <b>Note:</b><br>
                    1. The scanned copies of the withholding tax certificate shall be submitted to IEMOP thru tax data facility no later than three (3) working days from the end of the calendar month.
                    <br><br>
                    2. The original copy shall be submitted at the offices of the IEMOP at the 9th Floor Robinsons Equitable Tower ADB Avenue, Ortigas Center, Pasig City.
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20">
                    <table width="100%" class="">
                        <tr>
                            <td colspan="2" style="background:#e5e5e5; border-right:5px solid #fff"><b>Prepared by:</b></td>
                            <td colspan="6" style="background:#e5e5e5; border-right:5px solid #fff"><b>Checked by:</b></td>
                            <td colspan="2" style="background:#e5e5e5;"><b>Noted by:</b></td>
                        </tr>
                        <tr>
                            <td colspan="20"><br></td>
                        </tr>
                        <tr>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? strtoupper($fullname[$y]) : strtoupper($_SESSION['fullname']); ?></td>
                            <input type="hidden" id="prepared_by" name="prepared_by[]" class="form-control" value="<?php echo $_SESSION['user_id']; ?>">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_emg[$y] : 'JOEMAR DELOS SANTOS'; ?></td>
                            <input type="hidden" id="checked_by_emg" name="checked_by_emg[]" class="form-control" value="JOEMAR DELOS SANTOS">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_accounting[$y] : 'CRISTY CESAR'; ?></td>
                            <input type="hidden" id="checked_by_accounting" name="checked_by_accounting[]" class="form-control" value="CRISTY CESAR">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_finance[$y] : 'ZYNDYRYN PASTERA'; ?></td>
                            <input type="hidden" id="checked_by_finance" name="checked_by_finance[]" class="form-control" value="ZYNDYRYN PASTERA">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $noted_by[$y] : 'MILA ARANA'; ?></td>
                            <input type="hidden" id="noted_by" name="noted_by[]" class="form-control" value="MILA ARANA">
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y] != '') ? $prepared_by_pos[$y] : 'Billing'; ?></td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_emg_pos[$y] : 'EMG'; ?></td>
                            <input type="hidden" id="checked_by_emg_pos" name="checked_by_emg_pos[]" class="form-control" value="EMG">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_accounting_pos[$y] : 'Accounting'; ?></td>
                            <input type="hidden" id="checked_by_accounting_pos" name="checked_by_accounting_pos[]" class="form-control" value="Accounting">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_finance_pos[$y] : 'Finance'; ?></td>
                            <input type="hidden" id="checked_by_finance_pos" name="checked_by_finance_pos[]" class="form-control" value="Finance">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $noted_by_pos[$y] : 'General Manager'; ?></td>
                            <input type="hidden" id="noted_by_pos" name="noted_by_pos[]" class="form-control" value="General Manager">
                            <td width="1%"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br><br></td>
            </tr>
        </table>
    </div>
</page>
<?php 
    if(!empty($sub_part_second)){  
        foreach($sub_second AS $sec){  
            if($as['serial_no']==$sec['serial_no']){
?>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-bordsered" style="border-collapse: collapse;">
            <tr>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
                <td width="5%"></td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td colspan="4">
                    <img class="logo-print" src="<?php echo base_url().LOGO;?>">   
                </td>
                <td colspan="11" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo TIN;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="4"></td>           
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center"> 
                    <h3 style="margin:0px">BILLING STATEMENT</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">Customer:</td>
                <td colspan="9" class="bor-btm"><?php echo $company_name[$y];?></td>
                <input type="hidden" id="company_name" name="company_name[]" class="form-control" value="<?php echo $company_name[$y]; ?>">
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm"><?php echo $serial_no[$y];?></td>
                <input type="hidden" id="serial_no" name="serial_no[]" class="form-control" value="<?php echo $serial_no[$y]; ?>">
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    <?php echo $address[$y];?>
                </td>
                <input type="hidden" id="address" name="address[]" class="form-control" value="<?php echo $address[$y]; ?>">
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($transaction_date[$y]));?></td>
                <input type="hidden" id="transaction_date" name="transaction_date[]" class="form-control" value="<?php echo $transaction_date[$y]; ?>">
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($due_date[$y]));?></td>
                <input type="hidden" id="due_date" name="due_date[]" class="form-control" value="<?php echo $due_date[$y]; ?>">
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm"><?php echo $tin[$y]; ?></td>
                <input type="hidden" id="tin" name="tin[]" class="form-control" value="<?php echo $tin[$y]; ?>">
                <td colspan="4"></td>
                <td colspan="3">STL ID:</td>
                <td colspan="5" class="bor-btm"><?php echo $settlement[$y]; ?></td>
                <input type="hidden" id="settlement" name="settlement[]" class="form-control" value="<?php echo $settlement[$y]; ?>">
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%" class="table-bordereds">
                        <tr style="border:1px solid #000">
                            <td style="vertical-align:text-bottom;" width="19%" align="center" class="p-r-10 p-b-5">Transaction Reference</td>
                            <td style="vertical-align:text-bottom;" width="15%" align="center" class="p-r-10 p-b-5">Billing Period</td>
                            <td style="vertical-align:text-bottom;" width="20%" align="center" class="p-r-10 p-b-5">Billing ID</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">Vatable Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">Zero-Rated Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">12% VAT on Sales</td>
                            <td style="vertical-align:text-bottom;" width="10%" align="center" class="p-r-10 p-b-5">EWT</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">NET AMOUNT DUE</td>
                        </tr>
                        <?php 
                            if(!empty($sub_second)){ 
                                $vatable=0;
                                $zero=0;
                                $total_rated_sales=0;
                                $total_rated_ecozones=0;
                                $total=0;
                                $vat=0;
                                $ewt_arr=0;
                                $overall_totals=0;
                        ?>
                        <?php
                            if(!empty($sub_part_second)){ 
                                $h=0;
                                $x=1;
                                foreach($sub_part_second AS $sps){ 
                                    if($sps['serial_no']==$sec['serial_no']){
                                            if($bs_head_adjustment_id[$y] != ''){
                                                $vatable=$total_vatable_sales[$y];
                                                $zero=$total_zero_rated[$y];
                                                $total_rated_sales = $total_rated_sales[$y];
                                                $total_rated_ecozones = $total_rated_ecozones[$y];
                                                $vat=$total_vat[$y];
                                                $ewt_arr=$total_ewt[$y];
                                                $overall_totals=$total_net_amount[$y];
                                            }else{
                                                $vatable = $overall_vatable_sales; 
                                                $zero = $overall_zero_rated;
                                                $total_rated_sales = $overall_zero_rated_sales;
                                                $total_rated_ecozones = $overall_zero_rated_ecozones;
                                                $vat = $overall_vat_on_sales; 
                                                $ewt_arr = $overall_ewt;
                                                $overall_totals = $all_total;
                                            }
                                        if($sps['counter'] >= 11 || $sps['counter_h'] > 11){
                        ?>
                        <tr>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo $sps['ref_no'];?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo date("Y-m-d",strtotime($sps['billing_from']))." <br> ".date("Y-m-d",strtotime($sps['billing_to']));?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="center"><?php echo $sps['sub_participant'];?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['vatable_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['zero_rated_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><?php echo number_format($sps['vat_on_sales'],2); ?></td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right">(<?php echo number_format($sps['ewt'],2); ?>)</td>
                            <td style="font-size:smaller" class="p-r-10 p-b-5" align="right"><b><?php echo number_format($sps['overall_total'],2); ?></b></td>

                            <input type="hidden" id="ref_no" name="ref_no[]" class="form-control" value="<?php echo $sps['ref_no']; ?>">
                            <input type="hidden" id="billing_from" name="billing_from[]" class="form-control" value="<?php echo $sps['billing_from']; ?>">
                            <input type="hidden" id="billing_to" name="billing_to[]" class="form-control" value="<?php echo $sps['billing_to']; ?>">
                            <input type="hidden" id="sub_participant" name="sub_participant[]" class="form-control" value="<?php echo $sps['sub_participant']; ?>">
                            <input type="hidden" id="vatable_sales" name="vatable_sales[]" class="form-control" value="<?php echo $sps['vatable_sales']; ?>">
                            <input type="hidden" id="rated_sales" name="rated_sales[]" class="form-control" value="<?php echo $sps['rated_sales']; ?>">
                            <input type="hidden" id="rated_ecozones" name="rated_ecozones[]" class="form-control" value="<?php echo $sps['zero_rated_ecozones']; ?>">
                            <input type="hidden" id="zero_rated_sales" name="zero_rated_sales[]" class="form-control" value="<?php echo $sps['zero_rated_sales']; ?>">
                            <input type="hidden" id="vat_on_sales" name="vat_on_sales[]" class="form-control" value="<?php echo $sps['vat_on_sales']; ?>">
                            <input type="hidden" id="ewt" name="ewt[]" class="form-control" value="<?php echo $sps['ewt']; ?>">
                            <input type="hidden" id="net_amount" name="net_amount[]" class="form-control" value="<?php echo $sps['overall_total']; ?>">
                            <input type="hidden" id="invoice_no" name="invoice_no[]" class="form-control" value="<?php echo $serial_no[$y]; ?>">
                        </tr> 
                        <?php } $h++; } $x++; } } ?>
                        <tr>
                            <td class="p-r-10 p-b-5"><b>TOTAL AMOUNT</b></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($vatable,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($zero,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><?php echo number_format($vat,2); ?></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">(<?php echo number_format($ewt_arr,2); ?>)</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><b><?php echo number_format($overall_totals,2); ?></b></td>

                            <input type="hidden" id="vatable" name="vatable[]" class="form-control" value="<?php echo $vatable; ?>">
                            <input type="hidden" id="zero" name="zero[]" class="form-control" value="<?php echo $zero; ?>">
                            <input type="hidden" id="total_rated_sales" name="total_rated_sales[]" class="form-control" value="<?php echo $total_rated_sales; ?>">
                            <input type="hidden" id="total_rated_ecozones" name="total_rated_ecozones[]" class="form-control" value="<?php echo $total_rated_ecozones; ?>">
                            <input type="hidden" id="vat" name="vat[]" class="form-control" value="<?php echo $vat; ?>">
                            <input type="hidden" id="ewt_arr" name="ewt_arr[]" class="form-control" value="<?php echo $ewt_arr; ?>">
                            <input type="hidden" id="overall_total" name="overall_total[]" class="form-control" value="<?php echo $overall_totals; ?>">
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"></td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <b>Note:</b><br>
                    1. The scanned copies of the withholding tax certificate shall be submitted to IEMOP thru tax data facility no later than three (3) working days from the end of the calendar month.
                    <br><br>
                    2. The original copy shall be submitted at the offices of the IEMOP at the 9th Floor Robinsons Equitable Tower ADB Avenue, Ortigas Center, Pasig City.
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 0.4rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20">
                    <table width="100%" class="">
                        <tr>
                            <td colspan="2" style="background:#e5e5e5; border-right:5px solid #fff"><b>Prepared by:</b></td>
                            <td colspan="6" style="background:#e5e5e5; border-right:5px solid #fff"><b>Checked by:</b></td>
                            <td colspan="2" style="background:#e5e5e5;"><b>Noted by:</b></td>
                        </tr>
                        <tr>
                            <td colspan="20"><br></td>
                        </tr>
                         <tr>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? strtoupper($fullname[$y]) : strtoupper($_SESSION['fullname']); ?></td>
                            <input type="hidden" id="prepared_by" name="prepared_by[]" class="form-control" value="<?php echo $_SESSION['user_id']; ?>">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_emg[$y] : 'JOEMAR DELOS SANTOS'; ?></td>
                            <input type="hidden" id="checked_by_emg" name="checked_by_emg[]" class="form-control" value="JOEMAR DELOS SANTOS">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_accounting[$y] : 'CRISTY CESAR'; ?></td>
                            <input type="hidden" id="checked_by_accounting" name="checked_by_accounting[]" class="form-control" value="CRISTY CESAR">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_finance[$y] : 'ZYNDYRYN PASTERA'; ?></td>
                            <input type="hidden" id="checked_by_finance" name="checked_by_finance[]" class="form-control" value="ZYNDYRYN PASTERA">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo ($bs_head_adjustment_id[$y]!='') ? $noted_by[$y] : 'MILA ARANA'; ?></td>
                            <input type="hidden" id="noted_by" name="noted_by[]" class="form-control" value="MILA ARANA">
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y] != '') ? $prepared_by_pos[$y] : 'Billing'; ?></td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_emg_pos[$y] : 'EMG'; ?></td>
                            <input type="hidden" id="checked_by_emg_pos" name="checked_by_emg_pos[]" class="form-control" value="EMG">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_accounting_pos[$y] : 'Accounting'; ?></td>
                            <input type="hidden" id="checked_by_accounting_pos" name="checked_by_accounting_pos[]" class="form-control" value="Accounting">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $checked_by_finance_pos[$y] : 'Finance'; ?></td>
                            <input type="hidden" id="checked_by_finance_pos" name="checked_by_finance_pos[]" class="form-control" value="Finance">
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11"><?php echo ($bs_head_adjustment_id[$y]!='') ? $noted_by_pos[$y] : 'General Manager'; ?></td>
                            <input type="hidden" id="noted_by_pos" name="noted_by_pos[]" class="form-control" value="General Manager">
                            <td width="1%"></td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br><br></td>
            </tr>
        </table>
    </div>
</page>
<?php } } } ?>
<?php $y++; } ?>
</form>

</html>
