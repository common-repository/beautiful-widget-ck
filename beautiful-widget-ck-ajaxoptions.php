<?php
defined('ABSPATH') or die;	
?>
<div id="ckajaxoptionsstyles"></div>
<div id="ckajaxoptionsgooglefont"></div>
<div id="ckajaxoptions">
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/banner/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/cornerleft_en/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/cornerleft_fr/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/cornerright_en/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/cornerright_fr/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/glossy/beautiful-widget-ck.css" />
	<link rel="stylesheet" href="<?php echo $this->pluginurl ?>/themes/simple/beautiful-widget-ck.css" />
	<input type="hidden" id="ckajaxoptions-return" value="<?php echo (string)$_POST['returninput'] ?>" />
	<?php if (! $this->ispro ) $this->show_pro_message(); ?>
	<?php if ($this->ispro ) $this->pro->show_suggestions(); ?>
	<div style="float:left;width:80%;">
		<div class="menulinkck current" tab="tab_blockstyles"><?php _e('Block'); ?></div>
		<div class="menulinkck" tab="tab_titlestyles"><?php echo _e('Title'); ?></div>
		<div class="clr"></div>
		<div class="tabck menustyles current" id="tab_blockstyles">
			<div>
				<label for="<?php echo $this->get_field_name( 'bgcolor1' ); ?>"><?php _e( 'Background Color'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
				<?php echo $this->get_field('color', $this->get_field_name( 'block_bgcolor1' ), '', 'beautifulck_admincss') ?>
				<?php echo $this->get_field('color', $this->get_field_name( 'block_bgcolor2' ), '', 'beautifulck_admincss') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'bgcolor1' ); ?>"><?php _e( 'Text Color'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
				<?php echo $this->get_field('color', $this->get_field_name( 'block_fontcolor' ), '', 'beautifulck_admincss') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'margins' ); ?>"><?php _e( 'Margins'); ?></label>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_top.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Top'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_paddingtop' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_right.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Right'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_paddingright' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_bottom.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Bottom'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_paddingbottom' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/padding_left.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Left'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_paddingleft' ), '', 'beautifulck_admincss') ?></span>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'borderradius' ); ?>"><?php _e( 'Border Radius'); ?></label>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tl.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Top Left'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_roundedcornerstl' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_tr.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Top Right'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_roundedcornerstr' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_br.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Bottom Right'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_roundedcornersbr' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/border_radius_bl.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Bottom Left'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'block_roundedcornersbl' ), '', 'beautifulck_admincss') ?></span>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'borders' ); ?>"><?php _e( 'Borders'); ?></label>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
				<span><?php echo $this->get_field('color', $this->get_field_name( 'block_bordercolor' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shape_square.png" /></span>
				<span style="width:35px;"><?php echo $this->get_field('text', $this->get_field_name( 'block_borderwidth' ), '', 'beautifulck_admincss') ?></span>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'shadow' ); ?>"><?php _e( 'Shadow'); ?></label>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" /></span>
				<span><?php echo $this->get_field('color', $this->get_field_name( 'block_shadowcolor' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_blur.png" /></span>
				<span style="width:35px;"><?php echo $this->get_field('text', $this->get_field_name( 'block_shadowblur' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/shadow_spread.png" /></span>
				<span style="width:35px;"><?php echo $this->get_field('text', $this->get_field_name( 'block_shadowspread' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsetx.png" /></span>
				<span style="width:35px;"><?php echo $this->get_field('text', $this->get_field_name( 'block_shadowoffsetx' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/offsety.png" /></span>
				<span style="width:35px;"><?php echo $this->get_field('text', $this->get_field_name( 'block_shadowoffsety' ), '', 'beautifulck_admincss') ?></span>
				<?php 
				$optionsboxshadowinset = array('0' =>__('Out'), '1'=>__('In'));
				echo $this->get_field('radio', $this->get_field_name( 'block_shadowinset' ), '', 'beautifulck_admincss', $optionsboxshadowinset);
				?>
			</div>
		</div>
		<div class="tabck menustyles" id="tab_titlestyles">
			<div>
				<label for="<?php echo $this->get_field_name( 'title_fontcolor' ); ?>"><?php _e( 'Text Color'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/color.png" />
				<?php echo $this->get_field('color', $this->get_field_name( 'title_fontcolor' ), '', 'beautifulck_admincss') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'title_fontsize' ); ?>"><?php _e( 'Font Size'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_fontsize.png" />
				<?php echo $this->get_field('text', $this->get_field_name( 'title_fontsize' ), '', 'beautifulck_admincss') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'title_fontfamily' ); ?>"><?php _e( 'Font Family'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/style.png" />
				<?php echo $this->get_field('text', $this->get_field_name( 'title_fontfamily' ), '', 'beautifulck_admincss') ?>
				<?php if ($this->ispro) : ?>
				<br />
				<label for="<?php echo $this->get_field_name( 'title_googlefont' ); ?>"><?php _e( 'Google Font'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/google.png" />
				<?php echo $this->get_field('text', $this->get_field_name( 'title_googlefont' ), '', '') ?>
				<a class="button btn-primary btn" href="javascript:void(0)" onclick="ck_load_googlefont();" title="Example: <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>"><?php _e('Import'); ?></a>
				<span id="title_googlefont_wait" style="height:16px;width:16px;"></span>
				<?php endif; ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'title_fontweight' ); ?>"><?php _e( 'Font Weight'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/text_bold.png" />
				<?php 
				$optionsfontweight = '<option value="normal">Normal</option>
						<option value="bold">Bold</option>';
				echo $this->get_field('radio', $this->get_field_name( 'title_fontweight' ), '', 'beautifulck_admincss', $optionsfontweight); ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'margins' ); ?>"><?php _e( 'Margins'); ?></label>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_top.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Top'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'title_margintop' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_right.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Right'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'title_marginright' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_bottom.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Bottom'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'title_marginbottom' ), '', 'beautifulck_admincss') ?></span>
				<span><img class="iconck" src="<?php echo $this->pluginurl ?>/images/margin_left.png" /></span>
				<span style="width:35px;" title="<?php _e( 'Left'); ?>"><?php echo $this->get_field('text', $this->get_field_name( 'title_marginleft' ), '', 'beautifulck_admincss') ?></span>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'margins' ); ?>"><?php _e( 'Icon'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/photo.png" />
				<?php echo $this->get_field('select', $this->get_field_name( 'icon' ), '', '', CKfolder::files($this->plugindir . '/icons', '.png'), true, 'onchange="update_titleicon_admin_css()"') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'margins' ); ?>"><?php _e( 'Theme'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/images.png" />
				<?php echo $this->get_field('select', $this->get_field_name( 'theme' ), '', '', array_merge(array('0'=>__('None')), CKfolder::folders($this->plugindir . '/themes')), true, 'onchange="update_theme_admin_css()"') ?>
			</div>
			<div>
				<label for="<?php echo $this->get_field_name( 'margins' ); ?>"><?php _e( 'Theme Color'); ?></label>
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/palette.png" />
				<?php
				$optionscolorvariation = '<option value="pink">' . __( 'Pink') . '</option>
						<option value="red">' . __( 'Red') . '</option>
						<option value="orange">' . __( 'Orange') . '</option>
						<option value="yellow">' . __( 'Yellow') . '</option>
						<option value="green">' . __( 'Green') . '</option>
						<option value="blue">' . __( 'Blue') . '</option>
						<option value="grey">' . __( 'Grey') . '</option>';
				echo $this->get_field('select', $this->get_field_name( 'colorvariation' ), '', '', $optionscolorvariation, false, 'onchange="update_theme_admin_css()"') ?>
			</div>
		</div>
	</div>
	<div style="float:left;width:20%;">
		<div style="padding: 10px 20px;">
			<div  id="beautifulck_preview" class="beautifulck banner red">
				<div class="beautifulck_title">
					<img class="beautifulckicon" src="<?php echo $this->pluginurl ?>/icons/search.png">
					<div class="beautifulck_titletext">
					<h1 class="widget-title"><?php esc_attr_e($_POST['widget_title']) ?></h1>
					</div>
				</div>
				<div>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor imperdiet bibendum. Suspendisse ut molestie lectus.
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery('#ckajaxoptions div.tabck:not(.current)').hide();
jQuery('.menulinkck', jQuery('#ckajaxoptions')).each(function(i, tab) {
	jQuery(tab).click(function() {
		jQuery('#ckajaxoptions div.tabck').hide();
		jQuery('.menulinkck', jQuery('#ckajaxoptions')).removeClass('current');
		if (jQuery('#' + jQuery(tab).attr('tab')).length)
			jQuery('#' + jQuery(tab).attr('tab')).show();
		jQuery(this).addClass('current');
	});
});
</script>
<style>
.menulinkck {
    float: left;
    padding: 5px 10px;
    margin: 5px 5px 0 5px;
    /*border-right: 1px solid #ddd;*/
    cursor: pointer;
    color: #a6a6a6;
	background: #eee;
	border-radius: 5px 5px 0 0;
	border: 1px solid #ddd;
	position: relative;
	z-index: 1;
}

.menulinkck:hover, .menulinkck.current {
    color: #000;
	background: #fff;
	border-bottom: 1px solid #fff;
}

.menulinkck + .clr {
	clear: both;
	border-top: 1px solid #ddd;
	margin-bottom: 5px;
	position: relative;
	z-index: 0;
	top: -1px;
}

#ckajaxoptions label { float: left; width: 180px; }
#ckajaxoptions input { max-width: 100%; }
#ckajaxoptions .ckheading { color: #2EA2CC; font-weight: bold; }
#ckajaxoptions span { display: inline-block; }
#ckajaxoptions .wp-color-result { vertical-align: middle; }
#ckajaxoptions img { vertical-align: middle; }
#ckajaxoptions fieldset { vertical-align: middle; }
#ckajaxoptions div.menustyles > div { margin: 2px 0; }
</style>
<script type="text/javascript">
	jQuery('.beautifulck_admincss', jQuery('#ckajaxoptions')).change(function() {
		ckajax_render_admin_css();
	});
</script>
<?php

die(); // this is required to return a proper result