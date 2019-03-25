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




## Instalación / Configuración
* Requiere que el SimpleStorageService este instalado (VER DE CREAR ESTE PACKAGE)
* Agregar submodulo en la carpeta packages/Lebenlabs 
  ``` bash
    git submodule add https://github.com/leamasuero/simplecms  
  ``` 
* Editar composer.json agregando (y/o corroborando) lo siguiente:
  ``` php    
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "path",
            "url": "./packages/Lebenlabs/simplecms/"
        }
    ]
  ``` 
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
* Ejecutar composer require del package (No fue probado)
  ``` bash    
    composer require lebenlabs/simplecms:dev-master
  ``` 
* Agregar Service provider en config/app.pp
  ``` php   
    /**
     * Lebenlabs\SimpleCMS Package
     */
     Lebenlabs\SimpleCMS\SimpleCMSServiceProvider::class,
  ```         

* Ejecutar un composer install (No fue probado)
  ``` bash
    composer install
  ```       
* Ejecutar un composer dump-autoload
  ``` bash
    composer dump-autoload
  ```     
* Agregar mapping de entidaddes en el config/doctrine.php (VER DE QUITAR ESTO)
  ``` php
    'paths'         => [
        ....
    
        base_path('packages/Lebenlabs/SimpleCMS/src/Models'),
    ],
  ``` 
* Correr publish del package.Elegir el package en la pregunta (Provider: Lebenlabs\SimpleCMS\SimpleCMSServiceProvider)
  ``` bash
      php artisan vendor:publish
  ``` 
* Ejecutar un composer dump-autoload
  ``` bash
    composer dump-autoload
  ```         
* Config de filesystems
  ``` php  

      // Added for SimpleStorage package
      'archivos' => [
          'driver' => 'local',
          'root' => storage_path('app/archivos'),
      ],
  
      // Added for Lebenlabs\SimpleCMS package
      'simplecms_imagenes' => [
          'driver' => 'local',
          'root' => storage_path('app/public/lebenlabs_simplecms/imagenes/publicaciones'),
      ],
  ```   
* Ejecutar migrations
* Correr seeds package  
  ``` bash
      php artisan db:seed --class='Lebenlabs\SimpleCMS\Database\Seeds\PackageDatabaseSeeder'
  ``` 
* Configurar config/doctrine.php
  ``` php
        'paths'         => [
          base_path('app/Models'),

          // SimpleStorage package
          base_path('packages/Lebenlabs/simplestorage/src/Models'),

          // Lebenlabs/SimpleCMS package
          base_path('packages/Lebenlabs/simplecms/src/Models'),
      ],  
  
      'extensions'                 => [
        // ....
        LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
  
  ``` 
* En el authenticable
  ``` php
      /* --------------------------*/
      
      abstract class Usuario implements Authenticatable, CanResetPassword, CanEditMenu, CanEditMenuItem, CanManagePublicaciones, CanViewPublicacion
      
      /* --------------------------*/
      

    /**
     * Returns true if the Entity can edit Menu
     *
     * @return boolean
     */
    public function canEditMenu()
    {
        return $this->esAdministrador();
    }

    /**
     * Returns true if the Entity can edit Menu Item
     *
     * @return bool
     */
    public function canEditMenuItem()
    {
        return $this->esAdministrador();
    }

    /**
     * Returns true if the Entity can manage publicaciones
     *
     * @return bool
     */
    public function canManagePublicaciones()
    {
        return $this->esAdministrador();
    }

    /**
     * @param Publicacion $publicacion
     * @return bool
     */
    public function canViewPublicacion(Publicacion $publicacion)
    {
        if ($this->esAdministrador()) {
            return true;
        }

        if ($publicacion->getPublicada()) {
            return true;
        }

        return false;
    }
    
       /* --------------------------*/      
      
  ``` 
  

* Generar doctrine proxies
* Incluir SimpleCMS Menú item en backend
  ``` php
    {{--Laravel Package - Lebenlabs\SimpleCMS--}}
    @include('Lebenlabs/SimpleCMS::Partials.header_shortcut')
  ```
* Editar las vistas que fueron publisheadas mas arriba 
