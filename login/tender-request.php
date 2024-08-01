<?php

session_start();


if (!isset($_SESSION["login_user"])) {
    header("location: index.php");
}

include("db/config.php");
$name = $_SESSION['login_user'];


$adminID= $_SESSION['login_user_id'];
$adminPermissionQuery = "SELECT nm.title FROM admin_permissions ap 
inner join navigation_menus nm on ap.navigation_menu_id = nm.id where ap.admin_id='" . $adminID . "'";
$adminPermissionResult = mysqli_query($db, $adminPermissionQuery);

while ($row = mysqli_fetch_row($adminPermissionResult)) {
    $userPermissions[]=$row[0];
}
$allowedAction=!in_array('All',$userPermissions) && in_array( 'Update Tenders',$userPermissions) ? 'update' :
 (!in_array('All',$userPermissions) && in_array( 'View Tenders',$userPermissions) ? 'view' : 'all');

$query = "SELECT DISTINCT
m.name, 
m.member_id, 
m.firm_name, 
m.mobile, 
m.email_id, 
department.department_name, 
ur.due_date, 
ur.file_name, 
ur.tenderID, 
ur.created_at, 
ur.id,
ur.file_name2 
FROM 
    user_tender_requests ur 
inner join 
    members m on ur.member_id= m.member_id
inner join 
    department on ur.department_id = department.department_id 
where ur.status= 'Requested' AND ur.delete_tender = '0' 
GROUP BY 
 ur.id
ORDER BY 
 NOW() >= CAST(ur.due_date AS DATE), 
 ABS(DATEDIFF(NOW(), CAST(ur.due_date AS DATE)))";

$result = mysqli_query($db, $query);


?>

