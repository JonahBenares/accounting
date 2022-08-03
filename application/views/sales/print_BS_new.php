<script>
    function goBack() {
        window.history.back();
        window.close() ;
    }
</script>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button onclick="goBack()" class="btn btn-warning ">Back</button>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <a href='<?php echo base_url(); ?>sales/print_invoice_multiple/<?php echo $sales_detail_id; ?>/<?php echo $print_identifier; ?>/<?php echo $count; ?>' class="btn btn-primary button" target="_blank">Invoice</a> 
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
<?php $y=0; 
//print_r($company_name);
foreach($sub AS $as){  //for($f=0;$f<$count;$f++){ ?>
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
                <td></td>
                <td colspan="3">Invoice No.:</td>
                <td colspan="5" class="bor-btm"><?php echo $serial_no[$y];?></td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" style="vertical-align:top">Address:</td>
                <td colspan="9" rowspan="2" style="vertical-align:top" class="bor-btm">
                    <?php echo $address[$y];?>
                </td>
                <td></td>
                <td colspan="3">Statement Date:</td>
                <!-- <td colspan="5" class="bor-btm"><?php echo date("M d,Y");?></td> -->
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($transaction_date[$y]));?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Billing Period:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($billing_from[$y]))." to ".date("M d,Y",strtotime($billing_to[$y]));?></td>
            </tr>
            <tr>
                <td colspan="2">TIN:</td>
                <td colspan="6" class="bor-btm"><?php echo $tin[$y]; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Due Date:</td>
                <td colspan="5" class="bor-btm"><?php echo date("M d,Y",strtotime($due_date[$y]));?></td>
            </tr>
            <tr>
                <td colspan="2">STL ID:</td>
                <td colspan="6" class="bor-btm"><?php echo $settlement[$y]; ?></td>
                <td colspan="4"></td>
                <td colspan="3">Reference:</td>
                <td colspan="5" class="bor-btm"><?php echo $reference_number[$y]; ?></td>
            </tr>
            <tr>
                <td colspan="20">
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="20" style="padding:5px">
                    <table width="100%" class="table-bordered">
                        <tr style="border:1px solid #000">
                            <td style="vertical-align:text-bottom;" width="30%" align="center" class="p-r-10 p-b-5">Billing ID</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">Vatable Sales</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">Zero-Rated Sales</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">12% VAT on Sales</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">EWT</td>
                            <td style="vertical-align:text-bottom;" width="14%" align="center" class="p-r-10 p-b-5">NET AMOUNT DUE</td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5">SOLARPHTC</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">34</td>
                            <td class="p-r-10 p-b-5" align="right">2223</td>
                            <td class="p-r-10 p-b-5" align="right">23</td>
                            <td class="p-r-10 p-b-5" align="right"><b>23</b></td>
                        </tr>
                        <tr>
                            <td class="p-r-10 p-b-5"><b>TOTAL AMOUNT</b></td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">34</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">34</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">2223</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right">23</td>
                            <td class="p-r-10 p-b-5 bor-btm" align="right"><b>23</b></td>
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
                            <td width="19%" align="center" class="bor-btm font-10"><?php echo strtoupper($_SESSION['fullname']);?></td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10">JEOMAR DELOS SANTOS</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10">CRISTY CESAR</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10">ZYNDYRYN PASTERA</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="bor-btm font-10">MILA ARANA</td>
                            <td width="1%"></td>
                        </tr>
                        <tr>
                            <td width="19%" align="center" class="font-11">Billing</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">EMG</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">Accounting</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">Finance</td>
                            <td width="1%"></td>
                            <td width="19%" align="center" class="font-11">General Manager</td>
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

<?php $y++; } ?>

</html>
