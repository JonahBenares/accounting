<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<!-- Modal -->
<div class="modal fade" id="updateSeries" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Series Number</h5>
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
                    <button type="button" class="btn btn-primary" onclick="saveSeries()">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="d-flex justify-content-between">  
                                    <div>
                                        <div class="d-flex justify-content-start">
                                            <span class="badge badge-primary badge-sm" style="margin-right:10px">MERGE</span><h4>Reserve Collection List</h4>
                                        </div>
                                    </div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <select class="form-control select2" name="col_date" id="col_date">
                                                        <option value="">-- Select Collection Date --</option>
                                                    </select>
                                                </td>
                                                <td width="21%">
                                                    <select class="form-control select2" name="reference_no" id="reference_no">
                                                        <option value="">-- Select Statement No --</option>
                                                    </select>
                                                </td>
                                                <td width="21%">
                                                    <select class="form-control select2" name="stl_id" id="stl_id">
                                                        <option value="">-- Select Buyer --</option>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input type='button' class="btn btn-primary btn-block"  onclick="collection_reserve_filter()" value="Filter">
                                                </td>
                                                <!-- <td width="9%">
                                                    <a href="<?php echo base_url();?>sales/PDF_OR_bulk_reserve/<?php echo $date;?>/<?php echo $ref_no;?>/<?php echo $stl_id;?>" target='_blank' class="btn btn-success btn-block">Export Bulk PDF</a>   
                                                </td> -->
                                            </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <strong>Quick Scan!</strong> 
                                    <a href="http://localhost/accounting/sales/export_not_download_reserve/" target="_blank"><u>Click here</u></a> to check if downloaded files are complete.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>  
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 offset-lg-3">
                                        <select name="signatory" id="signatory" class="form-control select2" onchange="select_signatory_reserve()">
                                            <option value="">--Select Authorized Person--</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <a href="<?php echo base_url();?>sales/PDF_OR_bulk_reserve/" target='_blank' class="btn btn-success btn-block" id="export">Export Bulk PDF</a> 
                                    </div>
                                </div>
                                <div style="overflow-x:scroll;">
                                    <table class="table-bordered table table-hosver" id="table-3" width="200%"> 
                                        <thead>
                                            <tr>
                                                <th width="1%"><center><span class="fas fa-bars"></span></center></th>
                                                <th width="5%">OR#</th>
                                                <th width="2%">OR Date</th>
                                                <th width="5%">OR Remarks</th>
                                                <th width="2%" align="center">Def Int</th>
                                                <th width="2%">Billing Remarks</th>
                                                <th width="2%">Particulars</th>
                                                <th width="2%">STL ID</th>
                                                <th width="5%">Participant Name</th>
                                                <th width="3%">Reference No</th>
                                                <th width="3%" align="center">Vatable Sales</th>
                                                <th width="3%" align="center">Zero Rated Sales</th>
                                                <th width="3%" align="center">Zero Rated Ecozone</th>
                                                <th width="3%" align="center">VAT</th>
                                                <th width="3%" align="center">EWT</th>
                                                <th width="3%" align="center">Total</th>
                                                <th width="3%" align="center">Overall Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" style="vertical-align: middle;" align="center">
                                                    <div style="display:flex">
                                                        <a href="<?php echo base_url(); ?>sales/print_OR_new_reserve/" target='_blank' class="btn btn-primary btn-sm text-white" style="margin-right: 2px;"><span class="fas fa-print" style="margin:0px"></span></a>
                                                        <a href="<?php echo base_url();?>sales/PDF_OR_reserve/" title="Export PDF" target='_blank' class="btn btn-success btn-sm text-white print_pdf" id="print_pdf">
                                                            <span class="fas fa-file-export" style="margin:0px"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="series_number" id="series_number" value="" onchange="updateSeriesReserve" placeholder='Input Series Number'>
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="date" name="or_date" id="or_date" value="" onchange="updateORDateReserve">
                                                </td>
                                                    <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="or_no_remarks" id="or_no_remarks" value="" onchange="updateorRemarksReserve('');" placeholder='Input OR Remarks'>
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="def_int" id="def_int" value="" onchange="updateDefIntReserve('');" placeholder='Input Def Int'>
                                                </td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
<div class="modal fade" id="bulk_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width:1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Bulk Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' enctype="multipart/form-data" target='_blank'>
                <div class="modal-body">
                    <span class="m-b-20">
                        <b>Important Note: </b>Make sure the columns are correct before uploading the file to make sure the data are correctly captured. Refer to the details below to ensure your columns are correctly formatted:
                    </span>
                    <br>
                    <br>
                    <table class="table-bordered" width="100%" style="font-size: 13px;">
                        <tr>
                            <td colspan="15">Column Description</td>
                        </tr>
                        <tr>
                            <td width="6%" align="center">A</td>
                            <td width="6%" align="center">B</td>
                            <td width="6%" align="center">C</td>
                            <td width="6%" align="center">D</td>
                            <td width="6%" align="center">E</td>
                            <td width="6%" align="center">F</td>
                            <td width="6%" align="center">G</td>
                            <td width="6%" align="center">H</td>
                            <td width="6%" align="center">I</td>
                            <td width="6%" align="center">J</td>
                            <td width="6%" align="center">K</td>
                            <td width="6%" align="center">L</td>
                            <td width="6%" align="center">M</td>
                            <td width="6%" align="center">N</td>
                            <td width="6%" align="center">O</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Item No</td>
                            <td style="vertical-align: top;">Billing Remarks</td>
                            <td style="vertical-align: top;">Particulars</td>
                            <td style="vertical-align: top;">Received From (STL ID)</td>
                            <td style="vertical-align: top;">Buyer Full Name</td>
                            <td style="vertical-align: top;">Statement No</td>
                            <td style="vertical-align: top;">Vatable Sales</td>
                            <td style="vertical-align: top;">Zero Rated Sales</td>
                            <td style="vertical-align: top;">Zero Rated Ecozone</td>
                            <td style="vertical-align: top;">VAT on Sales</td>
                            <td style="vertical-align: top;">Withholding Tax</td>
                            <td style="vertical-align: top;">Total</td>
                            <td style="vertical-align: top;">OR #</td>
                            <td style="vertical-align: top;">Def Int</td>
                            <td style="vertical-align: top;">Series #  </td>
                        </tr>
                    </table>
                    <br>
                    <span class="">
                        <b>Additional Notes:</b> "Subtotal" word should be in column F. There must be a an empty row every after subtotal. DefInt and Series # should be encoded inline with the "Subtotal" row at the N and O columns, consecutively.
                    </span>
                    <br>
                    <br>
                    <br>
                     <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Collection Date:</label>
                               <input type="date" name="collection_date" id="collection_date" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Upload File here:</label>
                               <input type="file" name="collectionbulk" id="collectionbulk" class="form-control">
                               <center><span id='alt'></span></center>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label><br></label>
                                <input type="button" class="btn btn-primary btn-block" id="upload" value='Upload' onclick="uploadCollection()">
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>