# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2018-09-04
### Changed
- [Task] Enhanced configuration for custom TYPO3 web directory

## [1.7.2] - 2018-08-28
### Changed
- [Task] Initial composer install with execution of scripts

## [1.7.1] - 2018-06-19
### Fixed
- [Bugfix] Fix wrong output information in target "Publish new build"

## [1.7.0] - 2018-06-19
### Fixed
- [Bugfix] '.env.dist' is not available in 'current/'

## [1.7.0] - 2018-06-19
### Added
- [Task] Target 'ci:release:create:next' will create symlink for '.env'
- [Task] Target 'init:shared' creates .env if exists
- [Task] Target 'service:copy' available

## [1.6.0] - 2019-05-21
### Changed
- [Task] Improved error handling while composer execution
- [Task] Improved first time installation

## [1.5.0] - 2018-08-10
### Changed
- [Task] Should create dir 'uploads' if not exists
- [Task] ExecTask output should redirected to stdout

## [1.4.0] - 2018-08-10
### Changed
- [Task] Make it available for php 7.3.x

## [1.3.2] - 2018-08-10
### Removed
- [Remove] Deprecated 'PackageStates.php'-Handling from 'build.hook.xml' removed

## [1.3.1] - 2018-08-10
### Changed
- [Task] It's better to check the presence of shared 'LocalConfiguration.php' instead of 'shared'-Dir while executing 'ci:release:create:next'

## [1.3.0] - 2018-07-23
### Changed
- [Task] For better deployment controls ci:release should split in 3 dedicated targets

## [1.2.2] - 2018-07-20
### Fixed
- [Bugfix] Lock backend should work for current

## [1.2.1] - 2018-03-21
### Changed
- [Task] Update composer dependencies - supports only >=7.0 <7.3

### Addeded
- [CI] Add Travis CI configuration

## [1.2.0] - 2018-03-21
### Removed
- [Task] Remove installation of .gitignore file

## [1.1.3] - 2018-03-21
### Changed
- [Bugfix] Locked editors after deployment

## [1.1.2] - 2018-03-19
### Changed
- [Bugfix] After deployment backend is locked for editors

## [1.1.1] - 2018-03-13
### Changed
- 'composer update' calls 'bin/typo3cms' actions twice

## [1.1.0] - 2018-03-13
### Added
- Improved typo3_composer.json scripts configuration

### Removed
- Remove target 'typo3console-before-release' in release process, because this should managed in TYPO3 composer.json

## [1.0.0] - 2018-02-02
### Removed
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
### Addeded
- [Task] Move .htaccess while ci:release into web-directory

## [0.5.1] - 2017-10-12
### Addeded
- [Remove] Redundant 'Create Symlink for PackageStates.php' in ci:release

### Changed
- [Bugfix] ci:release calls wrong hook before release

### Removedd
- [Doc] Improved documentation

## [0.5.0] - 2017-10-10
### Addeded
- [Task] RSYNC excludes should be installed by composer extra configuration

## [0.4.0] - 2017-10-09
### Addeded
- [Task] Add typo3console tasks for ci:release
- [Task] Installation of basic rsync excludes.txt

### Changed
- [Bugfix] Wrong path for 'bin/typo3cms'
- [Bugfix] .gitignore installation fails, because file 'src/phing/dist/_.gitignore' does not exists
- [Task] Improved installer with better check for targets
- [Doc] Fix misspelling

## [0.3.0] - 2017-10-06
### Addeded
- [Task] Add TYPO3 composer.json and installation if not exists
- [Task] Improved composer.json settings
- [Task] Use dirname to generate dist path

### Changed
- [Task] Improved installation guide
- [Task] Set composer type as 'project'

## [0.2.0] - 2017-10-05
### Addeded
- [Task] The installer schuld install TYPO3-directory if not exists
- [Doc] Add copyright information and link to LICENSE

### Changed
- [Task] Improved .gitignore configuration
- [Doc] Improvement of documentation

## [0.1.1] - 2017-10-05
### Addeded
- [Doc] Update Readme - Add RSYNC configuration

### Changed
- [Bugfix] shared.typo3conf.PackageStates_php

## [0.1.0] - 2017-10-03
### Addeded
- [Task] Inital commit
