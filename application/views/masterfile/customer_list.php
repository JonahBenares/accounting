<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/masterfile.js"></script>
<style type="text/css">
    #buttons_here{
        display: none;
    }
</style>
<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">                        
                            <div class="row">
                                <div class="col-6">
                                    <h4>Customer List</h4>
                                </div>
                                <div class="col-6">
                                    <div class="form-group pull-right">
                                        <a href="<?php echo base_url(); ?>masterfile/customer_add" type="button" class="btn btn-primary">
                                            <span class="fas fa-plus"></span> Add
                                        </a>
                                        <!-- <a href="<?php echo base_url(); ?>masterfile/customer_add" type="button" class="btn btn-warning ">
                                            <span class="fas fa-plus"></span> Add
                                        </a> -->
                                        <button type="button" class="btn btn-warning " data-target="#bulk_upload" data-toggle="modal">
                                            <span class="fas fa-upload"></span> Bulk Upload
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-respsonsive">
                                <table class="table table-hover" id="save-stage"  style="width:100%;">
                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>Participant Name</th>
                                            <th>Billing ID</th>
                                            <th>Settlement ID</th>
                                            <th>Category</th>
                                            <th width="1%" align="center">
                                                <center><span class="fas fa-bars"></span></center>
                                            </th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $x = 1;
                                        if(!empty($participant)){
                                        foreach($participant AS $p){ ?>
                                        <tr>
                                            <!-- <td>
                                                <?php echo $x; ?>
                                            </td> -->
                                            <td class="td-vertical">
                                                <div id="accordion">
                                                    <div class="accordion mb-0">
                                                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-<?php echo $x; ?>">
                                                            <h4><?php echo $p['participant_name'];?></h4>
                                                        </div>
                                                        <div class="accordion-body collapse" id="panel-body-<?php echo $x; ?>" data-parent="#accordion">
                                                            <table class="" width="100%">
                                                                <tr>
                                                                    <td class="p-0">Sub Company 1</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="p-0">Sub Company 2</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="p-0">Sub Company 3</td>
                                                                </tr>
                                                            </table>
                                                            <hr class="mb-0">
                                                        </div>
                                                      </div>
                                                </div>
                                            </td>
                                            <td class="td-vertical"><?php echo $p['billing_id'];?> </td>
                                            <td class="td-vertical"><?php echo $p['settlement_id'];?></td>
                                            <td class="td-vertical"><?php echo $p['category'];?></td>
                                            <td class="td-vertical" align="center">

                                                <button onclick="myFunction()">Try it</button>

                                                <div id="myDIV">
                                                This is my DIV element.
                                                </div>

                                                
                                                <button class="btn btn-sm btn-primary" onclick="main_button()"><span class="fas fa-bars m-0"></span></button>
                                                <div class="btn-group" id="buttons_here">
                                                    <a href="" class="btn btn-sm btn-primary">1</a>
                                                    <a href="" class="btn btn-sm btn-primary">2</a>
                                                    <a href="" class="btn btn-sm btn-primary">3</a>
                                                    <a href="" class="btn btn-sm btn-primary">4</a>
                                                </div>




                                                <!-- <div class="btn-group mb-0">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                        Option
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_view/<?php echo $p['participant_id'];?>"><span class="fas fa-eye mr-2"></span>View</a>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_update/<?php echo $p['participant_id'];?>"><span class="far fa-edit mr-2"></span>Update</a>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>masterfile/customer_delete/<?php echo $p['participant_id'];?>" onclick="confirmationDelete(this);return false;"><span class="fas fa-trash mr-2"></span>Delete</a>
                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="subparticipant('<?php echo base_url(); ?>','<?php echo $p['participant_id']; ?>')"><span class="fas fa-building mr-2"></span>Add Sub Company</a>
                                                    </div>
                                                </div> -->
                                            </td>  
                                        </tr>
                                        <?php 
                                         $x++;
                                        } 

                                        }

                                        ?>
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


<div class="modal fade" id="bulk_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Bulk Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action="<?php echo base_url();?>masterfile/upload_customer" enctype="multipart/form-data" target='_blank'>
            <div class="modal-body">
                <div class="form-group">
                    <label>Add File:</label>
                   <input type="file" name="excelfile_customer" class="form-control">
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <!-- <button type="button" class="btn btn-primary">Upload</button> -->
                <input type="submit" class="btn btn-primary" value='Upload'>
            </div>
             </form>
        </div>
    </div>
</div>

