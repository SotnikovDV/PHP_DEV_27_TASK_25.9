<?php
class Controller_Logoff
 extends Controller { 
    function action_index() { 
        $user = new User();
        $user->logoff();
        header("Location: /"); 

    } 
}
?>