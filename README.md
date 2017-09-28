- [Einleitung](#org09f5895)
- [Konfigurationsdateien](#org0f9804f)
- [Schritt für Schritt Konfiguration](#orge34053b)
  - [Anlegen von Gruppen](#org2f74ab3)
  - [Gruppenanzeige](#org3a0144c)
  - [Auswahleinstellungen](#orgb2d6c0a)
  - [Registrierungseinschränkungen](#org7159371)
    - [Mindestanzahl an ausgewählten Gruppen](#org10964f3)
    - [Festlegen von Gruppen, von denen mindestens eine ausgewählt werden muss](#orgc853bb2)
  - [Einstellen der Anmeldedaten](#orgf4b604c)



<a id="org09f5895"></a>

# Einleitung

Das Framework besteht aus zwei Komponenten: Die Webkomponente erlaubt das Erstellen einer Webseite, auf der sich Studenten zum Übungsbetrieb anmelden können. Die Gradle-Komponente benutzt die Registrierungen, um eine Übungsgruppeneinteilung zu erstellen.


<a id="org0f9804f"></a>

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


<a id="orge34053b"></a>

# Schritt für Schritt Konfiguration

Dieser Abschnitt beschreibt die Beispielkonfiguration des Frameworks, die sich in \`example\_configuration/configuration.json\` befindet.


<a id="org2f74ab3"></a>

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


<a id="org3a0144c"></a>

## Gruppenanzeige

Hinter dem Gruppenlabel können zusätzliche Informationen angezeigt werden. Dies kann mit `groupLabels` konfiguriert werden:

```js
"groupLabels" : ["location", "time"]
```

Diese Konfiguration generiert zum Beispiel für Gruppe 1 die folgende Gruppenbezeichnung auf der Webseite:

    Gruppe 1 ( SR3, Montag 12:15 - 13:45 )


<a id="orgb2d6c0a"></a>

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


<a id="org7159371"></a>

## Registrierungseinschränkungen

Es ist möglich, Einschränkungen für die Gruppenanmeldungen festzulegen. Dies kann im \`groupConditions\` Eintrag getan werden, der eine Liste an Bedingungen enthält. Momentan werden zwei Bedingungstypen unterstützt:


<a id="org10964f3"></a>

### Mindestanzahl an ausgewählten Gruppen

```js
{
    "type" : "atLeast",
    "amount" : 2
}
```

`"amount"` stellt die Gruppenanzahl dar, die der Student mindestens ausgewählt haben muss.


<a id="orgc853bb2"></a>

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


<a id="orgf4b604c"></a>

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