<div class="navbar-bg"></div>
<script>
    function val_cpass() {
        var password = $("#newpass").val();
        var confirm_password = $("#renewpass").val();
        if(password != confirm_password) {
            $("#cpass_msg").show();
            $("#cpass_msg").html("Confirm password not match!");
            $("#submit_pass").hide();
        }
        else{
            $("#cpass_msg").hide();
            $("#submit_pass").show();
        }
    }
</script>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                collapse-btn"> 
                    <i data-feather="align-justify"></i>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle">
            <a data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg" style="color: #000;text-transform: capitalize;">
                <?php echo $_SESSION['fullname'];?>
            </a>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                <img alt="image" src="<?php echo base_url(); ?>assets/img/user.png" class="user-img-radious-style"> 
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title"><!-- Hello <?php echo $_SESSION['fullname'];?> --></div>
                <a data-toggle="modal" data-target="#changePass" class="dropdown-item has-icon text-danger"> 
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="<?php echo base_url(); ?>index.php/masterfile/user_logout" class="dropdown-item has-icon text-danger"> 
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?php echo base_url(); ?>masterfile/change_password">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Old Password</label>
                        <input type="password" name = "oldpass" id="oldpass" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name = "newpass" id = "newpass" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" onchange="val_cpass()" name = "renewpass" id="renewpass" class="form-control" required>
                    </div>
                    <div class="alert alert-danger alert-shake" id="cpass_msg" style = "display:none;">
                        <center>Confirm Password not Match!</center>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <input type='hidden' name='userid' value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="submit" class="btn btn-primary" id="submit_pass" value="Save">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand" style="position: sticky;top:0;z-index: 999;background: #fff;">
            <a href="<?php echo base_url(); ?>masterfile/dashboard"> 
                <img alt="image" src="<?php echo base_url(); ?>assets/img/logo.png" class="header-logo" /> 
                <span class="logo-name">FEBA SYSTEM</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
                <a href="<?php echo base_url(); ?>masterfile/dashboard" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>Masterfile</span>
                </a>
                <ul class="dropdown-menu">
                    <?php if($_SESSION['department']=='Billing' || $_SESSION['department']=='billing' || $_SESSION['user_id']==1){ ?>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/customer_list">Customer</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/reserve_customer_list">Reserve Customer</a></li>
                    <?php } ?>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/supplier_list">Supplier</a></li> -->
                    <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/user_list">Users</a></li>
                </ul>
            </li>
            <li class="menu-header">Sales</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span class="line-height">Upload WESM <br>Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_sales">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_sales_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span class="line-height">Upload Reserve <br>Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_reserve_sales">Main</a></li>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_sales_adjustment">Adjustment</a></li> -->
                </ul>
            </li>
            <!-- <li class="dropdown">
                <a href="<?php echo base_url(); ?>sales/collection_list" class="nav-link">
                    <i data-feather="layers"></i>
                    <span>Collected</span>
                </a>
            </li> -->
             <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>Collected</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_collection">Upload Collection</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/collection_list">Collection List</a></li>
                </ul>
            </li>
             <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span class="line-height">BIR Monitoring Bulk Update</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/bulk_update_main">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/bulk_update_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span class="line-height">Reserve BIR Monitoring Bulk Update</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/bulk_update_reserve_main">Main</a></li>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>sales/bulk_update_adjustment">Adjustment</a></li> -->
                </ul>
            </li>
             <li>
                <a href="<?php echo base_url(); ?>sales/bulk_invoicing" ><i data-feather="briefcase"></i><span class="line-height">Bulk Upload - Invoicing of Sales Adjustments</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>WESM Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/sales_wesm">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/sales_wesm_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>Reserve Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/reserve_sales_wesm">Main</a></li>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>sales/sales_wesm_adjustment">Adjustment</a></li> -->
                </ul>
            </li>
            <li class="menu-header">Purchases</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span class="line-height">Upload WESM <br>Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>purchases/upload_purchases">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>purchases/upload_purchases_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>purchases/payment_list" class="nav-link">
                    <i data-feather="dollar-sign"></i>
                    <span>Payment</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>purchases/paid_list" class="nav-link">
                    <i data-feather="dollar-sign"></i>
                    <span>Paid</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>purchases/or_bulk" class="nav-link">
                    <i data-feather="edit"></i>
                    <span>Bulk OR Update</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>WESM Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>purchases/purchases_wesm">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>purchases/purchases_wesm_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="menu-header">Report</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>BIR</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_summary">Sales Summary</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_summary">Purchases Summary</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/ewt_summary">Expanded Withholding Tax Summary</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/cwht_summary">Creditable Withholding Tax Summary</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>reports/collection_report" ><i data-feather="layout"></i><span>Collection Report</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Ledger</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_ledger">Sales Ledger</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_ledger">Purchases Ledger</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>reports/or_summary" ><i data-feather="layout"></i><span>OR Summary</span></a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>reports/payment_report" ><i data-feather="layout"></i><span>Payment Report</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Subsidiary Ledger</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/cs_ledger">Customer Subsidiary Ledger</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/cs_ledger_salesadj">Customer Subsidiary Ledger(Sales Adjustment)</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/cs_ledger_purchaseadj">Customer Subsidiary Ledger(Purchase Adjustment)</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/ss_ledger">Supplier Subsidiary Ledger</a></li>
                </ul>
            </li>
             <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of Adjustment Billing Statement</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/adjustment_sales">Sales</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/adjustment_purchases">Purchases</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of All Transaction</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_all">Sales</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_all">Purchases </a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of All Adjustment Transaction</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_all_adjustment">Sales</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_all_adjustment">Purchases </a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of All Unpaid Invoices</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/unpaid_invoices_sales">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/unpaid_invoices_salesadj">Adjustment </a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of Sales Total EWT Variance</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_main_ewt_variance">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_adj_ewt_variance">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of Reserve Sales Total EWT Variance</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/res_sales_main_ewt_variance">Main</a></li>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>reports/sales_adj_ewt_variance">Adjustment</a></li> -->
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span class="line-height">Summary of Purchases Total Amount Variance</span></a> 
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_main_total_variance">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/purchases_adj_total_variance">Adjustment</a></li>
                </ul>
            </li>
            <br><br>
            <br>
        </ul>
    </aside>
</div>