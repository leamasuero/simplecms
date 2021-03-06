# SimpleCMS

## Instalación / Configuración
* Instalar package
  ``` bash
    composer require "lebenlabs/simplecms=0.0.*"
  ```

* Ejecutar un composer dump-autoload
  ``` bash
    composer dump-autoload
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
  
* Agregar mapping de entidaddes en el config/doctrine.php 
  ``` php
    'paths'         => [
       ....
  
        // Lebenlabs/SimpleCMS package
        base_path('vendor/lebenlabs/simplecms/src/Models'),

        // SimpleStorage package
        base_path('vendor/lebenlabs/simplestorage/src/Models'),
      ....
  ]
  ```
* Generar doctrine proxies
* Incluir SimpleCMS Menú item en backend
  ``` php
    {{--Laravel Package - Lebenlabs\SimpleCMS--}}
    @include('Lebenlabs/SimpleCMS::Partials.header_shortcut')
  ```
* Editar las vistas que fueron publisheadas mas arriba 
* Agregar en los layouts los CSS/JS 
  ``` php

    {{--Laravel Package - Lebenlabs\SimpleCMS - CSS --}}
    @include('Lebenlabs/SimpleCMS::Partials.header_css')

    .....

    {{--Laravel Package - Lebenlabs\SimpleCMS - JS --}}
    @include('Lebenlabs/SimpleCMS::Partials.header_js')
  ```
* Generar JS/CSS del package. Agregar las lineas a webpack.mix.js
  ``` js
   mix.
     -----
      // Lebenlabs - SimpleCMS - JS
    .js('vendor/lebenlabs/simplecms/src/Resources/Assets/js/SimpleCMS', 'public/js')
    .js('vendor/lebenlabs/simplecms/src/Resources/Assets/js/SummernoteHelper', 'public/js')
    .js('vendor/lebenlabs/simplecms/src/Resources/Assets/js/bootstrap-datetimepicker.min', 'public/js')
    
    ----
    // Lebenlabs - SimpleCMS - CSS
    .sass('vendor/lebenlabs/simplecms/src/Resources/Assets/css/bootstrap-datetimepicker.min', 'public/css')
  ```
* Compilar assets
  ```bash 
    npm run dev
  ```
* Crear menu ejecutando el siguiente comando:
  ```bash 
    php artisan lebenlabs:simplecms:create-menu 
  ```
  NOTA: Hoy en día solo se soporta un solo Menú - IMPROVE THIS
* Cargar los menu items que se pretendan utilizar utilizando un view composer registrado desde la aplicación (IMPROVE: seleccionar determinado menu - posibilidad de varios)
  ``` php
    // ComposerServiceProvider
    public function boot()
    {
        View::composer(
            'Lebenlabs/SimpleCMS::Partials.Menu.show', SimpleCMSViewComposer::class
        );
    }
  
    // SimpleCMSViewComposer
    public function compose(View $view)
    {
        $view->with('rootMenuItems', $this->simpleCMS->findAllRootMenuItems());
    }

  ``` 

