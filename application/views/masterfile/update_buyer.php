        <div tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header primary-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Update Buyer</h5>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_buyer">
                            <?php foreach($update_buyer AS $b){ ?>
                            <div class="form-group">
                                <label >Buyer</label>
                                <input type="text" class="form-control" name="buyer_name" value = "<?php echo $b->buyer_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <input type="Text" class="form-control" name="address"value = "<?php echo $b->address; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person" value = "<?php echo $b->contact_person; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Contact Number</label>
                                <input type="text" class="form-control" name="contact_no" value = "<?php echo $b->contact_no; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Tin Number</label>
                                <input type="text" class="form-control" name="tin" value = "<?php echo $b->tin; ?>">
                            </div>
                     <input type = "hidden" name = "buyer_id" value="<?php echo $id; ?>">
                        <center><button type="submit" class="btn btn-primary">Update</button></center>
                    <?php } ?>
                    </form>
                    </div>
                </div>
            </div>
        </div>