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
                    <input type="hidden" id="saved" name="saved" value="<?php echo $ref_no; ?>">
                    <input type="hidden" id="ref_no" name="ref_no">
                    <input type="hidden" id="old_series_no" name="old_series_no" class="form-control">
                    <input type="hidden" id="res_collection_id" name="res_collection_id" class="form-control">
                    <input type="hidden" id="settlement_id" name="settlement_id" class="form-control">
                    <input type="hidden" id="baseurl" name="baseurl" value="<?php echo base_url(); ?>">
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
                                        <h4>Reserve Collection List</h4>
                                    </div>
                                    <div>
                                        
                                    </div>
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
                                                        <?php foreach($collection_date AS $cd){ ?>
                                                            <option value="<?php echo $cd->collection_date;?>"><?php echo date("F d, Y",strtotime($cd->collection_date));?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="21%">
                                                    <select class="form-control select2" name="reference_no" id="reference_no">
                                                        <option value="">-- Select Statement No --</option>
                                                        <?php foreach($reference_no AS $rn){ ?>
                                                            <option value="<?php echo $rn->reference_no;?>"><?php echo $rn->reference_no;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="21%">
                                                    <select class="form-control select2" name="stl_id" id="stl_id">
                                                        <option value="">-- Select Buyer --</option>
                                                        <?php foreach($buyer AS $b){ ?>
                                                            <option value="<?php echo $b->settlement_id;?>"><?php echo $b->buyer_fullname;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="1%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary btn-block"  onclick="collection_reserve_filter()" value="Filter">
                                                </td>
                                                <!-- <td width="9%">
                                                    <a href="<?php echo base_url();?>sales/PDF_OR_bulk_reserve/<?php echo $date;?>/<?php echo $ref_no;?>/<?php echo $stl_id;?>" target='_blank' class="btn btn-success btn-block">Export Bulk PDF</a>   
                                                </td> -->
                                            </tr>
                                        </table>
                                    </div>
                                    
                                </div>
                                <?php if(!empty($collection)){ ?>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <strong>Quick Scan!</strong> 
                                    <a href="http://localhost/accounting/sales/export_not_download_reserve/<?php echo $date; ?>/<?php echo $ref_no; ?>/<?php echo $stl_id; ?>" target="_blank"><u>Click here</u></a> to check if downloaded files are complete.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>  
                                <?php } ?>
                                <hr>
                                <?php if(!empty($collection)){ ?>
                                    <div class="row">
                                        <div class="col-lg-3 offset-lg-3">
                                            <select name="signatory" id="signatory" class="form-control" onchange="select_signatory_reserve()">
                                                <option value="">--Select Signatory--</option>
                                                <?php foreach($employees AS $emp){ ?>
                                                    <option value="<?php echo $emp->user_id; ?>"><?php echo $emp->fullname; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <a href="<?php echo base_url();?>sales/PDF_OR_bulk_reserve/<?php echo $date;?>/<?php echo $ref_no;?>/<?php echo $stl_id;?>" target='_blank' class="btn btn-success btn-block" id="export">Export Bulk PDF</a> 
                                        </div>
                                        <input type="hidden" id="date_collect" value="<?php echo $date; ?>">
                                        <input type="hidden" id="refno" value="<?php echo $ref_no; ?>">
                                        <input type="hidden" id="stlid" value="<?php echo $stl_id; ?>">
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
                                                <?php 
                                                    $data2 = array();
                                                    foreach($collection as $value) {
                                                        $key = $value['series_number'].$value['settlement_id'].$value['reference_no'].$value['collection_date'];
                                                        if(!isset($data2[$key])) {
                                                            $data2[$key] = array(
                                                                'item_no' => $value['item_no'], 
                                                                'item_no_array' => array(), 
                                                                'res_collection_id' => $value['res_collection_id'], 
                                                                'res_collection_details_id' => $value['res_collection_details_id'], 
                                                                'series_number' => $value['series_number'], 
                                                                'billing_remarks' => array(),
                                                                'billing_remarks_single'=>$value['billing_remarks'],
                                                                'particulars' => array(),
                                                                'particular_single'=>$value['particulars'], 
                                                                'settlement_id' => array(),
                                                                'settlement_id_single'=>$value['settlement_id'], 
                                                                'company_name' => array(),
                                                                'company_single'=>$value['company_name'], 
                                                                'reference_no' => array(),
                                                                'reference_no_single'=>$value['reference_no'], 
                                                                'amount' => array(),
                                                                'amount_single'=>$value['amount'],
                                                                'zero_rated' => array(),
                                                                'zero_rated_single'=>$value['zero_rated'], 
                                                                'zero_rated_ecozone' => array(),
                                                                'zero_rated_ecozone_single'=>$value['zero_rated_ecozone'], 
                                                                'vat' => array(),
                                                                'vat_single'=>$value['vat'], 
                                                                'ewt' => array(),
                                                                'ewt_single'=>$value['ewt'], 
                                                                'total' => array(),
                                                                'total_single'=>$value['total'],
                                                                'defint_single'=>$value['defint'],
                                                                'or_date_single'=>$value['or_date'],  
                                                                'or_no_remarks_single'=>$value['or_no_remarks'],  
                                                                'count_series'=>$value['count_series'],
                                                                'overall_total'=>$value['overall_total'],
                                                            );
                                                        }
                                                        $data2[$key]['item_no_array'][] = $value['item_no'];
                                                        $data2[$key]['billing_remarks'][] = $value['billing_remarks'];
                                                        $data2[$key]['particulars'][] = $value['particulars'];
                                                        $data2[$key]['settlement_id'][] = $value['settlement_id'];
                                                        $data2[$key]['company_name'][] = $value['company_name'];
                                                        $data2[$key]['reference_no'][] = $value['reference_no'];
                                                        $data2[$key]['amount'][] = $value['amount'];
                                                        $data2[$key]['zero_rated'][] = $value['zero_rated'];
                                                        $data2[$key]['zero_rated_ecozone'][] = $value['zero_rated_ecozone'];
                                                        $data2[$key]['vat'][] = $value['vat'];
                                                        $data2[$key]['ewt'][] = $value['ewt'];
                                                        $data2[$key]['total'][] = $value['total'];
                                                    }
                                                    $x=1;
                                                    foreach($data2 as $log) {
                                                ?>
                                                <tr>
                                                    <td class="td-btm pt-1 pb-1" style="vertical-align: middle;" align="center">
                                                        <div style="display:flex">
                                                            <a href="<?php echo base_url(); ?>sales/print_OR_new_reserve/<?php echo $log['res_collection_id'];?>/<?php echo $log['settlement_id_single'];?>/<?php echo $log['reference_no_single'];?>" target='_blank' class="btn btn-primary btn-sm text-white" style="margin-right: 2px;"><span class="fas fa-print" style="margin:0px"></span></a>
                                                            <a href="<?php echo base_url();?>sales/PDF_OR_reserve/<?php echo $log['res_collection_id'];?>/<?php echo $log['settlement_id_single'];?>/<?php echo $log['reference_no_single'];?>/<?php echo $log['series_number'];?>" title="Export PDF" target='_blank' class="btn btn-success btn-sm text-white print_pdf" id="print_pdf<?php echo $x; ?>"><span class="fas fa-file-export" style="margin:0px"></span></a>
                                                            <input type="hidden" id="collection_idurl<?php echo $x; ?>" value="<?php echo $log['res_collection_id']; ?>">
                                                            <input type="hidden" id="settlement_id_singleurl<?php echo $x; ?>" value="<?php echo $log['settlement_id_single']; ?>">
                                                            <input type="hidden" id="reference_no_singleurl<?php echo $x; ?>" value="<?php echo $log['reference_no_single']; ?>">
                                                            <input type="hidden" id="series_numberurl<?php echo $x; ?>" value="<?php echo $log['series_number']; ?>">
                                                        </div>
                                                    </td>
                                                    <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                        <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="series_number" id="series_number<?php echo $x; ?>" value="<?php echo $log['series_number'];?>" onchange="updateSeriesReserve('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['res_collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input Series Number'>
                                                        <span hidden><?php echo $log['series_number'];?></span>
                                                        <input type="hidden" name="old_series_no" id="old_series_no<?php echo $x; ?>" value='<?php echo $log['series_number'];?>'> 
                                                    </td>
                                                    <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                    <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="date" name="or_date" id="or_date<?php echo $x; ?>" value="<?php echo $log['or_date_single'];?>" onchange="updateORDateReserve('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['res_collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');">
                                                    <span hidden><?php echo $log['or_date_single'];?></span>
                                                    <input type="hidden" name="old_or_date" id="old_or_date<?php echo $x; ?>" value='<?php echo $log['or_date_single'];?>'> 
                                                    </td>
                                                     <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                        <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="or_no_remarks" id="or_no_remarks<?php echo $x; ?>" value="<?php echo $log['or_no_remarks_single'];?>" onchange="updateorRemarksReserve('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['res_collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input OR Remarks'>
                                                        <span hidden><?php echo $log['or_no_remarks_single'];?></span>
                                                    </td>
                                                    <td class="td-btm pt-1 pb-1" align="center" style="padding:0px">
                                                        <input  style="border:0px solid #000;background: #dde1ff;padding: 3px;" type="text" name="def_int" id="def_int<?php echo $x; ?>" value="<?php echo $log['defint_single'];?>" onchange="updateDefIntReserve('<?php echo base_url(); ?>','<?php echo $x; ?>','<?php echo $log['res_collection_id'];?>','<?php echo $log['settlement_id_single'];?>','<?php echo $log['reference_no_single'];?>','<?php echo implode(',',$log['item_no_array']) ?>');" placeholder='Input Def Int'>
                                                        <span hidden><?php echo $log['defint_single'];?></span>
                                                    </td>
                                                    <?php if($log['count_series']>=1){ ?>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                    <?php }else if($log['count_series']<=2){ ?>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                    <?php }else{ ?>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['billing_remarks_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['particular_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                        <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['amount_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_ecozone_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['vat_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['ewt_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['total_single']; ?></td>
                                                        <td align="center" class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php $x++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
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