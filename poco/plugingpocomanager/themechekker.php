<?php
/**
 * Plugin Name: Poco Theme Checker and Installer
 * Description: Vérifie le thème Poco, les widgets Elementor, les liaisons et l'installation de la démo. Fournit les fichiers manquants si nécessaire depuis GitHub, et inclut des outils de débogage.
 * Version: 1.1
 * Author: Tourak Adnan
 */

// Vérification du thème activé
function verify_active_theme() {
    $current_theme = wp_get_theme();
    if ($current_theme->get('Name') !== 'Poco') {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Le thème Poco n\'est pas activé. Veuillez activer le thème Poco pour utiliser toutes les fonctionnalités.</p></div>';
        });
    }
}
add_action('after_setup_theme', 'verify_active_theme');

// Vérification de la présence des classes CSS et des balises HTML
function verify_html_structure() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var missingElements = [];
        
        // Liste des éléments et classes à vérifier
        var elementsToCheck = [
            '.elementor-section',
            '.elementor-widget',
            '.revslider-initialised'
        ];
        
        elementsToCheck.forEach(function(selector) {
            if (!document.querySelector(selector)) {
                missingElements.push(selector);
            }
        });

        if (missingElements.length > 0) {
            console.error('Poco Theme Checker: Les éléments suivants sont manquants:', missingElements);
            alert('Poco Theme Checker: Certains éléments nécessaires sont manquants. Consultez la console pour plus de détails.');
        } else {
            console.log('Poco Theme Checker: Tous les éléments nécessaires sont présents.');
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'verify_html_structure');

// Activer les widgets nécessaires
function activate_necessary_widgets() {
    if (class_exists('Elementor\Plugin')) {
        $elementor = \Elementor\Plugin::instance();

        // Liste des widgets à vérifier et activer
        $widgets_to_check = [
            'Elementor\Widgets\Brand',
            'Elementor\Widgets\Call_To_Action',
            'Elementor\Widgets\Categories_Carousel',
            'Elementor\Widgets\Header_Group',
            'Elementor\Widgets\Image_Carousel',
            'Elementor\Widgets\Instagram',
            'Elementor\Widgets\Mailchimp',
            'Elementor\Widgets\Menu_Carousel',
            'Elementor\Widgets\Menu_List',
            'Elementor\Widgets\Nav_Menu',
            'Elementor\Widgets\Posts_Grid',
            'Elementor\Widgets\Product_Categories',
            'Elementor\Widgets\Product_Tab',
            'Elementor\Widgets\Products',
            'Elementor\Widgets\Rev_Slider',
            'Elementor\Widgets\Search',
            'Elementor\Widgets\Site_Logo',
            'Elementor\Widgets\Tab_Hover',
            'Elementor\Widgets\Team_Box',
            'Elementor\Widgets\Team_Carousel',
            'Elementor\Widgets\Testimonial',
            'Elementor\Widgets\Timeline',
            'Elementor\Widgets\Vertical_Menu',
            'Elementor\Widgets\Vertical',
            'Elementor\Widgets\Video'
        ];

        foreach ($widgets_to_check as $widget) {
            if (!$elementor->widgets_manager->is_widget_registered($widget)) {
                // Activer le widget s'il n'est pas déjà activé
                $elementor->widgets_manager->register_widget_type(new $widget());
            }
        }
    }
}
add_action('elementor/widgets/widgets_registered', 'activate_necessary_widgets');

