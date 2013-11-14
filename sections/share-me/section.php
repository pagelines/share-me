<?php
/*
	Section: Share Me
	Author: Simple Mama
	Author URI: http://www.simplemama.com
	Description: Share Me allows your visitors to share content to the most popular social media sites and includes a customizable instructional greeting. For PageLines DMS only.
	Class Name: ShareMe
	Demo:
	Version: 1.2
	PageLines: true
	v3: true
	Filter: social
*/

class ShareMe extends PageLinesSection {

	const version = '1.1';

/* STUFF THAT LOADS ONLY IN THE SECTION HEADER */

	function section_head() {
		
		echo load_custom_font($this->opt('shareme_text_font'),'.shareme_text');
		if($this->opt('shareme_google')){
			?><script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script> <?php
		}
		if($this->opt('shareme_pin')){
			?><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script><?php
		}
		
	}
	
	/*function section_persistent() {
		
		if ($this->opt('shareme_excerpt')) {
			add_filter('pagelines_loop_before_excerpt', array(&$this,'shareme_excerpt'), 10, 2);
		}
		
	}*/
	
	/*function shareme_excerpt($input, $format){
		
		if (!class_exists('ShareMe') || $format == 'clip' || is_single() || is_page())
			return $input;
		global $post;
		ob_start();
		$this->section_template();
		$shareit = ob_get_clean();
		$shareme_share = sprintf('<div class="shareme_excerpt">%s</div>', $shareit);
		return $input.$shareme_share;
		
	}*/

/* THE FRONT END */

	function section_template() {

		global $post;
		$textcolor = sprintf('color:%s; ',pl_hashify($this->opt('shareme_text_color')));
		$textsize = sprintf('font-size:%spx;',($this->opt('shareme_text_size')));
		$styles = $textcolor.'' .$textsize.'';
		?>
		<div class="shareme">
			<div class="shareme_text <?php
				if ($this->opt('shareme_text_float') == 'text_left')
					echo 'left';
				else if ($this->opt('shareme_text_float') == 'text_right')
					echo 'right';
			?>" data-sync="shareme_text_greeting" style="<?php echo $styles;?>">
            <?php echo $this->opt('shareme_text_greeting'); ?></div>
			<div class="shareme_icons <?php
				if ($this->opt('shareme_icon_float') == 'icons_left')
					echo 'left';
				if ($this->opt('shareme_icon_float') == 'icons_right')
					echo 'right';
			?>">
				<?php
				$upermalink = urlencode( get_permalink( $post->ID ) );
				$utitle = urlencode( strip_tags( get_the_title() ) );
				$string = '<a class="shareme_sharelink" href="%s" title="%s" rel="nofollow" target="_blank"><img class="shareme_shareimg" src="%s" alt="%s" /></a>';
				$pinstring = '<a class="shareme_sharelink" href="%s" title="%s" rel="nofollow" target="_blank"><img class="shareme_pin_shareimg" src="%s" alt="%s" /></a>';
				//*** NEEDED FOR PINTEREST ***//
				$size = 'large';
				$image_id = get_post_thumbnail_id();
				$image_src = wp_get_attachment_image_src($image_id,$size);
				$image_url = $image_src[0];
				//*** END PINTEREST ***//
				if($this->opt('shareme_google')){
					printf('<div class="g-plusone" data-annotation="none"></div>');
				}
				if($this->opt('shareme_pin')){
					$url = sprintf('http://pinterest.com/pin/create/button/?url=%s&amp;media=%s&amp;description=%s via %s', $upermalink, $image_url, $post->post_title, $upermalink);
					printf($pinstring, $url, 'Share on Pinterest', $this->base_url.'/sbe_pinit.png', 'Pinterest');
				}
				if($this->opt('shareme_fb')){
					$url = sprintf('http://www.facebook.com/sharer.php?u=%s&amp;t=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on Facebook', $this->base_url.'/facebook.png', 'Facebook');
				}
				if($this->opt('shareme_tw')){
					$url = sprintf('http://twitter.com/home?status=%s%s%s', urlencode(html_entity_decode(get_the_title())), (' ' . urlencode(get_permalink($post->ID))), ($this->opt('twittername')) ? (' @' . $this->opt('twittername')) : '');					
					printf($string, $url, 'Share on Twitter', $this->base_url.'/twitter.png', 'Twitter');
				}
				if($this->opt('shareme_su')){
					$url = sprintf('http://www.stumbleupon.com/submit?url=%s&amp;title=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on StumbleUpon', $this->base_url.'/stumble.png', 'StumbleUpon');
				}
				if($this->opt('shareme_redd')){
					$url = sprintf('http://reddit.com/submit?phase=2&amp;url=%s&amp;title=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on Reddit', $this->base_url.'/reddit.png', 'Reddit');
				}
				if($this->opt('shareme_del')){
					$url = sprintf('http://del.icio.us/post?url=%s&amp;title=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on Delicious', $this->base_url.'/delicious.png', 'Delicious');
				}
				if($this->opt('shareme_digg')){
					$url = sprintf('http://digg.com/submit?phase=2&amp;url=%s&amp;title=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on Digg', $this->base_url.'/digg.png', 'Digg');
				}
				if($this->opt('shareme_linkedin')){
					$url = sprintf('http://www.linkedin.com/shareArticle?&amp;url=%s&amp;title=%s', $upermalink, $utitle);
					printf($string, $url, 'Share on LinkedIn', $this->base_url.'/linkedin.png', 'LinkedIn');
				}
				if($this->opt('shareme_email')){
					?><a class="shareme_sharelink" href="mailto:?subject=A recommended read: <?=urldecode($utitle)?>&body=I think you'll enjoy this article: <?=$upermalink?>" title="Share by Email"><img class="shareme_shareimg" src="<?php echo ($this->base_url.'/email.png') ?>" alt="Share by Email" /></a><?php
				}
				/*if($this->opt('shareme_print')){
					?><a class="shareme_sharelink" href="javascript:window.print()" title="Print This Page"><img src="<?php echo ($this->base_url.'/print.png') ?>" alt="" /></a><?php
					//printf($string, $url, 'Print This Page', $this->base_url.'/print.png', 'Print');
				}*/
				if ( ('' == $this->opt('shareme_google')) && ('' == $this->opt('shareme_pin')) && ('' == $this->opt('shareme_fb')) && ('' == $this->opt('shareme_tw')) && ('' == $this->opt('shareme_su')) && ('' == $this->opt('shareme_redd')) && ('' == $this->opt('shareme_del')) && ('' == $this->opt('shareme_digg')) && ('' == $this->opt('shareme_linkedin')) && ('' == $this->opt('shareme_email')) ) {
					echo setup_section_notify($this, 'Please set up Share Me');
				}
				?>
			</div> <!--END icons div-->
			<div class="clear"></div>
		</div><!--END main shareme div-->
	<?php
	}

/** WELCOME MESSAGE **/

