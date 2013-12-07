<?php

/**
 * Un paramètre est une variable envoyé par le client en GET ou POST
 * 
 * L'annotation permet de charger une entité et d'envoyer en argument à la méthode du controlleur concerné
 */
class Parameter extends Annotation {
    public $name;
    public $value;
    public $method;

    public $type;
    public $column;

    public $default;
    public $redirect;

    protected function checkConstraints($target) {
        GLOBAL $em;


        /************

            Methode
        
        ************/

        if( $this->method  === "GET" and isset($_GET[$this->name]) )
            $param_value = $_GET[$this->name];
        elseif( $this->method  === "POST" and isset($_POST[$this->name]) )
            $param_value = $_POST[$this->name];
        else if( isset( $_REQUEST[$this->name]) )
            $param_value = $_REQUEST[$this->name];
        else{ // Erreur
            if( !is_null($this->default) )
                $param_value = $this->default;
            else
                redirect($this->redirect);
        }


        /**
         *  Si entité
         *  Sinon variable
         */
        if( !is_null($this->type) ){
            $this->value = $this->getEntity( $this->type , $this->column , $param_value  );

            if( $this->value == NULL){ // Erreur
                if( !is_null($this->default) ){
                    $this->value = $this->getEntity( $this->type , $this->column , $this->default  );                    
                }else{                 // Erreur
                    redirect($this->redirect);
                }
            }
        }
        else{
            $this->value = $param_value;
        }
    }

    private static function getEntity($type, $column, $value){
        GLOBAL $em;
        return $em->getRepository( $type )
                ->findOneBy( array( $column => $value ) );
    }

}


?>