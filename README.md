Phalcon-starterkit
==================

Phalcon is a wonderful C-based framework. It works fast and is really flexible, but its flexibility become a default as there is no fixed default structure when you start your project.

The Phalcon StarterKit was developed to let people start their project with a ready-to-use structure which doesn't need further configuration.

Structure
=========

```
app
  | cache
    | volt 
  | config
    | config.php 
    | di.php 
    | loader.php
    | routes.php
  | controllers
    | ControllerBase.php 
    | ErrorsController.php
    | HelloController.php
  | libraries 
  | models
  | views
    | errors 
      | show404.volt
    | hello
      | index.volt 
    | layouts
      |index.volt
  | public 
    | assets 
      | css 
      | img 
      | js
      | libs
    | uploads
  
```

Features
=========

- Landing page de départ
- Erreur 404
- Friendly Phalcon tools
- Base structure
- Htaccess base
- Système d'environnement (chargement automatique du module débug de phalcon quand on est en développement)
- Principaux services lancés (config, url, database, view, router, dispatcher (execution des différentes actions), volt)
- Système magique de config, autoload de fichiers de config
- Système de détection du base URI
