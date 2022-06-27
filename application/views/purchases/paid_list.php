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
                                                <td width="5%"></td>
                                                <td width="45%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value="">-- Select Participant --</option>
                                                        <?php foreach($participant AS $p){ ?>
                                                            <option value="<?php echo $p->billing_id; ?>"><?php echo $p->billing_id." - ".$p->participant_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="44%">
                                                    <select class="form-control select2" name="reference_number" id="reference_number">
                                                        <option value="">-- Select Reference Number --</option>
                                                        <?php foreach($head AS $r){ ?>
                                                            <option value="<?php echo $r->reference_number; ?>"><?php echo $r->reference_number; ?></option>
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
                               <!--  <div class="row">
                                    <div class="col-lg-4 offset-lg-4">
                                        <a style="color:#fff" class="btn btn-success btn-md btn-block" onclick="pay_all('<?php echo base_url(); ?>')">Pay All</a>
                                    </div>
                                </div>   
                                <br>  -->
                                <div class="table-responsive" id="payment-list">
                                    <table class="table-bordered table table-hover" id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <!-- <th width="1%" align="center" style="background:rgb(245 245 245)">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th> -->
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
                                                <!-- <td align="center" style="background: #fff;">
                                                    <div class="btn-group mb-0">
                                                        <a style="color:#fff" onclick="add_payment('<?php echo base_url(); ?>','<?php echo $d['purchase_id'];?>','<?php echo $d['purchase_detail_id'];?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Payment Details">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                </td> -->
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


                
                                       
         