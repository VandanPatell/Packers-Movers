<?php
    session_start();
    include_once "./_req.php";

    if (isset($_POST['UserRegister'])){

        print_r(Register_User($_POST['UserRegister']));
    }

    function Register_User($data){
        global $conn;
        $params = array();
        parse_str($data, $params);

        $name = $params['user_name'];
        $mail = $params['user_email'];
        $password = $params['password'];
        $CPassword = $params['cpassword'];

        // echo($name.",".$mail.",".$password.",");

        if($CPassword === $password){
            $sql = "INSERT INTO `users`(`u_name`, `u_password`, `u_email`) VALUES ('$name','$password','$mail')";
            $res = mysqli_query($conn,$sql);

            
            if($res){
                $sql = "SELECT `u_id` FROM `users` WHERE `u_email` = '$mail'";
                $res = mysqli_query($conn,$sql);
                $row = mysqli_fetch_assoc($res);
                $uid = $row['u_id'];
                // echo $uid;

                $_SESSION['u_id'] = $uid;
            }
            return TRUE;
        }
    }

    if(isset($_POST['TransRegister'])){
        global $conn;
        $data = $_POST['TransRegister'];

        $params = array();
        parse_str($data, $params);
        
        $password = $params['password'];
        $rep_passrword = $params['rep_password'];

        // print_r($password .','.$rep_passrword);

        if($password == $rep_passrword){

            $name = $params['user_name'];
            $mail = $params['mail'];
            $address = $params['address'];
            $a_no = $params['aadhar_no'];
            $l_no = $params['lic_no'];
            $contact = $params['contact_no'];
            $e_contact = $params['e_contact_no'];
            $vehicle_type = $params['v_type'];
            $v_no_plate = $params['v_no_plate'];
            $cargo_cap = $params['cargo_cap'];
            $v_permit = $params['v_permit'];

            $sql = "INSERT INTO `transporters`(`t_name`, `t_password`, `t_email`, `t_contact`, `t_address`, `t_emergency_contact`, `t_aadhar_no`, `t_license_no`, `t_driving_permit`, `t_vehicle_type`, `t_vehicle_no`, `t_max_cargo`) VALUES ('$name','$password','$mail',$contact,'$address',$e_contact,$a_no,'$l_no','$v_permit','$vehicle_type','$v_no_plate',$cargo_cap)";

            // print_r($sql);
            $res = mysqli_query($conn,$sql);

            if($res){
                echo '<div class="alert alert-primary" role="alert">
                Wola ! Your account is registered, you will be able to login after your details are reviewed by the admin.
                <br>
                contact <a href="">contact@admin.com</a> for more queries.
            </div>';
            }else{
                echo '<div class="alert alert-warning" role="alert">
                oops ! looks like you missed out some fields.
              </div>';
            }
            
        }else{
            echo '<div class="alert alert-danger" role="alert">
            The Entered Password and Repeat Password does Not Match !
          </div>';
        }
        


    }

?>  