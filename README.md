# VN-Geography Manager Package for Laravel

- [VN-Geography Manager Package for Laravel](#vn-geography-manager-package-for-laravel)
  - [Installation](#installation)
  - [Composer](#composer)
  - [Service Provider](#service-provider)
  - [Config and Migration](#config-and-migration)
  - [Environment](#environment)
- [Configuration](#configuration)
    - [URL namespace](#url-namespace)
    - [Model and Transformer](#model-and-transformer)
    - [Auth middleware](#auth-middleware)
    
    
    
VN-Geography management package for managing provinces, districts and wards of Vietnam in laravel framework

## Installation

### Composer

To include the package in your project, Please run following command.

```
composer require webpress/vn-geography
```

### Service Provider

In your  `config/app.php`  add the following Service Providers to the end of the  `providers`  array:

```php
'providers' => [
        ...
    VCComponent\Laravel\Geography\Providers\GeographyServiceProvider::class,
],
```

### Config and Migration

Run the following commands to publish configuration and migration files.

```
php artisan vendor:publish --provider="VCComponent\Laravel\Geography\Providers\GeographyServiceProvider"
php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
php artisan vendor:publish --provider "Prettus\Repository\Providers\RepositoryServiceProvider"
```
Create tables:

```
php artisan migrate
```

### Environment

In `.env` file, we need some configuration.

```
API_PREFIX=api
API_VERSION=v1
API_NAME="Your API Name"
API_DEBUG=false
```

## Configuration

### URL namespace

To avoid duplication with your application's api endpoints, the package has a default namespace for its routes which is  `post-management`. For example:

    {{url}}/api/vn-geography/admin/provinces
    {{url}}/api/vn-geography/admin/districts
    {{url}}/api/vn-geography/admin/wards

You can modify the package url namespace to whatever you want by modifying the `GEOGRAPHY_COMPONENT_NAMESPACE` variable in `.env` file.

    GEOGRAPHY_COMPONENT_NAMESPACE="your-namespace"

### Model and Transformer

You can use your own model and transformer class by modifying the configuration file `config\geography.php`

```php
    'models' => [
        'province' => VCComponent\Laravel\Geography\Entities\Province::class,
        'district' => VCComponent\Laravel\Geography\Entities\District::class,
        'ward' => VCComponent\Laravel\Geography\Entities\Ward::class,
    ],

    'transformers' => [
        'province' => VCComponent\Laravel\Geography\Transformers\ProvinceTransformer::class,
        'district' => VCComponent\Laravel\Geography\Transformers\DistrictTransformer::class,
        'ward' => VCComponent\Laravel\Geography\Transformers\WardTransformer::class,
    ],
```

Model Province

```php
<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_CITY       = 1;
    const TYPE_PROVINCIAL = 2;

    protected $table = 'provinces';

    protected $fillable = [
        'name',
        'type',
        'status',
        'order',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
```

Model District

```php
namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_CITY     = 1; // Thành Phố
    const TYPE_TOWNSHIP = 2; // Quận
    const TYPE_DISTRICT = 3; // Huyện
    const TYPE_TOWN     = 4; // Thị Xã

    protected $table = 'districts';

    protected $fillable = [
        'name',
        'type',
        'location',
        'province_id',
        'status',
        'order',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
```

Model Ward

```php
<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_WARD    = 1; // Phường
    const TYPE_COMMUNE = 2; // Xã
    const TYPE_TOWN    = 3; // Thị trấn

    protected $table = 'wards';

    protected $fillable = [
        'name',
        'type',
        'district_id'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
```

### Auth middleware

Configure auth middleware in configuration file `config\geography.php`

```php
'auth_middleware' => [
        'admin'    => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
        'frontend' => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
],
```

## Routes

The api endpoint should have these format:

Province

| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/{namespace}/admin/provinces               |
| GET    | /api/{namespace}/admin/provinces/{id}          |
| POST   | /api/{namespace}/admin/provinces/list          |
| POST   | /api/{namespace}/admin/provinces               |
| PUT    | /api/{namespace}/admin/provinces/{id}          |
| PUT    | /api/{namespace}/admin/provinces/{id}/status   |
| DELETE | /api/{namespace}/admin/provinces/{id}          |
| ----   | ----                                           |
| GET    | /api/{namespace}/provinces                     |
| GET    | /api/{namespace}/provinces/{id}                |
| POST   | /api/{namespace}/provinces/list                |

District

| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/{namespace}/admin/districts               |
| GET    | /api/{namespace}/admin/districts/{id}          |
| POST   | /api/{namespace}/admin/districts/list          |
| POST   | /api/{namespace}/admin/districts               |
| PUT    | /api/{namespace}/admin/districts/{id}          |
| PUT    | /api/{namespace}/admin/districts/{id}/status   |
| DELETE | /api/{namespace}/admin/districts/{id}          |
| ----   | ----                                           |
| GET    | /api/{namespace}/districts                     |
| GET    | /api/{namespace}/districts/{id}                |
| POST   | /api/{namespace}/districts/list                |

Ward

| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/{namespace}/admin/wards                   |
| GET    | /api/{namespace}/admin/wards/{id}              |
| POST   | /api/{namespace}/admin/wards/list              |
| POST   | /api/{namespace}/admin/wards                   |
| PUT    | /api/{namespace}/admin/wards/{id}              |
| PUT    | /api/{namespace}/admin/wards/{id}/status       |
| DELETE | /api/{namespace}/admin/wards/{id}              |
| ----   | ----                                           |
| GET    | /api/{namespace}/wards                         |
| GET    | /api/{namespace}/wards/{id}                    |
| POST   | /api/{namespace}/wards/list                    |



