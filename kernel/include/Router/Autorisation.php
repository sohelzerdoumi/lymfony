<?php

class Autorisation extends Annotation{
        public $level;

        protected function checkConstraints($target) {
            if( ! in_array( $_SESSION['user']->getLevel(), $this->level ) )
                redirect();
        }
}


?>