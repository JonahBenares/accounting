<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>FEBA SYSTEM</title>
    <!-- General CSS Files -->    

    <link rel="stylesheet" href="assets/css/app.min.css" >
    <link rel="stylesheet" href="assets/bundles/pretty-checkbox/pretty-checkbox.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/logo.png' />
</head>
<?php 
     // Database connection
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "db_accounting";
     $conn = new mysqli($servername, $username, $password, $dbname);
     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
?>
<form method="POST">
    <div style="margin-top:100px">
        <div class="row">
            <div class="col-lg-2 offset-lg-1">
                <select name="reference_number" class="form-control select2" required>
                    <option value="">--Select Reference Number--</option>
                    <?php 
                        $sql = mysqli_query($conn,"SELECT * FROM sales_transaction_head GROUP BY reference_number ORDER BY reference_number DESC");
                        while($row = mysqli_fetch_array($sql)){
                    ?>
                    <option value="<?php echo $row['sales_id']."|".$row['reference_number']?>"><?php echo $row['reference_number']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-2">
                <select id="day" name="day" class="form-control select2" required>
                    <option value="">--Select Day--</option>
                    <?php for($x=1;$x<=31;$x++) { ?>
                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-2">
                <select id="month" name="month" class="form-control select2" required>
                    <option value="">--Select Month--</option>
                    <?php 
                        for($y=1;$y<=12;$y++) { 
                            $time = strtotime(sprintf('%d months', $y));   
                            $label = date('F', $time);   
                            $value = date('n', $time);
                    ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-2">
                <select id="year" name="year" class="form-control select2" required>
                    <option value="">--Select Year--</option>
                    <?php for($z=2020;$z< date('Y-m-d');$z++) { ?>
                    <option value="<?php echo $z; ?>"><?php echo $z; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-lg-2">
                <input type="submit" class="form-control btn btn-sm btn-primary" name="submit" value="Filter">
            </div>
        </div>
    </div>
</form>
<form method="POST">
<div style="margin: 10px 150px">
    <?php 
        if(isset($_POST['reference_number'])){
            $exp_ref=$_POST['reference_number'];
            $exp=explode('|',$exp_ref);
            $date_merge=$_POST['year']."-".$_POST['month']."-".$_POST['day'];
            $date= date('Y-m-d',strtotime($date_merge));
        
    ?>
        <span style="font-size:15px"><b>Reference Number:</b> <?php echo $exp[1]; ?> </span>
        <span style="font-size:15px"><b>Date:</b> <?php echo date('d F Y',strtotime($date)); ?> </span>
        <br>
        <hr>
    <?php } ?>
    <?php
        if(isset($_POST['submit'])){
            // Query to get filenames from the database
            $sql = "SELECT filename,reference_number,sales_detail_id FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE std.sales_id='$exp[0]' AND serial_no != '' AND saved = '1' GROUP BY serial_no";
            $result = $conn->query($sql);
            // $filesInFolder = scandir($folderPath);
            // print_r($filesInFolder);
            $checker_exist=0;
            if ($result->num_rows > 0) {
                $x=0;
                while ($row = $result->fetch_assoc()) {
                    // Folder path
                    $folderPath = "//DESKTOP-98VALUG/cnpr billing 2023/CENPRI BILLING/BS WESM/WESM TAXATION/Sales Invoice to IEMOP/".$_POST['day']." ".date('F',strtotime($date))." ".$_POST['year']."/".$row['reference_number']."/";
                    $folder_checker = is_dir($folderPath);         // string(5) "/home"
                    $filename = trim($row['filename']);
                    $filePath = $folderPath . $filename;
                    // Check if the file does not exists in the folder
                    if (!empty($filename) && !file_exists($filePath) && $folder_checker) {
                        $checker_exist=1;
                        echo "<b>File does not exist:</b> " . $filename . "<br>";
                        echo "<input type='hidden' name='sales_detail_id".$x."' value='".$row['sales_detail_id']."'>";
                        echo "<input type='hidden' name='sales_id' value='".$exp[0]."'>";
                        echo "<input type='hidden' name='day' value='".$_POST['day']."'>";
                        echo "<input type='hidden' name='date' value='".$date."'>";
                        echo "<input type='hidden' name='year' value='".$_POST['year']."'>";
                        $x++;
                    }
                }
            } else {
                echo '<script> alert("No files found in the database.")</script>';
            }

            $conn->close();
        }
    ?>
    <?php 
        if(isset($_POST['submit']) && $checker_exist!=0){
    ?>
        <input type="submit" name="update" class="form-control btn btn-sm btn-primary" value="Update">
    <?php } else{ echo (isset($_POST['submit'])) ? '<script> alert("No files found in the database.")</script>' : ''; } ?>
    </div>
</form>
<?php 
     if(isset($_POST['update'])){
        $sql1 = "SELECT filename,reference_number,sales_detail_id FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE std.sales_id='$_POST[sales_id]' AND serial_no != '' AND saved = '1' GROUP BY serial_no";
        $result1 = $conn->query($sql1);
        $y=0;
        while ($rows = $result1->fetch_assoc()) {
            // Folder path
            $folderPath = "//DESKTOP-98VALUG/cnpr billing 2023/CENPRI BILLING/BS WESM/WESM TAXATION/Sales Invoice to IEMOP/".$_POST['day']." ".date('F',strtotime($_POST['date']))." ".$_POST['year']."/".$rows['reference_number']."/";
            $folder_checker = is_dir($folderPath);// string(5) "/home"
            $filename = trim($rows['filename']);
            $filePath = $folderPath . $filename;
            // Check if the file does not exists in the folder
            if (!empty($filename) && !file_exists($filePath) && $folder_checker) {
                $sales_details_id=$_POST['sales_detail_id'.$y];
                $sql_update = "UPDATE sales_transaction_details SET bulk_pdf_flag='0', filename='' WHERE sales_detail_id='$sales_details_id'";
                $result=mysqli_query($conn,$sql_update);
                $y++;
            }
        }
        if ($result) {
            echo '<script> alert("Record updated successfully"); window.location.href = window.location.href; </script>';
        } else {
            echo '<script> alert(Error updating record: "'.$conn->error.'); window.location.href = window.location.href; </script>';
        }
    }
?>
<script type="text/javascript" src="assets/js/jquery.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/custom.js"></script>
<link href="assets/css/select2.min.css" rel="stylesheet" />
<script src="assets/js/select2.min.js"></script>