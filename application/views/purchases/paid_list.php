<script src="<?php echo base_url(); ?>assets/js/purchases.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Paid</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%">
                                            <tr>
                                                <td width="2%"></td>
                                                <td width="45%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value="">-- Select Participant --</option>
                                                        <?php foreach($participant AS $p){ ?>
                                                            <option value="<?php echo $p->settlement_id; ?>"><?php echo $p->participant_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="30%">
                                                    <select class="form-control select2" name="reference_number" id="reference_number">
                                                        <option value="">-- Select Reference Number --</option>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="20%">
                                                    <select class="form-control select2" name="due_date" id="due_date">
                                                        <option value="">-- Select Due Date --</option>
                                                        <?php foreach($date AS $d){ ?>
                                                            <option value="<?php echo $d->due_date; ?>"><?php echo $d->due_date; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input name="baseurl" id="baseurl" value="<?php echo base_url(); ?>" class="form-control" type="hidden" >
                                                    <button class="btn btn-primary btn-block" type="button" onclick="paid_filter()">Filter</button>
                                                </td>
                                                <td width="5%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php if(!empty($details) && !empty($ref_no)){ ?>
                                <div class="table-responsive" id="payment-list">
                                    <table class="table-bordered table table-hover" id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th>Payment Date</th>
                                                <th>Trading Participant Name</th>
                                                <th>Mode of Purchase</th>
                                                <th>Mode of Payment</th>
                                                <th>Puchase Amount</th>
                                                <th>VAT</th>
                                                <th>EWT</th>   
                                                <th>Total Amount</th>                                           
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($details AS $d){ 
                                            ?>
                                            <tr>
                                                <td><?php echo ($d['payment_date']!='') ? date("F d,Y",strtotime($d['payment_date'])) : '';?></td>
                                                <td><?php echo $d['company_name'];?></td>
                                                <td><?php echo $d['purchase_mode'];?></td>
                                                <td><?php echo ($d['payment_mode']==1) ? 'Check' : 'Cash';?></td>
                                                <td><?php echo $d['purchase_amount'];?></td>
                                                <td><?php echo $d['vat'];?></td>
                                                <td><?php echo $d['ewt'];?></td>
                                                <td><?php echo $d['total_amount'];?></td>
                                            </tr>
                                            <?php } ?>
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


                
                                       
         