<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-6">
                                    <h4>User List</h4>
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
                                            <td align="center">
                                                <div class="btn-group mb-0 dropleft">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                        <span class="fas fa-bars">
                                                    </button>
                                                    <div class="dropdown-menu dropleft p-0" style="width:0px!important;text-align:center;box-shadow: 0 0 0 rgba(0,0,0,0)!important;background: #fff0;">
                                                        <span style="background:#fff;right: 0;position: absolute;">
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="View" data-original-title="View" href=""><span class="fas fa-eye m-0"></span></a>
                                                            <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Update" data-original-title="Update" href=""><span class="far fa-edit m-0"></span></a>
                                                           <!--  <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete" data-original-title="Delete" href="" onclick="confirmationDelete(this);return false;"><span class="fas fa-trash m-0"></span></a> -->
                                                        </span>
                                                    </div>
                                                </div> 
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>user</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>