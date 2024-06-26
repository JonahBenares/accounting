<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title_sub"></h5>
            </div>
                <div class="modal-body">
                <table class="table table-striped table-hover"  style="width:100%;">
                    <thead>
                        <tr>
                            <th>Sub Participant</th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php 
                            if(!empty($subparticipant)){
                            foreach($subparticipant AS $sp){ ?>
                        <tr >
                            <td>
                                <?php echo $sp['res_billing_id'];?> - <?php echo $sp['res_participant_name'];?>
                            </td>
                            <td>
                                <a onclick="return confirm('Are you sure you want to delete this data?')" href="<?php echo base_url(); ?>masterfile/delete_res_subparticipant/<?php echo $sp['res_participant_id']?>/<?php echo $sp['res_subparticipant_id']?>" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    </tbody>
                    <?php } } else { ?>
                        <tr>
                            <td align="center" colspan='9'><center>No Data Available.</center></td>
                        </tr>
                        <?php } ?>
                </table>
            </div>
            <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/insert_res_sub">
            <div class="modal-body">
                <div class="form-group addSub">
            <label>Add Sub Participant </label>
            <table class="m-b-10 append" width="100%">
            <tr>
                <td width="90%">
                    <select class="form-control select2" name="res_sub_participant[]" id="res_sub_participant" multiple="multiple">
                        
                        <?php foreach($res_sub_participant as $sp) {
                        //if ($sp->participant_id != $id) { ?>
                            <option value='<?php echo $sp->res_participant_id; ?>'><?php echo $sp->res_billing_id; ?></option>
                        <?php } //}?>
                    </select>
                </td>
                <td>
                    <!-- <div class="btn-group addmoresub">
                        <button type="button" id="btnsub" class="btn btn-sm btn-primary addSub"><span class="fa fa-plus"></span></button>
                        <button class="btn btn-sm btn-danger"><span class="fa fa-times"></span></button>
                    </div> -->
                </td>
            </tr>
            </table>
            </div>
            </div>
            <input type='hidden' name='res_participant_id' value='<?php echo $id; ?>'>
            <div class="modal-footer bg-whitesmoke br">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.close();">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    // var  z = 1;
    //     $("body").on("click", ".addSub", function() {
    //         z++;
    //         var $append = $(this).parents('.append');
    //         var nextHtml = $append.clone().find("input").val("").end();
    //         nextHtml.attr('id', 'append' + z);
    //         var hasRmBtn = $('.remSub', nextHtml).length > 0;
    //         if (!hasRmBtn) {
    //             var rm = "<button class='btn-danger btn-sm btn-fill remSub' style='color:white'><span class='fa fa-times'></span></button>"
    //             $('.addmoresub', nextHtml).append(rm);
    //         }
    //         $append.after(nextHtml); 

    //     });

    //     $("body").on("click", ".remSub", function() {
    //         $(this).parents('.append').remove();
    //     });

        $( document ).ready(function() {
            $('.select2').select2({
                tokenSeparators: [','],
                tags: true,
                multiple: true,
                maximumSelectionLength: 0,
            });
        });
</script>