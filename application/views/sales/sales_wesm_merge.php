<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                            <div class="card-header">
                                <h4>WESM Transaction - Sales</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table-bordsadered" width="100%">
                                                <tr>
                                                    <td>
                                                        <input type='text' class="form-control" name="billing_from" id="billing_from" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Billing From">
                                                    </td>
                                                    <td>
                                                        <input type='text' class="form-control" name="billing_to" id="billing_to" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Billing To">
                                                    </td>
                                                    <td>
                                                        <select class="form-control select2" name="participant" id="participant">
                                                            <option value=''>-- Select Participant --</option>
                                                        </select>
                                                    </td>
                                                    <td  width="2%" rowspan="2">
                                                        <button type="button" onclick="filterSales();" class="btn btn-primary btn-block">Filter</button>
                                                    </td>
                                                    <input name="baseurl" id="baseurl" value="" class="form-control" type="hidden" >
                                                    <td width="10%" rowspan="2">
                                                        <a href="<?php echo base_url();?>sales/sales_wesm_pdf_or_bulk/" target='_blank' class="btn btn-success btn-block">Bulk OR PDF </a>   
                                                        <a href="<?php echo base_url();?>sales/sales_wesm_pdf_si_bulk/" target='_blank' class="btn btn-warning btn-block">Bulk SI PDF</a>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="form-control select2" name="ref_no" id="ref_no">
                                                            <option value=''>-- Select Reference No --</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select2" name="due_date" id="due_date">
                                                            <option value="">-- Select Due Date --</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="in_ex_sub" id="in_ex_sub">
                                                            <option value="">-- Select Include or Exlcude Sub-participant--</option>
                                                                <option value="0">Include Sub-participant</option>
                                                                <option value="1">Exclude Sub-participant</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <strong>Quick Scan!</strong> 
                                    <a href="<?php echo base_url(); ?>sales/export_not_download_sales_wesm/" target="_blank"><u>Click here</u></a> to check if downloaded files are complete.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>  
                                <hr>
                                <table class="table-bsordered" width="100%">
                                    <tr>
                                        <td>Participant Name</td>
                                        <td>: </td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Reference Number</td>
                                        <td>: </td>
                                        <td width="15%">Billing Period (From)</td>
                                        <td>: </td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>: </td>
                                        <td>Billing Period (To)</td>
                                        <td>: </td>
                                    </tr>                                    
                                    <tr>
                                        <td>Due Date</td>
                                        <td>: </td>
                                    </tr>
                                
                                </table>
                                <br>
                                <div class="table-responsive">
                                    <form method="POST" id="print_mult">
                                        <table class="table-bordered table table-hover " id="table-5" style="width:200%;">
                                            <thead>
                                                <tr>         
                                                    <th width="2%"><input class="form-control" type="checkbox" id="select-all"></th>
                                                    <th width="2%" hidden=""><input class="form-control" type="checkbox" id="select-all"></th>
                                                    <th width="2%" align="center" style="background:rgb(245 245 245)">
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Multiple" onclick="printMultiple()"><span class="fas fa-print mr-1 mt-1 mb-1"></span></button>
                                                    </th>
                                                    <th>PDF</th>
                                                    <th>Item No</th>
                                                    <th>BS No.</th>
                                                    <th>OR No.</th>
                                                    <th>STL ID / TPShort Name</th>
                                                    <th style="position:sticky; left:0;  z-index: 10;background: rgb(240 240 240);">Billing ID</th>
                                                    <th style="position:sticky; left:99px; z-index: 10;background: rgb(240 240 240);">Trading Participant Name</th>
                                                    <th style="position:sticky; left:283px; z-index: 10;background: rgb(240 240 240);">Billing Period</th>
                                                    <th style="position:sticky; left:462px; z-index: 10;background: rgb(240 240 240);">Reference No</th>
                                                    <th>Facility Type </th>
                                                    <th>WHT Agent Tag</th>
                                                    <th>ITH Tag</th>
                                                    <th>Non Vatable Tag</th>
                                                    <th>Zero-rated Tag</th>
                                                    <th>Vatable Sales</th>
                                                    <th>Zero Rated Sales</th>
                                                    <th>Zero Rated EcoZones Sales</th>
                                                    <th>Vat On Sales</th>
                                                    <th>EWT</th>
                                                    <th>Total Amount</th>
                                                    <th>EWT Amount</th>
                                                    <th>Original Copy</th>
                                                    <th>Scanned Copy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align="center">
                                                        <input type="checkbox" class="form-control multiple_print" name="multiple_print[]" id="print_checked" style="width: 25px;" value="">
                                                    </td>
                                                    <td hidden=""></td>
                                                    <td style="width:100px;margin: 0px 6px;">
                                                        <input type="text" class="form-control" onblur="saveBseries('')" name="series_number" id="series_number" value="">
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url();?>sales/sales_wesm_pdf_or/" title="Export PDF" target='_blank' class="btn btn-success btn-sm text-white"><span class="fas fa-file-export" style="margin:0px"></span></a>
                                                        <a href="<?php echo base_url();?>sales/sales_wesm_pdf_si/" title="Export PDF" target='_blank' class="btn btn-warning btn-sm text-white"><span class="fas fa-file-export" style="margin:0px"></span></a>
                                                    </td>
                                                    <td><center></center></td>
                                                    <td width="7%"><a href="" data-toggle="modal" id="BSNo" data-target="#olSeries" data-bs="" data-old-bs="" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"></a></td>
                                                  
                                                    <td width="7%"><a href="" data-toggle="modal" id="ORNo" data-target="#oldOR" data-series-col="" data-old-series-col="" class="btn-link" style="font-size:13px;text-align: left;" title="View Old OR"></a></td>
                                                    
                                                  
                                                    <td></td>
                                                    <td style="position: sticky;left:0;background:#fff;z-index: 999;"></td>
                                                    <td style="position: sticky;left:99px;background:#fff;z-index: 999;"></td>
                                                    <td style="position: sticky;left:283px;background:#fff;z-index: 999;"><</td>
                                                    <td style="position: sticky;left:462px;background:#fff;z-index: 999;"></td>
                                                    <td align="center"></td>
                                                    <td align="center"></td>
                                                    <td align="center"></td>
                                                    <td align="center"></td>
                                                    <td align="center"></td>
                                                    <td align="right"></td>
                                                    <td align="right"></td>
                                                    <td align="right"></td>
                                                    <td align="right"></td>
                                                    <td align="right">()</td>
                                                    <td align="right"></td>
                                                    <td align="right" style="padding:0px">
                                                        <input type="text" class="form-control" onblur="updateSales('')" name="ewt_amount" id="ewt_amount" value="">
                                                    </td>
                                                    <td align="center">
                                                        <span class="m-b-10">Yes</span>
                                                        <label style="width:20px;margin: 0px 6px;">
                                                            <input type="radio"  onchange="updateSales('')" name="orig_copy" id="orig_yes" value='1'>
                                                        </label>
                                                        <span class="m-b-10">No</span>
                                                        <label style="width:20px;margin: 0px 6px;">
                                                        <input type="radio" onchange="updateSales('')" name="orig_copy" id="orig_no" value='2'>
                                                        </label>
                                                    </td>
                                                    <td align="center">
                                                        <span class="m-b-10">Yes</span>
                                                        <label style="width:20px;margin: 0px 6px;">
                                                            <input type="radio"  onchange="updateSales('')" name="scanned_copy" id="scanned_yes" value='1' >
                                                        </label>
                                                        <span class="m-b-10">No</span>
                                                        <label style="width:20px;margin: 0px 6px;">
                                                            <input type="radio" onchange="updateSales('')" name="scanned_copy" id="scanned_no" value='2' >
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                    <!-- <div><center><b>No Available Data...</b></center></div> -->
                       <!--  </form> -->
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="updateSerial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Billing Statement Series</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Series Number</label>
                        <input type="text" id="series_number" name="series_number" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="hidden" id="ref_no" name="ref_no" class="form-control" value="<?php echo $ref_no; ?>">
                    <input type="hidden" id="old_series_no" name="old_series_no" class="form-control">
                    <input type="hidden" id="sales_detail_id" name="sales_detail_id" class="form-control">
                    <input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
                    <button type="button" class="btn btn-primary" onclick="saveBseries()">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
                
          
<div class="modal fade" id="oldOR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #6777ef;color:#fff">
                <h4 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current OR</small>
                    <br><!-- <input type="text" id="series_no" class="form-control"> --><span id="series_no"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_series_no_disp"></span></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>                             
<div class="modal fade" id="olSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:400px">
        <div class="modal-content">
            <div class="modal-header" style="background: #ffa426;color:#fff">
                <h5 class="modal-title" id="exampleModalLabel" style="line-height: 1">
                    <small style="font-size: 10px;">Current Series</small>
                    <br><span id="bs_no"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-bordered">
                    <tr>
                        <td style="padding: 3px;border-left: 1px solid #fff;border-right:1px solid #fff;">
                            <b><span id="old_bs_no_disp"></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
    })
});
</script>                             
