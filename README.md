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

Debug
------------------------------------------------

```
Florent\FordPress::debug($var);
```

Séparation de la logique métier de la présentation
--------------------------------------------------

Créer un le template WordPress correspondant au [template hierarchy](http://codex.wordpress.org/Template_Hierarchy).

Initialiser le controller comme suit :

```
Acme\FooController::bar();
```

_Le namespace Acme doit être déplarer dans l'autoloader du composer.json._

Créer le controller dans le répertoire src/Acme/.

_Acme correspond au namespace déplarer dans composer.json._

Example d'un controller :

```
<?php

namespace Acme;

use Florent\FordPress;

class FooController
{
    public static function bar()
    {
        FordPress::render('welcome');
    }
}
```

Créer la vue dans le répertoire vue _views_ et l'afficher grace à la méthode render de la classe Fordpress.


Ajouter les feuilles de style à son thème proprement
----------------------------------------------------

Dans le fichier functions.php

```
Florent\FordPress::add_stylesheets(array(
    array('style', 'style.css'),
    array('style-min', 'assets/stylesheets/style.min.css')
));
```

Ajouter les javascripts à son thème proprement
----------------------------------------------

Dans le fichier functions.php

```
Florent\FordPress::add_javascripts(array(
    array('app', 'assets/javascripts/app.js')
));
```

Passer des paramètres depuis PHP vers Javascript
------------------------------------------------

Dans le fichier functions.php

```
Florent\FordPress::pass_params_from_php_to_js(array(
    array( 'app', 'params', array('foo' => 'bar', 'setting' => 123) )
));
```

Ajout des fonctionnalités WordPress
-----------------------------------

Dans le fichier functions.php

```
Florent\FordPress::supports(array(
    // 'post-formats',
    // array('post-thumbnails'),
    // array('post-thumbnails', array('post')),
    // 'custom-background',
    // 'custom-header',
    // 'automatic-feed-links',
    // 'menus'
));
```

Ajouter les tailles d'images
----------------------------

Dans le fichier functions.php

```
Florent\FordPress::add_image_sizes(array(
    array('square', 100, 100, true),
    array('rectangular', 600, 250, true),
));
```

Ajouter des emplacements de menu et active le support menus s'il n'est pas activé
---------------------------------------------------------------------------------

Dans le fichier functions.php

```
Florent\FordPress::add_menus(array(
    'navigation_principale' => 'Navigation principale',
    'footer' => 'Footer'
));
```

Ajout des types de contenu personnalisés
----------------------------------------

Dans le fichier functions.php

```
Florent\FordPress::add_post_types(array(
    array( 'livres', 'livres', 'livre', 1 ),
));
```

Ajout des taxonomies
--------------------

Dans le fichier functions.php

```
Florent\FordPress::add_taxonomies(array(
    array( 'rayons', 'rayons', 'rayon', 1, 'livres' ),
));
```

Include des partials dans les vues
----------------------------------

```
get_template_part('views/partials/<NOM-PARTIAL>');
```

Pour les champs personnalisés
-----------------------------

Utilisation de ACF : [http://www.advancedcustomfields.com/](http://www.advancedcustomfields.com/)

Extrait personnalisés dans les vues
-----------------------------------

```
echo Florent\FordPress::excerpt(get_the_content());
echo Florent\FordPress::excerpt(get_the_content(), 200);
```

Ajouter Google Analytics dans les vues
--------------------------------------

```
echo Florent\FordPress::add_google_analytics('UA-1234-56');
```


TODO
----

* Panneaux d'options
* CSS pour l'écran de login
* Enregistrement de sidebars