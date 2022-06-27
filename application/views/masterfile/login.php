<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>FEBA SYSTEM</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/bootstrap-social/bootstrap-social.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
        <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
    </head>
    <body class="body">
                <?php
            $error_msg= $this->session->flashdata('error_msg');  
        ?>
        <?php 
            if($error_msg){
        ?>
            <div class="alert alert-danger alert-shake">
                <center><?php echo $error_msg; ?></center>                    
            </div>
        <?php } ?>
        <div class="loader"></div>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-4 offset-lg-8">
                             <div class="card card-primary m-t-100">
                                <div class="card-header">
                                    <h4>Login</h4>
                                </div>
                                <div class="card-body">
                                     <form method = "POST" action="<?php echo base_url(); ?>masterfile/login_process">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                                            <div class="invalid-feedback">
                                            Please fill in your email
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="d-block">
                                                <label for="password" class="control-label">Password</label>
                                                <div class="float-right">
                                                    <a href="auth-forgot-password.html" class="text-small">
                                                        Forgot Password?
                                                    </a>
                                                </div>
                                            </div>
                                            <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                            <div class="invalid-feedback">
                                              please fill in your password
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                              Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>
        </div>
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    </body>
</html>