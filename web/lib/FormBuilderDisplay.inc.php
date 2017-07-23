<?php

class FormBuilderDisplay extends FormBuilder{

    /*-------------------------------------------------------------------------*/

    public function __construct() {
        parent::__construct();
    }

    /*-------------------------------------------------------------------------*/

    protected function build_student_input($name, $type) {

        // check if the input is saved in POST
        if( isset($_POST[$name]) ) {
            // yes -> put it into the form
            $value = $_POST[$name];
            return "<input type=\"$type\" name=\"$name\" value=\"$value\" required/>";
        }
        else{
            // no -> build empty form
            return parent::build_student_input($name);
        }

    }

    /*-------------------------------------------------------------------------*/

    protected function is_choice_selected($choice, $group) {
        if( isset($_POST[$group]) ){
            return $choice == $_POST[$group];
        }
        else{
            return parent::is_choice_selected($choice, $group);
        }
    }

    /*-------------------------------------------------------------------------*/

}

?>
