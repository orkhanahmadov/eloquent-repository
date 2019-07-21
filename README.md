# Eloquent Repository for Laravel 5

[![Latest Stable Version](https://poser.pugx.org/innoscripta/eloquent-repository/v/stable)](https://packagist.org/packages/innoscripta/eloquent-repository)
[![Latest Unstable Version](https://poser.pugx.org/innoscripta/eloquent-repository/v/unstable)](https://packagist.org/packages/innoscripta/eloquent-repository)
[![GitHub license](https://img.shields.io/github/license/innoscripta/eloquent-repository.svg)](https://github.com/innoscripta/eloquent-repository/blob/master/LICENSE.md)

[![Build Status](https://img.shields.io/travis/innoscripta/eloquent-repository.svg)](https://travis-ci.org/innoscripta/eloquent-repository)
[![Test Coverage](https://img.shields.io/codeclimate/coverage/innoscripta/eloquent-repository.svg)](https://codeclimate.com/github/innoscripta/eloquent-repository/test_coverage)
[![Maintainability](https://img.shields.io/codeclimate/maintainability/innoscripta/eloquent-repository.svg)](https://codeclimate.com/github/innoscripta/eloquent-repository/maintainability)
[![Quality Score](https://img.shields.io/scrutinizer/g/innoscripta/eloquent-repository.svg)](https://scrutinizer-ci.com/g/innoscripta/eloquent-repository)
[![StyleCI](https://github.styleci.io/repos/197324305/shield?branch=master)](https://github.styleci.io/repos/197324305)

Eloquent Repository package for Laravel created with total "repository pattern" in-mind.

## Requirements

**PHP 7.2** or higher.

## Installation

You can install the package via composer:

```bash
composer require innoscripta/eloquent-repository
```

## Usage

Create a repository class and extend `Innoscripta\EloquentRepository\EloquentRepository` abstract class.

Repository class that extends `EloquentRepository` must implement `entity` method. When using Eloquent models it's enough to return model's full namespace from the method.

``` php
namespace App\Repositories;

use App\User;
use Innoscripta\EloquentRepository\EloquentRepository;

class UserRepository extends EloquentRepository
{
    /**
     * Defines entity.
     *
     * @return mixed
     */
    protected function entity()
    {
        return User::class;
    }
}
```

You can use Laravel's container to inject `UserRepository` repository.

``` php
namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class HomeController extends Controller
{
    public function index(UserRepository $userRepository)
    {
        return $userRepository->get();
    }
}
```

### Available methods

Extending `EloquentRepository` class offers has many familiar shortcut methods from Eloquent.

**Create a model:**
``` php
$userRepository->create(['first_name' => 'John', 'last_name' => 'Doe']);
```
Creates a user with given parameters and returns created model instance.

**Return all models:**
``` php
$userRepository->all();
```
Finds and returns all users with all allowed columns.

**Return all models with listed columns:**
``` php
$userRepository->get(['id', 'first_name']);
```
Finds and returns all users with listed columns. You can skip list of columns, method will act same as `all()`.

**Paginate and return all models with given "per page" value:**
``` php
$userRepository->paginate(10);
```
Paginates all users with given "per page" value and returns paginated result.

**Find a user with primary key:**
``` php
$userRepository->find(1); 
```
Finds user with given primary key and returns model instance. If model is not available method will throw `Illuminate\Database\Eloquent\ModelNotFoundException` exception.

**Return all users with given "where" statement:**
``` php
$userRepository->getWhere('first_name', 'John');
```
Returns all users where `first_name` is "John".

You can also pass multiple multiple "where" statements in first parameter and skip second parameter.
``` php
$userRepository->getWhere(['first_name' => 'John', 'last_name' => 'Doe']);
```
Returns all users where `first_name` is "John" and `last_name` is "Doe".

**Return first user with given "where" statement:**
``` php
$userRepository->getWhereFirst('first_name', 'John');
$userRepository->getWhereFirst(['first_name' => 'John', 'last_name' => 'Doe']);
```
Returns first user where `first_name` is "John".

You can also pass multiple multiple "where" statements in first parameter and skip second parameter.
``` php
$userRepository->getWhereFirst(['first_name' => 'John', 'last_name' => 'Doe']);
```
Returns first user where `first_name` is "John" and `last_name` is "Doe".

**Return all users with given "whereIn" statement:**
``` php
$userRepository->getWhereIn('first_name', ['John', 'Jane', 'Dave']);
```
Returns all users where `first_name` is "John", "Jane" or "Dave".

**Return first user with given "whereIn" statement:**
``` php
$userRepository->getWhereInFirst('first_name', ['John', 'Jane', 'Dave']);
```
Returns first user where `first_name` is "John", "Jane" or "Dave".

**Update a model with given properties:**
``` php
$user = \App\User::find(1);
$userRepository->update($user, ['first_name' => 'Dave']);
$userRepository->findAndUpdate(1, ['first_name' => 'Dave']); // finds user with ID=1, updates it with given values and returns instance
```
Updates `$user` model's `first_name` to "Dave" and returns updated instance.

**Find a model using primary key and update with given properties:**
``` php
$userRepository->findAndUpdate(1, ['first_name' => 'Dave']);
```
Finds a user with given primary key, updates `first_name` to "Dave" and returns updated instance. If model is not available method will throw `Illuminate\Database\Eloquent\ModelNotFoundException` exception.

**Delete a model:**
``` php
$user = \App\User::find(1);
$userRepository->delete($user);
$userRepository->findAndDelete(1); // finds user with ID=1 and deletes it
```
Deletes `$user` model.

**Find a model using primary key and delete:**
``` php
$userRepository->findAndDelete(1);
```
Finds a user with given primary key and deletes. If model is not available method will throw `Illuminate\Database\Eloquent\ModelNotFoundException` exception.

**Restore a "soft deleted" model:**
``` php
$user = \App\User::onlyTrashed()->find(1);
$userRepository->restore($user);
```
Restores a "soft deleted" a `$user` model. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception.

**Find a "soft deleted" model using primary key and restore:**
``` php
$userRepository->findAndRestore(1);
```
Finds a "soft deleted" user with given primary key and restores. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception. If model is not available method will throw `Illuminate\Database\Eloquent\ModelNotFoundException` exception.

**Find a "soft deleted" model:**
``` php
$userRepository->findFromTrashed(1);
```
Finds a "soft deleted" user with given primary key. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception. If model is not available method will throw `Illuminate\Database\Eloquent\ModelNotFoundException` exception.

### Criteria

// todo

### Caching

Repository also supports caching models. To enable caching implement `Innoscripta\EloquentRepository\Repository\Contracts\Cachable` interface to your repository:

``` php
namespace App\Repositories;

use App\User;
use Innoscripta\EloquentRepository\Repository\Contracts\Cachable;
use Innoscripta\EloquentRepository\EloquentRepository;

class UserRepository extends EloquentRepository implements Cachable
{
    /**
     * Defines entity.
     *
     * @return mixed
     */
    protected function entity()
    {
        return User::class;
    }
}
```

Once implemented, `all` , `get()` and `find()` methods will cache models.
Repository will empty the cache automatically when `update()`, `findAndUpdate()`, `delete()` and `findAndDelete()` methods used.

You can implement `cacheKey()` method in your repository to set cache key. Default is model's table name.

You can implement `cacheTTL()` method in your repository to set cache time-to-live. Default is 3600 seconds (1 hour).

You can implement `forgetCache($model)` method in your repository to change cache invalidation logic when `update()`, `findAndUpdate()`, `delete()`, `findAndDelete()` methods being used.

### Extending

// todo

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ahmadov90@gmail.com instead of using the issue tracker.

## Credits

- [Orkhan Ahmadov](https://github.com/innoscripta)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
