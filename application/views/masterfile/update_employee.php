<div class="main-panel">
    <div class="content-wrapper">    
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-info text-white mr-2">
                  <i class="mdi mdi-import"></i>
                </span> Employee
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masterfile/employee_list">Employee List</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Employee</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-info card-img-holder text-white">
                        <h4 class="m-0">Update Employee</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action = "<?php echo base_url();?>index.php/masterfile/edit_employee">
                            <?php foreach($update_employee AS $e){ ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Employee Name</label>
                                            <input type="text" class="form-control" name="employee_name" value = "<?php echo $e->employee_name; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Position</label>
                                            <input type="Text" class="form-control" name="position"value = "<?php echo $e->position; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select name = "department_id" class = "form-control">
                                            <?php 
                                                foreach($department AS $dep){
                                            ?>
                                            <option value = "<?php echo $dep->department_id;?>" <?php echo ($e->department_id==$dep->department_id) ? " selected" : '';?>><?php echo $dep->department_name;?></option>
                                            <?php } ?>  
                                            </select>           
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" class="form-control" name="contact_no" value = "<?php echo $e->contact_no; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" class="form-control" name="email" value = "<?php echo $e->email; ?>">
                                        </div>
                                        <input type = "hidden" name = "employee_id" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-info">Update</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
        





     