<?php
    session_start();

    require './_req.php';

    $tid = $_SESSION['t_id'];


    // ################ index.html ###############
    // ------------------   1   ------------------ 
    // Fetch Transporter Name  
    if(isset($_POST['FetchTransporterName'])){
        print_r(TransporterName());
    }

    function TransporterName(){
        global $conn;
        global $tid;
        
        $sql = "SELECT `t_name` FROM `transporters` WHERE `t_id` = '$tid'";
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($result);
        $name = $row['t_name'];
        return $name;
    }

    // ------------------   2   ------------------
    // Fetch Contracts Completed By Transporter

    if(isset($_POST['FetchContractsRecieved'])){
        print_r(contractsReceived());
    }

    function contractsReceived(){
        global $conn,$tid;
        
        $sql = "SELECT `t_contracts_completed` FROM `transporters` WHERE `t_id` = '$tid'";
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($result);
        
        return $row['t_contracts_completed'];
    }


    // ################ posted_contracts.html ###############
    // ------------------   3   ------------------
    // Fetches the all posted contracts
    if(isset($_POST['fetchPosetedContract'])){
        print_r(ongoingPostedContracts());
    }

    function ongoingPostedContracts(){
        global $conn;
    
        $sql = "SELECT c_id,c_job_category,c_pickup_city,c_delivery_city,c_weight ,TIMEDIFF(`c_bid_end_time`,CURRENT_TIMESTAMP) as diff FROM contracts WHERE (c_bid_end_time > CURRENT_TIMESTAMP) AND `c_status` = 0";
    
        $result = mysqli_query($conn,$sql);
        $html = '';
        while($row = mysqli_fetch_assoc($result)){
            $c_id = $row['c_id'];
            $min=$max=0;
            $sql_b = "SELECT MIN(`b_bid_amount`) as min ,MAX(`b_bid_amount`) as max FROM `bids` WHERE `b_contract_id` = '$c_id'";

            $res_b = mysqli_query($conn,$sql_b);
            $row_b = mysqli_fetch_assoc($res_b);

            if($row_b['min'] != NULL){ 
                $min = $row_b['min'];
                $max = $row_b['max'];
            }
           
            $diff = CalculateElapsedTime($row['diff']);

            $a = '<div class="col-md-6 col-sm-12">
            <div class="card card-border-c-blue">
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="mx-2">
                            <h5 class="d-inline-block">'.$row['c_pickup_city'].'</h5>
                        </div>
                        <div class="mx-2 text-center">
                            <h4 class="pcodec-micon">
                                <i class="mdi mdi-arrow-right-thick"></i>
                            </h4>
                        </div>
                        <div class="mx-2">
                            <h5 class="d-inline-block">'.$row['c_delivery_city'].'</h5>
                        </div>
                        <!-- <h5 class="d-inline-block m-b-10 ">John Doe</h5> -->
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list list-unstyled">
                                <li>Cargo Type : '.$row['c_job_category'].'</li>
                                <li>Cargo Weight : '.$row['c_weight'].'</li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list list-unstyled text-right">
                                <li>Highest Bid : Rs. '.$min.' /-</li>
                                <li>Lowest Bid : Rs. '.$max.' /-</li>
                            </ul>.
                        </div>
                    </div>
                    <div class="m-t-30">
                        <div class="task-list-table">
                            <p class="task-due"><strong> Due : </strong><strong
                                    class="label label-primary">'.$diff.'</strong></p>
                        </div>
                        <div class="task-board m-0 float-right">
                            <a href="contract.html?id='.$row['c_id'].'" class="btn btn-primary">
                                <!-- <i class="fas fa-eye m-0"></i> -->
                                View Contract
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        $html.=$a;
        }
        return $html;
    }

    function CalculateElapsedTime($diff){
        $time = explode(":",$diff);
        $str = '';
        if($time[0]>24){
    
            $str = floor($time[0] / 24)." Days";
    
        }else{
            
            if($time[0]>0){
                $str = $time[0]." Hours";
            }else{
                $str = $time[1]." Min.";
            }
        }
        return $str;
    }

    // ################ contracts.html ###############
    // ------------------   4   ------------------
    // Get Sender Info.

    if(isset($_POST['senderInfoContract'])){
        print_r(senderInfoContarcts($_POST['senderInfoContract']));
    }

    function senderInfoContarcts($c_id){
        global $conn;

        $sql = "SELECT `c_pickup_person`,`c_pickup_address`,`c_pickup_city`,`c_pickup_contact` FROM `contracts` WHERE `c_id` = '$c_id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        $html = '<h6>Pickup Info : </h6>
        <h6 class="m-0">'.$row['c_pickup_person'].'</h6>
        <p class="m-0 m-t-10">'.$row['c_pickup_address'].'</p>
        <p class="m-0 m-t-10">'.$row['c_pickup_city'].'</p> 
        <p class="m-0">'.$row['c_pickup_contact'].'</p>
        <p><a class="text-secondary">demo@gmail.com</a></p>';

        return $html;

    }

    // ------------------   5   ------------------
    // Get Receiver Info.

    if(isset($_POST['receiverInfoContract'])){
        print_r(receiverInfoContracts($_POST['receiverInfoContract']));
    }

    function receiverInfoContracts($c_id){
        global $conn;

        $sql = "SELECT `c_delivery_person`,`c_delivery_address`,`c_delivery_city`,`c_delivery_contact` FROM `contracts` WHERE `c_id` = '$c_id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        $html = '<h6>Delivery Info : </h6>
        <h6 class="m-0">'.$row['c_delivery_person'].'</h6>
        <p class="m-0 m-t-10">'.$row['c_delivery_address'].'</p>
        <p class="m-0 m-t-10">'.$row['c_delivery_city'].'</p> 
        <p class="m-0">'.$row['c_delivery_contact'].'</p>
        <p><a class="text-secondary">demo@gmail.com</a></p>';

        return $html;

    }

    // ------------------   6   ------------------
    // Get Contract end Time
    
    if(isset($_POST['timerContract'])){
        print_r(contractTimeDiffEnd($_POST['timerContract']));
    }

    function contractTimeDiffEnd($c_id){
        global $conn;

        $sql = "SELECT `c_bid_end_time` FROM `contracts` WHERE `c_id` = '$c_id'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);
        
        return $row['c_bid_end_time'];
        
    }
    
    // ------------------   7   ------------------
    // Get order info

    if(isset($_POST['orderInfo'])){
        print_r(orderInfo($_POST['orderInfo']));
    }

    function orderInfo($c_id){
        global $conn;

        $sql = "SELECT * FROM `contracts` WHERE `c_id` = '$c_id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        $pickup_date = date_format(date_create($row['c_pickup_date']),"d M Y");
        $delivery_date = date_format(date_create($row['c_delivery_date']),"d M Y");


        $html = '<tbody>
        <tr>
            <th>Source </th>
            <th>:</th>
            <td> &nbsp; '.$row['c_pickup_city'].' ,'.$row['c_pickup_state'].'</td>
        </tr>
        <tr>
            <th>Destination </th>
            <th>:</th>
            <td> &nbsp; '.$row['c_delivery_city'].' ,'.$row['c_delivery_state'].'</td>
        </tr>
        <tr>
            <th>Cargo</th>
            <th>:</th>
            <td> &nbsp; '.$row['c_job_category'].'</td>
        </tr>
        <tr>
            <th>Cargo Length</th>
            <th>:</th>
            <td> &nbsp; '.$row['c_length'].' Inches. (Approx)</td>
        </tr>
        <tr>
            <th>Cargo Width</th>
            <th>:</th>
            <td> &nbsp; '.$row['c_width'].' Inches. (Approx)</td>
        </tr>
        <tr>
            <th>Cargo Height</th>
            <th>:</th>
            <td> &nbsp; '.$row['c_height'].' Inches. (Approx)</td>
        </tr>
        <tr>
            <th>Cargo Weight</th>
            <th>:</th>
            <td> &nbsp; '.$row['c_weight'].' Kgs. (Approx)</td>
        </tr>
        <tr>
            <th>No. Of Packages &nbsp;</th>
            <th>:</th>
            <td> &nbsp;
            '.$row['c_no_of_packages'].'
            </td>
        </tr>
        <tr>
            <th>Expected Job Price</th>
            <th>:</th>
            <td> &nbsp; Rs.'.$row['c_expected_job_price'].'</td>
        </tr>
        <tr>
            <th>Expected Pickup Date</th>
            <th>:</th>
            <td> &nbsp;'.$pickup_date.'</td>
        </tr>
        <tr>
            <th>Expected Delivery Date</th>
            <th>:</th>
            <td> &nbsp;'.$delivery_date.'</td>
        </tr>
    </tbody>';
    return $html;
    }
    
    // ------------------   8   ------------------
    // Bid Form

    if(isset($_POST['BidForm'])){
        print_r(BidFormSubmit($_POST['BidForm']));
    }

    function BidFormSubmit($data){
        global $conn;
        global $tid;

        $params = array();
        parse_str($data, $params);

        $pickup_start = $params['Pickup_start'];
        $pickup_end = $params['Pickup_end'];
        $delivery_start = $params['Delivery_start'];
        $delivery_end = $params['Delivery_end'];
        $bid_amount = $params['Bid_amount'];
        $msg = $params['Msg'];
        $c_id = $params['c_id'];

        $sql = "INSERT INTO `bids`(`b_contract_id`, `b_transporter_id`, `b_pickup_time_start`, `b_pickup_time_end`, `b_delivery_time_start`, `b_delivery_time_end`, `b_bid_amount`, `b_message`) VALUES ('$c_id','$tid','$pickup_start','$pickup_end','$delivery_start','$delivery_end','$bid_amount','$msg')";
        $res = mysqli_query($conn,$sql);
        
        if($res){
            $sql = "UPDATE `contracts` SET `c_quotes_received`= `c_quotes_received`+ 1 WHERE `c_id` = '$c_id'";
            $res = mysqli_query($conn,$sql);
        }
        return $res;

    }
    

    // ################ Bid_Status.html ###############
    // ------------------   9   ------------------
    // Fetches the all bidded contracts
    if(isset($_POST['FetchBidStatus'])){
        print_r(FetchBidStatus());
    }

    function FetchBidStatus(){
        global $conn,$tid;
        
        $sql = "SELECT `contracts`.`c_id`,`contracts`.`c_job_category`,`contracts`.`c_pickup_city`,`contracts`.`c_delivery_city`,`contracts`.`c_weight`, `bids`.`b_status`, `bids`.`b_transporter_id`,`bids`.`b_bid_amount` ,TIMEDIFF(`contracts`.`c_bid_end_time`, CURRENT_TIMESTAMP) as diff
        FROM `contracts`
            LEFT JOIN `bids` ON `bids`.`b_contract_id` = `contracts`.`c_id` WHERE `bids`.`b_transporter_id` = '$tid' AND `contracts`.`c_bid_end_time`+ INTERVAL 1 DAY > CURRENT_TIMESTAMP  AND `contracts`.`c_status` < 4";

        // $sql = "SELECT c_id,c_job_category,c_pickup_city,c_delivery_city,c_weight ,TIMEDIFF(`c_bid_end_time`,CURRENT_TIMESTAMP) as diff FROM contracts WHERE (c_bid_end_time > CURRENT_TIMESTAMP) AND `c_status` = 0";
    
        $result = mysqli_query($conn,$sql);
        $html = '';
        while($row = mysqli_fetch_assoc($result)){
            $c_id = $row['c_id'];
            $min=$max=0;
            $sql_b = "SELECT MIN(`b_bid_amount`) as min ,MAX(`b_bid_amount`) as max FROM `bids` WHERE `b_contract_id` = '$c_id'";

            $res_b = mysqli_query($conn,$sql_b);
            $row_b = mysqli_fetch_assoc($res_b);

            if($row_b['min'] != NULL){ 
                $min = $row_b['min'];
                $max = $row_b['max'];
            }
           if($row['b_status'] == 'pending'){
            $color = 'blue';
            $label = 'primary';
           }elseif($row['b_status'] == 'approved'){
               $color = 'green';
               $label = 'success';
           }
           else{
               $color = 'red';
               $label = 'danger';
           }

            $diff = CalculateElapsedTime($row['diff']);

            $a = '<div class="col-md-6 col-sm-12">
            <div class="card card-border-c-'.$color.'">
                <div class="card-body">
                    <div class="mb-2 row">
                        <div class="mx-2">
                            <h5 class="d-inline-block">'.$row['c_pickup_city'].'</h5>
                        </div>
                        <div class="mx-2 text-center">
                            <h4 class="pcodec-micon">
                                <i class="mdi mdi-arrow-right-thick"></i>
                            </h4>
                        </div>
                        <div class="mx-2">
                            <h5 class="d-inline-block">'.$row['c_delivery_city'].'</h5>
                        </div>
                        <!-- <h5 class="d-inline-block m-b-10 ">John Doe</h5> -->
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list list-unstyled">
                                <li>Cargo Type : '.$row['c_job_category'].'</li>
                                <li>Cargo Weight : '.$row['c_weight'].'</li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list list-unstyled text-right">
                                <li>Highest Bid : Rs. '.$min.' /-</li>
                                <li>Lowest Bid : Rs. '.$max.' /-</li>
                                <li class="text-green">Your Bid : Rs. '.$row['b_bid_amount'].' /-</li>
                            </ul>.
                        </div>
                    </div>
                    <div class="m-t-5">
                        <div class="task-list-table">
                            <p class="task-due"><strong> Due : </strong><strong
                                    class="label label-'.$label.'">'.$diff.'</strong></p>
                        </div>
                        <div class="task-board m-0 float-right">
                            <a href="contract.html?id='.$row['c_id'].'" class="btn btn-'.$label.'">
                                <!-- <i class="fas fa-eye m-0"></i> -->
                                View Contract
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        $html.=$a;
        }
        return $html;
    }

    // ################ ongoing_contracts.html ###############
    // ------------------   10   ------------------
    // Fetches The Ongoing Info of Contracts of Transporters
    if(isset($_POST['GetSenderInfoOngoing'])){
        
        global $conn,$tid;
        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        print_r(senderInfoContarcts($row['t_active_contract_id']));
    }
    if(isset($_POST['GetReceiverInfoOngoing'])){
        
        global $conn,$tid;
        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        print_r(receiverInfoContracts($row['t_active_contract_id']));
    }
    if(isset($_POST['GetContractNo'])){
        
        global $conn,$tid;
        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        print_r('#'.$row['t_active_contract_id']);
    }

    if(isset($_POST['GetOrderInfo'])){
        global $conn,$tid;
        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);
        $cid = $row['t_active_contract_id'];

        $sql = "SELECT `bids`.`b_bid_amount`, `contracts`.*
        FROM `bids` 
            LEFT JOIN `contracts` ON `bids`.`b_contract_id` = `contracts`.`c_id` WHERE `contracts`.`c_id` = '$cid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);
        $pickup_date = date_format(date_create($row['c_pickup_date']),"d M Y");
        $delivery_date = date_format(date_create($row['c_delivery_date']),"d M Y"); 
        
        $html = '<div class="col-md-12 col-sm-12">
        <h6>Order Information :</h6>
        <table
            class="table table-responsive invoice-table invoice-order table-borderless">
            <tbody>
                <tr>
                    <th>Source </th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_pickup_city'].'</td>
                </tr>
                <tr>
                    <th>Destination </th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_delivery_city'].'</td>
                </tr>
                <tr>
                    <th>Cargo</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_job_category'].'</td>
                </tr>
                <tr>
                    <th>Cargo weight</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_weight'].' Kgs. (Approx)</td>
                </tr>
                <tr>
                    <th>Cargo Length</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_length'].' Inches. (Approx)</td>
                </tr>
                <tr>
                    <th>Cargo Width</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_width'].' Inches. (Approx)</td>
                </tr>
                <tr>
                    <th>Cargo Height</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_height'].' Inches. (Approx)</td>
                </tr>
                <tr>
                    <th>Cargo Weight</th>
                    <th>:</th>
                    <td> &nbsp; '.$row['c_weight'].' Kgs. (Approx)</td>
                </tr>
                <tr>
                    <th>No. Of Packages &nbsp;</th>
                    <th>:</th>
                    <td> &nbsp;
                    '.$row['c_no_of_packages'].'
                    </td>
                </tr>
                <tr>
                    <th>Expected Job Price</th>
                    <th>:</th>
                    <td> &nbsp; Rs.'.$row['c_expected_job_price'].'</td>
                </tr>
                <tr>
                    <th>Expected Pickup Date</th>
                    <th>:</th>
                    <td> &nbsp;'.$pickup_date.'</td>
                </tr>
                <tr>
                    <th>Expected Delivery Date</th>
                    <th>:</th>
                    <td> &nbsp;'.$delivery_date.'</td>
                </tr>
            </tbody>
    </table>

    <h3 class="text-uppercase text-primary">Receivable amount
        :&nbsp;
        <span>Rs.'.$row['b_bid_amount'].'</span>
    </h3>
