# Changelog

All notable changes to `eloquent-repository` will be documented in this file

## 2.2.0 - 2020-08-27

- Now it is possible to set a model entity with `$entity` property in Repository class
- Dynamically set model entity with `setEntity()` method

## 2.1.1 - 2020-03-30

- Namespace bug fixed

## 2.1.0 - 2020-03-30

- Laravel ^7.0 support added

## 2.0.0 - 2019-09-19

- Laravel ^6.0 compatibility added
- Dropped support for Laravel 5.6, 5.7 and 5.8

## 1.2.1 - 2019-08-13

- Cache logic changes.
- `get` methods no longer caches results because of unpredictable `$columns` argument.

## 1.2.0 - 2019-08-08

- `$cacheTTL` property added alongside `cacheTTL()` method for simplicity.

## 1.1.2 - 2019-08-01

- Container helper method replaced with dependency injection
- Cachable interface renamed to Cacheable

## 1.1.1 - 2019-07-30

- PHP docBlocks updated

## 1.1.0 - 2019-07-28

- Array type-hinting removed from methods to make them more flexible

## 1.0.0 - 2019-07-28

- Initial release
