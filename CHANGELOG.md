# Changelog

All notable changes to `eloquent-repository` will be documented in this file

## 2.0.0 - 2019-09-19

- Laravel ^6.0 compatibility added
- Dropped support for Laravel 5.6 and 5.7

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
