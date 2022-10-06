<?php 
$conn = new mysqli("localhost","root","","db_accounting_new");

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;


  //$new_registration = $this->super_model->select_row_where('users', 'emailSent', '0');

$get = mysqli_query($conn, "SELECT * FROM purchase_transaction_details where original_copy = '0' OR scanned copy = '0'");
        while($fetch = mysqli_fetch_assoc($get)){
       
                //$this->load->library('phpmailer_lib');
                
             
                //$mail = $this->phpmailer_lib->load();
                $mail = new PHPMailer(true);
               //echo "1";
                
                $mail->isSMTP();
                $mail->Host     = 'sg2plcpnl0199.prod.sin2.secureserver.net';
                $mail->SMTPAuth = true;
                $mail->Username = "admin@schulify.com";
                $mail->Password = "Schul!fy00";
                $mail->SMTPSecure = 'tls';
                $mail->Port     = 587;
                 //$mail->SMTPDebug=1;
                $mail->setFrom('admin@schulify.com', 'Schulify');
                $mail->addReplyTo('admin@schulify.com', 'Schulify');

               
                $mail->addAddress('jonahbenares@gmail.com');

             
                $mail->Subject = 'Schulify | Verify you email address';
                
                
                $mail->isHTML(true);
                
              
                $mailContent = "Thank you for your registration in Schulify. Enter the verification code to continue <br><br><h4 style='font-weight:bold;'></h4>";
                $mail->Body = $mailContent;
               /*
                if($mail->send()){
                    
                    
                mysqli_query($conn, "UPDATE users SET emailSent='1' WHERE user_id = '$fetch[user_id]'") or die(mysqli_error($conn));
               
                }*/
            
        }
        
?>