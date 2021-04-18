<?php
    session_start();

    // $_SESSION['uid'] = "1010";

    if(isset($_SESSION['u_id'])){
        echo $_SESSION['u_id'];
    }
    // session_unset();
    // session_destroy();

        // $time = time();
        // echo $time;


?>