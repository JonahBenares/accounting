<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>   
<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>   
<script type="text/javascript">
/*function onClick() {
  var pdf = new jsPDF('p', 'pt', 'letter');
  pdf.canvas.height = 72 * 11;
  pdf.canvas.width = 72 * 8.5;

  pdf.fromHTML(output);
  //pdf.fromHTML(document.body);

  pdf.save('test.pdf');
};

var element = document.getElementById("clickbind");
element.addEventListener("click", onClick);*/
</script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <h4>WESM Transaction - Purchases</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-10 offset-lg-1">
                                        <table class="table-borderded" width="100%">
                                            <tr>
                                                <td>
                                                    <select class="form-control select2" name="ref_no" id="ref_no">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Select Due Date --</option>
                                                        <?php foreach($date AS $d){ ?>
                                                            <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td  width="1%"><button type="button" onclick="filterPurchase();" class="btn btn-primary btn-block">Filter</button></td>
                                                <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($details) && (!empty($ref_no) || !empty($due_date))){ ?>
                                <table class="table-bsordered" width="100%">
                                    <?php 
                                        foreach($details AS $d){ 
                                            $reference_number=$d['reference_number'];
                                            $transaction_date=date("F d,Y",strtotime($d['transaction_date']));
                                            $billing_from=date("F d,Y",strtotime($d['billing_from']));
                                            $billing_to=date("F d,Y",strtotime($d['billing_to']));
                                            $due_dates=date("F d,Y",strtotime($d['due_date']));
                                        }

                                    ?>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: <?php echo (!empty($reference_number)) ? $reference_number : ''; ?></td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: <?php echo (!empty($billing_from)) ? $billing_from : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: <?php echo (!empty($transaction_date)) ? $transaction_date : ''; ?></td>
                                        <td>Billing Period (To)</td>
                                        <td>: <?php echo (!empty($billing_to)) ? $billing_to : ''; ?></td>
                                    </tr>                                    
                                    <tr>
                                        <td>Due Date</td>
                                        <td>: <?php echo (!empty($due_dates)) ? $due_dates : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><center><a href='<?php echo base_url(); ?>purchases/download_bulk/<?php echo $ref_no; ?>' target="_blank" class="btn btn-primary btn-block">Download Bulk 2307</a>
                                        </center></td>
                                    </tr>
                                    <tr>
                                        <td id="append"></td>
                                    </tr>
                                </table>
                                <br>
                                <a href="<?php echo base_url(); ?>purchases/export_purchasetrans/<?php echo $ref_no; ?>/<?php echo $due_date; ?>" class="btn btn-success btn-md pull-right">Export</a>
                                <br>
                                <br>
                                <div class="table-responsive">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:300%;">
                                        <thead>
                                            <tr>
                                                <th width="3%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th>Item No.</th>
                                                <th>STL ID / TPShort Name</th>
                                                <th>Billing ID</th>
                                                <th>Facility Type </th>
                                                <th>WHT Agent Tag</th>
                                                <th>ITH Tag</th>
                                                <th>Non Vatable Tag</th>
                                                <th>Zero-rated Tag</th>
                                                <th>Vatable Purchases</th>
                                                <th>Zero Rated Purchases</th>
                                                <th>Zero Rated EcoZones Purchases </th>
                                                <th>Vat On Purchases</th>
                                                <th>EWT</th>
                                                <th>Total Amount</th>
                                                <th>OR Number</th>
                                                <th>Total Amount</th>
                                                <th>Original Copy</th>
                                                <th>Scanned Copy</th>
                                                <!-- <th width="2%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th> -->

                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $x=1;
                                                foreach($details AS $d){ 
                                                    if(!empty($d['purchase_id'])){ 
                                            ?>
                                            <tr>
                                                <td align="center" style="background: #fff;">                                                 
                                                    <div class="btn-group mb-0">
                                                        <a href="<?php echo base_url(); ?>purchases/print_2307/<?php echo $d['purchase_id']; ?>/<?php echo $d['purchase_detail_id']; ?>" target="_blank" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print BIR Form No.2307">
                                                            <span class="m-0 fas fa-print"></span><span id="clicksBS" class="badge badge-transparent"><?php echo $d['print_counter']; ?></span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?php echo $d['item_no'];?></td>
                                                <td><?php echo $d['short_name'];?></td>
                                                <td><?php echo $d['billing_id']; ?></td>
                                                <td align="center"><?php echo $d['facility_type']; ?></td>
                                                <td align="center"><?php echo $d['wht_agent']; ?></td>
                                                <td align="center"><?php echo $d['ith_tag']; ?></td>
                                                <td align="center"><?php echo $d['non_vatable']; ?></td>
                                                <td align="center"><?php echo $d['zero_rated']; ?></td>
                                                <td align="right">(<?php echo $d['vatables_purchases']; ?>)</td>
                                                <td align="right">(<?php echo $d['zero_rated_purchases']; ?>)</td>
                                                <td align="right">(<?php echo $d['zero_rated_ecozones']; ?>)</td>
                                                <td align="right">(<?php echo $d['vat_on_purchases']; ?>)</td>
                                                <td align="right"><?php echo $d['ewt']; ?></td>
                                                <td align="right">(<?php echo $d['total_amount']; ?>)</td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="or_no" id="or_no<?php echo $x; ?>" value="<?php echo $d['or_no'];?>">
                                                </td>
                                                <td align="right" style="padding:0px">
                                                    <input type="text" class="form-control" onblur="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="total_update" id="total_update<?php echo $x; ?>" value="<?php echo $d['total_update']; ?>">
                                                </td>
                                                <td align="center">
                                                    <label style="width:20px">
                                                        Yes
                                                        <input type="radio" class="form-control" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_yes<?php echo $x; ?>" value='1' <?php echo ($d['original_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <label style="width:20px">
                                                        No
                                                        <input type="radio" class="form-control" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="orig_copy<?php echo $x; ?>" id="orig_no<?php echo $x; ?>" value='2' <?php echo ($d['original_copy']=='2') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                    <label style="width:20px">
                                                        Yes
                                                        <input type="radio" class="form-control" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_yes<?php echo $x; ?>" value='1' <?php echo ($d['scanned_copy']=='1') ? 'checked' : ''; ?>>
                                                    </label>
                                                    <label style="width:20px">
                                                        No
                                                        <input type="radio" class="form-control" onchange="updatePurchases('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $d['purchase_detail_id']; ?>','<?php echo $d['purchase_id']; ?>','<?php echo $d['billing_id']; ?>')" name="scanned_copy<?php echo $x; ?>" id="scanned_no<?php echo $x; ?>" value='2' <?php echo ($d['scanned_copy']=='2') ? 'checked' : ''; ?>>
                                                    </label>
                                                </td>
                                                <!-- <td align="center">
                                                    <a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit">
                                                            <span class="m-0 fas fa-pen"></span>
                                                        </a>
                                                </td> -->
                                            </tr>
                                            <?php } $x++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php }else{ ?>
                                    <div><center><b>No Available Data...</b></center></div>
                                <?php } ?>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

            
         