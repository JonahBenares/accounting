<script src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Employees
            </h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Employees List &nbsp;
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ol>         
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="m-0">Employee List</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="pull-right">
                                    <button type="button" class="btn btn-gradient-primary btn-sm btn-rounded" data-toggle="modal" data-target="#addEmployee">
                                        <b><span class="mdi mdi-plus"></span> Add</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="myTable">
                            <thead>
                                <tr>
                                    <th width="20%"> Employee Name </th>
                                    <th> Position</th>
                                    <th> Department </th>
                                    <th> Contact No. </th>
                                    <th> Email Address</th>
                                    <th width="10%">
                                        <center>
                                            <span class="mdi mdi-menu"></span>
                                        </center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(!empty($employee)){
                                        foreach($employee AS $emp){ ?>
                                        <td><?php echo $emp['employee']?></td>
                                        <td><?php echo $emp['position']?></td>
                                        <td><?php echo $emp['department']?></td>
                                        <td><?php echo $emp['contact_no']?></td>
                                        <td><?php echo $emp['email']?></td>
                                        <td align="center">
                                            <a href="<?php echo base_url(); ?>masterfile/update_employee/<?php echo $emp['employee_id'] ?>" class="btn btn-gradient-info btn-rounded btn-xs" data-toggle="tooltip" data-placement="top" title="Update">
                                                <span class="mdi mdi-pencil"></span>
                                            </a>
                                            <a href="<?php echo base_url(); ?>index.php/masterfile/delete_employee/<?php echo  $emp['employee_id']?>" data-toggle="tooltip" data-placement="top" title="Delete" onclick="confirmationDelete(this);return false;" class = "btn btn-gradient-danger btn-rounded btn-xs">
                                                <span class="mdi mdi-delete"></span>
                                            </a>
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

        <!-- //Add Employee Name// -->
        <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header primary-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/add_employee">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <input type="text" class="form-control" name="employee_name" placeholder="Employee Name">
                            </div>
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" class="form-control" name="position" placeholder="Position">
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select name = "department_id" class = "form-control">
                                <option value = "">--Select Department--</option>
                                <?php 
                                    foreach($department AS $dep){
                                ?>
                                <option value = "<?php echo $dep->department_id;?>"><?php echo $dep->department_name;?></option>
                                <?php } ?>  
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" name="contact_no" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" name="email" placeholder="Email Address">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-md">Add</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- //Filter Employee Name// -->
        <div class="modal fade" id="filterEmployee" tabindex="-1" role="dialog" aria-labelledby="filterEmployee Name" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header success-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Filter Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/search_employee">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <input type="text" class="form-control" name="employee_name" placeholder="Employee Name">
                            </div>
                            <div class="form-group">
                                <label>Position</label>
                                <input type="text" class="form-control" name="position" placeholder="Position">
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" class="form-control" name="department" placeholder="Department">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" name="contact_no" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" name="email" placeholder="Email Address">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                     </form>
                </div>
            </div>
        </div>

        <!-- //Delete Employee Name// -->
        <div class="modal fade" id="deleteEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content " >
                    <div class="modal-header danger-modalhead">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <br>
                        <br>
                        <center>Do you want to delete _________?</center>
                        <br>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger">Yes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
        




