- [Einleitung](#sec-1)
- [Konfigurationsdateien](#sec-2)
- [Schritt für Schritt Konfiguration](#sec-3)
  - [Anlegen von Gruppen](#sec-3-1)
  - [Gruppenbeschriftung](#sec-3-2)
  - [Auswahleinstellungen](#sec-3-3)
  - [Registrierungseinschränkungen](#sec-3-4)
    - [Mindestanzahl an ausgewählten Gruppen](#sec-3-4-1)
    - [Festlegen von Gruppen, von denen mindestens eine ausgewählt werden muss](#sec-3-4-2)
  - [Einstellen der Anmeldedaten](#sec-3-5)
  - [Konfiguration für Erzeugung der Punktelisten](#sec-3-6)
  - [Konfiguration für Notenliste](#sec-3-7)
- [Einrichtung der Webseite](#sec-4)
  - [Voraussetzungen](#sec-4-1)
  - [Einrichtung des Ausgabeverzeichnisses](#sec-4-2)
  - [Kopieren der nötigen Dateien](#sec-4-3)
- [Gradle](#sec-5)
  - [Voraussetzungen](#sec-5-1)
  - [Vorbereitungen](#sec-5-2)
  - [Erzeugung der Ergebnisse](#sec-5-3)
    - [Punktelisten](#sec-5-3-1)
    - [Notenliste](#sec-5-3-2)
    - [Gruppeneinteilungen](#sec-5-3-3)
  - [Analyse der Ergebnisse](#sec-5-4)
  - [Manuelle Anpassung der Gruppeneinteilung](#sec-5-5)


# Einleitung<a id="sec-1"></a>

Das Framework besteht aus zwei Komponenten: Die Webkomponente erlaubt das Erstellen einer Webseite, auf der sich Studenten zum Übungsbetrieb anmelden können. Die Gradle-Komponente benutzt die Registrierungen, um eine Übungsgruppeneinteilung zu erstellen.

# Konfigurationsdateien<a id="sec-2"></a>

Folgende Dateien erlauben die Konfiguration des Frameworks:

```sh
.
├── gradle
│   └── resources
│   	└── configuration.json
└── web
    ├── content
    │   └── description.md
    │   └── configuration.json
    └── style
        └── style.css
```

-   `configuration.json` : Diese JSON Datei enthält die gesamte Konfiguration des Frameworks.
-   `description.md` : In dieser Markdown Datei können Informationen zu Anmeldung verfasst werden, die auf der Webseite angezeigt werden.
-   `style.css` : Ein CSS Stylesheet, um die Webseite zu formatieren.

# Schritt für Schritt Konfiguration<a id="sec-3"></a>

Dieser Abschnitt beschreibt die Beispielkonfiguration des Frameworks, die sich in `example_configuration/configuration.json` befindet.

## Anlegen von Gruppen<a id="sec-3-1"></a>

Der `groups` Eintrag in der JSON Datei enthält eine Liste aller Gruppen. Ein Gruppeneintrag enthält die folgenden Informationen:

```json
{
    "id" : "Gruppe_1",
    "location" : "SR 3",
    "time" : "Montag 12:15 - 13:45",
    "label": "Gruppe 1",
    "capacity": 15
}

```

-   `id` : Eine eindeutige ID für die Gruppe.
-   `location` : Der Ort der Übung.
-   `time` : Termin der Übung.
-   `label` : Gruppenbezeichnung, der auf der Webseite und in den generierten Dateien angezeigt wird.
-   `capacity` : Die Kapazität der Gruppe, wird bei der Optimierung der Gruppeneinteilung verwendet.

Die Beispielkonfiguration enthält 5 Gruppen:

```json
"groups" :
[
    {
        "id" : "Gruppe_1",
        "location" : "SR 3",
        "time" : "Montag 12:15 - 13:45",
        "label": "Gruppe 1",
        "capacity": 15
    },
    {
        "id" : "Gruppe_2",
        "location" : "SR 2",
        "time" : "Montag 12:15 - 13:45",
        "label": "Gruppe 2",
        "capacity": 15
    },
    {
        "id" : "Gruppe_3",
        "location" : "SR 1",
        "time" : "Montag 14:15 - 15:45",
        "label": "Gruppe 3",
        "capacity": 15
    },
    {
        "id" : "Gruppe_4",
        "location" : "HS 1",
        "time" : "Montag 10:15 - 11:45",
        "label": "Gruppe 4",
        "capacity": 15
    }
]
```

## Gruppenbeschriftung<a id="sec-3-2"></a>

Hinter dem Gruppenlabel können zusätzliche Informationen angezeigt werden. Dies kann mit `groupLabels` konfiguriert werden:

```json
"groupLabels" : ["location", "time"]
```

Diese Konfiguration generiert zum Beispiel für Gruppe 1 die folgende Gruppenbezeichnung auf der Webseite:

    Gruppe 1 ( SR3, Montag 12:15 - 13:45 )

## Auswahleinstellungen<a id="sec-3-3"></a>

Bei der Anmeldung zu den Gruppen müssen Studenten ihre Präferenzen angeben. Mögliche Präferenzen werden als Liste im `choices` Eintrag festgelegt. Eine Präferenzdefinition besteht aus zwei Teilen:

```json
{
    "label" : "unmöglich",
    "value" : 0
}
```

-   `label` : Die angezeigte Bezeichnung für die Präferenz während der Anmeldung.
-   `value` : Der interne Wert, der bei der Optimierung benutzt wird.

Der Wert 0 bedeutet, dass eine Teilnahme nicht möglich ist. Höhere Werte bedeuten eine höhere Präferenz.

Die Beispielkonfiguration enthält 4 mögliche Präferenzen:

```json
"choices" :
[
    {
        "label" : "unmöglich",
        "value" : 0
    },
    {
        "label" : "passt notfalls",
        "value" : 1
    },
    {
        "label" : "passt",
        "value" : 2
    },
    {
        "label" : "passt sehr gut",
        "value" : 3
    }
]
```

## Registrierungseinschränkungen<a id="sec-3-4"></a>

Es ist möglich, Einschränkungen für die Gruppenanmeldungen festzulegen. Dies kann im \`groupConditions\` Eintrag getan werden, der eine Liste an Bedingungen enthält. Momentan werden zwei Bedingungstypen unterstützt:

### Mindestanzahl an ausgewählten Gruppen<a id="sec-3-4-1"></a>

```json
{
    "type" : "atLeast",
    "amount" : 2
}
```

`"amount"` stellt die Gruppenanzahl dar, die der Student mindestens ausgewählt haben muss.

### Festlegen von Gruppen, von denen mindestens eine ausgewählt werden muss<a id="sec-3-4-2"></a>

```json
{
    "type": "atLeastOne",
    "of" : ["Gruppe_1", "Gruppe_4"]
}
```

`` `of` `` enthält eine Liste von Labels, die zu Gruppen gehören, von denen der Student mindestens eine auswählen muss.

In der Beispielkonfiguration werden beide Bedingungen genutzt:

```js
"groupConditions" :
[
    {
        "type" : "atLeast",
        "amount" : 2
    },
    {
        "type": "atLeastOne",
        "of" : ["Gruppe_1", "Gruppe_4"]
    }
]
```

## Einstellen der Anmeldedaten<a id="sec-3-5"></a>

Persönliche Daten, die der Student angeben muss, werden als Liste im `studentData` Feld festgelegt. Ein Eintrag in dieser Liste hat folgende Form:

```json
{ "name": "Vorname", "type": "text" }
```

-   `name` : Bezeichnung für den Eintrag.
-   `type` : Der HTML-Eingabetyp, der verwendet werden soll.

Die Beispielkonfiguration legt folgende Daten fest:

```json
"studentData" :
[
    { "name": "Vorname", "type": "text" },
    { "name": "Nachname", "type": "text" },
    { "name": "Geburtsdatum", "type": "date" },
    { "name": "E-Mail", "type": "email" },
    { "name": "Matrikelnummer", "type": "number" },
    { "name": "Fach", "type": "text" },
    { "name": "Fachsemester", "type": "number" },
    { "name": "Studiensemester", "type": "number" }
]
```

Eines dieser Eingabedaten muss als eindeutige Identifikation des Studenten in der Datenbank verwendet werden. Dies geschieht im `studentID` Feld der JSON Datei. In unserer Beispielkonfiguration benutzen wir die *Matrikelnummer* als ID:

```json
"studentId" : "Matrikelnummer"
```

## Konfiguration für Erzeugung der Punktelisten<a id="sec-3-6"></a>

Die gradle Komponente kann genutzt werden, um für die einzelnen Übungsgruppen Punktelisten im CSV Format zu erstellen, die später von den Übungsgruppenleitern ausgefüllt werden können. Folgende Informationen können im `exerciseSheets` Eintrag festgelegt werden:

-   `label`: Bezeichnung der Übungsblätter, die durchnummeriert wird
-   `exerciseSheetAmount`: Anzahl der Übungsblätter
-   `studentData`: Studentendaten, die in der CSV Datei auftauchen

Die Beispielkonfiguration legt folgende Einstellungen fest:

```json
"exerciseSheets" : {
    "label" : "Blatt",
    "exerciseSheetAmount" : 10,
    "studentData" : [
        "Vorname", "Nachname", "Matrikelnummer"
    ]
}
```

Ein Ausschnitt der erzeugten Tabelle sieht dann wie folgt aus:

| Vorname | Nachname   | Matrikelnummer | Blatt 1 | Blatt 2 |
|------- |---------- |-------------- |------- |------- |
| Max     | Mustermann | 123456         | 0       | 0       |

## Konfiguration für Notenliste<a id="sec-3-7"></a>

Ähnlich wie bei den Punktelisten, kann die gradle Komponente auch eine Notenliste im CSV Format generieren. Folgende Einstellungen sind im `gradeSheet` Eintrag möglich:

-   `studentData`: Studentendaten, die in der Tabelle erscheinen sollen
-   `gradeData`: Noteninformationen, die in die Tabelle eingetragen werden

Die Beispielkonfiguration

```json
"gradeSheet" : {
    "studentData" : [
        "Vorname", "Nachname", "Matrikelnummer", "Geburtsdatum"
    ],
    "gradeData" : [
        "Note", "Bezeichnung"
    ]
}
```

würde eine Tabelle im folgenden Format erzeugen:

| Vorname | Nachname   | Matrikelnummer | Geburtsdatum | Note | Bezeichnung |
|------- |---------- |-------------- |------------ |---- |----------- |
| Max     | Mustermann | 123456         | 01.01.1998   | 1.0  | sehr gut    |

# Einrichtung der Webseite<a id="sec-4"></a>

## Voraussetzungen<a id="sec-4-1"></a>

Die Webkomponente hat folgende Voraussetzungen:

-   PHP >= 5.0
-   SQLite Unterstützung in PHP
-   Webserver mit .htaccess Unterstützung

## Einrichtung des Ausgabeverzeichnisses<a id="sec-4-2"></a>

Die Anmeldungen werden in einer SQLite Datenbank im Order `output` gespeichert. Es ist wichtig, dass dieser Ordner geschützt wird, um einen Zugriff auf sensible Daten zu verhindern. Dies kann mit [.htaccess](https://de.wikipedia.org/wiki/.htaccess) erreicht werden. Unter Linux würden wir den Schutz folgendermaßen einrichten:

Zuerst erzeugen wir das Ausgabeverzeichnis

```sh
mkdir output
```

Nun legen wir die `.htaccess` Datei an und blockieren jeglichen Zugriff auf den Ordner:

```sh
cd output
touch .htaccess
echo "Require all denied" >> .htaccess
```

Die erzeugte `.htaccess` hat somit den folgenden Inhalt:

```sh
Require all denied
```

Bei Aufrufen der Website wird immer überprüft, ob der Ordner `output` existiert und wird bei Bedarf angelegt. Fernerhin wird dann auch eine `.htaccess` Datei generiert, die wie oben jeglichen Zugriff auf den Ordner blockiert.

## Kopieren der nötigen Dateien<a id="sec-4-3"></a>

Nachdem die Konfiguration abgeschlossen ist, werden folgende Dateien auf den Webserver kopiert, wobei die Verzeichnisstruktur erhalten bleiben muss:

```sh
.
├── content
│   ├── config.json
│   └── description.md
├── index.php
├── lib
│   ├── Builder.inc.php
│   ├── Database.inc.php
│   ├── FormBuilder.inc.php
│   ├── FormBuilderDisplay.inc.php
│   ├── FormBuilderLocked.inc.php
│   ├── SQLiteWriter.inc.php
│   ├── Verifier.inc.php
│   └── thirdparty
│       ├── LICENSE.txt
│       └── Parsedown.php
└── style
    └── style.css
```

Die Seite ist nun erreichbar und Studenten können sich für die Übungsgruppen anmelden. Die Anmeldungen werden in der Datenbank

```sh
.
└── output
    └── database.db
```

gespeichert.

# Gradle<a id="sec-5"></a>

## Voraussetzungen<a id="sec-5-1"></a>

-   Java Version >=7

## Vorbereitungen<a id="sec-5-2"></a>

Um das Gradle Framework nutzen zu können, führen wir folgende Schritte durch: Wir laden zunächst die Datei `database.db` von unserem Webserver herunter und platzieren sie in:

```sh
.
└── gradle
    └── resources
```

Danach speichern wir dort auch die Konfigurationsdatei `configuration.json` ab.

## Erzeugung der Ergebnisse<a id="sec-5-3"></a>

Mit Hilfe des Befehls

```sh
./gradlew build
```

unter Linux oder

```sh
gradlew.bat build
```

unter Windows werden die Ergebnisdateien erstellt:

### Punktelisten<a id="sec-5-3-1"></a>

Punktelisten im CSV Format, die von den einzelnen Übungsgruppenleitern ausgefüllt werden können, befinden sich im Ordner:

```sh
.
└── gradle
    └── build
        └── pointLists
```

### Notenliste<a id="sec-5-3-2"></a>

Eine CSV Datei, die als Notenliste später dienen kann, wird hier abgespeichert:

```sh
.
└── gradle
    └── build
        └── gradeList
            └── gradeSheet.csv
```

### Gruppeneinteilungen<a id="sec-5-3-3"></a>

Eine Markdown Datei, die die gesamte Gruppeneinteilungen beinhaltet, befindet sich in der Datei

```sh
.
└── gradle
    └── build
        └── groupsMarkdown
            └── groups.md
```

Diese Markdown Datei kann zum Beispiel mit Hilfe des Tools [pandoc](https://www.pandoc.org) in andere Formate, wie PDF oder HTML umgewandelt werden.

## Analyse der Ergebnisse<a id="sec-5-4"></a>

Der Aufruf

```sh
./gradlew createAnalysisMarkdown
```

erstellt die Markdown Datei

```sh
.
└── gradle
    └── build
        └── analysis
            └── markdown.md
```

die zahlreiche Informationen zur Einteilung enthält, wie zum Beispiel die Auslastung der einzelnen Gruppen oder Studenten, die keiner Gruppe zugeordnet werden konnten.

## Manuelle Anpassung der Gruppeneinteilung<a id="sec-5-5"></a>

In bestimmten Fällen ist es nötig, dass die Gruppeneinteilung für manche Studenten manuell angepasst werden muss. Diese manuelle Zuteilung kann in der Datei

```sh
.
└── gradle
    └── build
        └── overrides.json
```

festgelegt werden. Diese JSON Datei enthält eine Liste von Einträgen:

```json
[
    { "id" : 123456, "group" : "Gruppe_1" },
    { "id" : 223456, "group" : "Gruppe_2" }
]
```

`id` ist die ID des Studenten, dessen Gruppe angepasst werden soll. `group` ist die ID der Gruppe, die dem Studenten zugeordnet werden soll.
