<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// サイト情報
define( 'HOME', home_url( '/' ) );
define( 'TITLE', get_option( 'blogname' ) );

// 状態
define( 'IS_ADMIN', is_admin() );
define( 'IS_LOGIN', is_user_logged_in() );
define( 'IS_CUSTOMIZER', is_customize_preview() );

// テーマディレクトリパス
define( 'T_DIRE', get_template_directory() );
define( 'S_DIRE', get_stylesheet_directory() );
define( 'T_DIRE_URI', get_template_directory_uri() );
define( 'S_DIRE_URI', get_stylesheet_directory_uri() );

define( 'THEME_NOTE', 'yukinari-kogyo' );

define( 'WPCF7_AUTOP', false );

error_reporting(0);

flush_rewrite_rules();

// 固定ページとMW WP Formでビジュアルモードを使用しない
// function stop_rich_editor($editor) {
//     global $typenow;
//     global $post;
//     if(in_array($typenow, array('page', 'post', 'blog', 'example', 'mw-wp-form'))) {
//         $editor = false;
//     }
//     return $editor;
// }

// add_filter('user_can_richedit', 'stop_rich_editor');

// エディター独自スタイル追加
//TinyMCE追加用のスタイルを初期化
if(!function_exists('initialize_tinymce_styles')) {
    function initialize_tinymce_styles($init_array) {
        //追加するスタイルの配列を作成
        $style_formats = array(
            array(
                'title' => '注釈',
                'inline' => 'span',
                'classes' => 'cmn_note'
            )
        );
        //JSONに変換
        $init_array['style_formats'] = json_encode($style_formats);
        return $init_array;
    }
}

add_filter('tiny_mce_before_init', 'initialize_tinymce_styles', 10000);