<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Tender Request</title>



    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/plugins/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'navbar.php'; ?>




    <header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed header-blue">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand" style="font-size:24px;">
                ADMIN PANEL

            </a>
            <a href="#!" class="mob-toggler">
                <i class="feather icon-more-vertical"></i>
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">

                    <div class="search-bar">

                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#!" class="full-screen" onClick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a>
                </li>
            </ul>


        </div>
        </div>
        </li>

        <div class="dropdown drp-user">
            <a href="#!" class="dropdown-toggle" data-toggle="dropdown">
                <img src="assets/images/user.png" class="img-radius wid-40" alt="User-Profile-Image">
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-notification">
                <div class="pro-head">
                    <img src="assets/images/user.png" class="img-radius" alt="User-Profile-Image">
                    <span><?php echo $name ?></span>
                    <a href="logout.php" class="dud-logout" title="Logout">
                        <i class="feather icon-log-out"></i>
                    </a>
                </div>
                <ul class="pro-body">
                    <li><a href="logout.php" class="dropdown-item"><i class="feather icon-lock"></i> Log out</a></li>
                </ul>
            </div>
        </div>
        </li>
        </ul>
        </div>
    </header>


    <section class="pcoded-main-container">
        <div class="pcoded-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Tender Request
                                </h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="#!"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header table-card-header">
                        </div>
                        <div class="card-body">
                            <div class="dt-responsive table-responsive">

                                <?php

                                if (isset($_GET['status'])) {
                                    $st = $_GET['status'];
                                    $st1 = base64_decode($st);

                                    if ($st1 > 0) {
                                        echo " <div class='alert alert-success alert-dismissible fade show' role='alert' style='font-size:16px;' id='updateuser'>
                                    <strong><i class='feather icon-check'></i>Thanks!</strong> Tender has been Updated Successfully.
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                    </div> ";
                                    } else {

                                        echo " <div class='alert alert-danger alert-dismissible fade show' role='alert' style='font-size:16px;' id='updateuser'>
                                        <strong>Error!</strong> Tender has been not Updated
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                        </div> ";
                                    }
                                }

                                ?>
                                <br />

                                <?php
                                echo "<div class='col-md row'>";
                               
                                if($allowedAction=='all'){
                                    echo "<a href='#' id='recycle_records' class='btn btn-danger'> <i class='feather icon-trash'></i>  &nbsp;
                                    Move to Bin Selected Items</a>";
                                }
                                if($allowedAction=='all' || $allowedAction=='update' ){
                                    echo"<a href='#' class='update_records px-1'><button type='button' class='btn btn-warning'>
                                    <i class='feather icon-edit'></i> &nbsp;Update Selected Items</button>
                                    </a>   
                                    </div> <br />";
                                }

                                
                                echo '<table  id="basic-btn" class="table table-striped table-bordered nowrap">';

                                echo "<thead>";

                                echo "<tr>";
                                echo "<th>SNO</th>";
                                echo "<th>User</th>";
                                 echo "<th>Firm Name</th>";
                                echo "<th>Mobile</th>";
                                echo "<th>Email</th>";

                                echo "<th>Department</th>";
                                echo "<th>Tender Id</th>";
                                echo "<th>Add Date</th>";
                                echo "<th>Add Time</th>";
                                 
                                echo "<th>Due Date</th>";
                                echo "<th>File Names </th>";
                                if($allowedAction=='all' || $allowedAction=='update' ){
                                echo "<th>Edit</th>";
                                }
                                echo "</tr>";
                                echo "</thead>";
                                ?>
                                <?php
                                $count = 1;

                                echo "<tbody>";
                                while ($row = mysqli_fetch_row($result)) {

                                    echo "<tr class='record'>";
                                    echo "<td><div class='custom-control custom-checkbox'>
                                    <input type='checkbox' class='custom-control-input request_checkbox' id='customCheck" .  $row[10] . "' data-request-id='" . $row[10] . "'>
                                    <label class='custom-control-label' for='customCheck" .  $row[10] . "'>" . $count . "</label>
                                </div>
                                </td>";

                                 
                                    
                                      echo "<td>" . "<span style='color:red;'> " . $row['0'] . "</td>";
                                      
                                        echo "<td>" . $row['2'] . "</td>";
                                    
                                    echo "<td>" . $row['3'] . "</td>";
                                    echo "<td>" . $row['4'] . "</td>";

                                    echo "<td>" . $row['5'] . "</td>";
                                    echo "<td>" . $row['8'] . "</td>";
                                    

                                    // Convert and display the date in 'd-m-Y' format
                                    $originalDate = $row[9];
                                    $timestamp = strtotime($originalDate);
                                    $istDate = date('d-m-Y', $timestamp);
                                    $istTime = date('h:i A', $timestamp + 5.5 * 3600);
                                    echo "<td>" . $istDate . "</td>";
                                    echo "<td>" . $istTime . "</td>";
                                    
                                    
                                    
                                    echo "<td>" . date_format(date_create($row['6']), "d-m-Y") . "</td>";

                                    if (!empty($row['7'])) {
                                        echo "<td>" . '<a href="../login/tender/' . $row['7'] . '" target="_blank" style="padding:6px 15.2px;" />View </a>' . "</br>";
                                    } else {
                                        echo "<td>" . '<a href="../login/tender/' . $row['7'] . '" class="btn disabled" target="_blank"/>No file </a>' . "</br>";
                                    }
                                    if (!empty($row['11'])) {
                                        echo  '<a href="../login/tender/' . $row['11'] . '" target="_blank" style="padding:6px 15.2px;" />View </a>' . "</td>";
                                    } else {
                                        echo '<a href="../login/tender/' . $row['11'] . '" class="btn disabled" target="_blank"/>No file </a>' . "</td>";
                                    }
                                    $res = $row[10];
                                    $res = base64_encode($res);
                                    
                                    if($allowedAction=='all' || $allowedAction=='update' ){
                                        echo "<td>  <a href='tender-edit.php?id=$res'>
                                    <button type='button' class='btn btn-warning'>
                                    <i class='feather icon-edit'></i> &nbsp;Update</button>
                                    </a>"; 
                                    } 
                                    
                                    echo "<br/>";echo "<br/>";
                                    if($allowedAction=='all'){
                                        echo"<a href='#' id='" . $row['10'] . "' class='recyclebutton btn btn-danger' title='Click To Delete'> 
                                        <i class='feather icon-trash'></i>  &nbsp; Move to Bin</a>
                                        </td>";
                                    } 
                                   
                                    echo "</tr>";
                                    $count++;
                                }

                                echo "</tfoot>";
                                echo "</table>";
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>





    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <!--<script src="assets/js/menu-setting.min.js"></script>-->

    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="assets/js/plugins/buttons.print.min.js"></script>
    <script src="assets/js/plugins/pdfmake.min.js"></script>
    <script src="assets/js/plugins/jszip.min.js"></script>
    <script src="assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="assets/js/plugins/buttons.html5.min.js"></script>
    <script src="assets/js/plugins/buttons.bootstrap4.min.js"></script>
    <script src="assets/js/pages/data-export-custom.js"></script>



    <script>
        $(document).ready(function() {
            $("#updateuser").delay(5000).slideUp(300);
        });
    </script>

    <script type="text/javascript">
        $(function() {

            $(".recyclebutton").click(function() {
                let element = $(this);

                let res_id = element.attr("id");

                let info = 'id=' + res_id;
                if (confirm("Are you sure you want to delete this Record?")) {
                    $.ajax({
                        type: "GET",
                        url: "recycleuser.php",
                        data: info,
                        success: function() {}
                    });
                    $(this).parents(".record").animate({
                            backgroundColor: "#FF3"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                    setTimeout(function () {
                        window.location.reload();
                    },2000);
                }
                return false;
            });

            $('#recycle_records').on('click', function(e) {
                let requestIDs = [];

                $(".request_checkbox:checked").each(function() {
                    requestIDs.push($(this).data('request-id'));
                });

                if (requestIDs.length <= 0) {
                    alert("Please select records.");
                } 
                else {
                    WRN_PROFILE_DELETE = "Are you sure you want to delete " + (requestIDs.length > 1 ? "these" : "this") + " Record?";
                    let checked = confirm(WRN_PROFILE_DELETE);
                    if (checked == true) {
                        let selected_values = requestIDs.join(",");
                        $.ajax({
                            type: "POST",
                            url: "recycleuser.php",
                            cache: false,
                            data: 'tender_request_ids=' + selected_values,
                            success: function() {
                                $(".request_checkbox:checked").each(function(){
                                    $(this).closest(".record").animate({
                                        backgroundColor:"#FF3"
                                    },"fast").animate({
                                        opacity:"hide"
                                    },"slow",function(){
                                        $(this).remove();
                                    });
                                });
                                setInterval(function(){
                                    window.location.reload();
                                },2000);
                                
                            }
                        });
                    }
                }
            });

            $('.update_records').on('click', function(e) {
                let updateIDs = [];
                if ($(".request_checkbox:checked").length == 0){
                    alert("Please select records.");
                }

                $(".request_checkbox:checked").each(function() {
                    updateIDs.push($(this).data('request-id'));

                    $('.update_records').attr('href',"update-tender-requests.php?tenderIds="+btoa(updateIDs));
                    console.log(updateIDs);
                });

                let selected_values = requestIDs.join(",");
                $.ajax({
                    type: "GET",
                    url:"update-tender-requests.php",
                    cache:false,
                    data:"tenderIds" + selected_values,
                })

                
            });
        });
    </script>

    

</body>

</html>