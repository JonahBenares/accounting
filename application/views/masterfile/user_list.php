<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-6">
                                    <h4>Users List</h4>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#basicModal">
                                        <span class="fas fa-plus"></span> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Positon</th>
                                            <th>Username</th>
                                            <th>E-Signature</th>
                                            <th width="1%" align="center">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(!empty($users)){
                                        foreach($users AS $u){ ?>
                                        <tr>
                                            <td><?php echo $u['fullname'];?></td>
                                            <td><?php echo $u['department'];?></td>
                                            <td><?php echo $u['position'];?></td>
                                            <td><?php echo $u['username'];?></td>
                                            <td>
                                                <?php if(!empty($u['user_signature'])) { ?>
                                                <img class="thumbnail" src="<?php echo "../uploads/".$u['user_signature'];?>">
                                                <?php } ?>
                                            </td>
                                            <td align="center">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateEmp" data-placement="bottom" title="Update" data-original-title="Update" data-id="<?php echo $u['user_id'];?>" data-username="<?php echo $u['username'];?>" data-fullname="<?php echo $u['fullname'];?>" data-position="<?php echo $u['position'];?>" data-dept="<?php echo $u['department'];?>" data-signature="<?php echo $u['user_signature'];?>" id="editEmp"><span class="far fa-edit m-0"></span></button>
                                                <!--  <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete" data-original-title="Delete" href="" onclick="confirmationDelete(this);return false;"><span class="fas fa-trash m-0"></span></a> -->
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                     <tr>
                                    <td align="center" colspan='9'><center>No Data Available.</center></td>
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

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?php echo base_url(); ?>masterfile/insert_employee" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>E-Signature</label>
                        <input type="file" name="signature" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Fullname</label>
                        <input type="text" name="fullname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="submit" class="btn btn-primary" value="Save">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateEmp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?php echo base_url(); ?>masterfile/edit_user" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>E-Signature</label>
                        <input type="file" name="signature" id="signature" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Fullname</label>
                        <input type="text" name="fullname" id="fullname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" id="department" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="position" id="position" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type="hidden" name="e_signature" id="e_signature" class="form-control">
                    <input type="hidden" name="user_id" id="user_id" class="form-control">
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>