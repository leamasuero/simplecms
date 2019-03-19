# SimpleCMS

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require lebenlabs/simplecms
```

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email devops@lebenlabs.com instead of using the issue tracker.

## Credits

- [Lebenlabs][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lebenlabs/menu.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lebenlabs/menu.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lebenlabs/menu/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/lebenlabs/menu
[link-downloads]: https://packagist.org/packages/lebenlabs/menu
[link-travis]: https://travis-ci.org/lebenlabs/menu
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/lebenlabs
[link-contributors]: ../../contributors




## Cosas a editar

* Agregar package en composer.json (Ver de mejorar esto)
  ``` php    
    "autoload": {
        "psr-4": {
            // ....

            "Lebenlabs\\SimpleCMS\\": "packages/Lebenlabs/simplecms/src"
        },
        //...
    },
  ``` 

* Agregar mapping de entidaddes en el config/doctrine.php (VER DE QUITAR ESTO)
  ``` php
    'paths'         => [
        ....
    
        base_path('packages/Lebenlabs/SimpleCMS/src/Models'),
    ],
  ``` 

* Correr seeds package  
  ``` bash
      php artisan db:seed --class='Lebenlabs\SimpleCMS\Database\Seeds\PackageDatabaseSeeder'
  ``` 
* En el authenticable
  ``` php
      /* --------------------------*/
      
      abstract class Usuario implements Authenticatable, CanResetPassword, CanEditMenu, CanEditMenuItem
      
      /* --------------------------*/
      

        /**
         * Returns true if the Entity can edit SimpleCMS
         *
         * @return boolean
         */
       public function canEditMenu()
       {
           return $this->esAdministrador();
       }
        
       /**
         * Returns true if the Entity can edit SimpleCMS Item
         *
         * @return bool
        */
       public function canEditMenuItem()
       {
           return $this->esAdministrador();
       }
    
       /* --------------------------*/      
      
  ``` 
  
  * Config de filesystems
  ``` php  
      // Added for Lebenlabs\SimpleCMS package
      'simplecms_imagenes' => [
          'driver' => 'local',
          'root' => storage_path('app/public/lebenlabs_simplecms/imagenes/publicaciones'),
      ],
  ``` 
