<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja" style="margin-top: 0 !important;">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta property="og:locale" content="ja_JP">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri() ?>/assets/img/common/header_logo.svg" type="image/x-icon">

  <title>
    <?php if(is_front_page() || is_home()){
      echo get_bloginfo('name');
    } else{
      wp_title('|',true,'right'); echo bloginfo('name');
    }?>
  </title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <?php wp_head(); ?>

</head>

<?php
  global $post;

  if( $post->post_type != "page" ) {
    $post_slug = $post->post_type;
  } else {
    $post_slug = $post->post_name;
  }
  if( is_archive() || is_category() || is_single() ) {
    $nav_last_category = [];
    $nav_query_categories = get_the_category();
    if(!empty($nav_query_categories)) {
      $nav_last_category = end(array_values($nav_query_categories));
    }
    if (!empty($nav_last_category)) {
      $post_slug = $nav_last_category->slug;
    }
  }
?>

<body>
  <header id="header">
    <div class="header-wrapper">
      <h1 class="header-logo">
        <a href="<?php echo HOME; ?>">
          <img src="<?php echo T_DIRE_URI; ?>/assets/img/common/header_logo.png" alt="">
        </a>
      </h1>
      <nav class="header-nav">
        <ul class="nav-menu">
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-facility" class="menu-link">
              <span>設備について</span>
            </a>
          </li>
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-scene" class="menu-link">
              <span>ご利用シーン</span>
            </a>
          </li>
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-voice" class="menu-link">
              <span>お客様の声</span>
            </a>
          </li>
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-company" class="menu-link">
              <span>会社情報</span>
            </a>
          </li>
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-message" class="menu-link">
              <span>開発者メッセージ</span>
            </a>
          </li>
          <li>
            <a href="<?php echo esc_url(home_url('/')); ?>#top-faq" class="menu-link">
              <span>よくある質問</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <a href="<?php echo esc_url(home_url('/')); ?>contact" class="contact-btn">
      <div class="btn-layout">
        <div class="btn-content"><p>お問い合わせ</p><div class="arrow"><span></span></div></div>
        <div class="shadow"></div>
      </div>
    </a>
  </header>
  <div id="mobile-nav">
    <nav class="mobile-nav-container">
      <ul class="mobile-nav-menu">
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-scene" class="menu-link">
            <h3 class="sub">SCENE</h3>
            <h1 class="lead">ご利用シーン</h1>
          </a>
        </li>
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-voice" class="menu-link">
            <h3 class="sub">VOICE</h3>
            <h1 class="lead">お客様の声</h1>
          </a>
        </li>
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-facility" class="menu-link">
            <h3 class="sub">FACILITY</h3>
            <h1 class="lead">設備について</h1>
          </a>
        </li>
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-message" class="menu-link">
            <h3 class="sub">MESSAGE</h3>
            <h1 class="lead">開発者メッセージ</h1>
          </a>
        </li>
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-faq" class="menu-link">
            <h3 class="sub">Q&A</h3>
            <h1 class="lead">よくある質問</h1>
          </a>
        </li>
        <li>
          <a href="<?php echo esc_url(home_url('/')); ?>#top-company" class="menu-link">
            <h3 class="sub">COMPANY</h3>
            <h1 class="lead">会社情報</h1>
          </a>
        </li>
      </ul>
      <section id="top-contact" class="top-contact">
        <div class="container">
          <div class="contact-layout">
            <div class="section-title">
              <h5 class="sub">contact</h5>
              <h3 class="lead">お問い合わせ</h3>
            </div>
            <a href="<?php echo esc_url(home_url('/')); ?>contact" class="link-btn white">お問い合わせはこちら</a>
          </div>
        </div>
      </section>
    </nav>
  </div>
  <main id="main">
