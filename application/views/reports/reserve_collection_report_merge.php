<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/report.js"></script>

<style type="text/css">
    /*.divblock{
        display: block;
    }*/
    .disnone{
        display: none!important;
    }
    @media print{
        .disnone{
            display: block!important;
        }
        .divblock{
            display: none!important;
        }
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-4">
                                    <h4>Reserve Collection Report </h4>
                                </div>
                                <div class="col-8">
                                    <!-- <button onclick="printDiv('printableArea')" class="btn btn-success btn-sm pull-right"><span class="fas fa-print"></span> Print</button> -->
                                    <button type="button" onclick="printDiv('printableArea')" class="btn btn-primary btn-sm pull-right"><span class="fas fa-print"></span> Print</button>
                                    <a href="<?php echo base_url(); ?>reports/export_reserve_collection_report/" class="btn btn-success btn-sm pull-right"><span class="fas fa-file-export"></span> Export</a>
                                    <a href="<?php echo base_url(); ?>reports/export_reserve_iemop/" class="btn btn-success btn-sm pull-right"><span class="fas fa-file-export"></span> IEMOP Export</a>
                                </div>
                            </div>
                        </div>
                            <div class="card-body">
                               <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 offset-lg-1 offset-md-1 offset-sm-1">
                                    <table width="100%">
                                        <tr>
                                            <td width="22%">
                                                <select class="form-control select2" name="collection_date" id="collection_date">
                                                    <option value="">-- Select Collection Date --</option>
                                                </select>
                                            </td>
                                            <td width="22%">
                                                <select class="form-control select2" name="reference_no" id="reference_no">
                                                    <option value="">-- Select Statement No --</option>
                                                </select>
                                            </td>
                                            <td width="22%">
                                                <select class="form-control select2" name="settlement_id" id="settlement_id">
                                                    <option value="">-- Select Buyer --</option>
                                                </select>
                                            </td>
                                            <td width="1%">
                                                <input type='button' class="btn btn-primary"  onclick="filter_reserve_collection()" value="Filter">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div style="overflow-x:scroll;">
                                <table class="table-bordered table table-hosver divblock" id="table-3" width="200%"> 
                                    <thead>
                                        <tr>
                                            <th width="1%"  hidden="">OR#</th>
                                            <th width="1%">OR#</th>
                                            <th width="2%">Billing Remarks</th>
                                            <th width="1%">Date</th>
                                            <th width="2%">Particulars</th>
                                            <th width="2%">STL ID</th>
                                            <th width="5%">Participant Name</th>
                                            <th width="3%">Reference No</th>
                                            <th width="2%" align="center">Def Int</th>
                                            <th width="2%" align="center">Vatable Sales</th>
                                            <th width="2%" align="center">Zero Rated Sales</th>
                                            <th width="2%" align="center">Zero Rated Ecozone</th>
                                            <th width="2%" align="center">VAT</th>
                                            <th width="2%" align="center">EWT</th>
                                            <th width="2%" align="center">Total</th>
                                            <th width="2%" align="center">OR Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="td-btm pt-1 pb-1" hidden=""></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td align="center" class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                        </tr>
                                        <tr>
                                            <td class="td-btm pt-1 pb-1" hidden=""></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                            <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td> 
                                            <td align="center" class="td-btm td-blue pt-1 pb-1"></td> 
                                        </tr>
                                    </tbody>
                                </table>
                                <div id="printableArea">
                                    <table class="table-bordered table table-hosver disnone"  width="200%" style="font-size: 11px" > 
                                        <thead>
                                            <tr>
                                                <th width="1%"  hidden="">OR#</th>
                                                <th width="1%">OR#</th>
                                                <th width="2%">Billing Remarks</th>
                                                <th width="1%">Date</th>
                                                <th width="2%">Particulars</th>
                                                <th width="2%">STL ID</th>
                                                <th width="5%">Participant Name</th>
                                                <th width="3%">Reference No</th>
                                                <th width="2%" align="center">Def Int</th>
                                                <th width="2%" align="center">Vatable Sales</th>
                                                <th width="2%" align="center">Zero Rated Sales</th>
                                                <th width="2%" align="center">Zero Rated Ecozone</th>
                                                <th width="2%" align="center">VAT</th>
                                                <th width="2%" align="center">EWT</th>
                                                <th width="2%" align="center">Total</th>
                                                <th width="2%" align="center">OR Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" hidden=""></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1" align="center" style="padding:0px"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td align="center" class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"  hidden=""></td>
                                            </tr>
                                            <tr>
                                                <td class="td-btm pt-1 pb-1" hidden=""></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                <td class="td-btm pt-1 pb-1"></td>
                                                    <td class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td>
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td> 
                                                <td align="center" class="td-btm td-blue pt-1 pb-1"></td> 
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
</script>