</div>';

 print_r($html);

    }

    if(isset($_POST['GetStatusBtn'])){
        global $conn,$tid;

        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        $cid = $row['t_active_contract_id'];

        $sql = "SELECT `c_status` FROM `contracts` WHERE `c_id` = '$cid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        $status = $row['c_status'];

        if($status == 2){
            $st = "Picked Up Package";
            $btn_st = "success";
        }elseif($status == 3){  
            $st = "Package Delivered";
            $btn_st = "info";
        }elseif($status == 4){
            $st = "Payment Received & Complete the Job";
            $btn_st = "danger"; 
        }

        $html = '<button type="button"
        class="btn btn-'.$btn_st.' btn-print-invoice m-b-10" id="StatusChngBtn">'.$st.'</button>';
        print_r($html);
    }

    if(isset($_POST['ChangeStatus'])){
        global $conn,$tid;
        $sql = "SELECT `t_active_contract_id` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        $cid = $row['t_active_contract_id'];

        $sql = "UPDATE `contracts` SET `c_status` =`c_status` + 1 WHERE `c_id` = '$cid'";
        $res = mysqli_query($conn,$sql);

        $sql = "SELECT `c_status` FROM `contracts` WHERE `c_id` = '$cid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        if($row['c_status'] == 4){
            $sql = "UPDATE `contracts` SET `c_delivered_time`= CURRENT_TIMESTAMP WHERE `c_id` = $cid";
            $res = mysqli_query($conn,$sql);
        }



    }

    // Index.html
    // Fetch The Balance Of Transporter
    if(isset($_POST['FetchTBalance'])){
        global $conn,$tid;

        $sql = "SELECT `t_balance` FROM `transporters` WHERE `t_id` = '$tid'";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        print_r($row['t_balance']);
    }


    // -------------------------------------------
    //  Check the ongoing Contract and fill up the data
    if(isset($_POST['CheckTheOngoingCOntracts'])){
        global $conn,$tid;

        $sql = "SELECT `t_contract_active_status` FROM `transporters` WHERE `t_id` = $tid";
        $res = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($res);

        $status = $row['t_contract_active_status'];
        $html = '<div class="container">
        <h6>No Ongoing Jobs, <a href="./posted_contracts.html">Bid Today</a> to get Jobs.
        </h6>
    </div>';
        if($status == 1){
            $html = '<div class="container" id="printTable">
            <div>
                <div class="card">
                    <div class="row invoice-contact">
                        <div class="col-md-8">
                            <div class="invoice-box row">
                                <div class="col-sm-12">
                                    <table
                                        class="table table-responsive invoice-table table-borderless p-l-20">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h4>Packers & Movers</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a class="text-secondary" href=""
                                                        target="_top">
                                                        admin@movers.com
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                <td>+91 919-91-91-919</td>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- <div class="card-body bg-c-blue">

                                <div class="counter text-center">
                                    <h4 id="timer" class="text-white m-0"></h4>
                                </div>
                            </div> -->

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row invoive-info">
                            <div class="col-md-4 col-xs-12 invoice-client-info">
                                <h6>Client Information :</h6>
                            </div>
                        </div>
                        <div class="row invoive-info">
                            <div class="col-md-4 col-xs-12 invoice-client-info" id="SenderInfo">
                                <h6>Sender Contact : </h6>
                                <h6 class="m-0">John Doe</h6>
                                <p class="m-0 m-t-10">1065 Mandan Road, Columbia MO, Missouri.
                                    (123)-65202</p>
                                <p class="m-0">(1234) - 567891</p>
                                <p><a class="text-secondary" href="mailto:demo@gmail.com"
                                        target="_top">demo@gmail.com</a></p>
                            </div>
                            <div class="col-md-4 col-xs-12" id="ReceiverInfo">
                                <h6>Receiver Contact : </h6>
                                <h6 class="m-0">John Doe</h6>
                                <p class="m-0 m-t-10">1065 Mandan Road, Columbia MO, Missouri.
                                    (123)-65202</p>
                                <p class="m-0">(1234) - 567891</p>
                                <p><a class="text-secondary" href="mailto:demo@gmail.com"
                                        target="_top">demo@gmail.com</a></p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="m-b-20">Contract Number <span
                                        id="ContractNo">#125863478945</span>
                                </h6>

                            </div>
                        </div>
                        <hr>
                        <div class="row invoive-info" id="OrderInfoContract">
                            <!--  -->
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Terms and Condition :</h6>
                                <p>lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                                    do eiusmod tempor incididunt ut labore et dolore magna
                                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                    ullamco
                                    laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                    irure dolor
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-12 invoice-btn-group text-center" id="StatusBtnDiv">
                        <!-- <button type="button"
                            class="btn btn-success btn-print-invoice m-b-10">Picked Up
                            Package</button> -->
                    </div>
                </div>
            </div>
        </div>';
        }

        print_r($html);

    }

    if(isset($_POST['CheckIsBidPlaced'])){
        global $conn,$tid;
        $cid = $_POST['CheckIsBidPlaced'];
        $sql = "SELECT `b_contract_id` FROM `bids` WHERE `b_transporter_id` = $tid";
        $res = mysqli_query($conn,$sql);
        $count = mysqli_num_rows($res);

        $html = "<h6> Already Bid is Placed </h6>";
        if($count == 0){
            $html = '<form id="example-form" action="#">
            <div>
                <!-- <h3>Select Service</h3>
                <section>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="userName">Service <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="exampleSelect1"
                                    disabled>
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="password">Transport
                                    vehicle <span class="text-danger">*</span></label>
                                <select class="form-control" id="exampleSelect1">
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <p>(<span class="text-danger">*</span>) Mandatory</p>
                </section> -->
                <h3>Timeframe</h3>
                <section>
                    <h5>Pickup Time</h5>
                    <div class="row py-2">
                        <div class="col-sm-6 col-6">
                            Pickup Between
                            <input type="datetime-local" name="Pickup_start" id="">
                        </div>
                        <div class="col-sm-6 col-6">
                            And
                            <input type="datetime-local" name="Pickup_end" id="">
                            From Date of Booking.
                        </div>
                    </div>
                    <h5 class="mt-3">Delivery Time</h5>
                    <div class="row py-2">
                        <div class="col-sm-6 col-6">
                            Delivery Between
                            <input type="datetime-local" name="Delivery_start" id="">
                        </div>
                        <div class="col-sm-6 col-6">
                            And
                            <input type="datetime-local" name="Delivery_end" id="">
                            From Date of Pickup.
                        </div>
                    </div>

                    <p>(<span class="text-danger">*</span>) Mandatory</p>
                </section>
                <h3>Bid Amount</h3>
                <section>
                    <label for="" class="from-label">Bid Amount</label>
                    <input type="number" name="Bid_amount" id="" class="form-control"
                        placeholder="Enter Bid amount">
                </section>
                <h3>Finish</h3>
                <section>
                    <label class="ctext-lg-right"></label>
                    <div class="col-12">
                        <textarea class="form-control max-textarea" maxlength="255"
                            rows="4"
                            placeholder="Type Your Message to the Shipping Customer"
                            name="Msg"></textarea>
                        <small>255 Character limit</small>
                    </div>
                </section>
            </div>
        </form>';
        }

        print_r($html);

    }
    
    
    
    ?>