<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == '')) {
        echo "<script>alert('You are currently not logged in.'); 
        window.location ='index.php; </script>";
        exit();
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <!-- General CSS Files -->    

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.min.css" >
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/pretty-checkbox/pretty-checkbox.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/logo.png' />
</head>
<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <!-- <div class="main-content"> -->