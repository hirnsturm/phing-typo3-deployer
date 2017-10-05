# Phing TYPO3 Deployer
*[Copyright 2017 by Steve Lenz](./LICENSE)*

## Inhalt

* [Einleitung](#einleitung)
    * [Was ist das Ziel dieses Pakets?](#was-ist-das-ziel-dieses-pakets)
    * [Voraussetzungen](#voraussetzungen)
    * [Projektstruktur](#projektstruktur)
* [Einrichtung eines neuen Projekts](#einrichtung-eines-neuen-projekts)
* [Einrichtung des Projekts auf dem Zielsystem](#einrichtung-des-projekts-auf-dem-zielsystem)
    * [Einrichtung für die automatisierte Veröffentlichung](#einrichtung-für-die-automatisierte-veröffentlichung)
* [Hooks](#hooks)
* [Properties](#properties)
    * [Globale Properties](#globale-properties)
    * [Eigene Properties](#eigene-properties)
* [FAQ](#faq)
    * [Verfügbare Befehle](#verfügbare-befehle)
    * [Auflistung aller verfügbaren Kommandos](#auflistung-aller-verfügbaren-kommandos)
    * [Lokale Entwicklung](#lokale-entwicklung)
    * [Wie erstellt man ein neues Release?](#wie-erstellt-man-ein-neues-release)
    * [Jenkins Projekt Konfiguration](#jenkins-projekt-konfiguration)
    * [RSYNC Konfiguration](#rsync-konfiguration)
* [Todo](#todo)

## Einleitung

### Was ist das Ziel dieses Pakets?
*Phing Typo3 Deployer* basiert auf dem Build-Tool [Phing](https://www.phing.info/) und dient zum automatisierten Veröffentlichen, i.S.v. Continuous Intregration und Continuous Depoyment, von TYPO3 CMS Instanzen.

### Voraussetzungen

* PHP 7
* TYPO3 CMS 7 || 8 im Composer-Mode
* Kommandozeile
* Unix or Linux
* Ausführung mit den nötigen Berechtigungen des Web-Users (i.d.R. www-data)

### Projektstruktur

Diese Tool benötigt und generiert folgende Verzeichnisstruktur und Dateien:

```bash
bin/
releases/
    current/
    previous/
    next/
shared/
typo3/
    composer.json
vendor/
.gitignore
build.custom.properties
build.env.properties
build.hook.xml
build.xml
composer.json
composer.lock
```

## Einrichtung eines neuen Projekts

1. *composer.json* im Webroot (htdocs-Verzeichnis) des Projekts erstellen

    ```json
    {
      "name": "my/typo3-project",
      "license": "MIT",
      "description": "",
      "type": "project",
      "require": {
        "sle/phing-typo3-deployer": "^0.1"
      },
      "config": {
        "vendor-dir": "vendor",
        "bin-dir": "bin"
      },
      "minimum-stability": "stable",
      "scripts": {
        "post-install-cmd": [
          "Sle\\PhingTypo3Deployer\\Composer\\Scripts::postInstall"
        ],
        "post-update-cmd": [
          "Sle\\PhingTypo3Deployer\\Composer\\Scripts::postUpdate"
        ]
      }
    }
    ```

2. Composer-Pakete installieren

    ```bash
    $ composer install
    ```

    Bei der Installation wird folgende Projektstruktur generiert:

    ```bash
    bin/
    vendor/
    typo3/
        composer.json
    .gitignore
    build.custom.properties
    build.env.properties
    build.hook.xml
    build.xml
    composer.json
    composer.lock
    ```

3. Initialisierung des Projekts und der Umgebungskonfiguration

    ```bash
    $ bin/phing init
    ```

4. Dateien zur Versionkonstrolle hinzufügen

    Folgende Dateien müssen zur Versionskontrolle hinzugefügt werden:

    ```bash
    .gitignore
    typo3/
        composer.json
    build.custom.properties
    build.hook.xml
    composer.json
    composer.lock
    ```

    Hinzufügen mit git:

    ```bash
    $ git add -A && git commit -m '[Build] Add phing-typo3-deployer'
    ```

## Einrichtung des Projekts auf dem Zielsystem

Unter dem Begriff *Zielsystem* wird hier ein Webserver oder Ähnliches verstanden.

Für die korrekte Einrichtung auf dem Zielsystem sind folgende Schritte erforderlich:

1. Projektdatein auf das Zielsystem kopieren

    Die Projektdateien bzw. Verzeichnisse, die sich in der Versionskontrolle befinden, müssen auf das
    Zielsystem kopiert werden. Die Verzeichnisstruktur sieht nun wie folgt aus:

    ```bash
    .gitignore -> Muss nicht mit auf dem Zielsystem installiert werden
    typo3/
        composer.json
        composer.lock
    build.custom.properties
    build.hook.xml
    composer.json
    composer.lock
    ```

2. Composer-Pakete installieren

    ```bash
    $ composer install
    ```

3. Erstes Release veröffentlichen

    Das Verzeichnis mit der akteullen Version befindet sich unter *releases/current*.
    damit dieses erstellt werden kann, muss folgender Befehl ausgeführt werden:

    ```bash
    $ bin/phing ci:release
    ```

    Debei werden folgende Datein und Verzeichnisse erstellt:

    ```bash
    releases/
        current/
    build.env.properties
    ```

    In das Verzeichnis *releases/current/* werden die Daten aus dem Verzeichnis *typo3* Kompiert und *composer install* ausgeführt.
    In der Datei *build.env.properties* können die Umgebungsvariablen ggf. umkonfiguriert werden.

4. vHost konfigurieren

    Der vHost muss auf das Verzeichnis *<project-root>/releases/current/web* zeigen.

5. TYPO3 CMS auf dem Zielsystem installieren

    Nun muss das TYPO3 CMS auf dem Zielsystem initial installiert werden. Die kann über
    den TYPO3 Install Wizard erfolgen.

6. Zentrale Ablage der gemeinsamen Dateien

    Nun werden die gemeinsamen Dateien (shared data) zentral abgelegt:

    ```bash
    $ bin/phing init:shared
    ```

    Hierbei werden die *LocalConfiguration.php* (ggf. auch *PackageStates.php*) sowie die Verzeichnisse *fileadmin* und *uploads* in das Verzeichnis *shared* kopiert, da diese immer gleich bleiben und somit für zukünftigen Releases zentral zur Verfügung stehen. Zudem werden die originale durch Symlinks ersetzt.

### Einrichtung für die automatisierte Veröffentlichung

Die automatierte Aktualisierung des Projekts kann über Git und Jenkins erfolgen.
Bei der Synchronisation müssen folgende Dateien aktualisiert werden:

    ```bash
    typo3/
    build.custom.properties
    build.hook.xml
    composer.json
    composer.lock
    ```

## Hooks
Alle Verfügbaren Hooks befinden sich in der Datei `build.hook.xml`.

## Properties

### Globale Properties

```
#
# This file contains basic build configurations
#
# (c) 2017, Steve Lenz
#
config.build_hook_xml = ${base.dir}/build.hook.xml

release.dir = ${base.dir}/releases
release.current = ${release.dir}/current
release.previous  = ${release.dir}/previous
release.next = ${release.dir}/next

#
# Next
#
release.next.web = ${release.next}/web
release.next.typo3conf = ${release.next.web}/typo3conf
release.next.LocalConfiguration_php = ${release.next.typo3conf}/LocalConfiguration.php
release.next.PackageStates_php = ${release.next.typo3conf}/PackageStates.php
release.next.fileadmin = ${release.next.web}/fileadmin
release.next.uploads = ${release.next.web}/uploads

#
# Current
#
release.current.web = ${release.current}/web
release.current.typo3conf = ${release.current.web}/typo3conf
release.current.LocalConfiguration_php = ${release.current.typo3conf}/LocalConfiguration.php
release.current.PackageStates_php = ${release.current.typo3conf}/PackageStates.php
release.current.fileadmin = ${release.current.web}/fileadmin
release.current.uploads = ${release.current.web}/uploads

#
# Shared
#
shared.dir = ${base.dir}/shared
shared.typo3conf = ${shared.dir}/typo3conf
shared.typo3conf.LocalConfiguration_php = ${shared.typo3conf}/LocalConfiguration.php
shared.typo3conf.PackageStates_php = ${shared.typo3conf}/PackageStates.php
shared.fileadmin = ${shared.dir}/fileadmin
shared.uploads = ${shared.dir}/uploads

#
# TYPO3
#
typo3.dir = ${base.dir}/typo3
typo3.composer.json = composer.json
config.typo3.env = ${typo3.dir}/.env
typo3.web.dir = ${typo3.dir}/web

```

### Eigene Properties

Eigene Properties können in der Datei `build.custom.properties` hinterlegt werden und stehen in den Hooks zur Verfügung.

## FAQ

### Verfügbare Befehle

#### Auflistung aller verfügbaren Kommandos

    ```bash
    $ bin/phing
    ```

### Lokale Entwicklung
Die lokale Entwicklung findet im Verzeichnis *htdocs/typo3* statt.
Die komplette Verzeichnisstruktur nach der installation sieht wie folgt aus:

```bash
bin/
typo3/
    vendor/
    web/
    composer.json
    composer.lock
vendor/
.gitignore
build.custom.properties
build.env.properties
build.hook.xml
build.xml
composer.json
composer.lock
```

Der vHost sollte auf *typo3/web* zeigen.

### Wie erstellt man ein neues Release?

```bash
$ bin/phing ci:release
```

### Jenkins Projekt Konfiguration

* Folgende Dateien müssen synchronisiert werden

    ```bash
    typo3/
    build.custom.properties
    build.hook.xml
    composer.json
    composer.lock
    ```

* Neues Release mit Hilfe der Remote-Shell erstellen

   ```bash
   $ ssh <user>@<server>
   <server>$ cd <webroot>/<project>
   <server>/<webroot>/<project>$ composer install
   <server>/<webroot>/<project>$ bin/phing ci:release
   ```

### RSYNC Konfiguration

```bash
# Sync
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/typo3 <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/layoutbuilder <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/build.custom.properties <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/build.custom.properties
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/build.hook.xml <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/build.hook.xml
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/composer.json <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/composer.json
rsync --delete -aze ssh --iconv=UTF-8 $WORKSPACE/composer.lock <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/composer.lock
# Excludes
rsync --exclude=.gitignore -aze ssh --iconv=UTF-8 $WORKSPACE <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
rsync --exclude=build.env.properties -aze ssh --iconv=UTF-8 $WORKSPACE/htdocs <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
rsync --exclude=shared -aze ssh --iconv=UTF-8 $WORKSPACE <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
rsync --exclude=releases -aze ssh --iconv=UTF-8 $WORKSPACE <USER>@<SERVER>:/<WEB_ROOT>/<PROJECT>/
```

## Todo
- Rollback implementieren
- Prozesse visualisieren
