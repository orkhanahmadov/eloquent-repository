# Eloquent Repository for Laravel

[![Latest Stable Version](https://poser.pugx.org/innoscripta/eloquent-repository/v/stable)](https://packagist.org/packages/innoscripta/eloquent-repository)
[![Latest Unstable Version](https://poser.pugx.org/innoscripta/eloquent-repository/v/unstable)](https://packagist.org/packages/innoscripta/eloquent-repository)
[![Total Downloads](https://poser.pugx.org/innoscripta/eloquent-repository/downloads)](https://packagist.org/packages/innoscripta/eloquent-repository)
[![License](https://poser.pugx.org/innoscripta/eloquent-repository/license)](https://packagist.org/packages/innoscripta/eloquent-repository)

[![Build Status](https://travis-ci.org/innoscripta/eloquent-repository.svg?branch=master)](https://travis-ci.org/innoscripta/eloquent-repository)
[![Test Coverage](https://api.codeclimate.com/v1/badges/1d0316bf39dcbc1ea910/test_coverage)](https://codeclimate.com/github/innoscripta/eloquent-repository/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/1d0316bf39dcbc1ea910/maintainability)](https://codeclimate.com/github/innoscripta/eloquent-repository/maintainability)
[![Quality Score](https://img.shields.io/scrutinizer/g/innoscripta/eloquent-repository.svg?style=flat-square)](https://scrutinizer-ci.com/g/innoscripta/eloquent-repository)
[![StyleCI](https://github.styleci.io/repos/197324305/shield?branch=master)](https://github.styleci.io/repos/197324305)

Eloquent Repository package for Laravel created with total "repository pattern" in-mind.

### Requirements

**PHP 7.2** or higher.

## Installation

You can install the package via composer:

```bash
composer require innoscripta/eloquent-repository
```

## Usage

Create a class that you want it act repository and extend `Innoscripta\EloquentRepository\Repository\EloquentRepository` abstract class.
Repository class must implement `entity` method. When using Eloquent models it's enough to return model's full namespace from `entity` method.

``` php
namespace App\Repositories;

use App\User;
use Innoscripta\EloquentRepository\Repository\EloquentRepository;

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

`EloquentRepository` abstract class has many familiar shortcut methods just like Eloquent methods.

Available methods:

``` php
$users = new UserRepository();

$user->create(['first_name' => 'John', 'last_name' => 'Doe']); // creates a user with given parameters and returns it

$user->all(); // returns all users with all columns

$user->get(); // returns all users. method accepts list of columns to get as array

$user->paginate(10); // paginates all users with given "per page" value and returns result

$user->find(1); // finds user with ID=1 and returns it. throws exception when not found

$user->getWhere('first_name', 'John'); // finds all users with "first_name" column "John"
$user->getWhere(['first_name' => 'John', 'last_name' => 'Doe']); // you can also pass multiple where statements in first parameter

$user->getWhereFirst('first_name', 'John'); // finds first user with "first_name" column "John"
$user->getWhereFirst(['first_name' => 'John', 'last_name' => 'Doe']); // you can also pass multiple where statements in first parameter

$user->getWhereIn('first_name', ['John', 'Jane', 'Dave']); // finds all users with "first_name" column "John", "Jane" or "Dave"
$user->getWhereInFirst('first_name', ['John', 'Jane', 'Dave']); // finds first user with "first_name" column "John", "Jane" or "Dave"

$user->update($userModelInstance, ['first_name' => 'Dave']); // updates $userModelInstance with given values and returns updated instance
$user->findAndUpdate(1, ['first_name' => 'Dave']); // finds user with ID=1, updates it with given values and returns instance

$user->delete($userModelInstance); // deletes $userModelInstance
$user->findAndDelete(1); // finds user with ID=1 and deletes it

$user->restore($userModelInstance); // restores "soft deleted" user model
$user->findAndRestore(1); // finds "soft deleted" user with ID=1 and restores it
$user->findFromTrashed(1); // finds "soft deleted" user with ID=1 and returns it
```

### Caching

// todo

### Criteria

### Extending

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ahmadov90@gmail.com instead of using the issue tracker.

## Credits

- [Orkhan Ahmadov](https://github.com/innoscripta)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
