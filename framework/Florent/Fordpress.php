<?php

namespace Florent;

/**
 * Fordpress est un framework personnel pour WordPress
 * 
 * @version 1.0
 */
class Fordpress
{
	/**
	 * Affiche la(les) valeur(s) d'une variable, de manière à ce qu'elle soit lisible.
	 *
	 * @var mixed
	 **/
	public static function debug($var = null) {
	    echo '<pre>';
	    print_r($var);
	    echo '</pre>';
	}

	/**
     * Fonction render()
     *
     * Rendre un template avec éventuellement des variables et un layout
     *
     * @param string $template
     * @param array $vars
     * @param string $layout
     * @since 1.0
     */
    public static function render($template, $vars = array(), $layout = null)
    {
        ob_start();
        if ( !empty( $vars ) ) {
            extract( $vars );
        }
        require_once TEMPLATEPATH . '/views/' . $template . '.php';
        $content_for_layout = ob_get_clean();
        $layout = ! empty($layout) ? $layout : 'default';
        require_once TEMPLATEPATH . '/views/layouts/' . $layout . '.php';
    }

    /**
     * Fonction throw_404()
     *
     * Lance un template d'erreur 404
     *
     * @since 1.0
     */
    public static function throw_404()
    {
        if (!file_exists(TEMPLATEPATH . '/views/error_404.php')) {
            exit('404 template is required!');
        }
        self::render('error_404');
    }
	
	/**
	 * Fonction add_stylesheets()
	 *
	 * Ajoute les fichiers CSS
	 *
	 * @param array $styles
	 * @since 1.0
	 */
	public static function add_stylesheets($styles = array())
	{
		add_action('wp_enqueue_scripts', function() use ($styles) {
			foreach ($styles as $style) {
				wp_register_style($style[0], get_template_directory_uri() . '/' . $style[1]);
				wp_enqueue_style($style[0]);
			}
		});
	}
	
	/**
	 * Fonction add_javascripts()
	 *
	 * Ajoute les fichiers javascript
	 *
	 * @param array $scripts
	 * @since 1.0
	 */
	public static function add_javascripts($scripts = array())
	{
		add_action('wp_enqueue_scripts', function() use ($scripts) {
			foreach ($scripts as $script) {
				wp_register_script($script[0], get_template_directory_uri() . '/' . $script[1]);
				wp_enqueue_script($script[0]);
			}
		});
	}
	
	/**
	 * Fonction passing_params_from_php_to_js()
	 *
	 * Passer des paramètres depuis PHP vers Javascript
	 *
	 * @param array $args
	 * @since 1.0
	 */
	public static function pass_params_from_php_to_js($args = array())
	{
		add_action('wp_enqueue_scripts', function() use ($args) {
			foreach ($args as $arg) {
				wp_localize_script($arg[0], $arg[1], $arg[2]);
			}
		});
	}
	
	/**
	 * Fonction supports()
	 *
	 * Ajout des fonctionnalités WordPress
	 *
	 * @param array $args
	 * @since 1.0
	 */
	public static function supports($features = array())
	{
		foreach ($features as $feature) {
			if ( ! is_array($feature)) {
				add_theme_support($feature);
			} else {
				add_theme_support($feature[0], $feature[1]);
			}
		}
	}
	
	/**
	 * Fonction add_image_sizes()
	 *
	 * Ajouter des nouveaux formats d’images
	 *
	 * @param array $sizes
	 * @since 1.0
	 */
	public static function add_image_sizes($sizes = array())
	{
		foreach ($sizes as $size) {
			add_image_size($size[0], $size[1], $size[2], $size[3]);
		}
	}
	
	/**
	 * Fonction add_menus()
	 *
	 * Ajouter des emplacements de menu
	 *
	 * @param array $locations
	 * @since 1.0
	 */
	public static function add_menus($locations = array())
	{
		if (function_exists('add_theme_support')) {
			add_theme_support('menus');
		}
		register_nav_menus($locations);
	}
	
	/**
	 * Fonction excerpt()
	 *
	 * Retourne un extrait
	 *
	 * @param string $string
	 * @param int $lenght
	 * @since 1.0
	 */
	public static function excerpt($string, $length = 150) {
		return (strlen($string) > $length ? strip_tags(substr(substr($string,0,$length),0,
		strrpos(substr($string,0,$length)," ")))."..." : strip_tags($string));
	}
	
	/**
	 * Fonction add_google_analytics()
	 *
	 * Ajoute automatiquement le code javascript de Google Analytics
	 *
	 * @param array $args
	 * @since 1.0
	 */
	public static function add_google_analytics($UA = false)
	{
		
		if ( $UA || preg_match('/^UA-\d+-\d+$/', $UA) == 1 ) {
			return "<script>var _gaq=_gaq||[];_gaq.push(['_setAccount','" . $UA . "']);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s)})();</script>";
		}
		
	}

