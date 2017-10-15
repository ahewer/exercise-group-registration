<?php

class FormBuilder{

    /*-------------------------------------------------------------------------*/

    public function __construct() {
        $this->configuration = json_decode(file_get_contents("./content/configuration.json"), true);
    }

    /*-------------------------------------------------------------------------*/

    public function build_form($action = "verify") {
        echo <<<END
    <form action="index.php" method="POST">
END;
        $this->build_student_field();
        $this->build_group_field();
        echo <<<END
    <input type="hidden" name="action" value="$action"/>
    <input type="submit" value="Anmelden"/>
    </form>
END;
    }

    /*-------------------------------------------------------------------------*/

    protected function build_student_field() {

        $studentData = $this->configuration["studentData"];

        echo <<<END
    <fieldset class="framed">
    <legend>Persönliche Daten</legend>
<hr/>
END;
        foreach($studentData as $value) {
            $name = $value['name'];
            $type = $value['type'];
            echo <<<END
      <div class="labeled_input">
      <label for="$name">$name:</label>
END;

            if( $type == 'select') {
                $options = $value['options'];
                echo "<select name=\"$name\" id=\"$name\" style=\"display: block; width: 20em;\">";

                foreach($options as $option) {
                    if($option['value'] == 0){
                        echo "<option value=\"${option['value']}\" selected>${option['label']}</option>";
                    }
                    else{
                        echo "<option value=\"${option['value']}\">${option['label']}</option>";
                    }
                }
                echo "</select>";
            }
            else{
                echo $this->build_student_input($name, $type);
            }
            echo "</div>";
        }
        echo "<hr/>";
        echo "</fieldset>";
    }

    /*-------------------------------------------------------------------------*/

    protected function build_group_field() {

        $groups = $this->configuration["groups"];
        $groupLabels = $this->configuration["groupLabels"];
        $choices = $this->configuration["choices"];

        echo <<<END
    <fieldset class="framed" id="groupselection">
    <legend>Übungswunschtermine</legend>
<hr/>
END;
        foreach($groups as $group) {
            $labels = Array();
            foreach($groupLabels as $id) {
              array_push($labels, $group[$id]);
            }
            $label = $group['label'] . " (" . implode(", ", $labels) . ")";
            echo <<<END
      <div class="labeled_group_input">
      <label class="group" for="${group['id']}">
      $label :
      </label>
END;
            $this->open_choice_select_tag($group['id']);
            foreach($choices as $choice) {
                if( $this->is_choice_selected($choice['value'], $group['id']) == true ) {
                    echo "<option value=\"${choice['value']}\" selected>${choice['label']}</option>";
                }
                else{
                    echo "<option value=\"${choice['value']}\">${choice['label']}</option>";
                }
            }
            echo "</select>";
            echo "</div>";
        }
        echo "<hr/>";
        echo "</fieldset>";
    }

    /*-------------------------------------------------------------------------*/

    protected function open_choice_select_tag($name) {
        echo "<select name=\"$name\" id=\"$name\">";
    }

    /*-------------------------------------------------------------------------*/

    protected function is_choice_selected($choice, $group) {
        return $choice == 0;
    }

    /*-------------------------------------------------------------------------*/

    protected function build_student_input($name, $type) {
        return "<input type=\"$type\" name=\"$name\" id=\"$name\" required/>";
    }

    /*-------------------------------------------------------------------------*/

    protected $configuration;

    /*-------------------------------------------------------------------------*/


}

?>
