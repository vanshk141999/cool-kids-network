# Cool Kids Network

The Cool Kids Network plugin brings a unique user management system to your WordPress site. It allows users to sign up and automatically receive a fun, randomly generated character with a name, country, and role.

Demo -

Download Plugin - https://github.com/vanshk141999/cool-kids-network/releases/tag/v1.0.0

## Features

- User registration with automatic character generation
- Role-based access control (Cool Kid, Cooler Kid, Coolest Kid)
- Random character assignment with unique traits:
  - Name
  - Country
  - Role
- Password-less login system
- User profile dashboard
- Administrative controls for character management
- Clean and modern UI
- Fully responsive design

## Requirements

- WordPress 6.7 or higher
- PHP 7.4 or higher

## Installation

1. Clone the repository
2. Run `composer i` to install the PHP packages
3. Run `vendor/bin/phpcs -ps . --standard=phpcs.xml` to check coding standards
4. Run `npm run lint-js` to check JavaScript code
5. Run `npm run lint-css` to check CSS code

## Usage

### For New Users

1. Visit the Homepage
2. Click on "Sign Up"
3. Enter your email address
4. Submit the form to receive your randomly generated character
5. Use your email to log in (no password required)

### For Administrators

Add these shortcodes to any page or post:

Please note that [site_url] should be replaced with your actual site URL.

1. First, add the following shortcode to render the Home Page:

   ```
   [ckn_home login_url=[site_url]/ckn-login signup_url=[site_url]/ckn-signup" dashboard_url=[site_url]/ckn-dashboard/]
   ```

2. Next, add the following shortcodes to render the Login and Signup forms. Please note that you need to add these shortcodes to separate pages:

   ```
   [ckn_login dashboard_url=[site_url]/ckn-dashboard/]
   [ckn_signup dashboard_url=[site_url]/ckn-dashboard/]
   ```

3. Finally, add the following shortcode to render the Dashboard page:
   ```
   [ckn_dashboard]
   ```
   Manage the user roles using API:
4. Access WordPress Admin > Cool Kids Network.

- Generate a new API key.
- POST the email id along with user_role that you want to assign to the user.
- Example:
  ```
  curl -X POST -H "Content-Type: application/json" -d '{"email": "example@example.com", "user_role": "coolest_kid"}' [site_url]/ckn-api/v1/assign-role
  ```

### Testing

1. Install PHPUnit
2. Run `sh bin/install-wp-tests.sh wp-test root '' localhost 6.7`
3. Run `vendor/bin/phpunit`

## Credits

This plugin integrates with:

- randomuser.me API - For generating random character data
- Lucide Icons - https://lucide.dev/icons/

## License

GPL v3.0 or later

## Changelog

### 1.0.0

- Initial release
- User registration with character generation
- Role-based access system
- Administrative controls API
- Responsive design implementation
