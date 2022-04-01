<script src="<?php echo base_url(); ?>assets/js/sales.js"></script>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <form>
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <h4>Collection</h4>
                                    </div>
                                    <!-- <div class="col-lg-6 col-md-6">
                                        <div class="input-group">
                                            <select class="custom-select" id="inputGroupSelect04">
                                                <option selected="">Choose Participant</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary m-0" type="button" style="border-radius: 0 .25rem .25rem 0;">Search</button>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-10 offset-lg-1">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input placeholder="Month From" class="form-control"  onfocus="(this.type='month')" type="text" id="start" name="start">
                                                </td>
                                                <td width="20%">
                                                    <input placeholder="Month To" class="form-control"  onfocus="(this.type='month')" type="text" id="start" name="start">
                                                </td>
                                                <td>
                                                    <select class="form-control">
                                                        <option>-- Select Participant --</option>
                                                    </select>
                                                </td>
                                                <td><button class="btn btn-primary" type="button" onclick="collection_filter()">Filter</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <div id="collection-list" style="display:none;">
                                    <table class="table-bordered table table-hover " id="table-1" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th align="center">
                                                    <center><span class="fas fa-bars"></span></center>
                                                </th>
                                                <th width="10%">Date</th>
                                                <th width="20%">Reference Number</th>
                                                <th width="15%">Billing period</th>
                                                <th width="15%">Vatable Sales</th>
                                                <th width="15%">Total Amount Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="center">
                                                    <div class="btn-group mb-0">
                                                        <a class="btn btn-success btn-sm" target="_blank" onClick="add_details_OR('<?php echo base_url(); ?>')" style="color:#fff">
                                                            <span class="m-0 fas fa-indent"></span>
                                                        </a>
                                                    </div>
                                                    <a id="clicksOR"></a>
                                                </td>
                                                <td>11-11-58</td>
                                                <td>REF-255566225-88858</td>
                                                <td>10-22-99</td>
                                                <td>Edinburgh</td>
                                                <td align="right">999,999.00</td>
                                            </tr>
                                            

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


                
                                       
         