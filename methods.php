<?php
    
    function sendEmail($to, $msg, $sbj, $from)
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $header = "From:" . $from;

        mail($to, $sbj, $msg, $header);
    }

?>