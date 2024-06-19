<?php $activation = get_post_meta($post->ID, 'activation', true); ?>
<?php if( !empty($activation) ): ?>
<!-- Googleしごと検索はじめ -->
<script type="application/ld+json">
{
 "@context" : "http://schema.org",
 "@type" : "JobPosting",
 "title" : "<?php the_field("job_title");?>",
 "hiringOrganization": {
   "@type": "Organization",
   "name": "<?php the_field("company_name");?>",
   <?php $sameAs = get_post_meta($post->ID, 'sameAs', true); if( !empty($sameAs) ): ?>"sameAs": "<?php the_field("sameAs");?>"<?php endif; ?>
   <?php $logo = get_post_meta($post->ID, 'logo', true); if( !empty($logo) ): ?>,"logo": "<?php the_field("logo");?>"<?php endif; ?>
 },
 "jobLocation": {
   "@type": "Place",
   "address": {
     "@type": "PostalAddress",
     "streetAddress": "<?php the_field("streetAddress");?>",
     "addressLocality": "<?php the_field("addressLocality");?>",
     "addressRegion": "<?php the_field("addressRegion");?>",
     "postalCode": "<?php the_field("postalCode");?>",
     "addressCountry": "JP"
   }
 },
 "baseSalary" : {
   "@type" : "MonetaryAmount",
   "currency" : "JPY",
   "value": {
     "@type": "QuantitativeValue",
	 <?php if(get_post_meta($post->ID,"set_value",true)=== "basic"): ?>
	 <?php $value = get_post_meta($post->ID, 'value', true); if( !empty($value) ): ?>"Value": <?php the_field("value");?><?php endif; ?>
	 <?php else: ?>
	 <?php $minValue = get_post_meta($post->ID, 'minValue', true); if( !empty($minValue) ): ?>"minValue": <?php the_field("minValue");?><?php endif; ?>
	 <?php $maxValue = get_post_meta($post->ID, 'maxValue', true); if( !empty($maxValue) ): ?>,"maxValue": <?php the_field("maxValue");?>,<?php endif; ?>
	 <?php endif; ?>
	 <?php $unitText = get_post_meta($post->ID, 'unitText', true); if( !empty($unitText) ): ?>"unitText": "<?php the_field("unitText");?>"<?php endif; ?>
   }
 },
	 "employmentType": "<?php the_field("employmentType"); ?>",
	 <?php $description = get_post_meta($post->ID, 'description', true); if( !empty($description) ): ?>"description": "<?php the_field("description");?>",<?php endif; ?>
 "datePosted" : "<?php the_time('Y-m-d'); ?>"
	 <?php $validThrough = get_post_meta($post->ID, 'validThrough', true); if( !empty($validThrough) ): ?>,"validThrough": "<?php the_field("validThrough");?>"<?php endif; ?>
}
</script>
<!-- Googleしごと検索おわり -->
<?php endif;?>