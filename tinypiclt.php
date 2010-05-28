<?php
/*
Plugin Name: TinyPic LT
Plugin URI: http://limitedtemplates.com/ltb/tinypic-lt-wordpress-plugin.html
Description: Adds metabox TinyPic widget to upload photos or videos while writing a new post or page.
Author: Jati MYW - LimitedTemplates
Version: 1.0 
Author URI: http://limitedtemplates.com/

Copyright (c) 2010
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt

    This file is part of WordPress.
    WordPress is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	INSTALL: 
	- Put folder tinypiclt to your plugin directory
	- Activate plugin from admin page and done.

*/

add_action('admin_menu', 'tlt_buat_menu'); 
add_action('admin_head', 'tlt_admin_script'); 
add_action('admin_menu', 'tinypiclt_meta_box'); 

function tlt_buat_menu() {
	add_options_page('Upload to TinyPic LT Options', 'TinyPic LT', 10, 'tinypic-limitedtemplates.php', 'tlt_print_plugin_page');
	add_action( 'admin_init', 'tlt_settings' );
}

function tlt_settings() { 
	register_setting( 'sftinypiclt', 'lt_page_default' );
	register_setting( 'sftinypiclt', 'lt_sh_search' );
	register_setting( 'sftinypiclt', 'lt_mt_opt' );
	register_setting( 'sftinypiclt', 'lt_lt_opt' );	
	register_setting( 'sftinypiclt', 'tlt_opt_metabox' );
}

function tinypiclt_meta_box() { 
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'TinyPic LT', __( 'Upload to TinyPic', 'tinypic-limitedtemplates.php' ), 'tinypiclt_isi_metabox', 'post', 'side', 'core' );
		add_meta_box( 'TinyPic LT', __( 'Upload to TinyPic', 'tinypic-limitedtemplates.php' ), 'tinypiclt_isi_metabox', 'page', 'side', 'core' );
	} else {
		add_action('dbx_post_advanced', 'tinypiclt_untuk_box_lama' );
		add_action('dbx_page_advanced', 'tinypiclt_untuk_box_lama' );
	}
}

function tinypiclt_isi_metabox() {
   tinypiclt_print_disana();
}

function tinypiclt_untuk_box_lama() { 
  echo '<div class="dbx-b-ox-wrapper">' . "\n";
  echo '<fieldset id="TinyPicLT" class="dbx-box">' . "\n";
  echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' .  __( 'Upload to TinyPic', 'tinypic-limitedtemplates.php' ) . "</h3></div>";   
  echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
  tinypiclt_isi_metabox();
  echo "</div></div></fieldset></div>\n";
}

function tlt_admin_script() {  
	$url_tlt_admin = $_SERVER['REQUEST_URI'];
	if ( preg_match('/limitedtemplates\.php/i', $url_tlt_admin) ) {
	$lokasi_plugin = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	?>
<!-- TinyPicLT Begin -->
<link rel="stylesheet" href="<?php echo $lokasi_plugin.'tinypiclt.css?ver=11'; ?>" type="text/css" media="screen" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
$("#tombol_preview").click(function() {
	var defpage = ($("input[name='lt_page_default']:checked").val());
	var cari = ($("input[name='lt_sh_search']:checked").val());
	var links = ($("input[name='lt_lt_opt']:checked").val());
	var media = ($("input[name='lt_mt_opt']:checked").val());
	var url_tinypic = "http://plugin.tinypic.com/plugin/"+defpage+".php?popts=l,narrow|t,"+media+"|c,"+links+"|i,en|s,"+cari;
	$("#iframe_preview").attr("src", url_tinypic);
});
});
</script>
<!-- TinyPicLT end -->
	<?php 
	}
} 

