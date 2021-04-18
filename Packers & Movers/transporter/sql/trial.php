<?php

    $diff = '65:42:15';
    $time = explode(":",$diff);
    $str = '';

    if($time[0]>24){

        echo (floor($time[0] / 24)." Days");

    }else{
        
        if($time[0]>0){
            echo ($time[0]." Hours");
        }else{
            echo ($time[1]." Min");
        }
    }

?>