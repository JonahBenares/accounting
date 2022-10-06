<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bulk OR Update - Purchases</h4>
                        </div>
                        <div class="card-body">
                            <form id='purchasehead'> 
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 offset-lg-2" >
                                        <div class="form-group">
                                            <label>Reference Number</label>
                                            <select type="text" class="form-control" name="reference_number" id="reference_number">
                                            	<option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label><br></label>
                                            <input type='button' class="btn btn-primary" id='save_head_button' type="button" onclick="proceed_btn()" value="Proceed" style="width:100%">
                                            <input type='button' class="btn btn-danger" id="cancel" onclick="cancelPurchase()" value="Cancel Transaction" style='display: none;width:100%'>
                                        </div>
                                    </div>
                                </div> 
                            </form>
                            <form method="POST" id="upload_wesm">
                                <div id="upload">
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 offset-md-3 offset-lg-3">
                                            <div class="form-group mb-0">
                                                <div class="input-group mb-0">
                                                    <input type="file" class="form-control" placeholder="" id="WESM_purchase">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" id="proceed_purchase" onclick="upload_btn()"  type="button">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </form>
                            <center><span id="alt"></span></center>
                            <div class="table-responsive" id="table-wesm" >
                                <hr>
                                <table class="table-bordered table table-hover " id="table-1" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Billing No</th>
                                            <th>OR Number</th>
                                            <th width="15%">Total Amount</th>
                                            <th width="10%">Original Copy</th>
                                            <th width="10%">Scanned Copy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<tr>
                                    		<td>sample</td>
                                    		<td></td>
                                    		<td align="right"></td>
                                    		<td align="center"><span class="fas fa-check" style="color:green"></span></td>
                                    		<td align="center"><span class="fas fa-check" style="color:green"></span></td>
                                    	</tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id='alt' style="font-weight:bold"></div>
                        <input type="button" id="submitdata" class="btn btn-success btn-md btn-block" onclick="saveAll();" value="Save">
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>