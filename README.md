# Eloquent Repository for Laravel

[![Latest Stable Version](https://poser.pugx.org/orkhanahmadov/eloquent-repository/v/stable)](https://packagist.org/packages/orkhanahmadov/eloquent-repository)
[![Latest Unstable Version](https://poser.pugx.org/orkhanahmadov/eloquent-repository/v/unstable)](https://packagist.org/packages/orkhanahmadov/eloquent-repository)
[![Total Downloads](https://img.shields.io/packagist/dt/orkhanahmadov/eloquent-repository)](https://packagist.org/packages/orkhanahmadov/eloquent-repository)
[![License](https://img.shields.io/github/license/orkhanahmadov/eloquent-repository.svg)](https://github.com/orkhanahmadov/eloquent-repository/blob/master/LICENSE.md)

[![Build Status](https://img.shields.io/travis/orkhanahmadov/eloquent-repository.svg)](https://travis-ci.org/orkhanahmadov/eloquent-repository)
[![Test Coverage](https://api.codeclimate.com/v1/badges/3d5f8ef41004f128df8a/test_coverage)](https://codeclimate.com/github/orkhanahmadov/eloquent-repository/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/3d5f8ef41004f128df8a/maintainability)](https://codeclimate.com/github/orkhanahmadov/eloquent-repository/maintainability)
[![Quality Score](https://img.shields.io/scrutinizer/g/orkhanahmadov/eloquent-repository.svg)](https://scrutinizer-ci.com/g/orkhanahmadov/eloquent-repository)
[![StyleCI](https://github.styleci.io/repos/197324305/shield?branch=master)](https://github.styleci.io/repos/197324305)

Eloquent Repository package for Laravel created with total "repository pattern" in-mind.

## Requirements

**Version ^2.0** - Laravel **^6.0** or **^7.0** or higher and **PHP 7.2** or higher.

**Version ^1.0** - Laravel **5.5, 5.6, 5.7, 5.8** and **PHP 7.1** or higher.

## Installation

You can install the package via composer:

```bash
composer require orkhanahmadov/eloquent-repository
```

## Usage

Create a repository class and extend `Orkhanahmadov\EloquentRepository\EloquentRepository` abstract class.

Repository class that extends `EloquentRepository` must implement `entity` method. When using Eloquent models it's enough to return model's full namespace from the method.

``` php
namespace App\Repositories;

use App\User;
use Orkhanahmadov\EloquentRepository\EloquentRepository;

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

Package also comes with console command that can create repository class. Pass repository class name to `make:repository` command to generate it.
``` bash
php artisan make:repository UserRepository
```
This will create repository class inside `Repositories` folder in your app's autoload directory.

You can also pass Eloquent model namespace to `make:repository` command to automatically apply add that model to repository.
``` bash
php artisan make:repository UserRepository --model=User
```
This will create `UserRepository` class and apply `User` model as entity to it.


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
Finds user with given primary key and returns model instance. If model is not available `Illuminate\Database\Eloquent\ModelNotFoundException` exception will be thrown.

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
$user = $userRepository->find(1);
$userRepository->update($user, ['first_name' => 'Dave']);
$userRepository->findAndUpdate(1, ['first_name' => 'Dave']); // finds user with ID=1, updates it with given values and returns instance
```
Updates `$user` model's `first_name` to "Dave" and returns updated instance.

**Find a model using primary key and update with given properties:**
``` php
$userRepository->findAndUpdate(1, ['first_name' => 'Dave']);
```
Finds a user with given primary key, updates `first_name` to "Dave" and returns updated instance. If model is not available `Illuminate\Database\Eloquent\ModelNotFoundException` exception will be thrown.

**Delete a model:**
``` php
$user = $userRepository->find(1);
$userRepository->delete($user);
```
Deletes `$user` model.

**Find a model using primary key and delete:**
``` php
$userRepository->findAndDelete(1);
```
Finds a user with given primary key and deletes. If model is not available `Illuminate\Database\Eloquent\ModelNotFoundException` exception will be thrown.

**Find a "soft deleted" model:**
``` php
$userRepository->findFromTrashed(1);
```
Finds a "soft deleted" user with given primary key. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception. If model is not available `Illuminate\Database\Eloquent\ModelNotFoundException` exception will be thrown.

**Restore a "soft deleted" model:**
``` php
$user = $userRepository->findFromTrashed(1);
$userRepository->restore($user);
```
Restores a "soft deleted" a `$user` model. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception.

**Find a "soft deleted" model using primary key and restore:**
``` php
$userRepository->findAndRestore(1);
```
Finds a "soft deleted" user with given primary key and restores. If model is not using "soft delete" feature method will throw `BadMethodCallException` exception. If model is not available `Illuminate\Database\Eloquent\ModelNotFoundException` exception will be thrown.

### Criteria

Package uses "criteria" for creating flexible queries. To use criteria chain `withCriteria()` method to repository. List of available criteria:

**EagerLoad:**

Use `Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\EagerLoad` to eager load relationships with query.

``` php
$userRepository->withCriteria(new EagerLoad('posts', 'country'))->get();
```
This will return all users with `posts` and `country` relationships eager loaded.

**Scope:**

Use `Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\Scope` to apply eloquent query scopes.

``` php
$userRepository->withCriteria(new Scope('active', 'admin'))->get();
```
This will apply `active` and `active` scopes to query and return all available users.

**OrderBy:**

Use `Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\OrderBy` to order results with specified column and type.

``` php
$userRepository->withCriteria(new OrderBy('username', 'asc'))->get();
```
This will return order users by ascending `username` column and return all of them.

**Latest:**

Use `Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\Latest` to order results with specified column and in descending order.

``` php
$userRepository->withCriteria(new OrderBy('username'))->get();
```
This will return order users by descending `username` column and return all of them.

You can apply multiple scopes to repository at the same time.

``` php
$userRepository->withCriteria([
    new EagerLoad('posts', 'country'),
    new Scope('active', 'admin'),
    new OrderBy('id')
])->get();
```

You can create your own criteria classes and use them with `withCriteria()` method.
Every criteria class must implement `Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion` interface. This interface requires having `apply($entity)` method:

``` php
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class Ascending implements Criterion
{
    /**
     * @var string
     */
    private $column;

    /**
     * @param string $column
     */
    public function __construct($column = 'id')
    {
        $this->column = $column;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity)
    {
        return $entity->orderBy($this->column, 'asc');
    }
}
```

### Caching

Repository also supports caching models. To enable caching implement `Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable` interface to your repository:

``` php
namespace App\Repositories;

use App\User;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;
use Orkhanahmadov\EloquentRepository\EloquentRepository;

class UserRepository extends EloquentRepository implements Cacheable
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

Once implemented, `all` and `find()` methods will cache results.
Repository will empty the cache automatically when `update()`, `findAndUpdate()`, `delete()` and `findAndDelete()` methods used.

You can implement `cacheKey()` method in your repository to set cache key. Default is model's table name.

You can set cache time-to-live with `$cacheTTL` property. By default it is set to 3600 (1 hour).
Alternatively you can implement `cacheTTL()` method in your repository to return cache time-to-live value.
Repository will ignore `$cacheTTL` property value when `cacheTTL()` method is implemented.

You can implement `invalidateCache($model)` method in your repository to change cache invalidation logic when `update()`, `findAndUpdate()`, `delete()`, `findAndDelete()` methods being used.

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

- [Orkhan Ahmadov](https://github.com/orkhanahmadov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
