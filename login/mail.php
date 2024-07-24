<?php
session_start();
include("db/config.php");
require_once "../vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



$en = $_POST["tender_sent_ids"];


$stat = 1;
$re = base64_encode($stat);

$res = mysqli_query($db,"SELECT auto_quotation FROM user_tender_requests WHERE id = '$en'") ;
$row12 = mysqli_fetch_assoc($res);
print_r($row12);

$upload_directory = "tender/";

if (isset($en)){

    $mail = new PHPMailer(true);
    
    //Enable SMTP debugging.

    $mail->SMTPDebug = 0;

    //Set PHPMailer to use SMTP.

    $mail->isSMTP();

    //Set SMTP host name                      

    $mail->Host = "smtp.hostinger.com";

    //Set this to true if SMTP host requires authentication to send email

    $mail->SMTPAuth = true;

    //Provide username and password

    $mail->Username = "info@quotetender.in";

    $mail->Password = "Zxcv@123";

    //If SMTP requires TLS encryption then set it

    $mail->SMTPSecure = "ssl";

    //Set TCP port to connect to

    $mail->Port = 465;

    $mail->From = "info@quotetender.in";


    $mail->FromName = "Quote Tender  ";
    $adminEmail = "info@quotetender.in";

    $mail->addAddress($adminEmail);
    $mail->IsHTML(true);

    $membersQuery = "SELECT m.email_id,  m.name, ur.file_name, ur.file_name2, ur.tenderID, ur.id FROM user_tender_requests ur 
    inner join members m on ur.member_id= m.member_id  WHERE ur.id='"  . $en . "'";
    $membersResult = mysqli_query($db, $membersQuery);
    $memberData = mysqli_fetch_row($membersResult);

    $mail->addAddress($memberData[0], "Recepient Name");
    
    $mail->Subject = "Tender Request Approved";
    
    $mail->addAttachment($upload_directory.$memberData[2]);
    if(!empty($memberData[3])){
    $mail->addAttachment($upload_directory.$memberData[3]);
    }
    $mail->Body =  "<p> Dear user, <br/>" .
    "The <b>Tender ID: </b> " .  $memberData[4] . "</b>  has been approved to you. Quotation file is attached below. For the further process, feel free to contact us.<br/><br/>
    <strong>Thanks, <br /> Admin Quote Tender</strong> <br/>
    Mobile: +91-9417601244 | Email: info@quotender.com ";
    
    if (!$mail->send()) {

        echo "Mailer Error: " . $mail->ErrorInfo;
    }

    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.location.href='tender-request.php?status=$re';
    </SCRIPT>");
}

if($_GET['id']){
    $id = $_GET['id'];
    $mail = new PHPMailer(true);
    
    //Enable SMTP debugging.

    $mail->SMTPDebug = 0;

    //Set PHPMailer to use SMTP.

    $mail->isSMTP();

    //Set SMTP host name                      

    $mail->Host = "smtp.hostinger.com";

    //Set this to true if SMTP host requires authentication to send email

    $mail->SMTPAuth = true;

    //Provide username and password

    $mail->Username = "info@quotetender.in";

    $mail->Password = "Zxcv@123";

    //If SMTP requires TLS encryption then set it

    $mail->SMTPSecure = "ssl";

    //Set TCP port to connect to

    $mail->Port = 465;

    $mail->From = "info@quotetender.in";


    $mail->FromName = "Quote Tender  ";
    $adminEmail = "info@quotetender.in";

    $mail->addAddress($adminEmail);
    $mail->IsHTML(true);

    $membersQuery = "SELECT m.email_id,  m.name, ur.file_name, ur.file_name2, ur.tenderID, ur.id FROM user_tender_requests ur 
    inner join members m on ur.member_id= m.member_id  WHERE ur.id='"  . $id . "'";
    $membersResult = mysqli_query($db, $membersQuery);
    $memberData = mysqli_fetch_row($membersResult);

    $mail->addAddress($memberData[0], "Recepient Name");
    
    $mail->Subject = "Tender Request Approved";
    
    $mail->addAttachment($upload_directory.$memberData[2]);
    if(!empty($memberData[3])){
    $mail->addAttachment($upload_directory.$memberData[3]);
    }
    $mail->Body =  "<p> Dear user, <br/>" .
    "The <b>Tender ID: </b> " .  $memberData[4] . "</b>  has been approved to you. Quotation file is attached below. For the further process, feel free to contact us.<br/><br/>
    <strong>Thanks, <br /> Admin Quote Tender</strong> <br/>
    Mobile: +91-9417601244 | Email: info@quotender.com ";
    
    if (!$mail->send()) {

        echo "Mailer Error: " . $mail->ErrorInfo;
    }

    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.location.href='tender-request.php?status=$re';
    </SCRIPT>");

}
?>