<?php
class Controller_Comment extends Controller { 
    function action_index() { 
        $this->view->generate('comment_view.php', 'template_view.php'); 
    } 
}
?>