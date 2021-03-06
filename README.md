# CakePHP Data Plugin
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-data.svg)](https://travis-ci.org/dereuromark/cakephp-data)
[![Coverage Status](https://coveralls.io/repos/dereuromark/cakephp-data/badge.svg)](https://coveralls.io/r/dereuromark/cakephp-data)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-data/license.svg)](https://packagist.org/packages/dereuromark/cakephp-data)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-data/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-data)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

A Cake3.x Plugin containing several useful data models that can be used in many projects.

## Features
- Continents => Countries => States => Counties => Districts => Cities
- Postal Codes
- Addresses
- Locations (geocodable)
- MimeTypes and MimeTypeImages
- Languages
- Currencies

Both schema and data.

## Demo
See http://sandbox.dereuromark.de/export

## How to include
Installing the plugin is pretty much as with every other CakePHP Plugin.
```
composer require dereuromark/cakephp-data
```

Make sure you have `Plugin::load('Data')` or `Plugin::loadAll()` in your bootstrap.

That's it. It should be up and running.

### Possible Dependencies

- Tools plugin
- FOC Search plugin (optional, if you want basic filtering)

## Disclaimer
Use at your own risk. Please provide any fixes or enhancements via issue or better pull request.
Some classes are still from 1.2 (and are merely upgraded to 2.x/3.x) and might still need some serious refactoring.
If you are able to help on that one, that would be awesome.

### TODOs

* Better test coverage
