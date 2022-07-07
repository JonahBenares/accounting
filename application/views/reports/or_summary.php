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
                                    <h4>OR Summary</h4>
                                </div>
                                <div class="col-8">
                                    <button class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <form method="POST">
                                        <table width="100%">
                                            <tr>
                                                <td width="50%">
                                                <select class="form-control select2" name="participant" id="participant">
                                                    <option value="">-- Select Participant --</option>
                                                    <?php foreach($participant AS $p){ ?>
                                                        <option value="<?php echo $p->settlement_id;?>"><?php echo $p->participant_name;?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date From" id="date_from" name="date_from" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Date To" id="date_to" name="date_to" class="form-control" type="text" onfocus="(this.type='date')" id="date">
                                                </td>
                                                <td width="10%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <button type="button" onclick="filterOR();" class="btn btn-primary btn-block">Filter</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <?php 
                            if(!empty($participant) || !empty($date_from) || !empty($date_to)){
                            ?>
                            <table class="table-bordersed" width="100%">
                                <tr>
                                    <td width="7%"></td>
                                    <td width="13%"><b>Participant Name:</b></td>
                                    <td width="32%">: <?php echo $part ?></td>
                                    <td width="12%"><b>Date From - To:</b></td>
                                    <td width="33%">: <?php echo $date_from ?> - <?php echo $date_to ?></td>
                                    <td width="3%"></td>
                                </tr>
                            </table>
                            <br>
                            <div>
                                <table class="table" id='table-4'>
                                    <thead>
                                        <tr>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Date</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">OR No</td>
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">STL ID</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Company Name</td> 
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Amount</td>    
                                            <td style="vertical-align:middle!important;" class="td-30 td-head" align="center">Remarks</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(!empty($or_summary)){
                                                //$or_summary = array_map("unserialize", array_unique(array_map("serialize", $or_summary)));
                                            foreach($or_summary AS $or){
                                        ?>
                                        <tr>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo ($or['date']!='') ? date("F d,Y",strtotime($or['date'])) : ''; ?></td>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $or['or_no']; ?></td>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $or['stl_id']; ?></td>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $or['company_name']; ?></td>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo ($or['amount']!='') ? number_format($or['amount'],2) : '' ; ?></td>
                                            <?php if($or['remarks']!=''){ ?>
                                            <td align="center" class="td-sticky left-col-1 sticky-back"><?php echo $or['remarks']; ?></td>
                                            <?php } else {?>
                                            <td align="center" class="td-sticky left-col-1 sticky-back">
                                            <a href="<?php echo base_url(); ?>index.php/reports/ignore_or/<?php echo $or['or_no']; ?>" class="btn btn-md btn-primary" onclick="return confirm('Are you sure you want to ignore this OR?')">Ignore</a>
                                            <a href="<?php echo base_url(); ?>index.php/reports/cancel_or/<?php echo $or['or_no']; ?>" class="btn btn-md btn-danger" onclick="return confirm('Are you sure you want to cancel this OR?')">Cancel</a>
                                            </td>
                                        <?php } ?>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                     <?php }else{ ?>
                            <div><center><b>No Available Data...</b></center></div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