// Assurer le chargement des fichiers CSS et JS
function ensure_css_js_loaded() {
    $theme_dir = get_template_directory_uri();
    $css_files = [
        'style.css',
        'theme.css',
        'woocommerce.css',
        'elementor.css'
    ];

    foreach ($css_files as $css_file) {
        if (!file_exists(get_template_directory() . '/' . $css_file)) {
            download_missing_file($css_file);
        }
        wp_enqueue_style('poco-theme-' . $css_file, $theme_dir . '/' . $css_file, [], '1.0.0', 'all');
    }

    $js_files = [
        'theme.js',
        'woocommerce.js',
        'elementor.js'
    ];

    foreach ($js_files as $js_file) {
        if (!file_exists(get_template_directory() . '/' . $js_file)) {
            download_missing_file($js_file);
        }
        wp_enqueue_script('poco-theme-' . $js_file, $theme_dir . '/' . $js_file, ['jquery'], '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'ensure_css_js_loaded');

// Fonction pour télécharger les fichiers manquants depuis GitHub
function download_missing_file($file_name) {
    $file_url = 'https://raw.githubusercontent.com/NitroD3/pocowptheme/main/poco/' . $file_name;
    $upload_dir = wp_upload_dir();
    $response = wp_remote_get($file_url);

    if (is_array($response) && !is_wp_error($response)) {
        $file_contents = wp_remote_retrieve_body($response);
        file_put_contents(get_template_directory() . '/' . $file_name, $file_contents);
    } else {
        add_action('admin_notices', function() use ($file_name) {
            echo '<div class="notice notice-error"><p>Impossible de télécharger le fichier manquant: ' . $file_name . '. Veuillez vérifier la connexion ou fournir le fichier manuellement.</p></div>';
        });
    }
}

// Vérification de l'installation de la démo
function check_demo_installation() {
    $demo_installed = get_option('poco_demo_installed');
    if (!$demo_installed) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>La démo Poco n\'est pas installée. Veuillez installer la démo pour utiliser toutes les fonctionnalités.</p></div>';
        });
    }
}
add_action('admin_init', 'check_demo_installation');

// Fonction pour définir l'option de la démo installée (à appeler après l'installation de la démo)
function set_demo_installed_option() {
    update_option('poco_demo_installed', true);
}

// Fonction pour importer les données de démo
function import_demo_data() {
    if (isset($_POST['poco_import_demo_data']) && check_admin_referer('poco_import_demo_nonce', 'poco_import_demo_nonce')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/import.php';

        // Définir l'URL du fichier de démo
        $demo_file_url = 'https://raw.githubusercontent.com/NitroD3/pocowptheme/main/poco/dummy-data/content.xml';

        // Téléchargez le fichier de démo
        $demo_file = download_url($demo_file_url);
        if (is_wp_error($demo_file)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Impossible de télécharger le fichier de démo. Veuillez vérifier la connexion ou fournir le fichier manuellement.</p></div>';
            });
            return;
        }

        // Importer les données de démo
        $importer = new WP_Import();
        $importer->import($demo_file);
        unlink($demo_file);

        set_demo_installed_option();
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Les données de la démo Poco ont été importées avec succès.</p></div>';
        });
    }
}
add_action('admin_init', 'import_demo_data');

// Activer le débogage
function poco_enable_debug() {
    if (isset($_POST['poco_enable_debug']) && check_admin_referer('poco_debug_nonce', 'poco_debug_nonce')) {
        if (!defined('WP_DEBUG')) {
            define('WP_DEBUG', true);
        }
        if (!defined('WP_DEBUG_LOG')) {
            define('WP_DEBUG_LOG', true);
        }
        if (!defined('WP_DEBUG_DISPLAY')) {
            define('WP_DEBUG_DISPLAY', false);
        }

        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Le mode débogage a été activé.</p></div>';
        });
    }
}
add_action('admin_init', 'poco_enable_debug');

// Fonction pour lire le contenu d'un fichier en toute sécurité
function get_file_contents($file) {
    if (file_exists($file)) {
        return file_get_contents($file);
    }
    return 'Aucun log trouvé.';
}

// Ajouter une page d'options pour importer les données de démo et activer le débogage
function poco_demo_import_page() {
    add_menu_page('Poco Theme Installer', 'Poco Installer', 'manage_options', 'poco-demo-import', 'poco_demo_import_page_html', 'dashicons-admin-tools', 80);
}
add_action('admin_menu', 'poco_demo_import_page');

function poco_demo_import_page_html() {
    ?>
    <div class="wrap">
        <h1>Importer la Démo Poco</h1>
        <form method="post" action="">
            <?php wp_nonce_field('poco_import_demo_nonce', 'poco_import_demo_nonce'); ?>
            <p>
                <input type="submit" name="poco_import_demo_data" class="button button-primary" value="Importer la démo">
            </p>
        </form>

        <h1>Outils de Débogage</h1>
        <form method="post" action="">
            <?php wp_nonce_field('poco_debug_nonce', 'poco_debug_nonce'); ?>
            <p>
                <input type="submit" name="poco_enable_debug" class="button button-secondary" value="Activer le débogage">
            </p>
        </form>

        <h1>Logs</h1>
        <pre><?php echo esc_html(get_file_contents(WP_CONTENT_DIR . '/debug.log')); ?></pre>
    </div>
    <?php
}
?>
