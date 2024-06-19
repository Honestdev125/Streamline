<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 Union Theme - Version: 1.4
*/

get_header();
?>

<main>

  <section class="p_fv_section">
    <div class="container">
      <div class="p_fv_info">
        <div class="info_wrap">
          <h2 class="lead_title">404</h2>
          <p class="sub_title">Page Not Found</p> 
        </div>
      </div>
    </div>
  </section>

  <section class="p_breadcrumbs_section">
    <div class="container">
      <ol>
        <li><a href="<?php echo HOME; ?>">TOP</a></li>
        <li>404</li>
      </ol>
    </div>
  </section>

  <?php remove_filter( 'the_content', 'wpautop' ); ?>

  <section class="p_main_section">
    <div class="container">
      <div class="m_text text_center sp_text_left mb_16">指定されたページまたはファイルは存在しません</div>
      <div class="m_text text_center sp_text_left">URL、ファイル名にタイプミスがないかご確認ください。<br>指定されたページは削除されたか、移動した可能性があります。</div>
      <div class="btn_wrap mt_50 mt_sp_40">
        <a href="<?php echo HOME; ?>" class="round_btn bgleft mx_auto">
          <span>トップへ戻る</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="9.338" height="15.894" viewBox="0 0 9.338 15.894">
            <path id="Path_27951" data-name="Path 27951" d="M1128.888,2033.043l6.321,6.066-5.951,6.294" transform="translate(-1127.12 -2031.276)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
          </svg>
        </a>
      </div>
    </div>
  </section>
  
  <?php the_content(); ?>

  <?php get_template_part('template', 'parts/contact_intro'); ?>

</main>

<?php get_footer();?>
