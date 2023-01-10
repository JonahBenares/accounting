
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
                                    <h4>Consolidation/Summary of all Sales Adjustment Transaction</h4>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-success btn-sm pull-right"  data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-file-export"></span> Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <table width="100%">
                                        <tr>
                                            <td width="30%">
                                                <select class="form-control select2" name="ref_no" id="ref_no">
                                                    <option value="">-- Select Company --</option>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                <input type='button' class="btn btn-primary"  value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive" id="table-wesm" >
                                <table class="table table-s table-bordered table-hover" id="save-stage" style="width:150%;">
                                    <thead>
                                        <tr>
                                            <th>Billing Period</th>
                                            <th width="15%" style="position:sticky; left:0; z-index: 10;background: rgb(245 245 245);">BIlling ID</th> 
                                            <th width="15%" style="position:sticky; left:231px; z-index: 10;background: rgb(245 245 245);">Company Name</th>  
                                            <th>Vatable Sales</th> 
                                            <th>Zero-Rated Ecozones</th>     
                                            <th>VAT on Sales</th>   
                                            <th>EWT Sales</th>    
                                            <th>Total</th> 
                                            <th width="2%">Original Copy</th>
                                            <th width="2%">Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff;">BIlling ID</td> 
                                            <td style="position:sticky; left:231px; z-index: 10;background: #fff;">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td> 
                                            <td>Yes</td> 
                                            <td>No</td> 
                                        </tr>
                                        <tr>
                                            <td>Billing Period</td>
                                            <td style="position:sticky; left:0; z-index: 10;background: #fff;">BIlling ID</td> 
                                            <td style="position:sticky; left:231px; z-index: 10;background: #fff;">Company Name</td>  
                                            <td>Vatable Sales</td> 
                                            <td>Zero-Rated Ecozones</td>     
                                            <td>VAT on Sales</td>   
                                            <td>EWT Sales</td>    
                                            <td>Total</td> 
                                            <td>No</td> 
                                            <td>Yes</td> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?php echo base_url(); ?>masterfile/insert_employee" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>Billing Date From</label>
                            <input type="date" name="signature" class="form-control">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Billing Date To</label>
                            <input type="date" name="signature" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control select2" name="ref_no" id="ref_no">
                            <option value="">-- Select Company --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="submit" class="btn btn-success" value="Export">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

