Fordpress framework pour Wordpress
==================================

Structure des fichiers
----------------------

```
nom-du-theme
|    assests
|    |   stylesheets
|    |   javascripts
|    |   icons
|    |   |   generic.png
|    |   images
|    |   ...
|    framework
|    |   Florent
|    |   |   Fordpress.php
|    src
|    |   Acme
|    |   |   FooController.php
|    views
|    |   layouts
|    |   |   default.php
|    |   partials
|    |   welcome.php
|    |   error_404.php
|    404.php
|    index.php
|    style.css
|    screenshot.png
```

Installation
------------

Créer un fichier composer.json à la racine de l'installation WordPress et remplir avec :

```
{
    "autoload": {
        "psr-0": {
            "Florent": "wp-content/themes/<NOM-DU-THEME>/framework/",
            "<NAMESPACE>": "wp-content/themes/<NOM-DU-THEME>/src/"
        }
    }
}
```

Lancer la commande : php composer.phar install

Insérer la ligne suivante dans le fichier functions.php :

```
require_once ABSPATH . 'vendor/autoload.php';
```

Activer le thème

TODO
----

* Panneaux d'options
* CSS pour l'écran de login
* Enregistrement de sidebars