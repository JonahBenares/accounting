<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>WESM Transaction - Sales Reserve (Unsaved)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-bordered table table-hover " id="table-5">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Billing Period (From)</th>
                                            <th>Billing Period (To)</th>
                                            <th>Reference Number</th>
                                            <th>Due Date</th>
                                            <th width="5%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($details AS $d){ 
                                        ?>
                                        <tr>
                                            <td><?php echo $d['date'];?></td>
                                            <td><?php echo $d['billing_from'];?></td>
                                            <td><?php echo $d['billing_to'];?></td>
                                            <td><?php echo $d['reference_number'];?></td>
                                            <td><?php echo $d['due_date'];?></td>
                                            <td class="text-center">
                                                <input type='hidden' name='baseurl' id='baseurl' value='<?php echo base_url(); ?>'>
                                                 <button type="button" class="btn btn-primary btn-sm text-white" style="margin-right: 2px;" onclick="save_unsaved_sales('<?php echo $d['reserve_sales_id']; ?>','<?php echo $d['reference_number']; ?>')">
                                                    <span class="fas fa-check" style="margin:0px"></span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm text-white" onclick="delete_unsaved_sales('<?php echo $d['reserve_sales_id']; ?>','<?php echo $d['reference_number']; ?>')">
                                                    <span class="fas fa-trash" style="margin:0px"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
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

<script type="text/javascript">
    $(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
    })
});


    function save_unsaved_sales(reserve_sales_id,reference_no) {
        var loc= document.getElementById("baseurl").value;
        var redirect = loc+"sales/save_unsaved_reserve";
        var msg = (reference_no && reference_no.trim() !== "")
        ? 'Are you sure you want to save ' + reference_no + '?'
        : 'Are you sure you want to save this transaction?';

        var conf = confirm(msg);
        if (conf) {
             $.ajax({
                data: "reserve_sales_id="+reserve_sales_id,
                type: "POST",
                url: redirect,
                success: function(response){
                    location.reload();
                }
            });
        }
    }

    function delete_unsaved_sales(reserve_sales_id,reference_no){
        var loc= document.getElementById("baseurl").value;
        var redirect = loc+"sales/cancel_reserve_sales";

        var msg = (reference_no && reference_no.trim() !== "")
        ? 'Are you sure you want to cancel ' + reference_no + '?'
        : 'Are you sure you want to cancel this transaction?';

        var conf = confirm(msg);
            if(conf){
                $.ajax({
                    data: "reserve_sales_id="+reserve_sales_id,
                    type: "POST",
                    url: redirect,
                    success: function(response){
                        location.reload();
                    }
                });
            }
    }
</script>                             
