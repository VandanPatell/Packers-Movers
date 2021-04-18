<?php
    session_start();

    require './_req.php';

    $uid = $_SESSION['u_id'];
    // echo $uid


    // ################ Common ###############
    // ------------------   1   ------------------ 
    // Fetch User Name  
    if(isset($_POST['GetUserName'])){
        print_r((GetUserName()));
    }

    function GetUserName(){
        global $conn;
        global $uid;

        $sql = "SELECT `u_name` FROM `users` WHERE `u_id` = '$uid'";
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($result);
        $name = $row['u_name']; 
        return $name;
    }


    // ############## post_new_job.html ################
    // ------------------   2   ------------------ 
    // Submit Post New Job Form
    if(isset($_POST['PostNewJob'])){
        print_r(postNewJob($_POST['PostNewJob']));
    }
    function postNewJob($data){

        global $conn;
        global $uid;

        $params = array();
        parse_str($data, $params);
        // print_r($params);
        $job_title = $params['job_title'];
        $job_desc = $params['job_description'];
        $length = $params['length'];
        $height = $params['height'];
        $width = $params['width'];
        $weight = $params['weight'];
        $price = $params['price'];
        $packages = $params['packages'];
        $pickupPerson = $params['PickupPerson'];
        $pickupPersonContact = $params['PickupPersonContact'];
        $pickup_Address = $params['Pickup_Address'];
        $pickup_State = $params['Pickup_State'];
        $pickup_City = $params['Pickup_City'];
        $pickup_Date = $params['Pickup_Date'];
        $Delivery_Person = $params['Delivery_Person'];
        $Delivery_Person_Contact = $params['Delivery_Person_Contact'];
        $Delivery_Address = $params['Delivery_Address'];
        $Delivery_State = $params['Delivery_State'];
        $Delivery_City = $params['Delivery_City'];
        $Delivery_Date = $params['Delivery_Date'];
        $job_cat = $params['job_Cat'];
        $Bid_Start_Time = $params['Bid_Start_Time'];
        $Bid_End_Time = $params['Bid_End_Time'];
        

        $sql = "INSERT INTO `contracts`(`c_creator_id`, `c_job_title`, `c_job_description`, `c_length`, `c_width`, `c_height`, `c_weight`, `c_expected_job_price`, `c_no_of_packages`, `c_job_category`, `c_pickup_person`, `c_pickup_contact`, `c_pickup_address`, `c_pickup_city`, `c_pickup_state`, `c_pickup_date`, `c_delivery_person`, `c_delivery_contact`, `c_delivery_address`, `c_delivery_city`, `c_delivery_state`, `c_delivery_date`,`c_bid_start_time`, `c_bid_end_time`) VALUES ('$uid','$job_title','$job_desc','$length','$width','$height','$weight','$price','$packages','$job_cat','$pickupPerson','$pickupPersonContact','$pickup_Address','$pickup_City','$pickup_State','$pickup_Date','$Delivery_Person','$Delivery_Person_Contact','$Delivery_Address','$Delivery_City','$Delivery_State','$Delivery_Date','$Bid_Start_Time','$Bid_End_Time')";

        $result = mysqli_query($conn,$sql);

        if(!$result)
        {
            echo "Error MySQLI QUERY: ".mysqli_error($conn)."";
            die();
        }
        else
        {
            echo "Success";
        }
    }

    // ############## index.html ################
    // ------------------   2   ------------------ 
    // Get Latest Posted Jobs
    if(isset($_POST['GetLatestPostedJobs'])){
        print_r(LatestPostedJobs());
    }
    function LatestPostedJobs(){

        global $conn;
        global $uid;

        $sql = "SELECT * FROM `contracts` WHERE `c_bid_end_time` > CURRENT_TIMESTAMP AND `c_creator_id` = '$uid' AND `c_status` = 0";
        $res = mysqli_query($conn,$sql);

        $html = '<thead>
        <tr class="tx-10">
            <th class="wd-10p pd-y-5">&nbsp;</th>
            <th class="pd-y-5">Job Name</th>
            <th class="pd-y-5 tx-right">Quotes</th>
            <th class="pd-y-5">Expected Price</th>
            <th class="pd-y-5 tx-center">Options</th>
        </tr>
    </thead>
    <tbody>
        ';
        $trs = '';
        if(mysqli_num_rows($res) > 0){
           
            while($row = mysqli_fetch_assoc($res)){
                $a = '<tr>
                <td class="pd-l-20"> </td>
                <td>
                    <a href="./job_review.html?id='.$row['c_id'].'" class="tx-inverse tx-14 tx-medium d-block">'.$row['c_job_title'].'</a>
                    <span class="tx-11 d-block"><span
                            class="square-8 bg-success mg-r-5 rounded-circle"></span> The job is active</span>

                </td>
                <td class="valign-middle tx-right">'.$row['c_quotes_received'].'</td>
                <td class="valign-middle"><span class="tx-success">Rs. '.$row['c_expected_job_price'].'</span></td>
                <td class="valign-middle tx-center">
                    <!--<a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a> -->
                    <a href="./job_review.html?id='.$row['c_id'].'" class="btn btn-primary btn-sm">View Job</a>
                </td>
            </tr>';

            $trs .= $a;
            }
        }else{
            $trs = '<tr>
                <td colspan="4" style="text-align: center;">No Data to show</td>
            </tr>';
        }
        

        $html .= ($trs .'</tbody>');
        return($html);
    }

    // ############## my_jobs.html ################
    // ------------------   3   ------------------ 
    // Get Active Jobs

    if(isset($_POST['GetActiveJobs'])){
        print_r(GetActiveJobs());
    }

    function GetActiveJobs(){
        global $conn,$uid;

        $sql = "SELECT `c_id`,`c_creation_date`, `c_job_title`, `c_expected_job_price`, `c_quotes_received` FROM `contracts` WHERE `c_bid_end_time` > CURRENT_TIMESTAMP AND `c_status` = 0";
        $res = mysqli_query($conn,$sql);

        $html = '';
        while($row = mysqli_fetch_assoc($res)){

            $a = '<tr>
            <td><a href="./job_review.html?id='.$row['c_id'].'">'.$row['c_job_title'].'</a><br>
                <small class="nb-padd">You have '.$row['c_quotes_received'].' active proposals.</small>
            </td>
            <td class="text-success">Rs. '.$row['c_expected_job_price'].'</td>
            <td>'. date_format(date_create($row['c_creation_date']),"d M Y") .'</td>
            <td>'.$row['c_quotes_received'].'</td>
            <td><a href="./job_review.html?id='.$row['c_id'].'" class="btn btn-outline-primary btn-sm">View Job</a></td>
        </tr>';
        $html .= $a;
        }
        return $html;
    }
    
    // ------------------      ------------------ 
    // Get Balance

    if(isset($_POST['GetBalance'])){
        print_r(GetBal());
    }

    function GetBal(){
        global $conn,$uid;
        $sql = "SELECT `u_balance` FROM `users` WHERE `u_id` = '$uid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        return '₹ '.$row['u_balance'];
    }


    // ------------------   4   ------------------ 
    // Get Job Title

    if(isset($_POST['GetJobTitle'])){
        print_r(GetJobTitle($_POST['GetJobTitle']));
    }

    function GetJobTitle($c_id){
        global $conn;

        $sql = "SELECT `c_job_title` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return $row['c_job_title'];
    }

    // ------------------   5   ------------------ 
    // Get Job Category

    if(isset($_POST['GetJobCategory'])){
        print_r(GetJobCategory($_POST['GetJobCategory']));
    }

    function GetJobCategory($c_id){
        global $conn;

        $sql = "SELECT `c_job_category` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return $row['c_job_category'];
    }

    // ------------------   6   ------------------ 
    // Get Job PickUp Date

    if(isset($_POST['GetJobPickup'])){
        print_r(GetJobPickup($_POST['GetJobPickup']));
    }

    function GetJobPickup($c_id){
        global $conn;

        $sql = "SELECT `c_pickup_date` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        $date = date_format(date_create($row['c_pickup_date']),"d M Y");
        return $date;
    }

    // ------------------   7   ------------------ 
    // Get Job Delivery Date

    if(isset($_POST['GetJobDelivery'])){
        print_r(GetJobDelivery($_POST['GetJobDelivery']));
    }

    function GetJobDelivery($c_id){
        global $conn;

        $sql = "SELECT `c_delivery_date` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        $date = date_format(date_create($row['c_delivery_date']),"d M Y");
        return $date;
    }

    // ------------------   8   ------------------ 
    // Get Job Packages

    if(isset($_POST['GetJobPackages'])){
        print_r(GetJobPackages($_POST['GetJobPackages']));
    }

    function GetJobPackages($c_id){
        global $conn;

        $sql = "SELECT `c_no_of_packages` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return $row['c_no_of_packages'].' Package(s)';
    }
    
    
    // ------------------   9   ------------------ 
    // Get Job weight

    if(isset($_POST['GetJobWeigth'])){
        print_r(GetJobWeigth($_POST['GetJobWeigth']));
    }

    function GetJobWeigth($c_id){
        global $conn;

        $sql = "SELECT `c_weight` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return $row['c_weight'].' Kgs';
    }

    // ------------------   10   ------------------ 
    // Get Job Description

    if(isset($_POST['GetJobDescription'])){
        print_r(GetJobDescription($_POST['GetJobDescription']));
    }

    function GetJobDescription($c_id){
        global $conn;

        $sql = "SELECT `c_job_description` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return $row['c_job_description'];
    }
    // ------------------   11   ------------------ 
    // Get Job Budget

    if(isset($_POST['GetJobBudget'])){
        print_r(GetJobBudget($_POST['GetJobBudget']));
    }

    function GetJobBudget($c_id){
        global $conn;

        $sql = "SELECT `c_expected_job_price` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        return 'Rs. '.$row['c_expected_job_price'];
    }
    
    // ------------------   12   ------------------ 
    // Get Recieved Quotes

    if(isset($_POST['GetRecievedQuotes'])){
        print_r(GetRecievedQuotes($_POST['GetRecievedQuotes']));
    }

    function GetRecievedQuotes($c_id){
        global $conn;

        $sql = "SELECT `transporters`.`t_id`,`transporters`.`t_name`, `bids`.`b_delivery_time_start`, `bids`.`b_bid_amount`, `bids`.`b_message` FROM `transporters` LEFT JOIN `bids` ON `bids`.`b_transporter_id` = `transporters`.`t_id` WHERE `b_contract_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        if(mysqli_num_rows($res) != 0){
            $html = '<table class="mt-4 table">
                        <thead>
                            <tr>
                                <th>Bidder</th>
                                <th>Bid Amt.</th>
                                <th>Delivery Date</th>
                                <th>Comment</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>';
            while($row = mysqli_fetch_assoc($res)){
                $a = '<tr>
                <td> <a href="">'.$row['t_name'].'</a>
                </td>
                <td>Rs. '.$row['b_bid_amount'].' </td>
                <td> '.date_format(date_create($row['b_delivery_time_start']), "d M Y").' </td>
                <td> '.$row['b_message'].' </td>
                <td> 
                <button class="btn btn-outline-success btn-sm Allot_Job" onclick="JobAllotBtn('.$row['t_id'].','.$c_id.')"> Choose Winner </button></td>

            </tr>';
            $html.=$a;
            }
            $html.='</tbody></table>';
        }else{
            $html = '<p class="no-gutters mt-2">There are no quotes yet.</p>';
        }

        return $html;
        
    }

    if(isset($_POST['AllotJob'])){
        // print_r($_POST['AllotJob']);
        print_r(AllotJob($_POST['AllotJob']));
    }

    function AllotJob($data){
        global $conn;
        
        $params = array();
        parse_str($data, $params);
        

        $tid = $params['tid'];
        $cid = $params['cid'];
        // echo($cid);

        
        $sql = "UPDATE `contracts` SET `c_bid_winner`= '$tid',`c_status`= 2 WHERE `c_id` = '$cid'";
        $res = mysqli_query($conn,$sql);

        $sql = "UPDATE `transporters` SET `t_contract_active_id`= '$cid' ,`t_contract_active_status`= 1 WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        
        $sql = "UPDATE `bids` SET `b_status`= 'rejected' WHERE `b_contract_id` = '$cid'";
        $res = mysqli_query($conn,$sql);
        
        $sql = "UPDATE `bids` SET `b_status`= 'approved' WHERE `b_contract_id` = '$cid' AND `b_transporter_id` = '$tid'";
        $res = mysqli_query($conn,$sql);


        
    }

    // ################## ongoing_jobs.html ######################
    // -----------------------------------------------
    // get the ongoing Jobs
    if(isset($_POST['GetOngoingJobs'])){
        print_r(FetchOngoingJobs());
    }

    function FetchOngoingJobs(){
        global $conn,$uid;

        // $sql = "SELECT `contracts`.`c_job_title`, `contracts`.`c_delivery_date`, `contracts`.`c_status`, `bids`.`b_bid_amount`, `transporters`.`t_name`
        // FROM `contracts`
        //     LEFT JOIN `transporters` ON `contracts`.`c_bid_winner` = `transporters`.`t_id`
        //     LEFT JOIN `bids` ON `bids`.`b_transporter_id` = `transporters`.`t_id` WHERE `contracts`.`c_creator_id` = '$uid' AND `contracts`.`c_status` >= 2 AND `contracts`.`c_status` <= 4";
        $sql = "SELECT `c_status`,`c_id`,`c_job_title`,`c_delivery_date`,`c_bid_winner` FROM `contracts` WHERE `c_creator_id` = '$uid' AND `c_status` >= 2 AND `contracts`.`c_status` <= 4";
        $res = mysqli_query($conn,$sql);
        
        $html = '';
        while($row = mysqli_fetch_assoc($res)){

            $tid = $row['c_bid_winner'];
            $cid = $row['c_id'];

            $sql = "SELECT `t_name` FROM `transporters` WHERE `t_id` = '$tid'";
            $res_1 = mysqli_query($conn,$sql);
            $row_1 = mysqli_fetch_assoc($res_1);

            $sql = "SELECT `b_bid_amount` FROM `bids` WHERE `b_contract_id` = '$cid' AND `b_transporter_id` = '$tid'";
            $res_2 = mysqli_query($conn,$sql);
            $row_2 = mysqli_fetch_assoc($res_2);


            if($row['c_status'] == 2){
                $status = 'Transporter Approved';
                $btn_st = 'disabled';
            }elseif($row['c_status'] == 3){
                $status = 'Picked';
                $btn_st = 'disabled';
            }elseif($row['c_status'] == 4){
                $status = 'Dropped';
                $btn_st = '';
            }else{
                $status = 'Payment Pending';
            }

            $a = '<tr>
            <td>'.$row['c_job_title'].'</td>
            <td>'.$row_1['t_name'].' </td>
            <td class="text-success">Rs. '.$row_2['b_bid_amount'].' /-</td>
            <td>'.date_format(date_create($row['c_delivery_date']),"M d, Y").'</td>
            <td>'.$status.'</td>
            <td>
                <button class="btn btn-outline-primary btn-sm" '.$btn_st.' id="MakePayment">
                    Package Received
                </button>
            </td>
        </tr>';
        $html.=$a;
        }
        return $html;
    }

    if(isset($_POST['MakePaymentAndCompleteJob'])){
        global $conn,$uid;

        $sql = "SELECT `bids`.`b_bid_amount`, `contracts`.`c_id`, `transporters`.`t_id`, `users`.`u_id`
        FROM `contracts`
            LEFT JOIN `bids` ON `bids`.`b_contract_id` = `contracts`.`c_id`
            LEFT JOIN `users` ON `contracts`.`c_creator_id` = `users`.`u_id`
            LEFT JOIN `transporters` ON `contracts`.`c_bid_winner` = `transporters`.`t_id` WHERE `contracts`.`c_creator_id` = '$uid' AND `contracts`.`c_status` = 4";

        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        $c_id = $row['c_id'];
        $bid_Amount = $row['b_bid_amount'];
        $t_id =  $row['t_id'];
        $u_id = $row['u_id'];

        //  print_r("c_id = ".$bid_Amount.",t_id=".$t_id."u_id=".$u_id);

        $sql = "UPDATE `contracts` SET `c_status`= `c_status`+ 1 WHERE `c_id` = '$c_id' ";
        $res_1 = mysqli_query($conn,$sql);        
        $sql = "UPDATE `transporters` SET `t_balance`=`t_balance`+'$bid_Amount',`t_contract_active_status`= 0 WHERE `t_id` = '$t_id'";
        $res_2 = mysqli_query($conn,$sql);        
        $sql = "UPDATE `users` SET `u_balance`= `u_balance`-'$bid_Amount' WHERE `u_id` = '$u_id'";
        $res_3 = mysqli_query($conn,$sql);    
    }

    // Check Session

    if(isset($_POST['CheckSession']))
    {
        if(isset($_SESSION['u_id'])){
            echo "success";
        }else{
            echo "failed";
        }
    }

    // getDeliveredJobs

    if(isset($_POST['getDeliveredJobs'])){
        print_r(GetJobsCompleted());
    }

    function GetJobsCompleted(){
        global $conn,$uid;

        $sql = "SELECT `bids`.`b_bid_amount`,`contracts`.`c_id`, `contracts`.`c_creation_date`,`contracts`.`c_job_title`,`contracts`.`c_delivered_time` , `transporters`.`t_name`,`transporters`.`t_id`
        FROM `transporters`
            LEFT JOIN `bids` ON `bids`.`b_transporter_id` = `transporters`.`t_id`
            LEFT JOIN `contracts` ON `bids`.`b_contract_id` = `contracts`.`c_id`
            WHERE `contracts`.`c_creator_id`= $uid AND `contracts`.`c_status` = 5 ";

        $res = mysqli_query($conn,$sql);
        
        $html = '';
        while($row = mysqli_fetch_assoc($res)){

            $a = '<tr>
                <td>'.$row['c_job_title'].'</td>
                <td>'.$row['t_name'].'</td>
                <td class="text-success">₹ '.$row['b_bid_amount'].'</td>
                <td>'. date_format(date_create($row['c_creation_date']),"d M Y") .'</td>
                <td>'. date_format(date_create($row['c_delivered_time']),"d M Y") .'</td>
            </tr>';
            $html .= $a;
        }
        return $html;

    }

    mysqli_close($conn)
    ?>