function post_menu_remove() { 
   remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_menu_remove'); 

function my_script_constants() {
?>
    <script type="text/javascript">
        var templateUrl = '<?php echo S_DIRE_URI; ?>';
        var baseSiteUrl = '<?php echo HOME; ?>';
        var themeAjaxUrl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
    </script>
<?php
}

add_action('wp_head', 'my_script_constants');
// CSS・スクリプトの読み込み
function theme_add_files() {
    global $post;

    wp_enqueue_style('c-reset', T_DIRE_URI.'/assets/css/reset.css', [], '1.0', 'all');
    wp_enqueue_style('c-animation', 'https://cdn.jsdelivr.net/animatecss/3.5.2/animate.min.css', [], '1.0', 'all');
    wp_enqueue_style('c-swiper-bundle', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', [], '1.0', 'all');
    wp_enqueue_style('c-common', T_DIRE_URI.'/assets/css/common.css', [], '1.0', 'all');
    wp_enqueue_style('c-style', T_DIRE_URI.'/assets/css/style.css', [], '1.0', 'all');
    wp_enqueue_style('c-theme', T_DIRE_URI.'/style.css', [], '1.0', 'all');

    // WordPress本体のjquery.jsを読み込まない
    if(!is_admin()) {
        wp_deregister_script('jquery');
    }

    wp_enqueue_script('s-jquery', T_DIRE_URI.'/assets/js/jquery.min.js', [], '1.0', false);
    wp_enqueue_script('s-swiper-bundle', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', [], '1.0', true);
    wp_enqueue_script('s-fontawesome', 'https://kit.fontawesome.com/8cbdf0a85f.js', [], '6.8.1', true);
    wp_enqueue_script('s-common', T_DIRE_URI.'/assets/js/common.js', [], '1.0', true);
	wp_enqueue_script('core-js', T_DIRE_URI.'/assets/js/core.js', [], '1.0', false);
    wp_enqueue_script('s-wow', T_DIRE_URI.'/assets/js/wow.js', [], '1.0', false);
}

add_action('wp_enqueue_scripts', 'theme_add_files');

function add_fontawesome_attributes( $tag, $handle ) {
    if ( 's-fontawesome' === $handle ) {
        return str_replace( 'src', 'crossorigin="anonymous" src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'add_fontawesome_attributes', 10, 2 );

function theme_admin_assets() {
    wp_enqueue_script( 'csv-uploader', T_DIRE_URI . '/admin/script.js', array( 'jquery' ) );
}

// add_action('admin_enqueue_scripts', 'theme_admin_assets');

function custom_term_radio_checklist( $args ) {
    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'blog_category' || $args['taxonomy'] === 'category' ) {
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { 
            if ( ! class_exists( 'WPSE_139269_Walker_Category_Radio_Checklist' ) ) {
                class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, ...$args ) {
                        $output = parent::walk( $elements, $max_depth, ...$args );
                        $output = str_replace(
                            array( 'type="checkbox"', "type='checkbox'" ),
                            array( 'type="radio"', "type='radio'" ),
                            $output
                        );

                        return $output;
                    }
                }
            }

            $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
        }
    }

    return $args;
}

add_filter( 'wp_terms_checklist_args', 'custom_term_radio_checklist' );

function theme_custom_setup() {
    add_theme_support( 'post-thumbnails' ); 
    add_image_size( "example_thumb", 340, 340, true );
    add_image_size( "blog_thumb", 340, 340, true );
    add_image_size( "instagram_thumb", 250, 250, true );
    add_image_size( "medium", 1080, 800, true );
    add_image_size( "thumb", 294, 225, true );
    add_image_size( "small", 250, 175, true );
    set_post_thumbnail_size( 294, 225, true );
    add_editor_style('assets/css/reset.css');
    add_editor_style('assets/css/common.css');
    add_editor_style('assets/css/style.css');
    add_editor_style('editor-style.css');
}

add_action( 'after_setup_theme', 'theme_custom_setup' );

function replaceImagePath( $arg ) {
    $content = str_replace('"images/', '"' . T_DIRE_URI . '/assets/img/', $arg);
    $content = str_replace('"/images/', '"' . T_DIRE_URI . '/assets/img/', $content);
    $content = str_replace(', images/', ', ' . T_DIRE_URI . '/assets/img/', $content);
    $content = str_replace("('images/", "('". T_DIRE_URI . '/assets/img/', $content);
    return $content;
}

add_action('the_content', 'replaceImagePath');

function replaceVideoPath( $arg ) {
    $content = str_replace('"movie/', '"' . T_DIRE_URI . '/assets/movie/', $arg);
    $content = str_replace('"/movie/', '"' . T_DIRE_URI . '/assets/movie/', $content);
    $content = str_replace(', movie/', ', ' . T_DIRE_URI . '/assets/movie/', $content);
    $content = str_replace("('movie/", "('". T_DIRE_URI . '/assets/movie/', $content);
    return $content;
}

add_action('the_content', 'replaceVideoPath');

function disable_wp_auto_p( $content ) {
    if ( is_singular( 'page' ) ) {
      remove_filter( 'the_content', 'wpautop' );
    }
    remove_filter( 'the_excerpt', 'wpautop' );
    return $content;
}

add_filter( 'the_content', 'disable_wp_auto_p', 0 );

add_filter('wpcf7_autop_or_not', '__return_false');

add_filter('query_vars', function($vars) {
	$vars[] = 'news_category';
	return $vars;
});

add_filter( 'wpcf7_validate_email*', 'custom_email_confirmation_validation_filter', 20, 2 );
  
function custom_email_confirmation_validation_filter( $result, $tag ) {
  if ( 'your-email-confirm' == $tag->name ) {
    $your_email = isset( $_POST['your-email'] ) ? trim( $_POST['your-email'] ) : '';
    $your_email_confirm = isset( $_POST['your-email-confirm'] ) ? trim( $_POST['your-email-confirm'] ) : '';
  
    if ( $your_email != $your_email_confirm ) {
      $result->invalidate( $tag, "これが正しいメールアドレスですか？" );
    }
  }
  
  return $result;
}

function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
    $first_img = $matches[1][0];
  
    if(empty($first_img)) {
      $first_img = T_DIRE_URI . "/assets/img/noimage.jpg";
    }
    return $first_img;
  }

//add SVG to allowed file uploads
function add_file_types_to_uploads($file_types){

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );

    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

function taxonomy_checklist_checked_ontop_filter ($args) {
    $args['checked_ontop'] = false;
    return $args;
}

add_filter('wp_terms_checklist_args','taxonomy_checklist_checked_ontop_filter');

function new_excerpt_length($length) {
    return 113;
}
add_filter('excerpt_length', 'new_excerpt_length');

function new_excerpt_more($more) {
    return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

function wp_set_post_views( $postID ) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta( $postID, $count_key, true );

    if( $count == '' ) {
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    } else {
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

function wp_get_post_views( $content ) {
    if ( is_single() ) {
        wp_set_post_views(get_the_ID());
    }
    return $content;
}
add_filter( 'the_content', 'wp_get_post_views' );

add_filter( 'previous_post_link', 'filter_single_post_pagination', 10, 4 );
add_filter( 'next_post_link',     'filter_single_post_pagination', 10, 4 );

function filter_single_post_pagination( $output, $format, $link, $post )
{
    if( $post ) {
        $title = get_the_title( $post );
        $url   = get_permalink( $post->ID );
        
        $class = 'prev-btn';

        if ( 'next_post_link' === current_filter() )
        {
            $class = 'next-btn';
        }
        if( $link ) {
            $text = $link;
        }
        ob_start();
        ?>
        <a href="<?php echo $url; ?>" class="page-btn <?php echo $class; ?>"><span><?php echo $text; ?></span></a>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    return false;
}

function wp_get_share_btns( $post_id = null ) {
    $post_id      = $post_id ? $post_id : get_the_ID();
    $share_title = html_entity_decode( get_the_title( $post_id ) );
    $share_url   = get_permalink( $post_id );
    $share_btns = [
        'twitter' => [
            'title'       => __( 'Twitter', THEME_NOTE ),
            'icon'        => '<i class="fa-brands fa-square-twitter"></i>',
            'href'        => 'https://twitter.com/intent/tweet?url=' .  urlencode( $share_url ) . '&text=' . $share_title . '',
            'class'       => 'twitter-link',
        ],
        'facebook' => [
            'title'       => __( 'Facebook', THEME_NOTE ),
            'icon'        => '<i class="fa-brands fa-square-facebook"></i>',
            'href'        => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $share_url ),
            'class'       => 'facebook-link',
        ],
        'line' => [
            'title'       => __( 'LINE', THEME_NOTE ),
            'icon'        => '<i class="fa-brands fa-line"></i>',
            'href'        => 'https://social-plugins.line.me/lineit/share?url' .  urlencode( $share_url ) . '&text=' . $share_title . '',
            'class'       => 'line-link',
        ],
    ];
    ob_start();
    ?>
    <div class="share-links">
        <span class="label">この記事をシェアする</span>
        <?php foreach ($share_btns as $key => $btn) : ?>
            <a href="<?php echo $btn['href']; ?>" class="<?php echo $btn['class']; ?>">
                <?php echo $btn['icon']; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php 
    $output = ob_get_contents();
    ob_end_clean();
    echo $output;
}

function custom_link_btn( $attr ) {

    $args = shortcode_atts( array(
        'link' => '/',
        'text' => 'もっと見る',
    ), $attr );
    
    ob_start();
    $link = $args['link'];
    if (strpos($args['link'], 'http') == false) {
        $link = home_url( $args['link']);
    }
    ?>
    <a href="<?php echo $link; ?>" class="link-btn mx-auto">
        <span><?php echo $args['text'] ?></span>
    </a>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('link-btn', 'custom_link_btn');

function my_shortcode() {
    ob_start();
    get_template_part('template', 'parts/breadcrumbs');
    return ob_get_clean();   
}

add_shortcode( 'my_shortcode', 'my_shortcode' );

function custom_get_news_posts( $attr ) {

    ob_start();

    $args = [
        'post_type' => 'blog',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => 1,
        'orderby' => 'post_date',
        'order' => "DESC"
    ];

    $post_query = new WP_Query( $args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <?php while( $post_query->have_posts() ) : $post_query->the_post(); ?>
            <div class="news-item">
                <div class="content">
                    <div class="wrap">
                        <div>
                            <time class="date"><?php the_time("Y/m/d"); ?></time>
                            <?php $blog_cats = get_the_terms(get_the_ID(), 'blog_category'); ?>
                            <?php if( $blog_cats ) : ?>
                                <div class="label"><?php echo $blog_cats[0]->name; ?></div>
                            <?php endif; ?>
                        </div>
                        <h4 class="title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                    </div>
                    <div class="arrow"><span></span></div>
                </div>
            <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-news-list', 'custom_get_news_posts');

function category() {
    ob_start();
    ?>

    <div class="blog-card-category">
        <a href="<?php echo esc_url(home_url('/')); ?>blog" class="all <?php if($blog_category_id == 100): echo 'active'; endif; ?>"><span>All</span></a>
        <div class="category-btn-group">
            <?php
                $args = array(
                    'post_type' => 'blog',
                    'orderby' => 'post_date',
                    'order'   => 'DESC',
                    'taxonomy' => 'blog_category',
                );

                $cats = get_categories($args);
                foreach($cats as $cat):?>
                <?php $color = get_field('color', 'blog_category' . '_' . $cat->term_id); ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>blog?blog_category_id=<?php echo $cat->cat_ID; ?>" class="category-btn<?php if($blog_category_id == $cat->cat_ID): echo 'active'; endif; ?>"><span><?php echo $cat->name; ?></span></a>
                <?php
            endforeach;
            ?>
        </div>
    </div>

    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('category', 'category');

function custom_get_blog_posts( $attr ) {

    $args = shortcode_atts( array(
        'count' => 3,
        'orderby' => 'post_date',
        'order' => 'DESC',
    ), $attr );
    
    ob_start();

    $post_args = [
        'post_type' => 'blog',
        'post_status' => 'publish',
        'posts_per_page' => $args['count'],
        'orderby' => $args['orderby'],
        'order' => $args['order'],
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="container">
            <div class="news-list">
                <?php while( $post_query->have_posts() ) : $post_query->the_post(); ?>
                    <div class="news-item">
                        <?php $thumbs = get_field('thumbs'); ?>
                        <?php if( $thumbs ) : ?>
                            <a href="<?php the_permalink(); ?>" class="thumb">
                                <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <div class="blog-concept">
                                <time class="date"><?php the_time("Y/m/d"); ?></time>
                                <?php $blog_cats = get_the_terms(get_the_ID(), 'blog_category'); ?>
                                <?php if( $blog_cats ) : ?>
                                    <p class="label"><?php echo $blog_cats[0]->name; ?></p>
                                <?php endif; ?>
                            </div>
                            <h4 class="title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
            <a href="<?php echo esc_url(home_url('/'));?>blog" class="link-btn">
                詳細はこちら
                <div class="arrow">
                    <span></span>
                </div>
            </a>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('blog-list', 'custom_get_blog_posts');

function custom_get_works_posts() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list', 'custom_get_works_posts');

function custom_get_works_posts01() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Replace with your custom taxonomy name
                'field' => 'slug',
                'terms' => 'iot', // Replace with your term slug
            ),
        ),
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list01', 'custom_get_works_posts01');

function custom_get_works_posts02() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Replace with your custom taxonomy name
                'field' => 'slug',
                'terms' => 'printer', // Replace with your term slug
            ),
        ),
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list02', 'custom_get_works_posts02');

function custom_get_works_posts03() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Replace with your custom taxonomy name
                'field' => 'slug',
                'terms' => 'pc', // Replace with your term slug
            ),
        ),
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list03', 'custom_get_works_posts03');


function custom_get_works_posts04() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Replace with your custom taxonomy name
                'field' => 'slug',
                'terms' => 'internet', // Replace with your term slug
            ),
        ),
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list04', 'custom_get_works_posts04');


function custom_get_works_posts05() {
    global $post;
    
    ob_start();

    $post_args = [
        'post_type' => 'works',
        'post_status' => 'publish',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'posts_per_page' => 4,
        'tax_query' => array(
            array(
                'taxonomy' => 'category', // Replace with your custom taxonomy name
                'field' => 'slug',
                'terms' => 'homepage', // Replace with your term slug
            ),
        ),
    ];

    $post_query = new WP_Query( $post_args );
    ?>

    <?php if( $post_query->have_posts() ) : ?>
        <div class="works-swiper-slider">
            <div class="swiper top-works-swiper">
                <div class="swiper-wrapper">
                    <?php while( $post_query->have_posts() ) : $post_query->the_post(); 
                        $post_id = get_the_ID();
                        $cat = get_the_category($post_id);
                        $color = get_field('color', 'category' . '_' . $cat[0]->term_id);
                        $title = mb_strimwidth(strip_tags($post->post_title), 0, 36, "…", "UTF-8");
                    ?>
                        <div class="swiper-slide">
                            <div class="works-item">
                                <?php $thumbs = get_field('thumbs'); ?>
                                <?php if( $thumbs ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="thumb">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/no_thumb.jpg" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="wrap">
                                        <time class="date"><?php the_time("Y.m.d"); ?></time>
                                        <div class="label"><?php echo $cat[0]->name; ?></div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="btn-group">
                    <div class="swiper-scrollbar swiper-scrollbar-works"></div>
                    <div class="swiper-button-group">
                        <div class="swiper-button-prev swiper-button-prev2">&#8249;</div>
                        <div class="swiper-button-next swiper-button-next2">&#8250;</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('top-works-list05', 'custom_get_works_posts05');

function my_breadcrumb() {
	echo "bcn_display($return = false, $linked = true, $reverse = false, $force = false)";
}
add_shortcode( 'breadcrumb' , 'my_breadcrumb' );

add_filter('query_vars', function($vars) {
    $vars[] = 'blog_category_id';
    return $vars;
});

add_filter('query_vars', function($vars) {
    $vars[] = 'works_category';
    return $vars;
});

function load_more_ajax() {	
    $ajaxposts = new WP_Query([
        'post_type' => 'blog',
        'posts_per_page' => 9,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'paged' => $_POST['paged'],
    ]);
	
	$max_pages = $ajaxposts->max_num_pages;
    
	ob_start();
    ?>

    <?php if( $ajaxposts->have_posts() ) {
        while( $ajaxposts->have_posts() ) : $ajaxposts->the_post(); ?>
        <li>
            <div class="blog-item">
                <?php $thumbs = get_field('thumbs'); ?>
                <?php if( $thumbs ) : ?>
                    <a href="<?php the_permalink(); ?>" class="thumb">
                        <img src="<?php echo esc_url($thumbs[0]['sizes']['small']); ?>" alt="<?php echo esc_html($thumbs[0]['title']); ?>">
                    </a>
                <?php endif; ?>
                <div class="content">
                    <div class="wrap">
                        <time class="date"><?php the_time("Y/m/d"); ?></time>
                        <?php $blog_cats = get_the_terms(get_the_ID(), 'blog_category'); ?>
                        <?php if( $blog_cats ) : ?>
                            <div class="label"><?php echo $blog_cats[0]->name; ?></div>
                        <?php endif; ?>
                    </div>
                    <h4 class="title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <a href="<?php the_permalink(); ?>" class="more-btn">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/top/icon_more.svg" alt="">
                    </a>
                </div>
            </div>
        </li>
        <?php endwhile; 
	}
    $output = ob_get_contents();
    ob_end_clean();
    $result = [
      'max' => $max_pages,
      'html' => $output,
      'ajax' => $_POST['paged']
	];
	echo json_encode( $result );
	exit;
}
add_action('wp_ajax_load_more_ajax', 'load_more_ajax');
add_action('wp_ajax_nopriv_load_more_ajax', 'load_more_ajax');

?>