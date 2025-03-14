<?php
/**
 * Frontend Class for rendering shortcodes and enqueueing scripts and styles
 *
 * @package WAC\Includes
 * @since 1.0.0
 */

namespace CKN\Includes;

use CKN\Includes\Traits\Get_Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Class for rendering shortcodes and enqueueing scripts and styles
 *
 * @package WAC\Includes
 * @since 1.0.0
 */
class Frontend {
	use Get_Instance;

	/**
	 * Frontend constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( 'ckn_home', array( $this, 'home_shortcode' ) );
		add_shortcode( 'ckn_login', array( $this, 'login_shortcode' ) );
		add_shortcode( 'ckn_signup', array( $this, 'signup_shortcode' ) );
		add_shortcode( 'ckn_dashboard', array( $this, 'dashboard_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Render the home shortcode
	 *
	 * @access public
	 * @param array $attrs shortcode attributes.
	 * @since 1.0.0
	 * @return string
	 */
	public function home_shortcode( $attrs ) {
		// get the required attributes.
		$login_url     = isset( $attrs['login_url'] ) ? esc_url( $attrs['login_url'] ) : '';
		$signup_url    = isset( $attrs['signup_url'] ) ? esc_url( $attrs['signup_url'] ) : '';
		$dashboard_url = isset( $attrs['dashboard_url'] ) ? esc_url( $attrs['dashboard_url'] ) : '';

		ob_start();
		?>
		<div class="ckn-welcome">
			<div class="ckn-left">
				<img src="<?php echo esc_attr( CKN_ASSETS ) . '/images/hero-image.jpeg'; ?>" alt="<?php esc_html_e( 'Cool Kids', 'ckn' ); ?>">
			</div>
			<div class="ckn-right">
				<h1><?php esc_html_e( 'Welcome to Cool Kids Network', 'ckn' ); ?></h1>
				<p><?php esc_html_e( 'Join the community of cool kids and have fun!', 'ckn' ); ?></p>
				<?php if ( ! is_user_logged_in() ) { ?>
					<div>
						<a href="<?php echo esc_url( $login_url ); ?>" class="ckn-button login"><?php esc_html_e( 'Login', 'ckn' ); ?></a>
						<a href="<?php echo esc_url( $signup_url ); ?>" class="ckn-button signup"><?php esc_html_e( 'Sign up', 'ckn' ); ?></a>
					</div>
				<?php } else { ?>
					<div>
						<a href="<?php echo esc_url( $dashboard_url ); ?>" class="ckn-button login"><?php esc_html_e( 'Go to Dashboard', 'ckn' ); ?></a>
						<a id="ckn-logout" class="ckn-button logout">
							<?php esc_html_e( 'Logout', 'ckn' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
								<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
								<polyline points="16 17 21 12 16 7"/>
								<line x1="21" x2="9" y1="12" y2="12"/>
							</svg>
						</a>
					</div>
				<?php } ?>
		 
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the login or signup form
	 *
	 * @access private
	 * @param string $type login or signup.
	 * @param array  $attrs shortcode attributes.
	 * @since 1.0.0
	 * @return string
	 */
	private function render_login_signup_form( $type, $attrs ) {
		$dashboard_url = isset( $attrs['dashboard_url'] ) ? esc_url( $attrs['dashboard_url'] ) : '';
		ob_start();

		// Check if the user is already logged in.
		if ( ! is_admin() && is_user_logged_in() ) {
			?>
			<div class="ckn-logged-in">
				<p><?php esc_html_e( 'You are already logged in.', 'ckn' ); ?></p>
				<div class="buttons">
					<a href="<?php echo esc_url( $dashboard_url ); ?>" class="ckn-button login"><?php esc_html_e( 'Go to Dashboard', 'ckn' ); ?></a>
					<button id="ckn-logout" class="ckn-button logout">
						<?php esc_html_e( 'Logout', 'ckn' ); ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
							<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
							<polyline points="16 17 21 12 16 7"/>
							<line x1="21" x2="9" y1="12" y2="12"/>
						</svg>
					</button>
				</div>
			</div>
			<?php
			return ob_get_clean(); }

		// Form for login or signup.
		?>
		<div class="ckn-container">
			<form id="ckn-<?php echo esc_attr( $type ); ?>-form" data-dashboard-url="<?php echo esc_url( $dashboard_url ); ?>" class="ckn-form">
				<h2><?php echo esc_attr( ucfirst( $type ) ); ?></h2>
				<input type="email" id="ckn-<?php echo esc_attr( $type ); ?>-email" placeholder="<?php esc_html_e( 'Enter email', 'ckn' ); ?>" required>
				<button type="submit"><?php echo 'login' === $type ? esc_attr__( 'Login', 'ckn' ) : esc_attr__( 'Confirm', 'ckn' ); ?></button>
			</form>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render the login shortcode
	 *
	 * @access public
	 * @param array $attrs shortcode attributes.
	 * @since 1.0.0
	 * @return string
	 */
	public function login_shortcode( $attrs ) {
		return $this->render_login_signup_form( 'login', $attrs );
	}

	/**
	 * Render the signup shortcode
	 *
	 * @access public
	 * @param array $attrs shortcode attributes.
	 * @since 1.0.0
	 * @return string
	 */
	public function signup_shortcode( $attrs ) {
		return $this->render_login_signup_form( 'signup', $attrs );
	}

	/**
	 * Render the dashboard shortcode
	 *
	 * @access public
	 * @param array $atts shortcode attributes.
	 * @since 1.0.0
	 * @return string
	 */
	public function dashboard_shortcode( $atts ) {
		ob_start();
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$character = array(
				'first_name' => get_user_meta( $current_user->ID, 'first_name', true ),
				'last_name'  => get_user_meta( $current_user->ID, 'last_name', true ),
				'country'    => get_user_meta( $current_user->ID, 'country', true ),
				'email'      => $current_user->user_email,
				'role'       => implode( ', ', $current_user->roles ),
			);
			?>
	
			<div class="ckn-dashboard">
				<div class="ckn-dashboard-ctn">
					<!-- User Profile Section -->
					<div class="ckn-gravatar ckn-card">
						<?php echo get_avatar( $current_user->ID, 100 ); ?>
						<h3><?php echo esc_html( "{$character['first_name']} {$character['last_name']}" ); ?></h3>
						<button id="ckn-logout" class="ckn-button logout">
							<?php esc_html_e( 'Logout', 'ckn' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
								<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
								<polyline points="16 17 21 12 16 7"/>
								<line x1="21" x2="9" y1="12" y2="12"/>
							</svg>
						</button>
					</div>
	
					<!-- Profile Card -->
					<div class="ckn-profile-card ckn-card">
						<h3><?php esc_html_e( 'Profile Information', 'ckn' ); ?></h3>
						<p><strong><?php esc_html_e( 'Name:', 'ckn' ); ?></strong> <span><?php echo esc_html( "{$character['first_name']} {$character['last_name']}" ); ?></span></p>
						<p><strong><?php esc_html_e( 'Country:', 'ckn' ); ?></strong> <span><?php echo esc_html( $character['country'] ); ?></span></p>
						<p><strong><?php esc_html_e( 'Email:', 'ckn' ); ?></strong> <span><?php echo esc_html( $character['email'] ); ?></span></p>
						<p class="ckn-badge">#<?php echo esc_html( $character['role'] ); ?></p>
					</div>
				</div>
	
				<?php
				// Pagination Setup.
				// phpcs:ignore
				$paged = max(1, intval($_GET['paged']  ?? 1)); // WordPress.Security.NonceVerification.Recommended - Nonce verification is not required here.
				// phpcs:ignoreEnd
				$users_per_page = 10;
				$total_users = count(get_users());
				$total_pages = ceil($total_users / $users_per_page);
				$users = get_users(array( 'number' => $users_per_page, 'offset' => ($paged - 1) * $users_per_page ));

				// exclude current user from the list.
				$users = array_filter($users, function ($user) use ($current_user) {
					return $user->ID !== $current_user->ID;
				});

				if (empty($users)) {
					echo '<p>' . esc_html__('No other cool kids found.', 'ckn') . '</p>';
					return ob_get_clean();
				}
	
				if (array_intersect(array( 'cooler_kid', 'coolest_kid', 'administrator' ), $current_user->roles)) : ?>
					<div class="ckn-users-list">
						<h3><?php esc_html_e('Other Cool Kids', 'ckn'); ?></h3>
						<table class="ckn-users-table">
							<thead>
								<tr>
									<th><?php esc_html_e('Name', 'ckn'); ?></th>
									<th><?php esc_html_e('Country', 'ckn'); ?></th>
									<?php if (array_intersect(array( 'coolest_kid', 'administrator' ), $current_user->roles)) : ?>
										<th><?php esc_html_e('Email', 'ckn'); ?></th>
										<th><?php esc_html_e('Role', 'ckn'); ?></th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $user) : ?>
									<tr>
										<td><strong><?php echo esc_html( get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true) ); ?></strong></td>
										<td><?php echo esc_html( get_user_meta($user->ID, 'country', true) ); ?></td>
										<?php if (array_intersect(array( 'coolest_kid', 'administrator' ), $current_user->roles)) : ?>
											<td><?php echo esc_html($user->user_email); ?></td>
											<td><span class="ckn-badge"><?php echo esc_html( implode(', ', $user->roles) ); ?></span></td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
	
						<!-- Pagination -->
						<div class="ckn-pagination">
							<?php for ($i = 1; $i <= $total_pages; $i++) : ?>
								<a href="?paged=<?php echo esc_html( $i ); ?>" class="ckn-button <?php echo ($i === $paged) ? 'ckn-active' : ''; ?>">
									<?php echo esc_html( $i ); ?>
								</a>
							<?php endfor; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php
			return ob_get_clean();
		}
	
		// Display login form if user is not logged in.
		echo wp_kses_post( $this->render_login_signup_form( 'login', $atts ) );
		return ob_get_clean();
	}
	

	/**
	 * Enqueue scripts and styles
	 *
	 * @access public
	 * @hooked wp_enqueue_scripts
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'ckn-style', CKN_ASSETS . '/css/style.css', array(), CKN_VERSION );
		wp_enqueue_script( 'ckn-script', CKN_ASSETS . '/js/script.js', array(), CKN_VERSION, true );
		// Localize the script with AJAX URL and nonce.
		wp_localize_script(
			'ckn-script',
			'cknAjax',
			array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'ckn-ajax-nonce' ),
			)
		);
	}
}
