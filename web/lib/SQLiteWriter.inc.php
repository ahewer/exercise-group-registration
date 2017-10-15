<?php

class SQLiteWriter{

    /*-------------------------------------------------------------------------*/

    public function __construct() {

        $this->configuration = json_decode(file_get_contents("./content/configuration.json"), true);

    }

    /*-------------------------------------------------------------------------*/

    public function write() {

      $db = new Database();
      $entry = $this->build();
      $entryString = SQLite3::escapeString(json_encode($entry));
      $id = $entry["studentData"][$this->configuration["studentId"]];

      $db->exec(
          "INSERT OR REPLACE INTO Registrations (id, data) VALUES ($id, '$entryString');"
          );

      $db->close();

    }

    /*-------------------------------------------------------------------------*/


    private function build() {

        $studentData = [];

        // output student data
        $studentFields = $this->configuration["studentData"];

        foreach($studentFields as $field) {
            $key = $field["name"];
            $value = $_POST[$field["name"]];

            if( $field["type"] == "date" ) {
                $date = date_parse($value);
                $value = $date["day"] . "." . $date["month"] . "." . $date["year"];
            }

            if( $field["type"] == "number" ) {
                $value = intval($value);
            }
            
            if( $field["type"] == "select") {
                $value = intval($value); 
            }

            $studentData[$key] = $value;

        }

        $groupChoices = [];

        $groups = $this->configuration["groups"];

        foreach($groups as $group) {
            $value = $_POST[$group['id']];
            $value = intval($value);
            array_push($groupChoices, $value);
        }

        $entry = array(
          "studentData" => $studentData,
          "groupChoices" => $groupChoices
          );

        return $entry;

    }

    /*-------------------------------------------------------------------------*/

    private $configuration;

    /*-------------------------------------------------------------------------*/
}

?>
