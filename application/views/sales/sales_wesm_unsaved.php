<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>WESM Transaction - Sales (Unsaved)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered table table-hover " id="table-5">
                                    <thead>
                                        <tr>        
                                            <th>Participant Name</th>
                                            <th>Participant Name</th>
                                            <th>Reference Number</th>
                                            <th>Date</th>
                                            <th>Due Date</th>
                                            <th>Billing Period (From)</th>
                                            <th>Billing Period (To)</th>
                                            <th width="5%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <a href="" class="btn btn-primary btn-sm text-white" style="margin-right: 2px;">
                                                    <span class="fas fa-print" style="margin:0px"></span>
                                                </a>
                                                <a href="" class="btn btn-danger btn-sm text-white" >
                                                    <span class="fas fa-trash" style="margin:0px"></span>
                                                </a>
                                            </td>
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