	function welcome(){

		ob_start();

		?><div style="font-size:14px;"><?php _e('Thanks for purchasing Share Me! Your contribution will go toward amazing things like putting my children through college. <i class="icon-smile"></i> If I can be of assistance, you may contact me at <a href="http://www.simplemama.com" target="_blank" title="Simple Mama">simplemama.com</a>. Enjoy your day!','share-me');?></div><?php

		return ob_get_clean();
	}

/* THE ADMIN OPTIONS */

	function section_opts(){

/* WELCOME BOX */

		$options[] = array(
			'key'		=> 'shareme_welcome',
			'type'		=> 'template',
			'title'		=> __('Welcome to Share Me!','share-me'),
			'template'	=> $this->welcome()
		);

/* ICON OPTIONS */

		$options[] = array(
			'key'   => 'shareme_icon_float',
			'type'  => 'select',
			'title' => 'Icon Options',
			'label' => 'Would you prefer icons on the left or right?',
			'default'	=> 'icons_right',
			'opts'=> array(
				'icons_left'	=> array( 'name' => 'Icons on left' ),
				'icons_right'	=> array( 'name' => 'Icons on right' )
			),
		);

/* SHARING OPTIONS */

		$options[] = array(
			'key'	=> 'shareme_sharing_options',
			'type'	=> 'multi',
			'title'	=> 'Sharing Options',
			'help'	=> 'Where would you like your readers to share your content?',
			'default' => 'shareme_fb',
			'opts'	=> array(
				'shareme_google' => array('label'=> 'Google+ Sharing Icon', 'type' => 'check'),
				'shareme_pin' => array('label'=> 'Pinterest Sharing Icon', 'type' => 'check'),
				'shareme_fb' => array('label'=> 'Facebook Sharing Icon', 'type' => 'check'),
				'shareme_tw' => array('label'=> 'Twitter Sharing Icon', 'type' => 'check'),
				'shareme_su' => array('label'=> 'StumbleUpon Sharing Icon', 'type' => 'check'),
				'shareme_redd' => array('label'=> 'Reddit Sharing Icon', 'type' => 'check'),
				'shareme_del' => array('label'=> 'Del.icio.us Sharing Icon', 'type' => 'check'),
				'shareme_digg' => array('label'=> 'Digg Sharing Icon', 'type' => 'check'),
				'shareme_linkedin' => array('label'=> 'LinkedIn Sharing Icon', 'type' => 'check'),
				'shareme_email' => array('label'=> 'Email Sharing Icon', 'type' => 'check'),
				/*'shareme_excerpt' => array('label'=> 'Add Social Icons Under Excerpts (Blog Page)', 'type' => 'check'),*/
			),
		);
		
/* TEXT OPTIONS */

		$options[] = array(
			'type'	=> 'multi',
			'title'	=> 'Text Options',
			'default'	=> 'If you like this, please share it!',
			'opts'	=> array(
				array(
					'key'		=> 'shareme_text_greeting',
					'type'		=> 'text',
					'label'		=> 'Set the text to display next to your social sharing icons (or leave blank for none). Example: "If you like this, please share it!"',
					'default'	=> 'If you like this, please share it!'
				),
				array(
					'key'	=> 'shareme_text_float',
					'type'	=> 'select',
					'label'	=> 'Would you prefer text on the left or right?',
					'default'	=> 'text_left',
					'opts'	=> array(
						'text_left'		=> array( 'name' => 'Text on left' ),
						'text_right'	=> array( 'name' => 'Text on right' )
					),
				),
				array(
					'key'		=> 'shareme_text_color',
					'type'		=> 'color',
					'label'		=> 'And what color should the text be?',
					'default'		=> '#000000',
				),
				array(
					'key'			=> 'shareme_text_size',
					'type'			=> 'count_select',
					'label'			=> 'How big should the text be?',
					'count_start'	=> 8,
					'count_number'	=> 50,
					'suffix'		=> 'px',
					'default'		=> '12'
				),
				array(
					'key'		=> 'shareme_text_font',
					'type'		=> 'type',
					'label'		=> 'And what font would you like to use?',
					'default'	=> 'Arial'
				),
			)
		);
		
		return $options;
	}

}//end class and file