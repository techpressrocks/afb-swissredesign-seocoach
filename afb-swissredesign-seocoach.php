<?php
/*
Plugin Name: AFB swissredesign.ch SEO Coach
Plugin URI: http://swissredesign.ch/
Description: swissredesign.ch SEO Coach
Version: 1.0.0
Author: Andrew F. Burton
Author URI: http://swissredesign.ch/
License: GPLv2
*/
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * The Class.
 */
class AFBSwissRedesignSeoCoach {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array($this, 'afb_sr_register_meta_boxes' ));
		add_action( 'save_post', array($this, 'save' ));
		add_action( 'init', array($this, 'afb_sr_seo_taxonomy' ));
		add_action( 'init', array($this, 'afb_sr_seocoach' ));
		add_action( 'admin_print_styles', array($this, 'afb_sr_admin_styles' ));
		add_action( 'plugins_loaded', array($this, 'sr_plugin_load_plugin_textdomain' ));	
		add_action( 'admin_enqueue_scripts', array($this, 'afb_sr_load_scripts' ));
		add_action('load-post-new.php', array($this, 'afb_sr_limit_seocoach' ));
	}

	public function sr_plugin_load_plugin_textdomain() {
		load_plugin_textdomain( 'afb-swissredesign-seocoach', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	public function afb_sr_load_scripts() {
		wp_enqueue_script( 'afb_sr_seocoach_scripts', plugins_url( '/js/afb-swissredesign-seocoach-scripts.js' , __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'afb_sr_logouploader', plugins_url( '/js/afb-swissredesign-logo-uploader.js' , __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-timepicker-addon', plugins_url( '/js/jquery-ui-timepicker-addon.js' , __FILE__ ), array( 'jquery-ui-core' ,'jquery-ui-datepicker', 'jquery-ui-slider') );
	}	
	
	public function afb_sr_seocoach() {
		register_post_type( 'seocoach',
			array(
				'labels' => array(
					'name' => __( 'swissredesign.ch - SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'singular_name' => __( 'SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'add_new' => __( 'Add New', 'afb-swissredesign-seocoach' ),
					'add_new_item' => __( 'Add New SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'edit' => __( 'Edit', 'afb-swissredesign-seocoach' ),
					'edit_item' => __( 'Edit SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'new_item' => __( 'New SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'view' => __( 'View', 'afb-swissredesign-seocoach' ),
					'view_item' => __( 'View SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'search_items' => __( 'Search SEO Coach Sheet', 'afb-swissredesign-seocoach' ),
					'not_found' => __( 'No SEO Coach Sheet found', 'afb-swissredesign-seocoach' ),
					'not_found_in_trash' => __( 'No SEO Coach Sheet found in Trash', 'afb-swissredesign-seocoach' ),
					'parent' => __( 'Parent SEO Coach Sheet', 'afb-swissredesign-seocoach' )
				),
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'editor', 'revisions', 'author', 'publicize'),
				'menu_icon' => plugins_url( 'images/swiss-redesign-16px.png', __FILE__ ),
				'has_archive' => false,
			)
		);
	}	

	public function afb_sr_seo_taxonomy() {

		$labels = array(
			'name'              => _x( 'Keywords', 'afb-swissredesign-seocoach' ),
			'singular_name'     => _x( 'Keyword', 'afb-swissredesign-seocoach' ),
			'search_items'      => __( 'Search Keywords', 'afb-swissredesign-seocoach' ),
			'all_items'         => __( 'All Keywords', 'afb-swissredesign-seocoach' ),
			'parent_item'       => __( 'Parent Keywords', 'afb-swissredesign-seocoach' ),
			'parent_item_colon' => __( 'Parent Keyword:s', 'afb-swissredesign-seocoach' ),
			'edit_item'         => __( 'Edit Keyword', 'afb-swissredesign-seocoach' ),
			'update_item'       => __( 'Update Keyword', 'afb-swissredesign-seocoach' ),
			'add_new_item'      => __( 'Add New Keyword', 'afb-swissredesign-seocoach' ),
			'new_item_name'     => __( 'New Keyword Name', 'afb-swissredesign-seocoach' ),
			'menu_name'         => __( 'Keywords', 'afb-swissredesign-seocoach' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'Keywords' ),
		);
		register_taxonomy( 'Keywords', array( 'seocoach' ), $args );
	}	

	public function afb_sr_limit_seocoach() {
		global $typenow;

		if( 'seocoach' !== $typenow ) {
			return;
		}
		
		$total = get_posts( array( 
			'post_type' => 'seocoach', 
			'numberposts' => -1, 
			'post_status' => 'publish,future,draft' 
		));

		if( $total && count( $total ) >= 1 ) {
			wp_die(
				'Sorry, maximum number of posts reached', 
				'Maximum reached',  
				array( 
					'response' => 500, 
					'back_link' => true 
				)
			); 
		}		
	}
	public function afb_sr_register_meta_boxes() {
		add_meta_box( 'sr-basisinfos', __( 'swissredesign.ch - Company - Basic Info', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_basicinfos'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-firmeninfos', __( 'swissredesign.ch - Detailed Info', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_companyinfos'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-interactions', __( 'swissredesign.ch - Interactions', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_interactions'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-designbrief', __( 'swissredesign.ch - Design Brief', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_designbrief'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-ziele', __( 'swissredesign.ch - Redesign Goals/Functionality', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_goals'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-contact', __( 'swissredesign.ch - Client Contacts', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_contact'), 'seocoach', 'normal', 'high' );
		add_meta_box( 'sr-projekt', __( 'swissredesign.ch - Project', 'afb-swissredesign-leads' ), array($this, 'afb_sr_meta_box_project'), 'seocoach', 'normal', 'high' );
	}

	public function afb_sr_meta_box_basicinfos ($post) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field('sr_nonce_check', 'sr_nonce_check_value');

		// Use get_post_meta to retrieve an existing value from the database.
		$sr_website_adresse = get_post_meta($post -> ID, '_sr_website_adresse', true);
		$sr_email_adresse = get_post_meta($post -> ID, '_sr_email_adresse', true);
		$sr_phone = get_post_meta($post -> ID, '_sr_phone', true);
		$sr_adresse = get_post_meta($post -> ID, '_sr_adresse', true);
		
		?>
		<p>
			<label for="sr_website_adresse" class="swiss_redesign_label"><?php _e( 'Website Address ', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_website_adresse" id="swiss_redesign_input" value="<?php if ( isset ( $sr_website_adresse ) ) echo esc_url($sr_website_adresse); ?>" />
		</p>
		<p>
			<label for="sr_adresse" class="swiss_redesign_label"><?php _e( 'Street Address ', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_adresse" id="swiss_redesign_input" value="<?php if ( isset ( $sr_adresse ) ) echo $sr_adresse; ?>" />
		</p>
		<p>
			<label for="sr_phone" class="swiss_redesign_label"><?php _e( 'Phone ', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_phone" id="swiss_redesign_input" value="<?php if ( isset ( $sr_phone ) ) echo $sr_phone; ?>" />
		</p>		
		<p>
			<label for="sr_email_adresse" class="swiss_redesign_label"><?php _e( 'Email Address ', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_email_adresse" id="swiss_redesign_input" value="<?php if ( isset ( $sr_email_adresse ) ) echo $sr_email_adresse; ?>" />
		</p>
	<?php	
	}	
	
	public function afb_sr_meta_box_companyinfos ($post) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field('sr_nonce_check', 'sr_nonce_check_value');

		// Use get_post_meta to retrieve an existing value from the database.
		$sr_kontaktperson = get_post_meta($post -> ID, '_sr_kontaktperson', true);
		$sr_zweck = get_post_meta($post -> ID, '_sr_zweck', true);
		$sr_alter = get_post_meta($post -> ID, '_sr_alter', true);
		$sr_mitarbeiter = get_post_meta($post -> ID, '_sr_mitarbeiter', true);
		$sr_umsatz = get_post_meta($post -> ID, '_sr_umsatz', true);
		$sr_ziele = get_post_meta($post -> ID, '_sr_ziele', true);
		$sr_konkurrenten = get_post_meta($post -> ID, '_sr_konkurrenten', true);
		$sr_firmen_infos = get_post_meta($post -> ID, '_sr_firmen_infos', true);
		
		?>
		<p>
			<label for="sr_kontaktperson" class="swiss_redesign_label"><?php _e( 'Contact Person', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_kontaktperson" id="swiss_redesign_input" value="<?php if ( isset ( $sr_kontaktperson ) ) echo $sr_kontaktperson; ?>" />
		</p>
		<p>
			<label for="sr_zweck" class="swiss_redesign_label"><?php _e( 'Company Purpose (Sector, Products, Services)', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_zweck" id="swiss_redesign_input" value="<?php if ( isset ( $sr_zweck ) ) echo $sr_zweck; ?>" />
		</p>
		<p>
			<label for="sr_alter" class="swiss_redesign_label"><?php _e( 'Company Age', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_alter" id="swiss_redesign_input" value="<?php if ( isset ( $sr_alter ) ) echo $sr_alter; ?>" />
		</p>
		<p>
			<label for="sr_mitarbeiter" class="swiss_redesign_label"><?php _e( 'No. of Employees ', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_mitarbeiter" id="swiss_redesign_input" value="<?php if ( isset ( $sr_mitarbeiter ) ) echo $sr_mitarbeiter; ?>" />
		</p>
		<p>
			<label for="sr_umsatz" class="swiss_redesign_label"><?php _e( 'Turnover (Yearly)', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_umsatz" id="swiss_redesign_input" value="<?php if ( isset ( $sr_umsatz ) ) echo $sr_umsatz; ?>" />
		</p>		
		<p>
			<label for="sr_ziele" class="swiss_redesign_label"><?php _e( 'Company Goals (Short & Longterm)', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_ziele" id="swiss_redesign_input" value="<?php if ( isset ( $sr_ziele ) ) echo $sr_ziele; ?>" />
		</p>
		<p>
			<label for="sr_konkurrenten" class="swiss_redesign_label"><?php _e( 'Main Competitors', 'afb-swissredesign-leads' )?></label>
			<input type="text" name="sr_konkurrenten" id="swiss_redesign_input" value="<?php if ( isset ( $sr_ziele ) ) echo $sr_konkurrenten; ?>" />
		</p>		
		<div>
			<label for="sr_firmen_infos" class="swiss_redesign_label"><?php _e( 'Further Company Infos (e.g. Links)', 'afb-swissredesign-leads' )?></label>
				<?php
					wp_editor( $sr_firmen_infos, '_wp_editor_firmeninfos', array(
						'wpautop'       => true,
						'media_buttons' => true,
						'textarea_name' => 'sr_firmen_infos',
						'textarea_rows' => 10,
						'teeny'         => true
					) );
				?>
		</div>
	<?php	
	}
	
	public function afb_sr_meta_box_interactions ($post) {	
	
		// Retrieve current date for cookie
		$sr_interaction1_date = get_post_meta( $post->ID, '_sr_interaction1_date', true  );
		$sr_interaction1_textarea = get_post_meta( $post->ID, '_sr_interaction1_textarea', true  );
		$sr_interaction2_date = get_post_meta( $post->ID, '_sr_interaction2_date', true  );
		$sr_interaction2_textarea = get_post_meta( $post->ID, '_sr_interaction2_textarea', true  );
		$sr_interaction3_date = get_post_meta( $post->ID, '_sr_interaction3_date', true  );
		$sr_interaction3_textarea = get_post_meta( $post->ID, '_sr_interaction3_textarea', true  );
		$sr_interaction4_date = get_post_meta( $post->ID, '_sr_interaction4_date', true  );
		$sr_interaction4_textarea = get_post_meta( $post->ID, '_sr_interaction4_textarea', true  );
		$sr_interaction5_date = get_post_meta( $post->ID, '_sr_interaction5_date', true  );
		$sr_interaction5_textarea = get_post_meta( $post->ID, '_sr_interaction5_textarea', true  );
		$sr_interaction6_date = get_post_meta( $post->ID, '_sr_interaction6_date', true  );
		$sr_interaction6_textarea = get_post_meta( $post->ID, '_sr_interaction6_textarea', true  );
		$sr_interaction7_date = get_post_meta( $post->ID, '_sr_interaction7_date', true  );
		$sr_interaction7_textarea = get_post_meta( $post->ID, '_sr_interaction7_textarea', true  );
		$sr_interaction8_date = get_post_meta( $post->ID, '_sr_interaction8_date', true  );
		$sr_interaction8_textarea = get_post_meta( $post->ID, '_sr_interaction8_textarea', true  );
		$sr_interaction9_date = get_post_meta( $post->ID, '_sr_interaction9_date', true  );
		$sr_interaction9_textarea = get_post_meta( $post->ID, '_sr_interaction9_textarea', true  );		
		$sr_interaction10_date = get_post_meta( $post->ID, '_sr_interaction10_date', true  );
		$sr_interaction10_textarea = get_post_meta( $post->ID, '_sr_interaction10_textarea', true  );		
		?>
		<h3><?php _e( 'Interaction 1', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'First Contact (Cold Call)', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Important: First Interaction is NOT the time to give value</p><p>Objectives:</p><ul><li>1) "We are going to create a set of planned, expected conversations."</li><li>2) Schedule the second interaction (which is planned, 3-5 days later)</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction1_date" class="datepicker"  value="<?php echo $sr_interaction1_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction1_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction1_textarea ) ) echo $sr_interaction1_textarea; ?></textarea>
		</p>
		</div>
		<div style="clear:both;"></div>
		<hr/>
		<h3><?php _e( 'Interaction 2', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Qualification', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Qualify the prospect</li><li>2) No more than 15 minutes</li><li>3) Commit to a scheduled discovery</li><li>4) Schedule next interaction</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction2_date" class="datepicker" value="<?php echo $sr_interaction2_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction2_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction2_textarea ) ) echo $sr_interaction2_textarea; ?></textarea>
		</p>
		</div>		
		<div style="clear:both;"></div>
		<hr/>
		<h3><?php _e( 'Interaction 3', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Discovery 1 - Intro & Prospect Business, Buying Process & Stated Needs', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Intro (Understand the prospect, Find answers to "I do not know" (like SEO), Desire to find and remove problems, Identify if problems are opportunities for you</li><li>2) Understand prospect\'s business, stated needs and how they buy websites</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction3_date" class="datepicker" value="<?php echo $sr_interaction3_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction3_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction3_textarea ) ) echo $sr_interaction3_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>		
		<h3><?php _e( 'Interaction 4', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Discovery 2 - Understand prospects customer', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Website exits to solve customers problems, speak to customers pain</li><li>2) Build an avatar, Who is their customer? Where does their customer hang out?, Pains & Problems, Opportunities</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction4_date" class="datepicker" value="<?php echo $sr_interaction4_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction4_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction4_textarea ) ) echo $sr_interaction4_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>		
		<h3><?php _e( 'Interaction 5', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Discovery 3 - Understand their Market & Competition', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Market (Size, Key competitors, Geography)</li><li>2) Online Business (Search/Keywords, Other Channels)</li><li>3) Competitors websites (Design/Professionalism, Voice/Speak to, Conversion Tactics)</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction5_date" class="datepicker" value="<?php echo $sr_interaction5_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction5_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction5_textarea ) ) echo $sr_interaction5_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>
		<h3><?php _e( 'Interaction 6', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Discovery 4 - Understand Strategy & Tactics', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Understand difference between strategy & tactics</li><li>2) Historical strategies/tactics (What have they done in the past that worked, what has not worked?)</li><li>3) Current/futures strategies/tactics</li><li>4) Web stragegies (Traffic, Conversion, Email followup)</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction6_date" class="datepicker" value="<?php echo $sr_interaction6_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction6_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction6_textarea ) ) echo $sr_interaction6_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>	
		<h3><?php _e( 'Interaction 7', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Solution Presentation', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) First time selling (Shift to talking what we can do for your business, remain a consultant but begin to talk about what you can do, present like a convo, face to face, no lecture)</li><li>2) Propose solution to the greatest pain you found in discovery (Validation (key pains to solve), Strategy (traffic & conversion model), Process overview, Investment, Results, Next Steps)</li><li>3) Getting specific about the interaction (Slide deck, the demo, similar work samples, solidify solution, schedule proposal presentation)</ul>', 'afb-swissredesign-leads' )?></h5>		
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction7_date" class="datepicker" value="<?php echo $sr_interaction7_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction7_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction7_textarea ) ) echo $sr_interaction7_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>	
		<h3><?php _e( 'Interaction 8', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Proposal Presentation', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Proposals do not sell, you do (Never just email a proposal, nothing should exist in proposal that you have not already specificially talked about)</li><li>2) Present a specific scope of work with budget and timeline to fulfill your solution (Read literally everything in proposal document)</li></ul>', 'afb-swissredesign-leads' )?></h5>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction8_date" class="datepicker" value="<?php echo $sr_interaction8_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction8_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction8_textarea ) ) echo $sr_interaction8_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
		<hr/>
		<h3><?php _e( 'Interaction 9', 'afb-swissredesign-leads' )?></h3>
		<h4><?php _e( 'Work Plan', 'afb-swissredesign-leads' )?></h4>
		<h5><?php _e( '<p>Objectives:</p><ul><li>1) Assumptive close (e.g. "Would you like to move forward this project? Schedule next meeting. Contract due upon start of meeting)</li><li>2) Lead way through buying process all the way to a close (Kickoff, Bring in resources, schedule deiliverables, project surveys)</li></ul>', 'afb-swissredesign-leads' )?></h5>		
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_interaction9_date" class="datepicker" value="<?php echo $sr_interaction9_date; ?>" />
		</div>
 		<div style="width:69%; float:right;">
		<p><strong><?php _e( 'Notes', 'afb-swissredesign-leads' )?></strong></p>
		<p>
			<textarea rows="2" name="sr_interaction9_textarea" id="sr_full_textarea"><?php if ( isset ( $sr_interaction9_textarea ) ) echo $sr_interaction9_textarea; ?></textarea>
		</p>
		</div>	
		<div style="clear:both;"></div>
	<?php	
	}
	
	public function afb_sr_meta_box_designbrief ($post) {
		wp_nonce_field('sr_nonce_check', 'sr_nonce_check_value');
		$sr_bisheriger_designstil = get_post_meta($post -> ID, '_sr_bisheriger_designstil', true);
		$sr_designbrief_checkbox1 = get_post_meta($post -> ID, '_sr_designbrief_checkbox1', true);
		$sr_designbrief_checkbox2 = get_post_meta($post -> ID, '_sr_designbrief_checkbox2', true);
		$sr_designbrief_checkbox3 = get_post_meta($post -> ID, '_sr_designbrief_checkbox3', true);
		$sr_designbrief_checkbox4 = get_post_meta($post -> ID, '_sr_designbrief_checkbox4', true);
		$sr_designbrief_checkbox5 = get_post_meta($post -> ID, '_sr_designbrief_checkbox5', true);
		$sr_designbrief_checkbox6 = get_post_meta($post -> ID, '_sr_designbrief_checkbox6', true);
		$sr_designbrief_checkbox7 = get_post_meta($post -> ID, '_sr_designbrief_checkbox7', true);
		$sr_designbrief_checkbox8 = get_post_meta($post -> ID, '_sr_designbrief_checkbox8', true);
		$sr_designbrief_checkbox9 = get_post_meta($post -> ID, '_sr_designbrief_checkbox9', true);
		$sr_designbrief_checkbox10 = get_post_meta($post -> ID, '_sr_designbrief_checkbox10', true);
		$sr_designbrief_checkbox11 = get_post_meta($post -> ID, '_sr_designbrief_checkbox11', true);
		$sr_designbrief_checkbox12 = get_post_meta($post -> ID, '_sr_designbrief_checkbox12', true);
		$sr_designbrief_checkbox13 = get_post_meta($post -> ID, '_sr_designbrief_checkbox13', true);
		$sr_designbrief_checkbox14 = get_post_meta($post -> ID, '_sr_designbrief_checkbox14', true);
		$sr_designbrief_checkbox15 = get_post_meta($post -> ID, '_sr_designbrief_checkbox15', true);
		$sr_designbrief_checkbox16 = get_post_meta($post -> ID, '_sr_designbrief_checkbox16', true);
		$sr_designbrief_checkbox17 = get_post_meta($post -> ID, '_sr_designbrief_checkbox17', true);
		$sr_designbrief_checkbox18 = get_post_meta($post -> ID, '_sr_designbrief_checkbox18', true);
		$sr_designbrief_checkbox19 = get_post_meta($post -> ID, '_sr_designbrief_checkbox19', true);
		$sr_designbrief_checkbox20 = get_post_meta($post -> ID, '_sr_designbrief_checkbox20', true);
		$sr_designbrief_checkbox21 = get_post_meta($post -> ID, '_sr_designbrief_checkbox21', true);
		$sr_designbrief_checkbox22 = get_post_meta($post -> ID, '_sr_designbrief_checkbox22', true);
		$sr_designbrief_checkbox23 = get_post_meta($post -> ID, '_sr_designbrief_checkbox23', true);
		$sr_designbrief_checkbox24 = get_post_meta($post -> ID, '_sr_designbrief_checkbox24', true);
		$sr_designbrief_checkbox25 = get_post_meta($post -> ID, '_sr_designbrief_checkbox25', true);
		$sr_designbrief_checkbox26 = get_post_meta($post -> ID, '_sr_designbrief_checkbox26', true);
		$sr_designbrief_checkbox27 = get_post_meta($post -> ID, '_sr_designbrief_checkbox27', true);
		
		$sr_design_primarycolor = get_post_meta($post -> ID, '_sr_design_primarycolor', true);
		$sr_design_secondarycolor = get_post_meta($post -> ID, '_sr_design_secondarycolor', true);

		?>
		<p>
			<label for="sr_bisheriger_designstil" class="swiss_redesign_label"><?php _e( 'Existing Design Style (in keywords)', 'afb-swissredesign-leads' )?></label>
			<textarea rows="2" name="sr_bisheriger_designstil" id="swiss_redesign_input"><?php if ( isset ( $sr_bisheriger_designstil ) ) echo $sr_bisheriger_designstil; ?></textarea>
		</p>
		<p class="swiss_redesign_label"><?php _e( 'Redesign Design Style', 'afb-swissredesign-leads' )?></p>
		
		<div style="clear:both;"></div>
		<div>
			<label for="sr_designbrief_checkbox1">
					<input type="checkbox" name="sr_designbrief_checkbox1" id="sr_designbrief_checkbox1" value="yes" <?php if ( isset ( $sr_designbrief_checkbox1 ) ) checked( $sr_designbrief_checkbox1, 'yes' ); ?> />
					<?php _e( 'Unemotional', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox2">
					<input type="checkbox" name="sr_designbrief_checkbox2" id="sr_designbrief_checkbox2" value="yes" <?php if ( isset ( $sr_designbrief_checkbox2 ) ) checked( $sr_designbrief_checkbox2, 'yes' ); ?> />
					<?php _e( 'Usual in the industry', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox3">
					<input type="checkbox" name="sr_designbrief_checkbox3" id="sr_designbrief_checkbox3" value="yes" <?php if ( isset ( $sr_designbrief_checkbox3 ) ) checked( $sr_designbrief_checkbox3, 'yes' ); ?> />
					<?php _e( 'Prominent', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox4">
					<input type="checkbox" name="sr_designbrief_checkbox4" id="sr_designbrief_checkbox4" value="yes" <?php if ( isset ( $sr_designbrief_checkbox4 ) ) checked( $sr_designbrief_checkbox4, 'yes' ); ?> />
					<?php _e( 'Down-to-earch', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox5">
					<input type="checkbox" name="sr_designbrief_checkbox6" id="sr_designbrief_checkbox6" value="yes" <?php if ( isset ( $sr_designbrief_checkbox6 ) ) checked( $sr_designbrief_checkbox6, 'yes' ); ?> />
					<?php _e( 'Emotionial', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox6">
					<input type="checkbox" name="sr_designbrief_checkbox7" id="sr_designbrief_checkbox7" value="yes" <?php if ( isset ( $sr_designbrief_checkbox7 ) ) checked( $sr_designbrief_checkbox7, 'yes' ); ?> />
					<?php _e( 'Modern', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox7">
					<input type="checkbox" name="sr_designbrief_checkbox8" id="sr_designbrief_checkbox8" value="yes" <?php if ( isset ( $sr_designbrief_checkbox8 ) ) checked( $sr_designbrief_checkbox8, 'yes' ); ?> />
					<?php _e( 'Lieblich', 'afb-swissredesign-leads' )?>
				</label>				
			</div>	
			<div>	
				<label for="sr_designbrief_checkbox8">
					<input type="checkbox" name="sr_designbrief_checkbox9" id="sr_designbrief_checkbox9" value="yes" <?php if ( isset ( $sr_designbrief_checkbox9 ) ) checked( $sr_designbrief_checkbox9, 'yes' ); ?> />
					<?php _e( 'Fotografisch', 'afb-swissredesign-leads' )?>
				</label>		
				<label for="sr_designbrief_checkbox9">
					<input type="checkbox" name="sr_designbrief_checkbox10" id="sr_designbrief_checkbox10" value="yes" <?php if ( isset ( $sr_designbrief_checkbox10 ) ) checked( $sr_designbrief_checkbox10, 'yes' ); ?> />
					<?php _e( 'Klassisch', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox10">
					<input type="checkbox" name="sr_designbrief_checkbox11" id="sr_designbrief_checkbox11" value="yes" <?php if ( isset ( $sr_designbrief_checkbox11 ) ) checked( $sr_designbrief_checkbox11, 'yes' ); ?> />
					<?php _e( 'Verspielt', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox11">
					<input type="checkbox" name="sr_designbrief_checkbox12" id="sr_designbrief_checkbox12" value="yes" <?php if ( isset ( $sr_designbrief_checkbox12 ) ) checked( $sr_designbrief_checkbox12, 'yes' ); ?> />
					<?php _e( 'Progressiv', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox12">
					<input type="checkbox" name="sr_designbrief_checkbox13" id="sr_designbrief_checkbox13" value="yes" <?php if ( isset ( $sr_designbrief_checkbox13 ) ) checked( $sr_designbrief_checkbox13, 'yes' ); ?> />
					<?php _e( 'Neutral', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox13">
					<input type="checkbox" name="sr_designbrief_checkbox14" id="sr_designbrief_checkbox14" value="yes" <?php if ( isset ( $sr_designbrief_checkbox14 ) ) checked( $sr_designbrief_checkbox14, 'yes' ); ?> />
					<?php _e( 'Männlich', 'afb-swissredesign-leads' )?>
				</label>		
				<label for="sr_designbrief_checkbox14">
					<input type="checkbox" name="sr_designbrief_checkbox15" id="sr_designbrief_checkbox15" value="yes" <?php if ( isset ( $sr_designbrief_checkbox15 ) ) checked( $sr_designbrief_checkbox15, 'yes' ); ?> />
					<?php _e( 'Traditionell', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox15">
					<input type="checkbox" name="sr_designbrief_checkbox16" id="sr_designbrief_checkbox16" value="yes" <?php if ( isset ( $sr_designbrief_checkbox16 ) ) checked( $sr_designbrief_checkbox16, 'yes' ); ?> />
					<?php _e( 'Bunt', 'afb-swissredesign-leads' )?>
				</label>				
			</div>	
			<div>	
				<label for="sr_designbrief_checkbox16">
					<input type="checkbox" name="sr_designbrief_checkbox17" id="sr_designbrief_checkbox17" value="yes" <?php if ( isset ( $sr_designbrief_checkbox17 ) ) checked( $sr_designbrief_checkbox17, 'yes' ); ?> />
					<?php _e( 'Weiblich', 'afb-swissredesign-leads' )?>
				</label>		
				<label for="sr_designbrief_checkbox17">
					<input type="checkbox" name="sr_designbrief_checkbox18" id="sr_designbrief_checkbox18" value="yes" <?php if ( isset ( $sr_designbrief_checkbox18 ) ) checked( $sr_designbrief_checkbox18, 'yes' ); ?> />
					<?php _e( 'Zeitgemäss', 'afb-swissredesign-leads' )?>
				</label>			
				<label for="sr_designbrief_checkbox18">
					<input type="checkbox" name="sr_designbrief_checkbox19" id="sr_designbrief_checkbox19" value="yes" <?php if ( isset ( $sr_designbrief_checkbox19 ) ) checked( $sr_designbrief_checkbox19, 'yes' ); ?> />
					<?php _e( 'Farbig', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox19">
					<input type="checkbox" name="sr_designbrief_checkbox20" id="sr_designbrief_checkbox20" value="yes" <?php if ( isset ( $sr_designbrief_checkbox20 ) ) checked( $sr_designbrief_checkbox20, 'yes' ); ?> />
					<?php _e( 'Seriös', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox20">
					<input type="checkbox" name="sr_designbrief_checkbox21" id="sr_designbrief_checkbox21" value="yes" <?php if ( isset ( $sr_designbrief_checkbox21 ) ) checked( $sr_designbrief_checkbox21, 'yes' ); ?> />
					<?php _e( 'Grafisch', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox21">
					<input type="checkbox" name="sr_designbrief_checkbox22" id="sr_designbrief_checkbox22" value="yes" <?php if ( isset ( $sr_designbrief_checkbox22 ) ) checked( $sr_designbrief_checkbox22, 'yes' ); ?> />
					<?php _e( 'S/W', 'afb-swissredesign-leads' )?>
				</label>
				<label for="sr_designbrief_checkbox22">
					<input type="checkbox" name="sr_designbrief_checkbox23" id="sr_designbrief_checkbox23" value="yes" <?php if ( isset ( $sr_designbrief_checkbox23 ) ) checked( $sr_designbrief_checkbox23, 'yes' ); ?> />
					<?php _e( 'Freundlich', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox23">
					<input type="checkbox" name="sr_designbrief_checkbox24" id="sr_designbrief_checkbox24" value="yes" <?php if ( isset ( $sr_designbrief_checkbox24 ) ) checked( $sr_designbrief_checkbox24, 'yes' ); ?> />
					<?php _e( 'Flat (flach)', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox24">
					<input type="checkbox" name="sr_designbrief_checkbox25" id="sr_designbrief_checkbox25" value="yes" <?php if ( isset ( $sr_designbrief_checkbox25 ) ) checked( $sr_designbrief_checkbox25, 'yes' ); ?> />
					<?php _e( 'Farbkräftig', 'afb-swissredesign-leads' )?>
				</label>				
			</div>	
			<div>				
				<label for="sr_designbrief_checkbox25">
					<input type="checkbox" name="sr_designbrief_checkbox26" id="sr_designbrief_checkbox26" value="yes" <?php if ( isset ( $sr_designbrief_checkbox26 ) ) checked( $sr_designbrief_checkbox26, 'yes' ); ?> />
					<?php _e( 'Exklusiv', 'afb-swissredesign-leads' )?>
				</label>		
				<label for="sr_designbrief_checkbox26">
					<input type="checkbox" name="sr_designbrief_checkbox27" id="sr_designbrief_checkbox27" value="yes" <?php if ( isset ( $sr_designbrief_checkbox27 ) ) checked( $sr_designbrief_checkbox27, 'yes' ); ?> />
					<?php _e( 'Illustrativ', 'afb-swissredesign-leads' )?>
				</label>	
				<label for="sr_designbrief_checkbox27">
					<input type="checkbox" name="sr_designbrief_checkbox28" id="sr_designbrief_checkbox28" value="yes" <?php if ( isset ( $sr_designbrief_checkbox28 ) ) checked( $sr_designbrief_checkbox28, 'yes' ); ?> />
					<?php _e( 'Kühl', 'afb-swissredesign-leads' )?>
				</label>					
			</div>
		<p>
			<label for="sr_design_primarycolor" class="swiss_redesign_label"><?php _e( 'Primäre Firmenfarbe', 'afb-swissredesign-leads' )?></label>
			<input name="sr_design_primarycolor" type="text" value="<?php if ( isset ( $sr_design_primarycolor ) ) echo $sr_design_primarycolor; ?>" class="sr_design_primarycolor" />
		</p>
		<p>
			<label for="sr_design_secondarycolor" class="swiss_redesign_label"><?php _e( 'Sekundäre Firmenfarbe', 'afb-swissredesign-leads' )?></label>
			<input name="sr_design_secondarycolor" type="text" value="<?php if ( isset ( $sr_design_secondarycolor ) ) echo $sr_design_secondarycolor; ?>" class="sr_design_secondarycolor" />
		</p>		
		<?php
			$meta_key = 'afb_sr_logo_image';
			echo $this->afb_sr_logo_uploader( $meta_key, get_post_meta($post->ID, $meta_key, true) );		
	}
	
	public function afb_sr_meta_box_goals ($post) {
		wp_nonce_field('sr_nonce_check', 'sr_nonce_check_value');
		$sr_goals_checkbox1 = get_post_meta($post -> ID, '_sr_goals_checkbox1', true);
		$sr_goals_checkbox2 = get_post_meta($post -> ID, '_sr_goals_checkbox2', true);
		$sr_goals_checkbox3 = get_post_meta($post -> ID, '_sr_goals_checkbox3', true);
		$sr_goals_checkbox4 = get_post_meta($post -> ID, '_sr_goals_checkbox4', true);
		$sr_goals_checkbox5 = get_post_meta($post -> ID, '_sr_goals_checkbox5', true);

		$sr_goals_textarea1 = get_post_meta($post -> ID, '_sr_goals_textarea1', true);
		
		$sr_goals_checkbox6 = get_post_meta($post -> ID, '_sr_goals_checkbox6', true);
		$sr_goals_checkbox7 = get_post_meta($post -> ID, '_sr_goals_checkbox7', true);
		$sr_goals_checkbox8 = get_post_meta($post -> ID, '_sr_goals_checkbox8', true);
		$sr_goals_checkbox9 = get_post_meta($post -> ID, '_sr_goals_checkbox9', true);
		$sr_goals_checkbox10 = get_post_meta($post -> ID, '_sr_goals_checkbox10', true);
		$sr_goals_checkbox11 = get_post_meta($post -> ID, '_sr_goals_checkbox11', true);
		$sr_goals_checkbox12 = get_post_meta($post -> ID, '_sr_goals_checkbox12', true);
		$sr_goals_checkbox13 = get_post_meta($post -> ID, '_sr_goals_checkbox13', true);
		$sr_goals_checkbox14 = get_post_meta($post -> ID, '_sr_goals_checkbox14', true);

		$sr_goals_textarea2 = get_post_meta($post -> ID, '_sr_goals_textarea2', true);		
	?>	
	<p class="swiss_redesign_label"><?php _e( 'Primäre Ziele des Redesigns', 'afb-swissredesign-leads' )?></p>
		<div style="clear:both;"></div>
		<div>
			<label for="sr_goals_checkbox1">
				<input type="checkbox" name="sr_goals_checkbox1" value="yes" <?php if ( isset ( $sr_goals_checkbox1 ) ) checked( $sr_goals_checkbox1, 'yes' ); ?> />
					<?php _e( 'Mehr Kundenkontakte (Lead Generation)', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox2">
				<input type="checkbox" name="sr_goals_checkbox2" value="yes" <?php if ( isset ( $sr_goals_checkbox2 ) ) checked( $sr_goals_checkbox2, 'yes' ); ?> />
					<?php _e( 'Direkter Verkauf (E-Commerce mit WooCommerce)', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>		
			<label for="sr_goals_checkbox3">
				<input type="checkbox" name="sr_goals_checkbox3" value="yes" <?php if ( isset ( $sr_goals_checkbox3 ) ) checked( $sr_goals_checkbox3, 'yes' ); ?> />
					<?php _e( 'Kunden-Support (z.B. mit FAQ, Support-Chat usw.)', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox4">
				<input type="checkbox" name="sr_goals_checkbox4" value="yes" <?php if ( isset ( $sr_goals_checkbox4 ) ) checked( $sr_goals_checkbox4, 'yes' ); ?> />
					<?php _e( 'Generell Auffrischung des Web-Auftritts', 'afb-swissredesign-leads' )?>
			</label>
		</div>	
		<div>
			<label for="sr_goals_checkbox5">
				<input type="checkbox" name="sr_goals_checkbox5" value="yes" <?php if ( isset ( $sr_goals_checkbox5 ) ) checked( $sr_goals_checkbox5, 'yes' ); ?> />
					<?php _e( 'Andere Ziele', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<p>
			<textarea rows="2" name="sr_goals_textarea1" id="sr_full_textarea"><?php if ( isset ( $sr_goals_textarea1 ) ) echo $sr_goals_textarea1; ?></textarea>
		</p>
		<p class="swiss_redesign_label"><?php _e( 'Funktionalität & Bestandteile', 'afb-swissredesign-leads' )?></p>
		<div style="clear:both;"></div>
		<div>
			<label for="sr_goals_checkbox6">
				<input type="checkbox" name="sr_goals_checkbox6" value="yes" <?php if ( isset ( $sr_goals_checkbox6 ) ) checked( $sr_goals_checkbox6, 'yes' ); ?> />
					<?php _e( 'Fotos (Bildmaterial)', 'afb-swissredesign-leads' )?>
			</label>
		</div>		
		<div>
			<label for="sr_goals_checkbox7">
				<input type="checkbox" name="sr_goals_checkbox7" value="yes" <?php if ( isset ( $sr_goals_checkbox7 ) ) checked( $sr_goals_checkbox7, 'yes' ); ?> />
					<?php _e( 'Kontaktform', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox8">
				<input type="checkbox" name="sr_goals_checkbox8" value="yes" <?php if ( isset ( $sr_goals_checkbox8 ) ) checked( $sr_goals_checkbox8, 'yes' ); ?> />
					<?php _e( 'E-Commerce (WooCommerce)', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>		
			<label for="sr_goals_checkbox9">
				<input type="checkbox" name="sr_goals_checkbox9" value="yes" <?php if ( isset ( $sr_goals_checkbox9 ) ) checked( $sr_goals_checkbox9, 'yes' ); ?> />
					<?php _e( 'Call-to-Action (Handlungsaufforderungen)', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox10">
				<input type="checkbox" name="sr_goals_checkbox10" value="yes" <?php if ( isset ( $sr_goals_checkbox10 ) ) checked( $sr_goals_checkbox10, 'yes' ); ?> />
					<?php _e( 'Suchfunktion', 'afb-swissredesign-leads' )?>
			</label>
		</div>	
		<div>
			<label for="sr_goals_checkbox11">
				<input type="checkbox" name="sr_goals_checkbox11" value="yes" <?php if ( isset ( $sr_goals_checkbox11 ) ) checked( $sr_goals_checkbox11, 'yes' ); ?> />
					<?php _e( 'Einbindung Soziale Medien', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox12">
				<input type="checkbox" name="sr_goals_checkbox12" value="yes" <?php if ( isset ( $sr_goals_checkbox12 ) ) checked( $sr_goals_checkbox12, 'yes' ); ?> />
					<?php _e( 'Blog- oder News-Bereich', 'afb-swissredesign-leads' )?>
			</label>
		</div>	
		<div>
			<label for="sr_goals_checkbox13">
				<input type="checkbox" name="sr_goals_checkbox13" value="yes" <?php if ( isset ( $sr_goals_checkbox13 ) ) checked( $sr_goals_checkbox13, 'yes' ); ?> />
					<?php _e( 'Sitemap', 'afb-swissredesign-leads' )?>
			</label>
		</div>
		<div>
			<label for="sr_goals_checkbox14">
				<input type="checkbox" name="sr_goals_checkbox14" value="yes" <?php if ( isset ( $sr_goals_checkbox14 ) ) checked( $sr_goals_checkbox14, 'yes' ); ?> />
					<?php _e( 'Other Functionality', 'afb-swissredesign-leads' )?>
			</label>
		</div>		
		<p>
			<textarea rows="2" name="sr_goals_textarea2" id="sr_full_textarea"><?php if ( isset ( $sr_goals_textarea2 ) ) echo $sr_goals_textarea2; ?></textarea>
		</p>	
	<?php	
	}	
	
	public function afb_sr_meta_box_contact ($post) {	
	
		$repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);

	?>
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="2%"></th>
			<th width="31%"><?php _e( 'Datum', 'afb-swissredesign-leads' )?></th>
			<th width="61%"><?php _e( 'Gesprächsnotiz', 'afb-swissredesign-leads' )?></th>
			</tr>
	</thead>
	<tbody>
	<?php
	if ( $repeatable_fields ) :
		$i = 0;
		foreach ( $repeatable_fields as $field ) {
	?>
	<tr>
		<td><a class="button remove-row" href="#">-</a></td>
		<td>
			<input type="text" class="widefat" name="date[]" value="<?php if($field['date'] != '') echo esc_attr( $field['date'] ); ?>" />
		</td>
		<td>
			<textarea rows="1" class="widefat" name="note[]"><?php if ($field['note'] != '') echo esc_attr( $field['note'] ); ?></textarea>
		</td>	
	</tr>
	<?php
		}
	else :
		// show a blank one
?>
	<tr>
		<td><a class="button remove-row" href="#">-</a></td>
		<td><input type="text" class="widefat" name="date[]" /></td>
		<td><textarea rows="1" class="widefat" name="note[]"></textarea></td>
	</tr>
	<?php endif; ?>

	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
		<td><a class="button remove-row" href="#">-</a></td>
		<td><input type="text" id="date0" class="widefat" name="date[]" /></td>
		<td><textarea rows="1" class="widefat" name="note[]"></textarea></td>
	</tr>
	</tbody>
	</table>

	<p><a id="add-row" class="button" href="#"><?php _e( 'Weitere Notiz hinzufügen', 'afb-swissredesign-leads' )?></a>
		<input type="submit" class="metabox_submit button" value="Save" />
	</p>
	
	<?php	
	}
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	 
	public function afb_sr_meta_box_project ($post) {	
	
		// Retrieve current date for cookie
		$sr_project_start_date = get_post_meta( $post->ID, '_sr_project_start_date', true  );
		$sr_project_projend_date = get_post_meta( $post->ID, '_sr_project_projend_date', true  );
		$sr_project_end_date = get_post_meta( $post->ID, '_sr_project_end_date', true  );
		?>
		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Start Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_project_start_date" class="datepicker" value="<?php echo $sr_project_start_date; ?>" />
		</div>
 
		<div style="width:31%; display:inline-block;">
		<p><strong><?php _e( 'Projected End Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_project_projend_date" class="datepicker" value="<?php echo $sr_project_projend_date; ?>" />
		</div>
 
		<div style="width:31%; float:right;">
		<p><strong><?php _e( 'Actual End Date', 'afb-swissredesign-leads' )?></strong></p>
		<input type="text" name="sr_project_end_date" class="datepicker" value="<?php echo $sr_project_end_date; ?>" />
		</div>
		<div style="clear:both;"></div>
		
		<?php
		$sr_projektstatus_selected = isset( $values['sr_projektstatus_select'] ) ? esc_attr( $values['sr_projektstatus_select'] ) : '';
		$sr_projektfort_selected = isset( $values['sr_projektfort_select'] ) ? esc_attr( $values['sr_projektfort_select'] ) : '';
		$sr_projektprio_selected = isset( $values['sr_projektprio_select'] ) ? esc_attr( $values['sr_projektprio_select'] ) : '';
		?>

		<div style="width:31%; float:left;">
		<p><strong><?php _e( 'Project Status', 'afb-swissredesign-leads' )?></strong></p>
        <select name="sr_projektstatus_select">
            <option value="status_nichtgesetzt" <?php selected( $sr_projektstatus_selected, 'status_nichtgesetzt' ); ?>><?php _e( 'Not Set', 'afb-swissredesign-leads' )?></option>
            <option value="kontaktphase" <?php selected( $sr_projektstatus_selected, 'kontaktphase' ); ?>><?php _e( 'Contact Phase', 'afb-swissredesign-leads' )?></option>
			<option value="offertphase" <?php selected( $sr_projektstatus_selected, 'offertphase' ); ?>><?php _e( 'Proposal Phase', 'afb-swissredesign-leads' )?></option>
			<option value="wartenofferte" <?php selected( $sr_projektstatus_selected, 'wartenofferte' ); ?>><?php _e( 'Wait for Proposal Acceptance', 'afb-swissredesign-leads' )?></option>
			<option value="aktiv" <?php selected( $sr_projektstatus_selected, 'aktiv' ); ?>><?php _e( 'Active', 'afb-swissredesign-leads' )?></option>
			<option value="beendet" <?php selected( $sr_projektstatus_selected, 'beendet' ); ?>><?php _e( 'Finished', 'afb-swissredesign-leads' )?></option>
        </select>
		</div>
 
		<div style="width:31%; display:inline-block;">
		<p><strong><?php _e( 'Projektfortschritt', 'afb-swissredesign-leads' )?></strong></p>
        <select name="sr_projektfort_select">
            <option value="fortschritt_nichtgesetzt" <?php selected( $sr_projektfort_selected, 'fortschritt_nichtgesetzt' ); ?>><?php _e( 'Not Set', 'afb-swissredesign-leads' )?></option>
            <option value="10" <?php selected( $sr_projektfort_selected, '10' ); ?>>10 %</option>
			<option value="20" <?php selected( $sr_projektfort_selected, '20' ); ?>>20 %</option>
			<option value="30" <?php selected( $sr_projektfort_selected, '30' ); ?>>30 %</option>
			<option value="40" <?php selected( $sr_projektfort_selected, '40' ); ?>>40 %</option>
			<option value="50" <?php selected( $sr_projektfort_selected, '50' ); ?>>50 %</option>
			<option value="60" <?php selected( $sr_projektfort_selected, '60' ); ?>>60 %</option>
			<option value="70" <?php selected( $sr_projektfort_selected, '70' ); ?>>70 %</option>
			<option value="70" <?php selected( $sr_projektfort_selected, '80' ); ?>>80 %</option>
			<option value="90" <?php selected( $sr_projektfort_selected, '90' ); ?>>90 %</option>
			<option value="100" <?php selected( $sr_projektfort_selected, '100' ); ?>>100 %</option>
        </select>
		</div>
 
		<div style="width:31%; float:right;">
		<p><strong><?php _e( 'Projektpriorität', 'afb-swissredesign-leads' )?></strong></p>
        <select name="sr_projektprio_select">
            <option value="prio_nichtgesetzt" <?php selected( $sr_projektprio_selected, 'prio_nichtgesetzt' ); ?>><?php _e( 'Nicht gesetzt', 'afb-swissredesign-leads' )?></option>
            <option value="niedrig" <?php selected( $sr_projektprio_selected, 'niedrig' ); ?>><?php _e( 'Niedrig', 'afb-swissredesign-leads' )?></option>
			<option value="normal" <?php selected( $sr_projektprio_selected, 'normal' ); ?>><?php _e( 'Normal', 'afb-swissredesign-leads' )?></option>
			<option value="hoch" <?php selected( $sr_projektprio_selected, 'hoch' ); ?>><?php _e( 'Hoch', 'afb-swissredesign-leads' )?></option>
        </select>
		</div>
		
		<div style="clear:both;"></div>
		
	<?php	
	}
	 
	public function save($post_id) {

		/*
		 * We need to verify this came from the our screen and with 
		 * proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if (!isset($_POST['sr_nonce_check_value']))
			return $post_id;

		$nonce = $_POST['sr_nonce_check_value'];

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($nonce, 'sr_nonce_check'))
			return $post_id;

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		// Check the user's permissions.
		if (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */
		
		/*
		 * Save values - function afb_sr_meta_box_basicinfos
		 */
		$sr_website_adresse = sanitize_text_field($_POST['sr_website_adresse']);
		update_post_meta($post_id, '_sr_website_adresse', $sr_website_adresse);
		
		$sr_email_adresse = sanitize_text_field($_POST['sr_email_adresse']);
		update_post_meta($post_id, '_sr_email_adresse', $sr_email_adresse);
		
		$sr_phone = sanitize_text_field($_POST['sr_phone']);
		update_post_meta($post_id, '_sr_phone', $sr_phone);
		
		$sr_adresse = sanitize_text_field($_POST['sr_adresse']);
		update_post_meta($post_id, '_sr_adresse', $sr_adresse);	
		
		/*
		 * Save values - function afb_sr_meta_box_companyinfos	
		 */	
		$sr_kontaktperson = sanitize_text_field($_POST['sr_kontaktperson']);
		update_post_meta($post_id, '_sr_kontaktperson', $sr_kontaktperson);	

		$sr_zweck = sanitize_text_field($_POST['sr_zweck']);
		update_post_meta($post_id, '_sr_zweck', $sr_zweck);	
		
		$sr_alter = sanitize_text_field($_POST['sr_alter']);
		update_post_meta($post_id, '_sr_alter', $sr_alter);	
		
		$sr_mitarbeiter = sanitize_text_field($_POST['sr_mitarbeiter']);
		update_post_meta($post_id, '_sr_mitarbeiter', $sr_mitarbeiter);
		
		$sr_umsatz = sanitize_text_field($_POST['sr_umsatz']);
		update_post_meta($post_id, '_sr_umsatz', $sr_umsatz);
		
		$sr_ziele = sanitize_text_field($_POST['sr_ziele']);
		update_post_meta($post_id, '_sr_ziele', $sr_ziele);	

		$sr_konkurrenten = sanitize_text_field($_POST['sr_konkurrenten']);
		update_post_meta($post_id, '_sr_konkurrenten', $sr_konkurrenten);			
		
		if ( isset ( $_POST['sr_firmen_infos'] ) ) {
			update_post_meta($post_id, '_sr_firmen_infos', $_POST['sr_firmen_infos']);
		}
		
		/*
		 * Save values - function afb_sr_meta_box_interactions	
		 */
		if( isset( $_POST['sr_interaction1_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction1_date', esc_attr( $_POST['sr_interaction1_date'] ) );	
		}
		if( isset( $_POST['sr_interaction1_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction1_textarea', esc_attr( $_POST['sr_interaction1_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction2_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction2_date', esc_attr( $_POST['sr_interaction2_date'] ) );	
		}
		if( isset( $_POST['sr_interaction2_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction2_textarea', esc_attr( $_POST['sr_interaction2_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction3_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction3_date', esc_attr( $_POST['sr_interaction3_date'] ) );	
		}
		if( isset( $_POST['sr_interaction3_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction3_textarea', esc_attr( $_POST['sr_interaction3_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction4_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction4_date', esc_attr( $_POST['sr_interaction4_date'] ) );	
		}
		if( isset( $_POST['sr_interaction4_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction4_textarea', esc_attr( $_POST['sr_interaction4_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction5_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction5_date', esc_attr( $_POST['sr_interaction5_date'] ) );	
		}
		if( isset( $_POST['sr_interaction5_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction5_textarea', esc_attr( $_POST['sr_interaction5_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction6_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction6_date', esc_attr( $_POST['sr_interaction6_date'] ) );	
		}
		if( isset( $_POST['sr_interaction6_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction6_textarea', esc_attr( $_POST['sr_interaction6_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction7_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction7_date', esc_attr( $_POST['sr_interaction7_date'] ) );	
		}
		if( isset( $_POST['sr_interaction7_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction7_textarea', esc_attr( $_POST['sr_interaction7_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction8_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction8_date', esc_attr( $_POST['sr_interaction8_date'] ) );	
		}
		if( isset( $_POST['sr_interaction8_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction8_textarea', esc_attr( $_POST['sr_interaction8_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction9_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction9_date', esc_attr( $_POST['sr_interaction9_date'] ) );	
		}
		if( isset( $_POST['sr_interaction9_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction9_textarea', esc_attr( $_POST['sr_interaction9_textarea'] ) );	
		}
		if( isset( $_POST['sr_interaction10_date'] ) ) {
			update_post_meta( $post_id, '_sr_interaction10_date', esc_attr( $_POST['sr_interaction10_date'] ) );	
		}
		if( isset( $_POST['sr_interaction10_textarea'] ) ) {
			update_post_meta( $post_id, '_sr_interaction10_textarea', esc_attr( $_POST['sr_interaction10_textarea'] ) );	
		}		
		
		/*
		 * Save values - function afb_sr_meta_box_designbrief	
		 */
		if ( isset ( $_POST['sr_bisheriger_designstil'] ) ) {
			update_post_meta($post_id, '_sr_bisheriger_designstil', $_POST['sr_bisheriger_designstil']);
		}		 
		if( isset( $_POST[ 'sr_designbrief_checkbox1' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox1', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox1', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox2' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox2', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox2', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox3' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox3', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox3', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox4' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox4', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox4', '' );
		}		
		if( isset( $_POST[ 'sr_designbrief_checkbox5' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox5', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox5', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox6' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox6', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox6', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox7' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox7', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox7', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox8' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox8', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox8', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox9' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox9', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox9', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox10' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox10', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox10', '' );
		}		
		if( isset( $_POST[ 'sr_designbrief_checkbox10' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox10', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox10', '' );
		}
		if( isset( $_POST[ 'sr_designbrief_checkbox11' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox11', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox11', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox12' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox12', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox12', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox13' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox13', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox13', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox14' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox14', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox14', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox15' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox15', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox15', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox16' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox16', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox16', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox17' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox17', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox17', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox18' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox18', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox18', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox19' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox19', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox19', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox20' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox20', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox20', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox21' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox21', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox21', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox22' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox22', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox22', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox23' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox23', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox23', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox24' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox24', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox24', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox25' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox25', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox25', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox26' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox26', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox26', '' );
		}	
		if( isset( $_POST[ 'sr_designbrief_checkbox27' ] ) ) {
			update_post_meta( $post_id, '_sr_designbrief_checkbox27', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_designbrief_checkbox27', '' );
		}	
		if( isset( $_POST[ 'sr_design_primarycolor' ] ) ) {
			update_post_meta( $post_id, 'sr_design_primarycolor', $_POST[ 'sr_design_primarycolor' ] );
		}
		if( isset( $_POST[ 'sr_design_secondarycolor' ] ) ) {
			update_post_meta( $post_id, 'sr_design_secondarycolor', $_POST[ 'sr_design_secondarycolor' ] );
		}		
		if ( isset ( $_POST[ 'afb_sr_logo_image'] ) ) {
			update_post_meta( $post_id, 'afb_sr_logo_image', $_POST[ 'afb_sr_logo_image'] );
		}	

		/*
		 * Save values - function afb_sr_meta_box_goals	
		 */	
		if( isset( $_POST[ 'sr_goals_checkbox1' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox1', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox1', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox2' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox2', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox2', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox3' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox3', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox3', '' );
		}
		if( isset( $_POST[ 'sr_goals_checkbox4' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox4', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox4', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox5' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox5', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox5', '' );
		}	
		if ( isset ( $_POST[ 'sr_goals_textarea1'] ) ) {
			update_post_meta( $post_id, '_sr_goals_textarea1', $_POST[ 'sr_goals_textarea1'] );
		}		
		if( isset( $_POST[ 'sr_goals_checkbox6' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox6', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox6', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox7' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox7', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox7', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox8' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox8', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox8', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox9' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox9', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox9', '' );
		}
		if( isset( $_POST[ 'sr_goals_checkbox10' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox10', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox10', '' );
		}
		if( isset( $_POST[ 'sr_goals_checkbox11' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox11', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox11', '' );
		}
		if( isset( $_POST[ 'sr_goals_checkbox12' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox12', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox12', '' );
		}
		if( isset( $_POST[ 'sr_goals_checkbox13' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox13', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox13', '' );
		}	
		if( isset( $_POST[ 'sr_goals_checkbox14' ] ) ) {
			update_post_meta( $post_id, '_sr_goals_checkbox14', 'yes' );
		} else {
			update_post_meta( $post_id, '_sr_goals_checkbox14', '' );
		}		
		if ( isset ( $_POST[ 'sr_goals_textarea2'] ) ) {
			update_post_meta( $post_id, '_sr_goals_textarea2', $_POST[ 'sr_goals_textarea2'] );
		}		

		/*
		 * Save values - function afb_sr_meta_box_contact	
		 */			
		$old = get_post_meta($post_id, 'repeatable_fields', true);
		$new = array();
		$dates = $_POST['date'];
		$notes = $_POST['note'];
		$count = count( $dates );
		for ( $i = 0; $i < $count; $i++ ) {
			if ( $dates[$i] != '' ) :
				$new[$i]['date'] = stripslashes( strip_tags( $dates[$i] ) );
			endif;
			if ( $notes[$i] != '' ) :
				$new[$i]['note'] = stripslashes( strip_tags( $notes[$i] ) );
			endif;
		}
		if ( !empty( $new ) && $new != $old )
			update_post_meta( $post_id, 'repeatable_fields', $new );
		elseif ( empty($new) && $old )
			delete_post_meta( $post_id, 'repeatable_fields', $old );
			
		/*
		 * Save values - function afb_sr_meta_box_project	
		 */			
		if( isset( $_POST['sr_project_start_date'] ) ) {
			update_post_meta( $post_id, '_sr_project_start_date', esc_attr( $_POST['sr_project_start_date'] ) );	
		}
		if( isset( $_POST['sr_project_projend_date'] ) ) {
			update_post_meta( $post_id, '_sr_project_projend_date', esc_attr( $_POST['sr_project_projend_date'] ) );	
		}
		if( isset( $_POST['sr_project_end_date'] ) ) {
			update_post_meta( $post_id, '_sr_project_end_date', esc_attr( $_POST['sr_project_end_date'] ) );	
		}		
		if( isset( $_POST['sr_projektstatus_select'] ) ) {
			update_post_meta( $post_id, 'sr_projektstatus_select', esc_attr( $_POST['sr_projektstatus_select'] ) );	
		}	
		if( isset( $_POST['sr_projektfort_select'] ) ) {
			update_post_meta( $post_id, 'sr_projektfort_select', esc_attr( $_POST['sr_projektfort_select'] ) );	
		}
		if( isset( $_POST['sr_projektprio_select'] ) ) {
			update_post_meta( $post_id, 'sr_projektprio_select', esc_attr( $_POST['sr_projektprio_select'] ) );	
		}		
	}
	
	public function afb_sr_admin_styles() {
		global $typenow;
		if( $typenow == 'seocoach' ) {
			wp_enqueue_style( 'sr_meta_box_styles', plugin_dir_url( __FILE__ ) . 'afb-swissredesign-seocoach.css' );
			wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
			wp_enqueue_style( 'jquery-ui-timepicker-addon', plugin_dir_url( __FILE__ ) . '/js/jquery-ui-timepicker-addon.css' );
		}
	}
	
	/*
	* @param string $name Name of option or name of post custom field.
	* @param string $value Optional Attachment ID
	* @return string HTML of the Upload Button
	*/
	public function afb_sr_logo_uploader( $name, $value = '') {
		$image = ' button">Upload image';
		$image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
		$display = 'none'; // display state ot the "Remove image" button
 
		if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
 
			// $image_attributes[0] - image URL
			// $image_attributes[1] - image width
			// $image_attributes[2] - image height
			
			$image = '"><img src="' . $image_attributes[0] . '" style="max-width:100%;height:auto;" />';
			$display = 'inline-block';
		} 
		return '
		<div>
		<div class="swiss_redesign_label">' . __( 'Kundenlogo (falls vorhanden)', 'afb-swissredesign-leads' ) . '</div>
			<a href="#" class="misha_upload_image_button' . $image . '</a>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<a href="#" class="misha_remove_image_button button" style="display:inline-block;display:' . $display . '">Remove image</a>
		</div>';
	}
}

/**
 * Finally, instantiate the class
 */

new AFBSwissRedesignSeoCoach();