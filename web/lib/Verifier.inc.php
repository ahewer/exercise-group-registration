<?php

class Verifier{

    /*-------------------------------------------------------------------------*/

    public function __construct() {
        $this->configuration = json_decode(file_get_contents("./content/configuration.json"), true);

    }

    /*-------------------------------------------------------------------------*/


    public function verify_student_data() {

        $studentData = $this->configuration["studentData"];

        foreach($studentData as $value) {

            if($value["type"] == 'select') {
                continue;
            }

            if( isset( $_POST[$value["name"]]) == false || $_POST[$value["name"]] == "") {
                return false;
            }
        }

        return true;
    }

    /*-------------------------------------------------------------------------*/

    public function verify_group_choices() {

        $groups = $this->configuration["groups"];
        $conditions = $this->configuration["groupConditions"];

        foreach($conditions as $condition) {
            switch($condition["type"]) {

            case "atLeast":
                if($this->verify_at_least($condition["amount"]) == false) {
                    return "GROUPS_AT_LEAST_ERROR";
                }
                break;
            case "atLeastOne":
                if($this->verify_at_least_one($condition["of"]) == false) {
                    return "GROUPS_AT_LEAST_ONE_ERROR";
                }
                break;
            }
        }

        return "GROUPS_OK";

    }

    /*-------------------------------------------------------------------------*/

    private function verify_at_least($amount) {

        $groups = $this->configuration["groups"];

        $positiveSelections = 0;

        foreach($groups as $group) {
            if( isset($_POST[$group['id']]) == false) {
                return false;
            }

            if( $_POST[$group['id']] != '0') {
                ++$positiveSelections;
            }
        }

        return $positiveSelections >= $amount;

    }

    /*-------------------------------------------------------------------------*/

    private function verify_at_least_one($of) {

        foreach($of as $id) {

            if( isset($_POST[$id]) == false )  {
                return false;
            }

            if( $_POST[$id] != '0') {
                return true;
            }

        }

        return false;

    }

    /*-------------------------------------------------------------------------*/

    private $configuration;

    /*-------------------------------------------------------------------------*/
}

?>