	/**
	 * Fonction add_post_types
	 *
	 * Usage :
	 *
		Theme::add_post_types(array(
			array( 'livres', 'livres', 'livre', 1, 100, array('title', 'thumbnail'), 'icon-books.png' ),
			array( 'evenements', 'événements', 'événement', 1, 5, array('title', 'editor'), 'icon-events.png' )
		));
	 *
	 * @param array $post_types
	 * @param array $post_types[index][0] Nom du post type à enregister, idéalement le pluriel en minuscules et sans accent (Requis)
	 * @param array $post_types[index][1] Nom du post type au pluriel (Requis)
	 * @param array $post_types[index][2] Nom du post type au singulier (Requis)
	 * @param array $post_types[index][3] Valeur 1 pour masculin, 0 pour féminin (Requis)
	 * @param array $post_types[index][4] Position (5 par défaut)
	 * @param array $post_types[index][5] Support (array('title', 'editor') par défaut)
	 * @param array $post_types[index][6] Nom du fichier d'icône placé dans "assets/icons" (generic.png par défaut)
	 * @since 1.0
	 */
	public static function add_post_types($post_types = array())
	{
		foreach ($post_types as $post_type) {
			
			$labels = array(
			    'name'               => ucfirst($post_type[1]),
			    'singular_name'      => ucfirst($post_type[2]),
			    'menu_name'          => ucfirst($post_type[1]),
			    'all_items'          => ($post_type[3] == 1) ? "Tous les $post_type[1]" : "Toutes les $post_type[1]",
			    'add_new'            => 'Ajouter',
			    'new_item'           => 'Ajouter',
			    'add_new_item'       => ($post_type[3] == 1) ? "Ajouter un $post_type[1]" : "Ajouter une $post_type[1]",
			    'edit_item'          => ($post_type[3] == 1) ? "Modifier le $post_type[2]" : "Modifier la $post_type[2]",
			    'search_items'       => "Rechercher dans les $post_type[1]",
			    'not_found'          => ($post_type[3] == 1) ? "Aucun $post_type[2] trouvé." : "Aucune $post_type[2] trouvée.",
			    'not_found_in_trash' => ($post_type[3] == 1) ? "Aucun $post_type[2] trouvé dans la corbeille." : "Aucune $post_type[2] trouvée dans la corbeille.",
			    'parent_item_colon'  => '',
			);

			$args = array(
			    'labels'             => $labels,
			    'public'             => true,
			    'publicly_queryable' => true,
			    'show_ui'            => true,
			    'show_ui_menu'       => true,
			    'query_var'          => true,
			    'capability_type'    => 'post',
			    'has_archive'        => true,
			    'hierarchical'       => true,
			    'menu_position'      => (!empty($post_type[4])) ? $post_type[4] : 5,
			    'supports'           => (!empty($post_type[5])) ? $post_type[5] : array('title', 'editor'),
			    'menu_icon'          => (!empty($post_type[6])) ? get_bloginfo( 'template_directory' ) . '/assets/icons/' . $post_type[6] : get_bloginfo( 'template_directory' ) . '/assets/icons/generic.png',
			);

			register_post_type( $post_type[0], $args );
		}
	}

	/**
	 * Fonction add_taxonomies
	 *
	 * Usage :
	 *
		Theme::add_taxonomies(array(
			array( 'rayons', 'rayons', 'rayon', 1, 'livres' )
		));
	 *
	 * @param array $taxonomies
	 * @param array $taxonomies[index][0] Nom de la taxonomie à enregister, idéalement pluriel en minuscules sans accents (Requis)
	 * @param array $taxonomies[index][1] Nom de la taxonomie au pluriel (Requis)
	 * @param array $taxonomies[index][2] Nom de la taxonomie au singulier (Requis)
	 * @param array $taxonomies[index][3] Valeur 1 pour masculin, 0 pour féminin (Requis)
	 * @param array $taxonomies[index][4] Post type (Requis)
	 * @since 1.0
	 */
	public static function add_taxonomies($taxonomies = array())
	{
		foreach ($taxonomies as $taxonomy) {
			
			$labels = array(
			    'name' => ucfirst($taxonomy[1]),
			    'menu_name' => ucfirst($taxonomy[1]),
			    'singular_name' => ucfirst($taxonomy[2]),
			    'search_items' => "Rechercher dans les $taxonomy[1]",
			    'all_items' => ($taxonomy[3] == 1) ? "Tous les $taxonomy[1]" : "Toutes les $taxonomy[1]",
			    'parent_item' => ($taxonomy[3] == 1) ? "$taxonomy[2] parent" : "$taxonomy[2] parente",
			    'parent_item_colon' => ($taxonomy[3] == 1) ? ucfirst($taxonomy[2]) . " parent" : ucfirst($taxonomy[2]) . " parente",
			    'edit_item' => ($taxonomy[3] == 1) ? "Modifier le $taxonomy[2]" : "Modifier la $taxonomy[2]",
			    'update_item' => ($taxonomy[3] == 1) ? "Mettre à jour ce $taxonomy[2]" : "Mettre à jour cette $taxonomy[2]",
			    'new_item_name' => ($taxonomy[3] == 1) ? "Nouveau $taxonomy[2]" : "Nouvelle $taxonomy[2]",
			    'add_new_item' => ($taxonomy[3] == 1) ? "Ajouter un nouveau $taxonomy[2]" : "Ajouter une nouvelle $taxonomy[2]",
			);

			$args = array(
			    'labels' => $labels,
			    'hierarchical' => true,
			    'show_ui' => true,
			    'query_var' => true,
			    'rewrite' => array( 'slug' => $taxonomy[0], 'with_front' => false  )
			);
			
			register_taxonomy( $taxonomy[0], $taxonomy[4], $args );
		}
	}

