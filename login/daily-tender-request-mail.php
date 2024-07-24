<?php
require_once "../vendor/autoload.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("db/config.php");

$query = "SELECT m.name, m.firm_name, m.mobile, m.email_id, department.department_name, ur.tenderID, ur.status,
ur.due_date,ur.created_at, sm.name, ur.allotted_at FROM user_tender_requests ur 
inner join members m on ur.member_id= m.member_id left join members sm on ur.selected_user_id= sm.member_id
inner join department on ur.department_id = department.department_id
where (ur.status='Requested' or ur.status='Allotted' )";

$requestedTenders = $allottedTenders = [];

$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_row($result)) {
    if ($row[6] == 'Requested') {
        $requestedTenders[] = $row;
    }
    if ($row[6] == 'Allotted') {
        $allottedTenders[] = $row;
    }
}

$email = "vibrantick@gmail.com";

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

$mail->addAddress($email, "Recepient Name");

$mail->isHTML(true);


$mail->Subject = "List of All Tender Requests";

$body =  "<p> Dear user, <br/>" .
    "These are the list of Tender requests and Allotted Tender requests<br/><br/>";
if (count($requestedTenders) > 0) {
    $body .= "<strong>Requested Tenders</strong> <br/>
        <table cellspacing='2'  cellpadding='3' bgcolor='#000000'>
	    <tr bgcolor='#ffffff'>
		<th>User Name</th>
		<th>Firm Name</th>
        <th>Mobile</th>
		<th>Email</th>
        <th>Department</th>
		<th>Tender ID</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>>Requested Date</th>
	</tr>";
    foreach ($requestedTenders as $item) {

        $body .= "<tr bgcolor='#ffffff'>
		<td>" . $item[0] . "</td>
		<td>" . $item[1] . "</td>
        <td>" . $item[2] . "</td>
		<td>" . $item[3] . "</td>
        <td>" . $item[4] . "</td>
		<td>" . $item[5] . "</td>
        <td>" . $item[6] . "</td>
		<td>" . date_format(date_create($item[7]), "d-m-Y") . "</td>
        <td>" . date_format(date_create($item[8]), "d-m-Y")  . "</td>
	</tr>";
    }
}
$body .= "</table><br/><br/>";

if (count($allottedTenders) > 0) {
    $body .= "<div style='overflow-x: scroll;'>
    <strong>Allotted Tenders</strong> <br/>
        <table style='width: 100%;' cellspacing='2' cellpadding='3' bgcolor='#000000'>
	    <tr bgcolor='#ffffff'>
		<th>User Name</th>
		<th>Firm Name</th>
        <th>Mobile</th>
		<th>Email</th>
        <th>Department</th>
		<th>Tender ID</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Requested Date</th>
        <th>Allotted User</th>
        <th>Allotted Date</th>
	</tr>";
    foreach ($allottedTenders as $item) {

        $body .= "<tr bgcolor='#ffffff'>
		<td>" . $item[0] . "</td>
		<td>" . $item[1] . "</td>
        <td>" . $item[2] . "</td>
		<td>" . $item[3] . "</td>
        <td>" . $item[4] . "</td>
		<td>" . $item[5] . "</td>
        <td>" . $item[6] . "</td>
		<td>" . date_format(date_create($item[7]), "d-m-Y") . "</td>
        <td>" . date_format(date_create($item[8]), "d-m-Y")  . "</td>
		<td>" . $item[9] . "</td>
        <td>" . date_format(date_create($item[10]), "d-m-Y")  . "</td>
	</tr>";
    }
}
$body .= "</table></div><br/><br/>
    Mobile: +91-9870443528 | Email: info@quotender.com ";

$mail->Body = $body;

if (!$mail->send()) {

    echo "Mailer Error: " . $mail->ErrorInfo;
}