function tlt_print_plugin_page() {
?>
<div class="wrap">
<div id="icon-tools" class="icon32"></div><h2><?php _e('TinyPic LT Plugin Options') ?></h2>
<form method="post" action="options.php">
<?php _e('<span class="utt_author">TinyPic LT '). _e('by <a href="http://limit'). _e('edtemplates.com" target="_blank" title="Limit'). _e('edTemplates.com">Jati</a></span>') ?>
<table class="form-table widefat">
<thead>
	<tr>
		<th scope="col"><?php _e('<span class="utt_customize_title">Customize TinyPic LT</span>') ?></th>
		<th scope="col"><?php _e('<span class="utt_live_preview_title">Live Preview</span>') ?></th>
	</tr>
</thead>
<tbody>
<tr valign="top">
	<th scope="row">
		<?php settings_fields( 'sftinypiclt' ); ?>
		<ul class="utt_bagian"><?php _e('Page as default :') ?>
			<li><label><input name="lt_page_default" type="radio" value="index"<?php checked('index', get_option('lt_page_default'), true); ?>><?php _e(' Show &quot;Upload Page&quot;.') ?></label></li>
			<li><label><input name="lt_page_default" type="radio" value="account"<?php checked('account', get_option('lt_page_default'), true); ?>><?php _e(' Show &quot;Your Home&quot;. <span class="utt_desc">( Show login page if not logged in )</span>') ?></label></li>
			<?php function tlt_option_default() {
					if ( get_option('lt_page_default') == 'account' ) { echo 'account'; }
					else echo 'index';
				}
			?>
		</ul> 
		<ul class="utt_bagian"><?php _e('Display search :') ?>
			<li><label><input name="lt_sh_search" type="radio" value="true"<?php checked('true', get_option('lt_sh_search')); ?>><?php _e(' Yes, show the search.') ?></label></li>
			<li><label><input name="lt_sh_search" type="radio" value="false"<?php checked('false', get_option('lt_sh_search')); ?>><?php _e(' No, do not show search.') ?></label></li>
			<?php function tlt_option_search() {
					if ( get_option('lt_sh_search') == 'false' ) { echo 'false'; }
					else echo 'true';
				}
			?>
		</ul>
		<ul class="utt_bagian"><?php _e('Media types :') ?>
			<li><label><input name="lt_mt_opt" type="radio" value="both"<?php checked('both', get_option('lt_mt_opt')); ?>><?php _e(' Images and videos.') ?></label></li>
			<li><label><input name="lt_mt_opt" type="radio" value="images"<?php checked('images', get_option('lt_mt_opt')); ?>><?php _e(' Images only.') ?></label></li>
			<li><label><input name="lt_mt_opt" type="radio" value="videos"<?php checked('videos', get_option('lt_mt_opt')); ?>><?php _e(' Videos only.') ?></label></li>
			<?php function tlt_option_media() {
					if ( get_option('lt_mt_opt') == 'images' ) { echo 'images'; }
					if ( get_option('lt_mt_opt') == 'videos' ) { echo 'videos'; }
					if ( get_option('lt_mt_opt') == 'both' ) { echo 'both'; }
					if ( get_option('lt_mt_opt') != ('images' || 'videos') ) { echo 'both'; }
				}
			?>
		</ul>
		<ul class="utt_bagian"><?php _e('Link types :') ?>
			<li><label><input name="lt_lt_opt" type="radio" value="html"<?php checked('html', get_option('lt_lt_opt')); ?>><?php _e(' HTML code. <span class="utt_desc">&nbsp;&nbsp;&nbsp; &lt;img src="http://tinypic.com/image.jpg" /&gt;</span>') ?></label></li>
			<li><label><input name="lt_lt_opt" type="radio" value="url"<?php checked('url', get_option('lt_lt_opt')); ?>><?php _e(' Direct image. <span class="utt_desc">&nbsp; http://tinypic.com/image.jpg</span>') ?></label></li>
			<?php function tlt_link_type() {
					if ( get_option('lt_lt_opt') == 'html' ) { echo 'html'; }
					else echo 'url';
				}
			?>
		</ul>
		<?php function generate_tinypiclt_url() {
				_e('http://plugin.tinypic.com/plugin/'). tlt_option_default() ._e('.php?popts=l,narrow|t,'). tlt_option_media() ._e('|c,'). tlt_link_type() . _e('|i,en|s,').tlt_option_search();
			}
		?>
		<div class="utt_border"></div>

		<?php _e('Javascript:<br />') ?>
<textarea rows="8" cols="90" readonly="readonly" class="utt_textarea" onclick="this.focus(); this.select();">
<?php _e('&lt;script type="text/javascript"&gt;') ?> 
<?php _e("tinypic_layout = 'narrow';") ?> 
<?php _e("tinypic_type = '").tlt_option_media(). _e("';") ?> 
<?php _e("tinypic_links = '").tlt_link_type(). _e("';") ?> 
<?php _e("tinypic_language = 'en';") ?> 
<?php _e("tinypic_search = '").tlt_option_search(). _e("';") ?> 
<?php _e("tinypic_autoload = true;") ?> 
<?php _e("&lt;/script&gt;") ?> 
<?php _e('&lt;script src="http://plugin.tinypic.com/j/plugin.js" type="text/javascript"&gt;&lt;/script&gt;') ?>
</textarea>
		 <?php _e('<br class="clear" /><p class="utt_desc utt_notes">Notes :<br />') ?>
		 <?php _e('- Use the code above to add the uploader into the sidebar, width 260 pixel is recomended.<br />') ?>
		 <?php _e('- Metabox widget updated as same as the preview after you have saved all the changes.</p>') ?>

	</th>
	<td>
		<div class="utt_iframe_preview">
		<iframe id="iframe_preview" src="<?php generate_tinypiclt_url(); ?>" width="100%" height="510px" scrolling="no" frameborder="0" marginwidth="0" marginheight="0" style="border:0px;"></iframe> 
		</div>
	</td>
</tr>
</tbody>
</table>
<br class="clear" />
<div class="utt_tombol_wrap">
	<p class="kiri"><input type="submit" class="button-primary button" value="<?php _e('Save Changes') ?>" /></p>
	<p class="knn"><input id="tombol_preview" type="submit" value="<?php _e('Update Preview') ?>" class="button" onClick="javascript: return false;"/></p>
</div>
</form>

</div> <!-- wrap -->
<?php 
}   

function pmt_option_default() {
	if ( get_option('lt_page_default') == 'account' ) { 
		echo 'account'; 
	} else echo 'index';
}
function pmt_option_search() {
	if ( get_option('lt_sh_search') == 'false' ) { 
		echo 'false'; 
	} else echo 'true';
}
function pmt_option_media() {
	if ( get_option('lt_mt_opt') == 'images' ) { echo 'images'; }
	if ( get_option('lt_mt_opt') == 'videos' ) { echo 'videos'; }
	if ( get_option('lt_mt_opt') == 'both' ) { echo 'both'; }
	if ( get_option('lt_mt_opt') != ('images' || 'videos') ) { echo 'both'; }
}
function pmt_link_type() {
	if ( get_option('lt_lt_opt') == 'html' ) { echo 'html'; }
	else echo 'url';
}
function tinypiclt_print_disana() { 
?>
	<iframe src="<?php _e('http://plugin.tinypic.com/plugin/'). pmt_option_default() ._e('.php?popts=l,narrow|t,'). pmt_option_media() ._e('|c,'). pmt_link_type() . _e('|i,en|s,').pmt_option_search(); ?>" width="100%" height="510px" scrolling="no" frameborder="0" marginwidth="0" marginheight="0" style="border:0px;"></iframe>
<?php } ?>