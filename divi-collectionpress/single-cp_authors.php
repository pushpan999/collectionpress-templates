<?php
/**
 * The template for displaying single CollectionPress authors
 * You can add this file to theme folder and paste this file  there.
 * Path will be "<theme_name>/single-cp_authors.php"
 * @author Avinash
 */
get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
    <div class="container karc-cp-container">
        <div id="content-area" class="clearfix">        
             <?php get_sidebar(); ?>
            <div id="left-area">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
                            <?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) { ?>
                                <div class="et_post_meta_wrapper">
                                    <h1 class="entry-title"><?php the_title(); ?></h1>

                                    <?php
                                    if ( ! post_password_required() ) :

                                    et_divi_post_meta();

                                    $thumb = '';

                                    $width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

                                    $height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
                                    $classtext = 'et_featured_image';
                                    $titletext = get_the_title();
                                    $thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
                                    $thumb = $thumbnail["thumb"];

                                    $post_format = et_pb_post_format();

                                    if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
                                        printf(
                                        '<div class="et_main_video_container">
                                        %1$s
                                        </div>',
                                        $first_video
                                        );
                                    } else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' ===     et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
                                        print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
                                    } else if ( 'gallery' === $post_format ) {
                                        et_pb_gallery_images();
                                    }
                                    ?>

                                    <?php
                                    $text_color_class = et_divi_get_post_text_color();

                                    $inline_style = et_divi_get_post_bg_inline_style();

                                    switch ( $post_format ) {
                                        case 'audio' :
                                            printf(
                                            '<div class="et_audio_content%1$s"%2$s>
                                            %3$s
                                            </div>',
                                            esc_attr( $text_color_class ),
                                            $inline_style,
                                            et_pb_get_audio_player()
                                            );

                                        break;
                                        case 'quote' :
                                            printf(
                                            '<div class="et_quote_content%2$s"%3$s>
                                            %1$s
                                            </div> <!-- .et_quote_content -->',
                                            et_get_blockquote_in_content(),
                                            esc_attr( $text_color_class ),
                                            $inline_style
                                            );

                                        break;
                                        case 'link' :
                                            printf(
                                            '<div class="et_link_content%3$s"%4$s>
                                            <a href="%1$s" class="et_link_main_url">%2$s</a>
                                            </div> <!-- .et_link_content -->',
                                            esc_url( et_get_link_url() ),
                                            esc_html( et_get_link_url() ),
                                            esc_attr( $text_color_class ),
                                            $inline_style
                                            );

                                        break;
                                    }

                                endif;
                                ?>
                            </div> <!-- .et_post_meta_wrapper -->
                        <?php  } ?>

                        <div class="entry-content">
                        <?php
                            do_action( 'et_before_content' );

                            the_content();

                            wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
                        ?>
                        </div> <!-- .entry-content -->

                        <div class="author-items-wrap">
                            <h3><?php echo __('Author Item&apos;s','cpress') ?></h3>

                            <?php
                            $show_items = get_post_meta(get_the_ID(),"show_items",true);
                            $author_keyword = get_post_meta(get_the_ID(),"author_keyword",true);
                            if ( $show_items=="yes" ){
                                if ( $author_keyword=='' ){
                                    $author_keyword = get_the_title();
                                }
                                echo do_shortcode('[collectionpress author="'.$author_keyword.'"]');
                            }
                            ?>
                        </div>
                        <div class="author-posts-wrap">
                            <h3><?php echo __('Author Blog Post&apos;s','cpress') ?></h3>
                            <?php
                            $show_posts = get_post_meta(get_the_ID(),"show_posts",true);
                            $cp_related_author = get_post_meta(get_the_ID(),"cp_related_author",true);
                            if ( $show_posts=="yes" && $cp_related_author!=''):
                                $aposts=1;
                                if (isset($_GET) && isset($_GET['aposts'])){
                                    if ( $_GET['aposts']!='' ){
                                        $aposts = $_GET['aposts'];
                                    }                                
                                }
                                $author_posts = new WP_Query(array(
                                "author"         =>$cp_related_author,
                                "post_type"      =>"post",
                                "post_status"    =>"publish",
                                "orderby"        =>"modified",
                                "order"          =>"DESC",
                                "posts_per_page" =>get_option('posts_per_page'),
                                "cache_results"  => false,
                                "paged"          => $aposts) );
                                $found_posts =$author_posts->found_posts;
                                $total_pages =$author_posts->max_num_pages;
                                if ($author_posts->have_posts()) :
                                    while ($author_posts->have_posts()) : $author_posts->the_post();

                                        if (file_exists(locate_template('collectionpress/author_display_posts.php'))) {
                                            include(locate_template('collectionpress/author_display_posts.php'));
                                        } else {
                                            include(CP_TEMPLATE_PATH.'/collectionpress/author_display_posts.php');
                                        }

                                    endwhile; ?>
                                    <div class="pagination">
                                        <?php               
                                        $big = 999999999; // need an unlikely integer
                                        echo paginate_links( array(
                                        //~ 'base'      =>str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                            'format'    =>'?aposts=%#%',
                                            'prev_text' =>__('&laquo;'),
                                            'next_text' =>__('&raquo;'),
                                            'current'   =>max(1, get_query_var('aposts')),
                                            'total'     =>$total_pages
                                            ) );
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="et_post_meta_wrapper">
                            <?php
                            if ( et_get_option('divi_468_enable') == 'on' ){
                                echo '<div class="et-single-post-ad">';
                                    if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
                                    else { ?>
                                        <a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
                                    <?php   }
                                echo '</div> <!-- .et-single-post-ad -->';
                            }
                            ?>

                            <?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>

                            <?php
                            if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) ) {
                                comments_template( '', true );
                            }
                            ?>
                        </div> <!-- .et_post_meta_wrapper -->
                    </article> <!-- .et_pb_post -->

                <?php endwhile; ?>
            </div> <!-- #left-area -->

     
        </div> <!-- #content-area -->
    </div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
