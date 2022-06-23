<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
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
                                        <h4>Collection</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group pull-right">
                                            <button type="button" class="btn btn-warning " data-target="#bulk_upload" data-toggle="modal">
                                                <span class="fas fa-upload"></span> Bulk Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%">
                                            <tr>
                                                <td width="25%"></td>
                                                <!-- <td width="45%">
                                                    <select class="form-control select2" name="participant" id="participant">
                                                        <option value="">-- Select Participant --</option>
                                                        <?php 
                                                            foreach($participant AS $p){
                                                        ?>
                                                        <option value="<?php echo $p->billing_id; ?>"><?php echo $p->billing_id." - ".$p->participant_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td> -->
                                                <td width="45%">
                                                    <select class="form-control select2" name="ref_number" id="ref_number">
                                                        <option value=''>-- Select Reference No --</option>
                                                        <?php 
                                                            foreach($reference AS $r){
                                                        ?>
                                                        <option value="<?php echo $r->reference_no; ?>"><?php echo $r->reference_no; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td width="5%">
                                                    <input type='hidden' name='baseurl' id='baseurl' value="<?php echo base_url(); ?>">
                                                    <input type='button' class="btn btn-primary"  onclick="collection_filter()" value="Filter"></td>
                                                <td width="25%"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <?php //if(!empty($ref_no) && $ref_no!='null'){ ?>
                                <div>
                                    <table class="table-bordered table table-hosver" id="table-3" width="200%"> 
                                        <thead>
                                            <tr>
                                                <th width="1%"><center><span class="fas fa-bars"></span></center></th>
                                                <th width="10%">OR#</th>
                                                <th width="8%">Billing Remarks</th>
                                                <th width="14%">Particulars</th>
                                                <th width="10%">STL ID</th>
                                                <th width="16%">Participant Name</th>
                                                <th width="16%">Reference No</th>
                                                <th width="10%">Vatable Sales</th>
                                                <th width="10%">Zero Rated Sales</th>
                                                <th width="10%">Zero Rated Ecozone</th>
                                                <th width="10%">VAT</th>
                                                <th width="10%">EWT</th>
                                                <th width="10%">Total</th>
                                                <th width="10%">Def Int</th>
                                                <th width="10%">Overall Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $data2 = array();
                                                foreach($collection as $value) {
                                                    $key = $value['series_number'].$value['settlement_id'].$value['reference_no'];
                                                    if(!isset($data2[$key])) {
                                                        $data2[$key] = array(
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
                                                            'defint' => array(),
                                                            'defint_single'=>$value['defint'],  
                                                            'count_series'=>$value['count_series'],
                                                            'overall_total'=>$value['overall_total'],
                                                        );
                                                    }
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
                                                    $data2[$key]['defint'][] = $value['defint'];
                                                }

                                                foreach($data2 as $log) {
                                            ?>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" style="vertical-align: middle;">
                                                    <a href="" class="btn btn-primary btn-sm btn-block"><span class="fas fa-print"></span> Print</a>
                                                </td>
                                                <td class="td-btm pt-1 pb-1" align="center"><?php echo $log['series_number'];?></td>
                                                <?php if($log['count_series']>=1){ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['defint_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php }else if($log['count_series']<=2){ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['billing_remarks']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['particulars']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['settlement_id']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['company_name']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['reference_no']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['amount']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['zero_rated_ecozone']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['vat']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['ewt']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo implode("<br /><br />",$log['total']); ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['defint_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php }else{ ?>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['billing_remarks_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['particular_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['settlement_id_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['company_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['reference_no_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['amount_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['zero_rated_ecozone_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['vat_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['ewt_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['total_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['defint_single']; ?></td>
                                                    <td class="td-btm pt-1 pb-1"><?php echo $log['overall_total']; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php //} ?>
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