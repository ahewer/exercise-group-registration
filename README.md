## Framework zur Übungsgruppeneinteilung

### Einleitung

Das Framework besteht aus zwei Komponenten:
Die Webkomponente erlaubt das Erstellen einer Webseite, auf der sich Studenten zum Übungsbetrieb anmelden können.
Die Gradle-Komponente benutzt die Registrierungen, um eine Übungsgruppeneinteilung zu erstellen.

### Konfigurationsdateien

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

- `configuration.json` : Diese JSON Datei enthält die gesamte Konfiguration des Frameworks.
- `description.md` : In dieser Markdown Datei können Informationen zu Anmeldung verfasst werden, die auf der Webseite angezeigt werden.
- `style.css` : Ein CSS Stylesheet, um die Webseite zu formatieren.

### Schritt für Schritt Konfiguration

Dieser Abschnitt beschreibt die Beispielkonfiguration des Frameworks, die sich in `example_configuration/configuration.json` befindet.

#### Anlegen von Gruppen

Das `groups` Eintrag in der JSON Datei enthält eine Liste aller Gruppen.
Ein Gruppeneintrag enthält die folgenden Informationen:

```json
{
    "id" : "Gruppe_1",
    "location" : "SR 3",
    "time" : "Montag 12:15 - 13:45",
    "label": "Gruppe 1",
    "capacity": 15
}
```

- `id` : Eine eindeutige ID für die Gruppe.
- `location` : Der Ort der Übung.
- `time` : Termin der Übung.
- `label` : Gruppenbezeichnung, der auf der Webseite und in den generierten Dateien angezeigt wird.
- `capacity` : Die Kapazität der Gruppe, wird bei der Optimierung der Gruppeneinteilung verwendet.

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

#### Gruppenanzeige

Hinter dem Gruppenlabel können zusätzliche Informationen angezeigt werden.
Dies kann mit `groupLabels` konfiguriert werden:

```json
    "groupLabels" : ["location", "time"]
```

Diese Konfiguration generiert zum Beispiel für Gruppe 1 die folgende Gruppenbezeichnung auf der Webseite: `Gruppe 1 ( SR3, Montag 12:15 - 13:45 )`.

#### Auswahleinstellungen

Bei der Anmeldung zu den Gruppen müssen Studenten ihre Präferenzen angeben.
Mögliche Präferenzen werden als Liste im `choices` Eintrag festgelegt.
Eine Präferenzdefinition besteht aus zwei Teilen:

```json
{
    "label" : "unmöglich",
    "value" : 0
}
```

- `label` : Die angezeigte Bezeichnung für die Präferenz während der Anmeldung.
- `value` : Der interne Wert, der bei der Optimierung benutzt wird.

Der Wert 0 bedeutet, dass eine Teilnahme nicht möglich ist.
Höhere Werte bedeuten eine höhere Präferenz.

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

#### Registrierungseinschränkungen

Es ist möglich, Einschränkungen für die Gruppenanmeldungen festzulegen.
Dies kann im `groupConditions` Eintrag getan werden, der eine Liste an Bedingungen enthält.
Momentan werden zwei Bedingungstypen unterstützt:

##### Mindestanzahl an möglichen Gruppen

```json
{
    "type" : "atLeast",
    "amount" : 2
}
```

`"amount"` stellt die Gruppenanzahl dar, die der Student mindestens ausgewählt haben muss.

##### Auswahl bestimmter Gruppen

```json
{
    "type": "atLeastOne",
    "of" : ["Gruppe_1", "Gruppe_4"]
}
```

`of` enthält eine Liste von Labels, die zu Gruppen gehören, zu denen der Student sich anmelden muss.

In der Beispielkonfiguration werden beide Bedingungen genutzt:

```json
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
