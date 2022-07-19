<div class="navbar-bg"></div>
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
            <li>
            <!-- <form class="form-inline mr-auto">
                <div class="search-element">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                    <button class="btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form> -->
          </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <!-- <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="mail"></i>
                <span class="badge headerBadge1"> 6 </span> 
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Messages
                    <div class="float-right">
                        <a href="#">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-1.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-2.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-3.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-4.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-5.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                    <a href="#" class="dropdown-item"> 
                        <span class="dropdown-item-avatar text-white"> 
                            <img alt="image" src="<?php echo base_url(); ?>assets/img/users/user-6.png" class="rounded-circle">
                        </span> 
                        <span class="dropdown-item-desc"> 
                            <span class="message-user">John Deo</span>
                            <span class="time messege-text">Please check your mail !!</span>
                            <span class="time">2 Min Ago</span>
                        </span>
                    </a> 
                </div>
                <div class="dropdown-footer text-center">
                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                <i data-feather="bell" class="bell"></i>
            </a>
        </li> -->
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
                <!-- <a href="profile.html" class="dropdown-item has-icon"> 
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="timeline.html" class="dropdown-item has-icon"> 
                    <i class="fas fa-bolt"></i> Activities
                </a> 
                <a href="#" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div> -->
                <a href="<?php echo base_url(); ?>index.php/masterfile/user_logout" class="dropdown-item has-icon text-danger"> 
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
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
                    <?php } ?>
                    <!-- <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/supplier_list">Supplier</a></li> -->
                    <li><a class="nav-link" href="<?php echo base_url(); ?>masterfile/user_list">Users</a></li>
                </ul>
            </li>
            <li class="menu-header">Sales</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>Upload WESM <br>Transaction</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_sales">Main</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>sales/upload_sales_adjustment">Adjustment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>sales/collection_list" class="nav-link">
                    <i data-feather="layers"></i>
                    <span>Collected</span>
                </a>
            </li>
            <!-- <li class="dropdown">
                <a href="<?php echo base_url(); ?>sales/collected_list" class="nav-link">
                    <i data-feather="layers"></i>
                    <span>Collected</span>
                </a>
            </li> -->
            <li class="dropdown">
                <a href="<?php echo base_url(); ?>sales/sales_wesm" class="nav-link">
                    <i data-feather="list"></i>
                    <span>WESM Transaction</span>
                </a>
            </li>
            <li class="menu-header">Purchases</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="briefcase"></i>
                    <span>Upload WESM <br>Transaction</span>
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
                <a href="<?php echo base_url(); ?>purchases/purchases_wesm" class="nav-link">
                    <i data-feather="list"></i>
                    <span>WESM Transaction</span>
                </a>
            </li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="copy"></i>
                    <span>Basic Components</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="alert.html">Alert</a></li>
                    <li><a class="nav-link" href="badge.html">Badge</a></li>
                    <li><a class="nav-link" href="breadcrumb.html">Breadcrumb</a></li>
                    <li><a class="nav-link" href="buttons.html">Buttons</a></li>
                    <li><a class="nav-link" href="flags.html">Flag</a></li>
                    <li><a class="nav-link" href="typography.html">Typography</a></li>
                </ul>
            </li> -->
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
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Subsidiary Ledger</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/cs_ledger">Customer Subsidiary Ledger</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/ss_ledger">Supplier Subsidiary Ledger</a></li>
                </ul>
            </li>
             <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Summary of Adjustment Billing Statement</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/adjustment_sales">Sales</a></li>
                    <li><a class="nav-link" href="<?php echo base_url(); ?>reports/adjustment_purchases">Purchases</a></li>
                </ul>
            </li>
            <br><br>
        </ul>
    </aside>
</div>