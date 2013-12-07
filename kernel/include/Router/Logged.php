<?php

class Logged extends Annotation{
        protected function checkConstraints($target) {
            if( check_user() == False )
                redirect('user/login');
        }
}


?>