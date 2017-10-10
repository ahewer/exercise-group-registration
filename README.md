- [Einleitung](#org0c5ec15)
- [Konfigurationsdateien](#orgfe44505)
- [Schritt für Schritt Konfiguration](#orgf4b504c)
  - [Anlegen von Gruppen](#orgd68eb33)
  - [Gruppenbeschriftung](#org72ef736)
  - [Auswahleinstellungen](#orgcaa6ed6)
  - [Registrierungseinschränkungen](#orgabe2d60)
    - [Mindestanzahl an ausgewählten Gruppen](#org4a1812c)
    - [Festlegen von Gruppen, von denen mindestens eine ausgewählt werden muss](#org3e11e4c)
  - [Einstellen der Anmeldedaten](#org2fc4ff8)
  - [Konfiguration für Erzeugung der Punktelisten](#org41ab7ec)
  - [Konfiguration für Notenliste](#orgc8e965f)
- [Einrichtung der Webseite](#org902b1fc)
  - [Voraussetzungen](#org505f8ac)
  - [Einrichtung des Ausgabeverzeichnisses](#orgf5c0099)
  - [Kopieren der nötigen Dateien](#orga9dfab6)
- [Gradle](#orgccde8de)
  - [Voraussetzungen](#org8459f3f)
  - [Herunterladen der Datenbank](#orgd98f95f)



<a id="org0c5ec15"></a>

# Einleitung

Das Framework besteht aus zwei Komponenten: Die Webkomponente erlaubt das Erstellen einer Webseite, auf der sich Studenten zum Übungsbetrieb anmelden können. Die Gradle-Komponente benutzt die Registrierungen, um eine Übungsgruppeneinteilung zu erstellen.


<a id="orgfe44505"></a>

# Konfigurationsdateien

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


<a id="orgf4b504c"></a>

# Schritt für Schritt Konfiguration

Dieser Abschnitt beschreibt die Beispielkonfiguration des Frameworks, die sich in `example_configuration/configuration.json` befindet.


<a id="orgd68eb33"></a>

## Anlegen von Gruppen

Der `groups` Eintrag in der JSON Datei enthält eine Liste aller Gruppen. Ein Gruppeneintrag enthält die folgenden Informationen:

```js
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

```js
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


<a id="org72ef736"></a>

## Gruppenbeschriftung

Hinter dem Gruppenlabel können zusätzliche Informationen angezeigt werden. Dies kann mit `groupLabels` konfiguriert werden:

```js
"groupLabels" : ["location", "time"]
```

Diese Konfiguration generiert zum Beispiel für Gruppe 1 die folgende Gruppenbezeichnung auf der Webseite:

    Gruppe 1 ( SR3, Montag 12:15 - 13:45 )


<a id="orgcaa6ed6"></a>

## Auswahleinstellungen

Bei der Anmeldung zu den Gruppen müssen Studenten ihre Präferenzen angeben. Mögliche Präferenzen werden als Liste im `choices` Eintrag festgelegt. Eine Präferenzdefinition besteht aus zwei Teilen:

```js
{
    "label" : "unmöglich",
    "value" : 0
}
```

-   `label` : Die angezeigte Bezeichnung für die Präferenz während der Anmeldung.
-   `value` : Der interne Wert, der bei der Optimierung benutzt wird.

Der Wert 0 bedeutet, dass eine Teilnahme nicht möglich ist. Höhere Werte bedeuten eine höhere Präferenz.

Die Beispielkonfiguration enthält 4 mögliche Präferenzen:

```js
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


<a id="orgabe2d60"></a>

## Registrierungseinschränkungen

Es ist möglich, Einschränkungen für die Gruppenanmeldungen festzulegen. Dies kann im \`groupConditions\` Eintrag getan werden, der eine Liste an Bedingungen enthält. Momentan werden zwei Bedingungstypen unterstützt:


<a id="org4a1812c"></a>

### Mindestanzahl an ausgewählten Gruppen

```js
{
    "type" : "atLeast",
    "amount" : 2
}
```

`"amount"` stellt die Gruppenanzahl dar, die der Student mindestens ausgewählt haben muss.


<a id="org3e11e4c"></a>

### Festlegen von Gruppen, von denen mindestens eine ausgewählt werden muss

```js
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


<a id="org2fc4ff8"></a>

## Einstellen der Anmeldedaten

Persönliche Daten, die der Student angeben muss, werden als Liste im `studentData` Feld festgelegt. Ein Eintrag in dieser Liste hat folgende Form:

```js
{ "name": "Vorname", "type": "text" }
```

-   `name` : Bezeichnung für den Eintrag.
-   `type` : Der HTML-Eingabetyp, der verwendet werden soll.

Die Beispielkonfiguration legt folgende Daten fest:

```js
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

```js
"studentId" : "Matrikelnummer"
```


<a id="org41ab7ec"></a>

## Konfiguration für Erzeugung der Punktelisten

Die gradle Komponente kann genutzt werden, um für die einzelnen Übungsgruppen Punktelisten im CSV Format zu erstellen, die später von den Übungsgruppenleitern ausgefüllt werden können. Folgende Informationen können im `exerciseSheets` Eintrag festgelegt werden:

-   `label`: Bezeichnung der Übungsblätter, die durchnummeriert wird
-   `exerciseSheetAmount`: Anzahl der Übungsblätter
-   `studentData`: Studentendaten, die in der CSV Datei auftauchen

Die Beispielkonfiguration legt folgende Einstellungen fest:

```js
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
|---|---|---|---|---|
| Max     | Mustermann | 123456         | 0       | 0       |


<a id="orgc8e965f"></a>

## Konfiguration für Notenliste

Ähnlich wie bei den Punktelisten, kann die gradle Komponente auch eine Notenliste im CSV Format generieren. Folgende Einstellungen sind im `gradeSheet` Eintrag möglich:

-   `studentData`: Studentendaten, die in der Tabelle erscheinen sollen
-   `gradeData`: Noteninformationen, die in die Tabelle eingetragen werden

Die Beispielkonfiguration

```js
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
|---|---|---|---|---|---|
| Max     | Mustermann | 123456         | 01.01.1998   | 1.0  | sehr gut    |


<a id="org902b1fc"></a>

# Einrichtung der Webseite


<a id="org505f8ac"></a>

## Voraussetzungen

Die Webkomponente hat folgende Voraussetzungen:

-   PHP >= 5.0
-   SQLite Unterstützung in PHP
-   Webserver mit .htaccess Unterstützung


<a id="orgf5c0099"></a>

## Einrichtung des Ausgabeverzeichnisses

Die Anmeldungen werden in einer SQLite Datenbank im Order `output` gespeichert. Es ist wichtig, dass dieser Ordner geschützt wird, um einen Zugriff auf sensible Daten zu verhindern. Dies kann mit [.htaccess](https://de.wikipedia.org/wiki/.htaccess) erreicht werden. 
Bei Aufrufen der Website wird immer überprüft, ob der Ordner `output` existiert und wird bei Bedarf angelegt.
Fernerhin wird eine `.htaccess` Datei generiert, die jeglichen Zugriff auf den Ordner blockiert.

<a id="orga9dfab6"></a>

## Kopieren der nötigen Dateien

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
├── output
│   └── database.db
```

gespeichert.


<a id="orgccde8de"></a>

# Gradle


<a id="org8459f3f"></a>

## Voraussetzungen


<a id="orgd98f95f"></a>

## Herunterladen der Datenbank
