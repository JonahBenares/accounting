<script>
    function goBack() {
      window.history.back();
    }
</script>
<div class="animated fadeInDown" style="margin-top:10px" id="printbutton">
    <center>
        <a onclick="goBack()" class="btn btn-warning text-white btn-w100 btn-round">Back</a>
        <a href="#" class="btn btn-success btn-w100 btn-round" onclick="window.print()">Print</a>
        <a href='<?php echo base_url(); ?>sales/print_invoice/<?php echo $sales_detail_id ?>' class="btn btn-primary btn-w100 btn-round">Invoice</a> 
        <!-- <button class="btn btn-danger btn-fill"onclick="printDiv('printableArea')" style="margin-bottom:5px;width:80px;"></span> Print</button><br> -->
    </center>
    <br>
</div>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-borsdered" style="border-collapse: collapse;">
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
                <td colspan="10" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="5"></td>           
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
                <td colspan="9" class="bor-btm"><?php echo $company_name; ?></td>
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm"> <?php echo $serial_no;?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    <?php echo $address;?>
                </td>
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y");?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Billing Period:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($billing_from))." to ".date("M d,Y",strtotime($billing_to));?></td>
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm"><?php echo $tin; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($due_date));?></td>
            </tr>
            <tr>
                <td colspan="2">STL ID:</td>
                <td colspan="6" class="bor-btm"><?php echo $settlement; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Reference:</td>
                <td colspan="5" class="bor-btm"><?php echo $reference_number; ?></td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%"> 
                        <?php if(!empty($sub)){ ?>
                        <tr class="table-bor">
                            <td align="center" width="15%">ITEMS</td>
                            <!-- <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC_SS</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC</td>
                            <td align="center" width="1%"></td> -->
                            <?php 
                                $x=1;
                                foreach($sub AS $s){ 
                                    if($x <= 5){
                                    $vatable_arraysum[]=$s['vatable_sales'];
                                    $zerorated_arraysum[]=$s['zero_rated_sales'];
                                    $total_arraysum[]=$s['total_amount'];
                                    $vat_arraysum[]=$s['vat_on_sales'];
                                    $ewt_arraysum[]=$s['ewt'];
                            ?>
                                <td align="center" width="13%"><?php echo $s['sub_participant'];?></td>
                                <td align="center" width="1%"></td>
                            <?php } $x++; } ?>
                            <td align="center" width="15%">TOTAL</td>
                        </tr>
                        <tr>
                            <td>Vatable Sales</td>
                            <?php 
                                $x=1; 
                                foreach($sub AS $s){ 
                                    if($x <= 5){
                            ?>
                            <td align="right"><?php echo number_format($s['vatable_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <?php 
                                $vatable=array_sum($vatable_arraysum);
                                $zero=array_sum($zerorated_arraysum);
                                $total=array_sum($total_arraysum);
                                $vat=array_sum($vat_arraysum);
                                $ewt=array_sum($ewt_arraysum);
                            ?>
                            <td align="right"><?php echo number_format(array_sum($vatable_arraysum),2);?></td> 
                        </tr>
                        <tr>
                            <td>Zero-Rated Sales</td>
                            <?php 
                                $x=1;
                                foreach($sub AS $s){
                                    if($x <= 5){ 
                            ?>
                            <td class="bor-btm" align="right"><?php echo number_format($s['zero_rated_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td class="bor-btm" align="right"><?php echo number_format(array_sum($zerorated_arraysum),2);?></td>
                        </tr>
                        <tr>
                            <td>Total Sales</td>
                            <?php 
                                $x=1; 
                                foreach($sub AS $s){ 
                                    if($x <= 5){ 
                            ?>
                            <td align="right"><?php echo number_format($s['total_amount'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td align="right"><?php echo number_format(array_sum($total_arraysum),2);?></td> 
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td>12% VAT on Sales</td>
                            <?php 
                                $x=1;
                                foreach($sub AS $s){ 
                                     if($x <= 5){ 
                            ?>
                            <td align="right"><?php echo number_format($s['vat_on_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td align="right"><?php echo number_format(array_sum($vat_arraysum),2);?></td> 
                        </tr>
                        <tr>
                            <td>EWT</td>
                            <?php 
                                $x=1;
                                foreach($sub AS $s){ 
                                     if($x <= 5){ 
                            ?>
                            <td class="bor-btm" align="right"><?php echo number_format($s['ewt'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td class="bor-btm" align="right"><?php echo number_format(array_sum($ewt_arraysum),2);?></td> 
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td><b>Net Amount Due</b></td>
                            <?php 
                                $x=1;
                                foreach($sub AS $s){ 
                                     if($x <= 5){ 
                            ?>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <?php } $x++; } ?>
                            <?php 
                                $overall_total=($vatable+$zero+$total+$vat)-$ewt;
                            ?>
                            <td class="bor-btm2" align="right"><b><?php echo number_format($overall_total,2);?></b></td> 
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td align="center">No Available Data...</td>
                        </tr>
                        <?php }?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
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
                    <br>
                    <br>

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
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td></td>
                            <td colspan="5"><b>Checked by:</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">JEOMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">EMG Supervisor</td>
                            <td></td>
                            <td align="center">Accounting</td>
                            <td></td>
                            <td align="center">Finance</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td width="1%"></td>
                            <td width="32%"><b>Prepared by:</b></td>
                            <td width="1%"></td>
                            <td width="32%"></td>
                            <td width="1%"></td>
                            <td width="32%"><b>Noted by:</b></td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center" class="bor-btm">CELINA TIGNERO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center" class="bor-btm">MILA ARANA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">Billing</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center">General Manager</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</page>
<?php if(!empty($sub_second)){ ?>
<page size="A4">
    <div style="padding:30px">
        <table width="100%" class="table-bor table-borsdered" style="border-collapse: collapse;">
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
                <td colspan="10" align="center" style="padding-left:10px">
                    <h3 style="margin:0px;margin-top:5px;font-size: 15px"><?php echo COMPANY_NAME;?></h3>
                    <?php echo ADDRESS;?> <br>
                    <?php echo TELFAX;?> <br>
                    <?php echo ADDRESS_2;?> <br>
                </td>
                <td colspan="5"></td>           
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
                <td colspan="9" class="bor-btm">1590 Energy Corporation</td>
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm">514</td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    907-908 Ayala Life FGU Ctr. Cebu Business Park Luz Cebu City, Cebu City (Capital) Philippines 6000
                </td>
                <td></td>
                <td colspan="3">Statement Date:</td>
                <td colspan="5" class="bor-btm">18/04/2022</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Billing Period:</td>
                <td colspan="5" class="bor-btm">Feb 26 - March 25, 2022</td>
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm">007-833-205-000</td>
                <td colspan="4"></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm">25/04/2022</td>
            </tr>
            <tr>
                <td colspan="2">STL ID:</td>
                <td colspan="6" class="bor-btm">1590EC</td>
                <td colspan="4"></td>
                <td colspan="3">Reference:</td>
                <td colspan="5" class="bor-btm">TS-WF-189F-0000089</td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%"> 
                        <tr class="table-bor">
                            <td align="center" width="15%">ITEMS</td>
                            <!-- <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%"></td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC_SS</td>
                            <td align="center" width="1%"></td>
                            <td align="center" width="13%">1590EC</td>
                            <td align="center" width="1%"></td> -->
                            <?php 
                                $x=1;
                                foreach($sub_second AS $s){ 
                                    if($x >= 6){
                                    $vatable_arraysum_second[]=$s['vatable_sales'];
                                    $zerorated_arraysum_second[]=$s['zero_rated_sales'];
                                    $total_arraysum_second[]=$s['total_amount'];
                                    $vat_arraysum_second[]=$s['vat_on_sales'];
                                    $ewt_arraysum_second[]=$s['ewt'];
                                    
                            ?>
                                <td align="center" width="13%"><?php echo $s['sub_participant'];?></td>
                                <td align="center" width="1%"></td>
                            <?php } $x++; } ?>
                            <td align="center" width="15%">TOTAL</td>
                        </tr>
                        <tr>
                            <td>Vatable Sales</td>
                            <?php 
                                $x=1; 
                                foreach($sub_second AS $s){ 
                                    if($x >= 6){
                            ?>
                            <td align="right"><?php echo number_format($s['vatable_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <?php 
                                $vatable2=array_sum($vatable_arraysum_second);
                                $zero2=array_sum($zerorated_arraysum_second);
                                $total2=array_sum($total_arraysum_second);
                                $vat2=array_sum($vat_arraysum_second);
                                $ewt2=array_sum($ewt_arraysum_second);
                            ?>
                            <td align="right"><?php echo number_format(array_sum($vatable_arraysum_second),2);?></td> 
                        </tr>
                        <tr>
                            <td>Zero-Rated Sales</td>
                            <?php 
                                $x=1;
                                foreach($sub_second AS $s){
                                    if($x >= 6){ 
                            ?>
                            <td class="bor-btm" align="right"><?php echo number_format($s['zero_rated_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td class="bor-btm" align="right"><?php echo number_format(array_sum($zerorated_arraysum_second),2);?></td>
                        </tr>
                        <tr>
                            <td>Total Sales</td>
                            <?php 
                                $x=1; 
                                foreach($sub_second AS $s){ 
                                    if($x >= 6){ 
                            ?>
                            <td align="right"><?php echo number_format($s['total_amount'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                             
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td>12% VAT on Sales</td>
                            <?php 
                                $x=1;
                                foreach($sub_second AS $s){ 
                                     if($x >= 6){ 
                            ?>
                            <td align="right"><?php echo number_format($s['vat_on_sales'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td align="right"><?php echo number_format(array_sum($vat_arraysum_second),2);?></td> 
                        </tr>
                        <tr>
                            <td>EWT</td>
                            <?php 
                                $x=1;
                                foreach($sub_second AS $s){ 
                                     if($x >= 6){ 
                            ?>
                            <td class="bor-btm" align="right"><?php echo number_format($s['ewt'],2);?></td>
                            <td></td>
                            <?php } $x++; } ?>
                            <td class="bor-btm" align="right"><?php echo number_format(array_sum($ewt_arraysum_second),2);?></td> 
                        </tr>
                        <tr>
                            <td colspan="12"><br></td>
                        </tr>
                        <tr>
                            <td><b>Net Amount Due</b></td>
                            <?php 
                                $x=1;
                                foreach($sub_second AS $s){ 
                                     if($x >= 6){ 
                            ?>
                            <td class="bor-btm2" align="right">-</td>
                            <td></td>
                            <?php } $x++; } ?>
                            <?php 
                                $overall_total2=($vatable2+$zero2+$total2+$vat2)-$ewt2;
                            ?>
                            <td class="bor-btm2" align="right"><b><?php echo number_format($overall_total2,2);?></b></td> 
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20">
                    <hr style="margin-top: 0.4rem;margin-bottom: 1rem;border: 0;border-top: 1px solid #000">
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
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
                    <br>
                    <br>

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
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td></td>
                            <td colspan="5"><b>Checked by:</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">JEOMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td class="bor-btm" align="center" width="32%">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">EMG Supervisor</td>
                            <td></td>
                            <td align="center">Accounting</td>
                            <td></td>
                            <td align="center">Finance</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="20"><br></td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%">
                        <tr>
                            <td width="1%"></td>
                            <td width="32%"><b>Prepared by:</b></td>
                            <td width="1%"></td>
                            <td width="32%"></td>
                            <td width="1%"></td>
                            <td width="32%"><b>Noted by:</b></td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td colspan="7"><br></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center" class="bor-btm">CELINA TIGNERO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center" class="bor-btm">MILA ARANA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">Billing</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center">General Manager</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</page>
<?php } ?>
<!-- <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<script src="assets/js/demo.js"></script>  -->
   

</html>
