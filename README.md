# Verify-Ldap-L4 - Laravel 4 LDAP Auth Package

---

A simple role/permission authentication package for Laravel 4 that uses LDAP to do the Auth
This package is a altered fork of the work of Toddish's Verify-L4 package which can be found at [github.com/Toddish/Verify-L4](github.com/Toddish/Verify-L4)

---

* Role/permission based authentication
* Exceptions for intelligent handling of errors
* Configurable/extendable

---

## Installation

Add Verify-Ldap-L4 to your composer.json file:

```
"require": {
  "djbarnes/verify-ldap-l4": "dev-master"
}

"repositories": [
  {
    "type": "vcs"
    "url": "git@github.com:DJBarnes/verify-ldap-l4"
  }
]
```

Now, run a composer update on the command line from the root of your project:

    composer update

### Registering the Package

Add the Verify-Ldap-L4 Service Provider to your config in ``app/config/app.php``:

```php
'providers' => array(
  'DJBarnes\VerifyLdapL4\VerifyLdapL4ServiceProvider'
),
```

### Change the driver

Then change your Auth driver to ``'verify-ldap-l4'`` in ``app/config/auth.php``:

```php
'driver' => 'verify-ldap-l4',
```

You may also change the ```'model'``` value to ```'Djbarnes\Verify-Ldap-L4\Models\User'``` if you want to be able to load Verify's User model when using ```Auth::user()```.

Alternatively, you can simply create your own User model, and extend Verify-Ldap-L4's:

```php
use Djbarnes\Verify-Ldap-L4\Models\User as VerifyLdapL4User;

class User extends VerifyLdapL4User
{
    // Code
}
```

### Publish the config

Run this on the command line from the root of your project:

    php artisan config:publish djbarnes/verify-ldap-l4

This will publish Verify's config to ``app/config/packages/djbarnes/verify-ldap-l4/``.

You may also want to change the ``'db_prefix'`` value if you want a prefix on Verify's database tables.

Fill in the missing fields for the configuration file at the location mentioned above.
```php
    'identified_by' => array('username', 'email'),

    // The Super Admin role
    // (returns true for all permissions)
    'super_admin' => 'Super Admin',

    // DB prefix for tables
    'prefix' => '',

    // Ldap Server Address
    'ldap_server' => 'dir.example.com'
```

### Migration

Now migrate the database tables for Verify-Ldap-L4. Run this on the command line from the root of your project:

    php artisan migrate --package="djbarnes/verify-ldap-l4"

You should now have all the tables imported.

## Usage

The package is intentionally lightweight. You add Users, Roles and Permissions like any other Model.

```php
$user = new Djbarnes\VerifyLdapL4\Models\User;
$role = new Djbarnes\VerifyLdapL4\Models\Role;
$permission = new Djbarnes\VerifyLdapL4\Models\Permission;
```

etc.

**All models are in the namespace 'Djbarnes\VerifyLdapL4\Models\'.**

The relationships are as follows:

* Roles have many and belong to Users
* Users have many and belong to Roles
* Roles have many and belong to Permissions
* Permissions have many and belong to Roles

Relationships are handled via the Eloquent ORM, too:

```php
$role->permissions()->sync(array($permission->id, $permission2->id));
```

More information on relationships can be found in the [Laravel 4 Eloquent docs](http://four.laravel.com/docs/eloquent).

Authentication is done by using the 'userdn' field of the User model, and the provided password to attempt an ldap bind to the directory server specified by the config file. With this auth package, Users, roles, and permissions are all stored in the database created by the migrations. Passwords are stored on the ldap server, and not in the database. When a user goes to authenticate, they are authenticated through the ldap server, but permissions and roles are pulled from the database.

## Basic Examples

```php
// Create a new Permission
$permission = new Djbarnes\VerifyLdapL4\Models\Permission;
$permission->name = 'delete_user';
$permission->save();

// Create a new Role
$role = new Djbarnes\VerifyLdapL4\Models\Role;
$role->name = 'Moderator';
$role->level = 7;
$role->save();

// Assign the Permission to the Role
$role->permissions()->sync(array($permission->id));

// Create a new User
$user = new Djbarnes\VerifyLdapL4\Models\User;
$user->username = 'david';
$user->email = 'barnesdavidj@gmail.com';
$user->userdn = 'uid=david, ou=people, o=example.com, dc=example, dc=com'
$user->save();

// Assign the Role to the User
$user->roles()->sync(array($role->id));

// Using the public methods available on the User object
var_dump($user->is('Moderator')); // true
var_dump($user->is('Admin')); // false

var_dump($user->can('delete_user')); // true
var_dump($user->can('add_user')); // false

var_dump($user->level(7)); // true
var_dump($user->level(5, '&lt;=')); // false
```

## Example of the Auth with Ldap

The following example assumes that there is a user in the database to test with.
A user can be added with the code listed above.

### View - login.blade.php
```php
{{ Form::open(array('action'=>'HomeController@postLogin', 'method'=>'POST')) }}
 
    <p>
      {{ Form::label('username', 'Username:') }}<br />
      {{ Form::text('username') }}
    </p>
 
    <p>
      {{ Form::label('password', 'Password:') }}<br />
      {{ Form::password('password') }}
    </p>
 
    <p>{{ Form::submit('Login') }}</p>
 
{{ Form::close() }}
```

### Controller - HomeController.php
```php
  public function showLogin()
  {
    return View::make('login');
  }

  public function postLogin()
  {
    if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
    {
      return 'Logged In';
    }
    else
    {
      return 'Not Authenticated';
    }
  }

}
```

### Routes - routes.php
```php
Route::get('/','HomeController@showLogin');
Route::post('/','HomeController@postLogin', array('before' => 'auth'));
```
---

## Documentation

For similar full documentation, have a look at [http://docs.toddish.co.uk/verify-l4](http://docs.toddish.co.uk/verify-l4) since this package is a fork from Toddish's original Verify-L4 package.