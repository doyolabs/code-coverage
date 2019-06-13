Code Coverage
----
This packages making your code coverage collecting easy.

| Project | License | Version | Build | Coverage | Score |
| :---: | :---: | :---: | :---: | :---: | :---: |
| [main project][main]   |  [![License][main-license-badge]][main-license] | [![Version][main-version-badge]][main-version] | [![Build Status][main-travis-badge]][main-travis] | [![Coverage][main-cover-badge]][main-cover] | [![Score][main-score-badge]][main-score] |
| [code-coverage-bridge][bridge] |  [![License][bridge-license-badge]][bridge-license] | [![Version][bridge-version-badge]][bridge-version] |  [![Build Status][bridge-travis-badge]][bridge-travis] | [![Coverage][bridge-cover-badge]][bridge-cover] | [![Score][bridge-score-badge]][bridge-score] |
| [behat-code-coverage][behat] |  [![License][behat-license-badge]][behat-license] | [![Version][behat-version-badge]][behat-version] |  [![Build Status][behat-travis-badge]][behat-travis] | [![Coverage][behat-cover-badge]][behat-cover] | [![Score][behat-score-badge]][behat-score] |
| [phpspec-code-coverage][phpspec] |  [![License][phpspec-license-badge]][phpspec-license] | [![Version][phpspec-version-badge]][phpspec-version] |  [![Build Status][phpspec-travis-badge]][phpspec-travis] | [![Coverage][phpspec-cover-badge]][phpspec-cover] | [![Score][phpspec-score-badge]][phpspec-score] |

[main]:                     https://github.com/doyolabs/code-coverage-bridge
[main-version]:             https://packagist.org/packages/doyo/code-coverage
[main-version-badge]:       https://img.shields.io/packagist/vpre/doyo/code-coverage.svg?style=flat-square
[main-license]:             https://github.com/doyolabs/code-coverage/blob/master/LICENSE
[main-license-badge]:       https://img.shields.io/packagist/l/doyo/code-coverage.svg?style=flat-square
[main-travis]:              https://travis-ci.com/doyolabs/code-coverage
[main-travis-badge]:        https://img.shields.io/travis/com/doyolabs/code-coverage/master.svg?style=flat-square
[main-cover]:               https://coveralls.io/github/doyolabs/code-coverage?branch=master
[main-cover-badge]:         https://img.shields.io/coveralls/github/doyolabs/code-coverage/master.svg?style=flat-square
[main-score]:               https://scrutinizer-ci.com/g/doyolabs/code-coverage/?branch=master
[main-score-badge]:         https://img.shields.io/scrutinizer/quality/g/doyolabs/code-coverage/master.svg?style=flat-square

[bridge]:                   https://github.com/doyolabs/code-coverage-bridge
[bridge-license]:           https://github.com/doyolabs/code-coverage-bridge/blob/master/LICENSE
[bridge-license-badge]:     https://img.shields.io/packagist/l/doyo/code-coverage-bridge.svg?style=flat-square
[bridge-version]:           https://packagist.org/packages/doyo/code-coverage-bridge
[bridge-version-badge]:     https://img.shields.io/packagist/vpre/doyo/code-coverage-bridge.svg?style=flat-square
[bridge-travis]:            https://travis-ci.com/doyolabs/code-coverage-bridge
[bridge-travis-badge]:      https://img.shields.io/travis/com/doyolabs/code-coverage-bridge/master.svg?style=flat-square
[bridge-cover]:             https://coveralls.io/github/doyolabs/code-coverage-bridge?branch=master
[bridge-cover-badge]:       https://img.shields.io/coveralls/github/doyolabs/code-coverage-bridge/master.svg?style=flat-square
[bridge-score]:             https://scrutinizer-ci.com/g/doyolabs/code-coverage-bridge/?branch=master
[bridge-score-badge]:       https://img.shields.io/scrutinizer/quality/g/doyolabs/code-coverage-bridge/master.svg?style=flat-square

[behat]:                   https://github.com/doyolabs/behat-code-coverage
[behat-license]:           https://github.com/doyolabs/behat-code-coverage/blob/master/LICENSE
[behat-license-badge]:     https://img.shields.io/packagist/l/doyo/behat-code-coverage.svg?style=flat-square
[behat-version]:           https://packagist.org/packages/doyo/behat-code-coverage
[behat-version-badge]:     https://img.shields.io/packagist/vpre/doyo/behat-code-coverage.svg?style=flat-square
[behat-travis]:            https://travis-ci.com/doyolabs/behat-code-coverage
[behat-travis-badge]:      https://img.shields.io/travis/com/doyolabs/behat-code-coverage/master.svg?style=flat-square
[behat-cover]:             https://coveralls.io/github/doyolabs/behat-code-coverage?branch=master
[behat-cover-badge]:       https://img.shields.io/coveralls/github/doyolabs/behat-code-coverage/master.svg?style=flat-square
[behat-score]:             https://scrutinizer-ci.com/g/doyolabs/behat-code-coverage/?branch=master
[behat-score-badge]:       https://img.shields.io/scrutinizer/quality/g/doyolabs/behat-code-coverage/master.svg?style=flat-square

[phpspec]:                   https://github.com/doyolabs/phpspec-code-coverage
[phpspec-license]:           https://github.com/doyolabs/phpspec-code-coverage/blob/master/LICENSE
[phpspec-license-badge]:     https://img.shields.io/packagist/l/doyo/phpspec-code-coverage.svg?style=flat-square
[phpspec-version]:           https://packagist.org/packages/doyo/phpspec-code-coverage
[phpspec-version-badge]:     https://img.shields.io/packagist/vpre/doyo/phpspec-code-coverage.svg?style=flat-square
[phpspec-travis]:            https://travis-ci.com/doyolabs/phpspec-code-coverage
[phpspec-travis-badge]:      https://img.shields.io/travis/com/doyolabs/phpspec-code-coverage/master.svg?style=flat-square
[phpspec-cover]:             https://coveralls.io/github/doyolabs/phpspec-code-coverage?branch=master
[phpspec-cover-badge]:       https://img.shields.io/coveralls/github/doyolabs/phpspec-code-coverage/master.svg?style=flat-square
[phpspec-score]:             https://scrutinizer-ci.com/g/doyolabs/phpspec-code-coverage/?branch=master
[phpspec-score-badge]:       https://img.shields.io/scrutinizer/quality/g/doyolabs/phpspec-code-coverage/master.svg?style=flat-square

About
----
This package provides a reusable libraries for collecting code coverage from various php tests like behat and phpspec.
Additional features that are provide by this library are:
*  Make possible to use mark test as passed in code coverage report
*  Add library to collecting coverage from sessions like remote/live site or php command line tools.

Project using this library:
1.  [doyo/behat-code-coverage](https://github.com/doyolabs/behat-code-coverage)
2.  [doyo/phpspec-code-coverage](https://github.com/doyolabs/phpspec-code-coverage)