	/**
	 * Fonction pagination()
	 *
	 * Gère une pagination via get_posts()
	 *
	 * @param array $options
	 * @param int $options['per_page'] Nombre de posts par page
	 * @param int $options['range'] Nombre de liens à afficher
	 * @param string $options['previous'] Texte pour le lien précédent
	 * @param string $options['next'] Texte pour le lien suivant
	 * @param string $options['first'] Texte pour le lien premier
	 * @param string $options['last'] Texte pour le lien dernier
	 * @param string $options['class'] Class ajoutée à la div pagination
	 * @param string $options['post_type'] Type de posts à requêter
	 * @return array Retourne un tableau contenant le HTML pour l'affichage de la pagination
	 * @since 1.0
	 */
	public static function pagination($options = array())
	{
		global $wp;

		$options['per_page'] = (!empty($options['per_page'])) ? $options['per_page'] : 10;
		$options['range'] = (!empty($options['range'])) ? $options['range'] : 3;
		$options['previous'] = (!empty($options['previous'])) ? $options['previous'] : 'Précédent';
		$options['next'] = (!empty($options['next'])) ? $options['next'] : 'Suivant';
		$options['first'] = (!empty($options['first'])) ? $options['first'] : '&lt;';
		$options['last'] = (!empty($options['last'])) ? $options['last'] : '&gt;';
		$options['class'] = (!empty($options['class'])) ? $options['class'] : 'pagination';
		$options['post_type'] = (!empty($options['post_type'])) ? $options['post_type'] : 'post';

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $count_posts = wp_count_posts($options['post_type'])->publish;
        $count_pages = ceil($count_posts / $options['per_page']);
        $current_url = home_url( $wp->request );
        $url = str_replace('/page/' . $paged, '', $current_url);

        $output = ( !empty($class) ) ? '<div class="' . $class . '">' : '<div class="pagination">';

        if ($count_pages > 1) {
        	$range_min = ($options['range'] % 2 == 0) ? ($options['range'] / 2) - 1 : ($options['range'] - 1) / 2;
            $range_max = ($options['range'] % 2 == 0) ? $range_min + 1 : $range_min;
            $page_min = $paged - $range_min;
            $page_max = $paged + $range_max;

            $page_min = ($page_min < 1) ? 1 : $page_min;
            $page_max = ($page_max < ($page_min + $options['range'] - 1)) ? $page_min + $options['range'] - 1 : $page_max;

            if ($page_max > $count_pages) {
                $page_min = ($page_min > 1) ? $count_pages - $options['range'] + 1 : 1;
                $page_max = $count_pages;
            }

            $page_min = ($page_min < 1) ? 1 : $page_min;

            // Premier
            if ( !empty($options['first']) && ($paged > ($options['range'] - $range_min)) && ($count_pages > $options['range']) ) {
                $output .= '<a href="' . $url . '/page/1">' . $options['first'] . '</a> ';
            }

            // Précédent
            if ( !empty($options['previous']) && $paged != 1 ) {
                $output .= '<a href="' . $url . '/page/' . ($paged - 1) . '">' . $options['previous'] . '</a> ';
            }

            // Liens page
            for ($i = $page_min; $i <= $page_max; $i++) {
                if ($i == $paged) {
                    $output .= '<span>' . $i . '</span> '; // current
                } else {
                    $output .= '<a href="' . $url . '/page/' . $i . '">'.$i.'</a> ';
                }
            }

            // Suivant
            if ( !empty($options['next']) && $paged < $count_pages ) {
                $output .= ' <a href="' . $url .'/page/' . ($paged + 1) . '">' . $options['next'] . '</a>';
            }

            // Dernier
            if ( !empty($options['last']) &&($paged < ($count_pages - $range_max)) && ($count_pages > $options['range']) ) {
                $output .= ' <a href="' . $url . '/page/' . $count_pages . '">' . $options['last'] . '</a> ';
            }
        }
        $output .= '</div>';

        $posts = get_posts(array(
			'posts_per_page' => $options['per_page'],
			'paged'          => $paged,
			'post_status'    => 'publish',
			'order_by'       => 'post_date',
			'order'          => 'DESC',
			'post_type'      => $options['post_type'],
        ));

		return array(
			'output' => $output,
			'posts'  => $posts
		);
		
	}	
}