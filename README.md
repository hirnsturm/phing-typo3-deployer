# Phing TYPO3 Deployer
*[Copyright 2017 by Steve Lenz](./LICENSE)*

![[Tracis CI](https://travis-ci.org/hirnsturm/phing-typo3-deployer)](https://img.shields.io/travis/hirnsturm/phing-typo3-deployer/master.svg)

## Inhalt

* [Einleitung](#introduction)
    * [Was ist das Ziel dieses Pakets?](#introduction-goal)
    * [Voraussetzungen](#introduction-requirements)
    * [Projektstruktur](#introduction-project-structure)
* [Einrichtung eines neuen Projekts](#project-installation)
* [Einrichtung des Projekts auf dem Zielsystem](#project-installation-target-system)
* [Einrichtung für die automatisierte Veröffentlichung](#deployment)
* [Hooks](#hooks)
* [Properties](#properties)
    * [Globale Properties](#properties-global)
    * [Eigene Properties](#properties-custom)
* [FAQ](#faq)
    * [Auflistung aller verfügbaren Kommandos](#faq-list-commands)
    * [Lokale Entwicklung](#faq-local-dev)
    * [Wie erstellt man ein neues Release?](#faq-release)
    * [Jenkins Projekt Konfiguration](#faq-jenkins)
    * [RSYNC](#faq-rsync)
        * [RSYNC Installation](#faq-rsync-installation)
        * [RSYNC Konfiguration](#faq-rsync-config)
    * [Versionskontrolle](#faq-vcs)
* [Todo](#todo)

<a name="introduction"></a>
## Einleitung

<a name="introduction-goal"></a>
### Was ist das Ziel dieses Pakets?

*Phing Typo3 Deployer* basiert auf dem Build-Tool [Phing](https://www.phing.info/) und dient zum automatisierten Veröffentlichen, i.S.v. Continuous Intregration und Continuous Depoyment, von TYPO3 CMS Instanzen.

<a name="introduction-requirements"></a>
### Voraussetzungen

* PHP 7
* TYPO3 CMS 7 || 8 im Composer-Mode
* Kommandozeile
* Unix or Linux
* Ausführung mit den nötigen Berechtigungen des Web-Users (i.d.R. www-data)

<a name="introduction-project-structure"></a>
### Projektstruktur

Dieses Tool benötigt und generiert folgende Verzeichnisstruktur und Dateien:

```bash
bin/
releases/
    current/
    previous/
    next/
rsync/ -> Wird nur generiert, wenn 'install-rsync-excludes' aktiviert ist.
    excludes.txt
shared/
typo3/
    composer.json
vendor/
build.custom.properties
build.env.properties
build.hook.xml
build.xml
composer.json
composer.lock
```

<a name="project-installation"></a>
## Einrichtung eines neuen Projekts

Für die Einrichtung sind lediglich diese drei Schritte notwendig:

1. `composer.json` im Webroot (htdocs-Verzeichnis) des Projekts erstellen

    ```json
    {
      "name": "my/typo3-project",
      "license": "MIT",
      "description": "",
      "type": "project",
      "require": {
        "sle/phing-typo3-deployer": "[VERSION]"
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
      },
      "extra": {
        "sle/phing-typo3-deployer": {
          "install-rsync-excludes": false
        }
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
    build.custom.properties
    build.env.properties
    build.hook.xml
    build.xml
    composer.json
    composer.lock
    ```

3. Dateien zur Versionskontrolle hinzufügen

    Folgende Dateien müssen zur Versionskontrolle hinzugefügt werden:

    ```bash
    rsync/
        excludes.txt
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

<a name="project-installation-target-system"></a>
## Einrichtung des Projekts auf dem Zielsystem

Unter dem Begriff *Zielsystem* wird hier ein Webserver oder Ähnliches verstanden.

Für die korrekte Einrichtung auf dem Zielsystem sind folgende Schritte erforderlich:

1. Projektdatein auf das Zielsystem kopieren

    Die Projektdateien bzw. Verzeichnisse, die sich in der Versionskontrolle befinden, müssen auf das
    Zielsystem kopiert werden. Die Verzeichnisstruktur sieht nun wie folgt aus:

    ```bash
    typo3/
        .env.dist
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

3. Bei einigen vServern (bspw. *Mittwald vServer*) kann Phing den absoluten Pfad nicht korrekt auslesen. Zudem wird PHP
   in der Kommandozeile mit `php_cli` statt mit `php` aufgerufen. Daher können die Umgebungsvariablen, in der nun
   angelegten `build.env.properties`, umkonfiguriert werden.

   * Bsp. *Mittwald vServer* ENV-Konfiguration:

       ```
       # Base directory configuration
       base.dir = /home/www/p<XXXXXX>/html/<project-dir>

       # Commandline configuration
       cmd.php = php_cli
       cmd.composer = composer
       ```

4. Initiales Release veröffentlichen

    Das Verzeichnis mit der akteullen Version befindet sich unter `releases/current/`.
    damit dieses erstellt werden kann, muss folgender Befehl ausgeführt werden:

    ```bash
    $ bin/phing ci:release
    ```
    Siehe auch [Wie erstellt man ein neues Release?](#faq-release)

    > **Achtung!**
    >
    > Ggf. kann es bei der Ausführung auf *Mittwald vServer* dazu kommen, das Phing nicht die `build.xml` finden
    > kann. Hierfür sollte alternativ eine `phing.phar` installiert werden (siehe https://www.phing.info/). Die `phing.phar`
    > sollte idealerweise in das Root-Verzeichnis des Projekt-Reporitories aufgenommen und auch mit deployed werden.
    >
    > ```bash
    > $ php_cli phing.phar ci:release
    > ```

    Dabei werden folgende Datein und Verzeichnisse erstellt:

    ```bash
    releases/
        current/
            bin/
            vendor/
            web/
        previous/ -> Wird ab dem zweiten Release generiert und beinhaltet immer das vorherige Release.
            bin/
            vendor/
            web/
    build.env.properties
    ```

    In das Verzeichnis `releases/current/` werden die Daten aus dem Verzeichnis `typo3` kopiert und `composer install` ausgeführt.

5. vHost konfigurieren

    Der vHost muss auf das Verzeichnis `<project-root>/releases/current/web` zeigen.

6. TYPO3 CMS auf dem Zielsystem installieren

    Nun muss das TYPO3 CMS auf dem Zielsystem initial installiert werden. Dies kann über
    den TYPO3 Install Wizard oder die *typo3console* erfolgen.

    Installation mit Hilfe der *typo3console*:

    ```bash
    releases/current$ bin/typo3cms install:setup
    ```

7. Zentrale Ablage der gemeinsamen Dateien

    Die gemeinsamen Dateien (shared data) müssen nun noch zentral abgelegt werden, damit sie für zukünftige Releases verfügbar sind:

    ```bash
    $ bin/phing init:shared
    ```

    Hierbei werden die `LocalConfiguration.php` sowie die Verzeichnisse `fileadmin` und `uploads` in das Verzeichnis `shared` kopiert, da diese immer gleich bleiben und somit für zukünftigen Releases zentral zur Verfügung stehen. Zudem werden die originale durch Symlinks ersetzt.

    Generierte Verzeichnisstruktur für `shared`:

    ```bash
    releases/
        current/
    shared/
        .env (Wenn typo3/.env.dist vorhanden ist)
        fileadmin/
        uploads/
        typo3conf/
            LocalConfiguration.php
    ```

<a name="deployment"></a>
## Einrichtung für die automatisierte Veröffentlichung

Die automatierte Aktualisierung des Projekts kann über Git und Jenkins erfolgen.
Bei der Synchronisation müssen folgende Dateien aktualisiert werden:

```bash
typo3/
build.custom.properties
build.hook.xml
composer.json
composer.lock
```

<a name="hooks"></a>
## Hooks

Alle Verfügbaren Hooks befinden sich in der Datei `build.hook.xml`.

<a name="properties"></a>
## Properties

<a name="properties-global"></a>
### Globale Properties

Eine Liste der verwendbaren Properties befindet sich in der Datei `src/phing/config/build.properties`.

<a name="properties-custom"></a>
### Eigene Properties

Eigene Properties können in der Datei `build.custom.properties` hinterlegt werden und stehen in den Hooks zur Verfügung.

<a name="faq"></a>
## FAQ

<a name="faq-list-commands"></a>
### Auflistung aller verfügbaren Kommandos

```bash
$ bin/phing
```

<a name="faq-local-dev"></a>
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
build.custom.properties
build.env.properties
build.hook.xml
build.xml
composer.json
composer.lock
```

Der vHost sollte auf `typo3/web` zeigen.

<a name="faq-release"></a>
### Wie erstellt man ein neues Release?

```bash
$ bin/phing ci:release
```

Alternativ kann man das Release über 3 dedizierte Targets erstellen und veröffentlichen lassen. Zwischen den Targets
können eigene Server-Skripte, bspw. Anpassung von Berechtigungen, ausgeführt werden. (Diese drei Targets werden in der
Reihenfolge auch vom `ci:release` ausgeführt)

```bash
$ bin/phing ci:release:create:next
$ bin/phing ci:release:publish:next
$ bin/phing ci:release:post-actions
```

<a name="faq-jekins"></a>
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
   Siehe auch [Wie erstellt man ein neues Release?](#faq-release)


<a name="faq-rsync"></a>
### RSYNC

<a name="faq-rsync-installation"></a>
#### Installation der excludes.txt

Über folgende Composer-Konfiguration kann die `excludes.txt` automatisch installiert werden:

```json
"extra": {
    "sle/phing-typo3-deployer": {
        "install-rsync-excludes": true
    }
}
```
<a name="faq-rsync-config"></a>
#### Konfiguration
```bash
rsync --delete -aze ssh --iconv=UTF-8 --exclude-from $WORKSPACE/rsync/excludes.txt $WORKSPACE/ <user>@<server>:/<webroot>/<project>/
```

In der Datei `rsync/excludes.txt` können die RSYNC-Excludes konfiguriert werden:

```bash
rsync
releases
shared
build.env.properties
build.xml
```

<a name="faq-vcs"></a>
### Versionskontrolle

Folgende Dateien und Verzeichnisse sollten nicht in die Versionskontrolle aufgenommen werden:

```bash
build.env.properties
build.xml
bin
shared
vendor
releases
```

## Todo
- Rollback implementieren
- Prozesse visualisieren
