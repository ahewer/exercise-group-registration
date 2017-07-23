<?php

require "FormBuilder.inc.php" ;

class FormBuilderLocked extends FormBuilder{

    /*-------------------------------------------------------------------------*/

    public function __construct() {
        parent::__construct();
    }

    /*-------------------------------------------------------------------------*/

    public function build_form($action = "verify") {
        echo "<form>";
        $this->build_student_field();
        $this->build_group_field();
        echo "</form>";
    }

    /*-------------------------------------------------------------------------*/


    protected function build_student_input($name, $type) {
        $value = $_POST[$name];
        return "<input type=\"$type\" name=\"$name\" value=\"$value\" required readonly></input>";
    }

    /*-------------------------------------------------------------------------*/

    protected function is_choice_selected($choice, $group) {
        return $choice == $_POST[$group];
    }

    /*-------------------------------------------------------------------------*/

protected function open_choice_select_tag($name) {
    echo "<select disabled name=\"$name\">";
}

    /*-------------------------------------------------------------------------*/
}

?>
