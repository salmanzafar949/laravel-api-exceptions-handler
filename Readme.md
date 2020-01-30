# laravel-api-exceptions-handler

![stars](https://img.shields.io/github/stars/salmanzafar949/laravel-api-exceptions-handler)
![issues](https://img.shields.io/github/issues/salmanzafar949/laravel-api-exceptions-handler)
![forks](https://img.shields.io/github/forks/salmanzafar949/laravel-api-exceptions-handler)
![license](https://img.shields.io/github/license/salmanzafar949/laravel-api-exceptions-handler)
[![Total Downloads](https://img.shields.io/packagist/dt/salmanzafar/laravel-api-exceptions-handler?style=flat-square)](https://packagist.org/packages/salmanzafar/laravel-jwt-auto-installer)

It's a Laravel package that makes it easy to handle and customize api exceptions and responses and also support for model uuid

## Table of contents
* [Installation](#installation)
* [Api Exceptions](#api-exceptions)
* [Custom Api Responses](#custom-api-responses)
* [Model Uuid](#model-uuid)
* [Publishing files / configurations](#publishing-files)


#Installation
```bash
composer require salmanzafar/laravel-api-exceptions-handler --dev
```
## Enable the package (Optional)
This package implements Laravel auto-discovery feature. After you install it the package provider and facade are added automatically for laravel >= 5.5.

# Api Exceptions

There are many cases in which you want to return a custom response to an api instead of a default response

For example you have a api to get user and you want to return a custom response instead of laravel default response

```php
public function getUser(User $user)
{
  return $user;
}
```

in above case by default laravel will throw 404 exception if user is not found.
now let's see how we can customize this:

Go to `app\Exceptions\Handler.php` and copy and paste the below part

```php
namespace App\Exceptions;
 
use Exception;
use App\Exceptions\ExceptionTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
 
class Handler extends ExceptionHandler
{
     use ExceptionTrait;
 
     /**
      * A list of the exception types that are not reported.
      *
      * @var array
      */
     protected $dontReport = [
         //
     ];
 
     /**
      * A list of the inputs that are never flashed for validation exceptions.
      *
      * @var array
      */
     protected $dontFlash = [
         'password',
         'password_confirmation',
     ];
 
     /**
      * Report or log an exception.
      *
      * @param  \Exception  $exception
      * @return void
      */
     public function report(Exception $exception)
     {
         parent::report($exception);
     }
 
     /**
      * Render an exception into an HTTP response.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \Exception  $exception
      * @return \Illuminate\Http\Response
      */
     public function render($request, Exception $exception)
     {
         if ($request->expectsJson())
         {
             return $this->ApiExceptions($request,$exception);
         }
 
         return parent::render($request, $exception);
     }
}
```

  - `ExceptionTrait` has all the exceptions handling
  - In `render function` we've added a conditional check add then returned `apiExceptions`

Now it'll return a the json customized response instead of default laravel response (see below):

```json5
{
   "error": "Model Not found"
}
```

### Custom Api Responses

There are plenty of cases where ypu want to customize the laravel default validation/form request response so in thise cas this will help you customizing your response

Let's see the default validation response for laravel

```json5
{
  "message": "The given data is invalid",
  "errors": {
      "name": ['name is require']
    }

}
```

Now Let's modify this by creating a form request e.g `CarRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Salman\ApiExceptionHandler\Concerns\MyValidationException;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new MyValidationException($validator);
    }
}
```

Now the api response will be:

```json5
{
  "error": "The name field is required"
}
```
What we did we just added `failedValidation` in our request and throwed our exception

you can easily modify the above the error based on your needs just publishing this this file ``MyValidationException``

### Model Uuid

There are many cases where we want to use `uuid` as `primray key` in our model now that is also easier you can use `uuid` as `pk` in a jiffy

```php
namespace App;

use \Salman\ApiExceptionHandler\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
   use UsesUuid;
}
``` 
By just using `UsesUuid` in your model now you have `uuid` as `pk` in your model. don't forget to make changes in migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();            
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

```

### Publishing files
The below command will publish every thing inside `App\Concerns` folder

```php artisan publish:traits```

### Tested on php 7.4 and laravel 6^
