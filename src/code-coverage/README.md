Code Coverage Bridge
----
This packages provides bridge from phpunit/php-code-coverage to various testing tools.

[![License](https://img.shields.io/packagist/l/doyo/code-coverage-bridge.svg?style=flat-square)](https://github.com/doyolabs/code-coverage-bridge/blob/master/LICENSE)
[![Build Status][travis-master]][travis]
[![Coverage][cover-stat-master]][cover-master]
[![Score][score-stat-master]][score-master] 

[travis]:                   https://travis-ci.com/doyolabs/code-coverage-bridge
[travis-master]:            https://img.shields.io/travis/com/doyolabs/code-coverage-bridge/master.svg?style=flat-square
[cover-master]:             https://coveralls.io/github/doyolabs/code-coverage-bridge?branch=master
[cover-stat-master]:        https://img.shields.io/coveralls/github/doyolabs/code-coverage-bridge/master.svg?style=flat-square
[score-master]:             https://scrutinizer-ci.com/g/doyolabs/code-coverage-bridge/?branch=master
[score-stat-master]:        https://img.shields.io/scrutinizer/quality/g/doyolabs/code-coverage-bridge/master.svg?style=flat-square

About
----
This package provides a reusable libraries for collecting code coverage from various php tests like behat and phpspec.
Additional features that are provide by this library are:
*  Make possible to use mark test as passed in code coverage report
*  Add library to collecting coverage from sessions like remote/live site or php command line tools.

Project using this library:
1.  [doyo/behat-code-coverage](https://github.com/doyolabs/behat-code-coverage)
2.  [doyo/phpspec-code-coverage](https://github.com/doyolabs/phpspec-code-coverage)
