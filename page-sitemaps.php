<?php
	get_header('subpage');
?>


	<section class="pageindex blog">
		<div class="container">
			<div class="pageindex-title">
				<p class="sub">サイトマップ</p>
				<h3 class="lead">Sitemaps</h3>
				
			</div>
		</div>
	</section>
	<section class="breadcrumbs">
		<div class="container">
			<ol>
				<li><a href="<?php echo HOME; ?>">TOP</a></li>
				<li>サイトマップ</li>
			</ol>
		</div>
	</section>
	<section class="p-sitemap">
		<div class="container">
			<div class="section-title">
				<h4 class="lead">東海三県スクラップ買取</h4>
			</div>
			<ul class="sitemap-menu">
				<li>
					<a href="<?php echo esc_url(home_url('/')); ?>" class="menu-link">ホーム</a>
				</li>
				<li>
					<a href="<?php echo esc_url(home_url('/')); ?>blog" class="menu-link">お知らせ</a>
				</li>
				<li>
					<a href="<?php echo esc_url(home_url('/')); ?>contact" class="menu-link">お問い合わせ</a>
				</li>

				<li>
					<a href="<?php echo esc_url(home_url('/')); ?>sitemaps" class="menu-link">
						サイトマップ
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url(home_url('/')); ?>privacy" class="menu-link">
						プライバシーポリシー
					</a>
				</li>
			</ul>
		</div>
	</section>
	</div>
<?php get_footer(); ?>
