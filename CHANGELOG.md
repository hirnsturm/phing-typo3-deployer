# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2018-03-13
### Add
- Improved typo3_composer.json scripts configuration

### Remove
- Remove target 'typo3console-before-release' in release process, because this should managed in TYPO3 composer.json

## [1.0.0] - 2018-02-02
### Remove
- 'typo3cms scheduler:run' from target 'typo3console-before-release', because this should be done in custom hook

## [0.5.5] - 2017-12-11
### Changed
- [Doc] Improved documentation
- [Task] Improved composer execution for systems like 'Mittwald vServer'

## [0.5.4] - 2017-12-11
### Changed
- [Bugfix] Wrong path to build.env.properties

## [0.5.3] - 2017-12-11
### Changed
- [Task] Improvement for Servers like 'Mittwald vServer', because they don't show the real absolute directory path
- [Doc] Improved documentation

## [0.5.2] - 2017-11-13
### Added
- [Task] Move .htaccess while ci:release into web-directory

## [0.5.1] - 2017-10-12
### Added
- [Remove] Redundant 'Create Symlink for PackageStates.php' in ci:release

### Changed
- [Bugfix] ci:release calls wrong hook before release

### Removed
- [Doc] Improved documentation

## [0.5.0] - 2017-10-10
### Added
- [Task] RSYNC excludes should be installed by composer extra configuration

## [0.4.0] - 2017-10-09
### Added
- [Task] Add typo3console tasks for ci:release
- [Task] Installation of basic rsync excludes.txt

### Changed
- [Bugfix] Wrong path for 'bin/typo3cms'
- [Bugfix] .gitignore installation fails, because file 'src/phing/dist/_.gitignore' does not exists
- [Task] Improved installer with better check for targets
- [Doc] Fix misspelling

## [0.3.0] - 2017-10-06
### Added
- [Task] Add TYPO3 composer.json and installation if not exists
- [Task] Improved composer.json settings
- [Task] Use dirname to generate dist path

### Changed
- [Task] Improved installation guide
- [Task] Set composer type as 'project'

## [0.2.0] - 2017-10-05
### Added
- [Task] The installer schuld install TYPO3-directory if not exists
- [Doc] Add copyright information and link to LICENSE

### Changed
- [Task] Improved .gitignore configuration
- [Doc] Improvement of documentation

## [0.1.1] - 2017-10-05
### Added
- [Doc] Update Readme - Add RSYNC configuration

### Changed
- [Bugfix] shared.typo3conf.PackageStates_php

## [0.1.0] - 2017-10-03
### Added
- [Task] Inital commit
