<?php

/**
 *
 * Creates post types:
 * - wpet_notify_attendees
 *
 * @since 2.0
 */
class WPET_Notifications extends WPET_Module {

	/**
	 * @since 2.0
	 */
	public function __construct() {
	    $this->mPostType = 'wpet_notifications';

		add_filter( 'wpet_admin_menu', array( $this, 'adminMenu' ), 26 );

		add_action( 'init', array( $this, 'registerPostType' ) );

		//do this after post type is set
		parent::__construct();
	}

	/**
	 * Displays page specific contextual help through the contextual help API
	 *
	 * @see http://codex.wordpress.org/Function_Reference/add_help_tab
	 * @since 2.0
	 */
	public function contextHelp( $screen ) {
		if ( isset( $_GET['action'] ) ) {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen allows you to send a notification to some or all of your attendees.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'options-explained',
				'title'	=> __( 'Options Explained' ),
				'content'	=> '<p>' . __( 'Here\'s an explanation of the options found on this page:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>To</strong> lets you decide which group of attendees will receive the notification.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Subject</strong> is what will be sent as the subject of the email.', 'wpet' ) .'</li>'.
						'<li>'. __( '<strong>Email Body</strong> is the content of the email message that will be sent.', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		} else {
			$screen->add_help_tab(
				array(
				'id'	=> 'overview',
				'title'	=> __( 'Overview' ),
				'content'	=> '<p>' . __( 'This screen provides access to all of your previously sent notifications.', 'wpet' ) . '</p>',
				)
			);
			$screen->add_help_tab(
				array(
				'id'	=> 'available-actions',
				'title'	=> __( 'Available Actions' ),
				'content'	=> '<p>' . __( 'Hovering over a row in the notification list will display action links that allow you to manage each of the previous notifications. You can perform the following actions:', 'wpet' ) . '</p>'.
					'<ul>'.
						'<li>'. __( '<strong>View</strong> allows you see the notification that was sent along with who it was sent to.', 'wpet' ) .'</li>'.
					'</ul>',
				)
			);
		}
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'Need help:' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://support.9seeds.com/" target="_blank">Support Forums</a>' ) . '</p>' .
			'<p>' . __( '<a href="https://github.com/9seeds/wp-event-ticketing/wiki/_pages" target="_blank">Developer Docs</a>' ) . '</p>'
		);
	}

	/**
	 * @since 2.0
	 */
	public function enqueueAdminScripts() {
		if ( isset( $_GET['action'] ) ) {
			wp_enqueue_style( 'editor' );
		}
	}

	/**
	 * Add Notify Attendees links to the Tickets menu
	 *
	 * @since 2.0
	 * @param type $menu
	 * @return array
	 */
	public function adminMenu( $menu ) {
		$menu[] = array( 'Notify Attendees', 'Notify Attendees', 'add_users', 'wpet_notifications', array( $this, 'renderAdminPage' ) );
		return $menu;
	}

	/**
	 * Renders the attendee notify page in wp-admin
	 *
	 * @todo Ensure email recipients are not duplicated in $attendees
	 * @todo Pull in proper attendees. Requires payment gateway to be operational
	 * @since 2.0
	 */
	public function renderAdminPage() {

		//might have to override maybeSubmit() to do this
		//$this->sendNotification( $to, $_POST['subject'], $_POST['email-body'] )

		if ( isset( $_GET['action'] ) ) {
			if ( ! empty( $_REQUEST['post'] ) ) {
				$this->render_data['notification'] = $this->findByID( $_REQUEST['post'] );
			}

			if( isset( $_GET['notify'] ) ) {

			    $organizer_name = WPET::getInstance()->settings->organizer_name;
			    $organizer_email = WPET::getInstance()->settings->organizer_email;

			    $headers[] = "From: $organizer_email <$organizer_email>";

			    /*
			     * Determine which attendees to send to. Start with the
			     * largest possible pool and work up
			     *
			     *
			     */
			    $attendees = array();
			    switch( $_POST['options']['to']) {
				case 'all-attendees':
				    $attendees = WPET::getInstance()->attendees->findAllByEvent( WPET::getInstance()->events->getWorkingEvent() );
				    break;
				case 'have-info':
				    $attendees = WPET::getInstance()->attendees->findWithInfoByEvent( WPET::getInstance()->events->getWorkingEvent() );
				    break;
				case 'no-info':
				    $attendees = WPET::getInstance()->attendees->findWithoutInfoByEvent( WPET::getInstance()->events->getWorkingEvent() );
				    break;
			    }

			    foreach( $attendees AS $a ) {
				$headers[] = 'Bcc: ' . $a->wpet_email;
			    }

			   $mail =  wp_mail( $organizer_email, $_POST['options']['subject'], $_POST['options']['email_body'], $headers );
			}
		    WPET::getInstance()->display( 'notifications-add.php', $this->render_data );
		} else {
		    WPET::getInstance()->display( 'notifications.php', $this->render_data );
		}
	}

	/**
	 * Prepare the page submit data for save
	 *
	 * @since 2.0
	 */
	public function getPostData() {
		$_POST['options']['sent_date'] = date( 'Y-m-d H:m:s', time() );
		$data = array(
			'post_type' => $post_type,
			'post_title' => $_POST['options']['subject'],
			'meta' => $_POST['options'],
		);
		return $data;
	}

	/**
	 * Retrieves all the notifications from the db
	 * @return array
	 */
	public function findAllNotificationsByEvent( ) {
	    $args = array(
		'post_type' => 'wpet_notifications',
		'showposts' => '-1',
		'posts_per_page' => '-1'
	    );

	    return get_posts( $args );
	}

	/**
	 * Add post type for object
	 *
	 * @since 2.0
	 */
	public function registerPostType() {
	    $labels = array(
		'name' => 'Notifications',
		'singular_name' => 'Notification',
		'add_new' => 'Create Notification',
		'add_new_item' => 'New Notification',
		'edit_item' => 'Edit Notification',
		'new_item' => 'New Notification',
		'view_item' => 'View Notification',
		'search_items' => 'Search Notifications',
		'not_found' => 'No Notifications found',
		'not_found_in_trash' => 'No Notifications found in trash'
	    );

	    $args = array(
		'public' => false,
		'supports' => array( 'page-attributes' ),
		'labels' => $labels,
		'hierarchical' => false,
		'has_archive' => false,
		'query_var' => 'notification',
		//'rewrite' => array( 'slug' => 'notification', 'with_front' => false ),
		//'menu_icon' => WPET_PLUGIN_URL . 'images/icons/reviews.png',
		//'register_meta_box_cb' => array( &$this, 'registerMetaBox' ),
		'show_ui' => false
	    );

	    register_post_type( $this->mPostType, $args );
	}

}