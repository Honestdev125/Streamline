<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<main id="main_content" class="l-mainContent l-article">
	<div class="l-mainContent__inner">
	<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();

				the_content();

			endwhile;
		endif;
	?>
	</div>
</main>
<?php
get_footer();?>