<?php

include "thirdparty/Parsedown.php";


class Builder{

    /*-------------------------------------------------------------------------*/

    public function build() {

        echo <<<END
<!DOCTYPE html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>Anmeldung zu den Übungen</title>
    <link rel="stylesheet" type="text/css" href="style/style.css"/>
  </head>
  <body>
  <div id="content">
  <div class="framed">
END;

  $markdown = file_get_contents("./content/description.md");
  $Parsedown = new Parsedown();
  echo $Parsedown->text($markdown);

        echo "</div>";

        switch($_POST['action']) {

        case "verify":
            $this->build_verify();
            break;
        case "submit":
            $this->build_start();
            break;
         
        default:
            $this->build_start();
        }

        echo <<<END
</div>
</body>
</html>
END;
    }

    /*-------------------------------------------------------------------------*/

    public function build_start() {
        $formBuilder = new FormBuilder();
        $formBuilder->build_form();
    }

    /*-------------------------------------------------------------------------*/

    public function build_verify() {
        $verifier = new Verifier();

        $studentDataState = $verifier->verify_student_data(); 
        $groupChoicesState = $verifier->verify_group_choices();

        if($studentDataState == false) {
            echo "<div class=\"warning\"><center><strong>Bitte füllen Sie die persönlichen Daten vollständig aus. </strong></center></div>";
            $formBuilder = new FormBuilderDisplay();
            $formBuilder->build_form("verify");
        }
        else if($groupChoicesState != "GROUPS_OK") {
            switch($groupChoicesState) {
            case "GROUPS_AT_LEAST_ERROR":
                echo "<div class=\"warning\"><center><strong>Bitte wählen Sie mindestens die geforderte Anzahl an Terminen aus. </strong></center></div>";
                break;
            case "GROUPS_AT_LEAST_ONE_ERROR":
                echo "<div class=\"warning\"><center><strong>Wählen sie mindestens einen der geforderten Termine aus. </strong></center></div>";
                break;
            }
            $formBuilder = new FormBuilderDisplay();
            $formBuilder->build_form("verify");
        }
        else{

        echo <<<END
            <div class="framed">
            <center><strong>Vielen Dank für Ihre Anmeldung!</strong></center>
            <center>Die unten stehenden Daten wurden gespeichert.</center>
            </div>
END;

            $sqlWriter = new SQLiteWriter();
            $sqlWriter->write();

            $formBuilder = new FormBuilderLocked();
            $formBuilder->build_form();

        }
    }

    /*-------------------------------------------------------------------------*/
}

?>
