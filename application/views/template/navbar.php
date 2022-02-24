<div id="app">
    <div class="main-wrapper main-wrapper-1">
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
                    <form class="form-inline mr-auto">
                        <div class="search-element">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                            <button class="btn" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                  </li>
                </ul>
            </div>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown dropdown-list-toggle">
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
                    <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                            Notifications
                            <div class="float-right">
                                <a href="#">Mark All As Read</a>
                            </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-icons">
                            <a href="#" class="dropdown-item dropdown-item-unread"> 
                                <span class="dropdown-item-icon bg-primary text-white"> 
                                    <i class="fas fa-code"></i>
                                </span> 
                                <span class="dropdown-item-desc"> Template update is available now! 
                                    <span class="time">2 Min Ago</span>
                                </span>
                            </a> 
                            <a href="#" class="dropdown-item"> 
                                <span class="dropdown-item-icon bg-info text-white"> 
                                    <i class="far fa-user"></i>
                                </span>
                                <span class="dropdown-item-desc"> <b>You</b> and <b>Dedik Sugiharto</b> are now friends 
                                    <span class="time">10 Hours Ago</span>
                                </span>
                            </a> 
                            <a href="#" class="dropdown-item"> 
                                <span class="dropdown-item-icon bg-success text-white"> 
                                    <i  class="fas fa-check"></i>
                                </span> 
                                <span class="dropdown-item-desc"> <b>Kusnaedi</b> has moved task <b>Fix bug header</b> to <b>Done</b>
                                    <span class="time">12 Hours Ago</span>
                                </span>
                            </a>
                            <a href="#" class="dropdown-item"> 
                                <span class="dropdown-item-icon bg-danger text-white"> 
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span> 
                                <span class="dropdown-item-desc"> Low disk space. Let's clean it! 
                                    <span class="time">17 Hours Ago</span>
                                </span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <span class="dropdown-item-icon bg-info text-white"> 
                                    <i class="fas fa-bell"></i>
                                </span> 
                                <span class="dropdown-item-desc"> Welcome to Otika template! 
                                    <span class="time">Yesterday</span>
                                </span>
                            </a>
                        </div>
                        <div class="dropdown-footer text-center">
                            <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown"class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                        <img alt="image" src="<?php echo base_url(); ?>assets/img/user.png" class="user-img-radious-style"> 
                        <span class="d-sm-none d-lg-inline-block"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pullDown">
                        <div class="dropdown-title">Hello Sarah Smith</div>
                        <a href="profile.html" class="dropdown-item has-icon"> 
                            <i class="far fa-user"></i> Profile
                        </a>
                        <a href="timeline.html" class="dropdown-item has-icon"> 
                            <i class="fas fa-bolt"></i> Activities
                        </a> 
                        <a href="#" class="dropdown-item has-icon">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="auth-login.html" class="dropdown-item has-icon text-danger"> 
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="index.html"> 
                        <img alt="image" src="<?php echo base_url(); ?>assets/img/logo.png" class="header-logo" /> 
                        <span class="logo-name">Otika</span>
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
                            <li><a class="nav-link" href="widget-data.html">Customer</a></li>
                            <li><a class="nav-link" href="widget-data.html">Suppliers</a></li>
                            <li><a class="nav-link" href="widget-chart.html">Users</a></li>
                        </ul>
                    </li>
                    <li class="menu-header">Sales</li>
                    <li class="dropdown">
                        <a href="<?php echo base_url(); ?>masterfile/dashboard" class="nav-link">
                            <i data-feather="upload"></i>
                            <span>Upload WESM Transaction</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo base_url(); ?>masterfile/dashboard" class="nav-link">
                            <i data-feather="layers"></i>
                            <span>Collection</span>
                        </a>
                    </li>
                    <li class="menu-header">Purchases</li>
                    <li class="dropdown">
                        <a href="<?php echo base_url(); ?>masterfile/dashboard" class="nav-link">
                            <i data-feather="upload"></i>
                            <span>Upload WESM Transaction</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo base_url(); ?>masterfile/dashboard" class="nav-link">
                            <i data-feather="dollar-sign"></i>
                            <span>Payment</span>
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
                            <li><a class="nav-link" href="basic-form.html">Sales Summary</a></li>
                            <li><a class="nav-link" href="forms-advanced-form.html">Purchases Summary</a></li>
                            <li><a class="nav-link" href="forms-editor.html">Expanded Withholding Tax Summary</a></li>
                            <li><a class="nav-link" href="forms-validation.html">Creditable Withholding Tax Summary</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Ledger</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="basic-form.html">Sales Ledger</a></li>
                            <li><a class="nav-link" href="forms-advanced-form.html">Advanced Form</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Subsidiary Ledger</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="basic-form.html">Per Customer</a></li>
                            <li><a class="nav-link" href="forms-advanced-form.html">Per Supplier</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
        </div>