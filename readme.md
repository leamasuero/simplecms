# SimpleCMS

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
* Agregar en los layouts los CSS/JS 
  ``` html

    {{--Laravel Package - Lebenlabs\SimpleCMS - CSS --}}
    @include('Lebenlabs/SimpleCMS::Partials.header_css')

    .....

    {{--Laravel Package - Lebenlabs\SimpleCMS - JS --}}
    @include('Lebenlabs/SimpleCMS::Partials.header_js')
  ```
