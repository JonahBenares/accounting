                    <!-- <div class="settingSidebar">
                        <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i></a>
                        <div class="settingSidebar-body ps-container ps-theme-default">
                            <div class=" fade show active">
                              <div class="setting-panel-header">Setting Panel
                              </div>
                              <div class="p-15 border-bottom">
                                <h6 class="font-medium m-b-10">Select Layout</h6>
                                <div class="selectgroup layout-color w-50">
                                  <label class="selectgroup-item">
                                    <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                                    <span class="selectgroup-button">Light</span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                                    <span class="selectgroup-button">Dark</span>
                                  </label>
                                </div>
                              </div>
                              <div class="p-15 border-bottom">
                                <h6 class="font-medium m-b-10">Sidebar Color</h6>
                                <div class="selectgroup selectgroup-pills sidebar-color">
                                  <label class="selectgroup-item">
                                    <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                      data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                                  </label>
                                  <label class="selectgroup-item">
                                    <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                      data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                                  </label>
                                </div>
                              </div>
                              <div class="p-15 border-bottom">
                                <h6 class="font-medium m-b-10">Color Theme</h6>
                                <div class="theme-setting-options">
                                  <ul class="choose-theme list-unstyled mb-0">
                                    <li title="white" class="active">
                                      <div class="white"></div>
                                    </li>
                                    <li title="cyan">
                                      <div class="cyan"></div>
                                    </li>
                                    <li title="black">
                                      <div class="black"></div>
                                    </li>
                                    <li title="purple">
                                      <div class="purple"></div>
                                    </li>
                                    <li title="orange">
                                      <div class="orange"></div>
                                    </li>
                                    <li title="green">
                                      <div class="green"></div>
                                    </li>
                                    <li title="red">
                                      <div class="red"></div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                              <div class="p-15 border-bottom">
                                <div class="theme-setting-options">
                                  <label class="m-b-0">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                      id="mini_sidebar_setting">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="control-label p-l-10">Mini Sidebar</span>
                                  </label>
                                </div>
                              </div>
                              <div class="p-15 border-bottom">
                                <div class="theme-setting-options">
                                  <label class="m-b-0">
                                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                      id="sticky_header_setting">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="control-label p-l-10">Sticky Header</span>
                                  </label>
                                </div>
                              </div>
                              <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                                  <i class="fas fa-undo"></i> Restore Default
                                </a>
                              </div>
                            </div>
                        </div>
                    </div> -->
                <!-- </div> -->
            </div>
        </div>
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/datatables/datatables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/page/datatables-col.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/page/datatables.js"></script>
   <!--      <script src="<?php echo base_url(); ?>assets/bundles/apexcharts/apexcharts.min.js"></script> -->
        <script src="<?php echo base_url(); ?>assets/js/page/index.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
        <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>assets/js/select2.min.js"></script>
        <script>
          $('.select2').select2();
          $(document).on("click", "#editEmp", function () {
            var user_id = $(this).attr("data-id");
            var username = $(this).attr("data-username");
            var fullname = $(this).attr("data-fullname");
            var department = $(this).attr("data-dept");
            var position = $(this).attr("data-position");
            var e_signature = $(this).attr("data-signature");
            $("#user_id").val(user_id);
            $("#username").val(username);
            $("#fullname").val(fullname);
            $("#department").val(department);
            $("#position").val(position);
            $("#e_signature").val(e_signature);
          });
        </script>
    </body>
</html>