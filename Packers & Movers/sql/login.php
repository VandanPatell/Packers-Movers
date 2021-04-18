<?php
    session_start();
    require("./_req.php");


    // Transporter Login
    if(isset($_POST['t_uname'])){
        $uname = $_POST['t_uname'];
        $password = $_POST['t_pwd'];

        $sql = "SELECT `t_id`,`t_email`,`t_password`,`t_approved` FROM `transporters` WHERE `t_email` = '$uname'";
        $res = mysqli_query($conn,$sql);

        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);

            if($row['t_password'] == $password ){
                if($row['t_approved'] == 1){
                    $_SESSION['t_id'] = $row['t_id'];
                    echo 'success';
                }else{
                    echo '<div class="alert alert-info" role="alert">Your account is yet not approved by the Admin. <br> contact@admin.com</div>';
                }
            }
            else{
                echo '<div class="alert alert-danger" role="alert">Incorrect UserName or Password</div>';
            }
        }else{
            echo '<div class="alert alert-danger" role="alert">No such Username found. 
            <br><a href="./transporter_register.html">Register Now.</a></div>';
        }
        
    }

    // User Login
    if(isset($_POST['u_uname'])){
        $uname = $_POST['u_uname'];
        $password = $_POST['u_pwd'];
        $time = $_POST['u_login_time'];

        $sql = "SELECT `u_id`,`u_email`,`u_password` FROM `users` WHERE `u_email` = '$uname'";
        $res = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($res);

        if($row['u_password'] == $password){
            $_SESSION['u_id'] = $row['u_id'];
            echo 'success';
            
            $sql = "UPDATE `users` SET `u_last_login`='$time'  WHERE `u_id` = '$uname'";
            $res = mysqli_query($conn,$sql);

        }
        else{
            echo 'fail';
        }
    }

    if(isset($_POST['AdminLogin'])){
        $data = $_POST['AdminLogin'];
        $params = array();
        parse_str($data,$params);

        $username = $params['username'];
        $pwd = $params['pwd'];

        global $conn;

        $sql = "SELECT * FROM `admin` WHERE `a_mail` = '$username'";
        $res = mysqli_query($conn,$sql);

        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);

            if($row['a_password'] === $pwd ){
                $_SESSION['a_id'] = $row['a_id'];
                echo 'success';
            }else{
                echo '<div class="alert alert-warning" role="alert">Username and passwor does not match</div>';
            }


        }else{
            echo '<div class="alert alert-danger" role="alert">No such Username found. </div>';
        }


    }


    


?>