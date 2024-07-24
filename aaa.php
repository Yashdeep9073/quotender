<?php
// session_start();
// include("./login/db/config.php");

// $sql = "SELECT name,email_id,firm_name,mobile from members where member_id = 240 ";
// $result = mysqli_query($db , $sql);
// $row = mysqli_fetch_assoc($result);

// $name = $row['name'];
// $email = $row['email_id'];
// $firmname = $row['firm_name'];
// $phone = $row['mobile'];

// echo "<pre>";
// print_r($row['0']);
// echo $row['email_id'];

// echo $name;
// echo $email;
// echo $firmname;
// echo $phone;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <form method="POST" action="">
        <select name="autoEmail" id="auto-email" class="form-control">
            <option value="">Select</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
<?php
session_start();
include("./login/db/config.php");

if (isset($_POST['submit'])) {
    $autoEmail = $_POST['autoEmail'];
    // echo $autoEmail;

    $sql = mysqli_query($db,"UPDATE user_tender_requests SET `auto_quotation`='$autoEmail' WHERE member_id = 221");

    if($sql){
        echo "<pre>";
        print_r($_POST);
    }else{
        echo "Getout";
    }
}
?>

</html>