# Laravel Model Shield

## installation

Add the new required package in your composer.json

```
"vinicius73/laravel-model-shield": "~1.0"
```

Run `composer update` or `php composer.phar update`.

After composer command, add new service provider in `app/config/app.php` :

```php
'Vinicius73\ModelShield\ModelShieldServiceProvider',
```

Now, add new aliases in `app/config/app.php`.

```php
'Shield' => 'Vinicius73\ModelShield\Facades\Shield',
```

Finally publish the configuration file of the package `php artisan config:publish vinicius73/laravel-model-shield`

## Usage

You have two ways to use, extending the class `Vinicius73\ModelShield\ModelShield`, or using the trait `Vinicius73\ModelShield\Traits\Shield`

```php
use Vinicius73\ModelShield\ModelShield;

class Student extends ModelShield
{
   protected $table = 'students';
}
```

or

```php
use Vinicius73\ModelShield\Traits\Shield;

class Student extends Eloquent
{
   use Shield;

   protected $table = 'students';
}
```

### Validation Rules

Your validation rules are organized into separate files, to better organization.
By default the files are in `app/models/rules/`, but you can change it by changing the local variable `path` of the configuration file
`app/config/packages/vinicius73/laravel-model-shield/config.php`.

Each rules file can contain up to three sets of rules: `default`, `creating` and `updating`.

```php
<?php
// students.php

return [
   'default' => [
      'name' => 'required|alpha'
   ],
   'creating' => [
      'email'    => 'required|email|unique:students',
      'password' => 'required|min:8',
   ],
   'updating' => [
      'email' => 'required|email'
   ],
   // Custom Error Messages
   'custom_messages' => [
      'required' => 'You need to report the value of :attribute :(',
      'password.min' => 'Your password must be at least 8 characters :/',
      'email.unique' => 'Oops! Your email already registered!'
   ],
   // Custom Attribute Names
   'attribute_names' => [
      'email' => 'Your name'
   ]
];
```

The differential Laravel Model Shield is the possibility of having a specific set of rules for each situation.  
- **default** is the set of rules that will always be used in all validations.
- **creating** is the set of rules that will be used when you are creating a new record with his model.
- **updating** is the set of rules that will be used when you are updating a record in your model.

> `creating` and `updating` inherit the rules of `default` and replace if necessary, creating a wide range of possibilities.

#### Custom Messages and Attribute Names

You can also define custom error messages and more beautiful names to their attributes.  
Just set the values for `custom_messages` and `attribute_names`, Shield will do the rest.

### Validating

Shield validates the model every time you save it, either through the `$model->save()` or `$model->update()`;

It will return `true`, indicating that the modem is valid and was successfully saved.   
And `false` stating that the model is not valid and was not saved.

#### Errors

When the model is not saved you can redeem the error messages by the method `$model->getErrors()`, which returns an object `Illuminate\Support\MessageBag`

```php
$student = new Student();
$student->name 'Amanda M.';

if($student->save()):
 // do something
else:
 $errors = $student->getErrors();
 // do something
endif;
```

## Credits
- [Vinicius73](https://github.com/vinicius73)