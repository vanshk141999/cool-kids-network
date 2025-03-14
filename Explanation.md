# Cool Kids Network - WordPress Plugin

## Problem Statement

The Cool Kids Network is a WordPress-based user management system that allows users to register, get automatically assigned characters, and interact based on role-based permissions. The system implements a hierarchy of roles (Cool Kid, Cooler Kid, Coolest Kid) with increasing levels of access to other users' information.

## Technical Specification

### Architecture Overview

The plugin is built using modern Object-Oriented Programming principles in PHP, organized into several key components:

1. **Frontend Class** (`includes/frontend.php`)

   - Handles all frontend rendering through WordPress shortcodes
   - Manages user interface components for login, signup, and dashboard
   - Implements role-based access control for viewing user data

2. **User Management Class** (`includes/user-management.php`)

   - Handles user registration and login logic
   - Integrates with randomuser.me API for generating fake identities
   - Manages user metadata and role assignments

3. **API Class** (`includes/api.php`)

   - Provides REST API endpoints for the system
   - Handles authentication and role management

4. **Admin Class** (`includes/admin.php`)
   - Manages administrative interfaces and settings

### Design Patterns Used

- **Singleton Pattern**: Implemented through the `Get_Instance` trait to ensure single instance of core classes
- **Namespace Usage**: Proper PHP namespacing (`CKN\Includes`) for better code organization
- **WordPress Integration**: Leverages WordPress hooks and filters for seamless integration

## Implementation Status

### Completed Features

#### User Story 1: Anonymous User Signup ✅

- Homepage with signup button for anonymous users
- Signup form with email field
- Integration with randomuser.me API for character generation
- Automatic role assignment ("Cool Kid")

#### User Story 2: User Login and Profile View ✅

- Login functionality without password (as per requirements)
- Character data display (name, country, email, role)
- Profile dashboard with user information

#### User Story 3: Cooler Kid Access ✅

- Role-based access control implemented
- Cooler Kids can view other users' names and countries
- Data visibility restrictions properly enforced

#### User Story 4: Coolest Kid Access ✅

- Extended data access for Coolest Kid role
- Complete user information visibility (including email and role)
- Proper permission checking

### Pending Features

#### User Story 5: Role Management API 🚧

- Protected endpoint for role management needs to be implemented
- Authentication system for API requests pending
- User identification by email or name combination not implemented

### Bonus Points Status

#### Implemented ✅

- Modern OOP architecture
- Frontend user interface with responsive design
- Basic error handling and logging

## Technical Decisions

### WordPress Integration

Chose to implement as a WordPress plugin for:

- Leveraging existing user management infrastructure
- Utilizing WordPress's security features
- Easy integration with existing WordPress sites

### Frontend Implementation

Used WordPress shortcodes for:

- Flexible placement of components in any page/post
- Clean separation of concerns
- Easy maintenance and updates

### Security Considerations

- Proper escaping of output using WordPress functions
- AJAX nonce implementation for form submissions
- Role-based access control implementation

## Monitoring and Resilience

### Error Handling

- WordPress error logging integration
- User-friendly error messages
- AJAX response handling

### Database Structure

Utilizes WordPress user meta tables for:

- Storing additional user data (country, character details)
- Maintaining role information
- Easy querying and updates

## How to Use

### Installation

1. Download the plugin ZIP file
2. Go to WordPress Admin > Plugins > Add New > Upload Plugin
3. Upload the ZIP file and click "Install Now"
4. Activate the plugin

### Configuration

1. Navigate to WordPress Admin > Settings > Cool Kids Network
2. Configure the following settings:
   - Default role for new registrations (default: "Cool Kid")
   - Enable/disable automatic character generation
   - Customize visibility settings for different roles

### Using Shortcodes

Add these shortcodes to any page or post:

1. Registration Form:

   ```
   [ckn_register]
   ```

2. Login Form:

   ```
   [ckn_login]
   ```

3. User Dashboard:
   ```
   [ckn_dashboard]
   ```

### User Guide

#### For New Users

1. Visit any page with the registration form
2. Enter your email address
3. Submit the form to receive your randomly generated character
4. Use your email to log in (no password required)

#### For Cool Kids

- View your own profile information
- See your assigned character details
- Access your current role status

#### For Cooler Kids

- All Cool Kid permissions
- View other users' names and countries
- Browse member directory

#### For Coolest Kids

- All Cooler Kid permissions
- View complete user information
- Access email addresses and roles of other users

#### For Administrators

1. Access WordPress Admin > Users
2. Manage user roles and permissions
3. View and modify user character assignments
4. Monitor user registrations and activity

### Troubleshooting

1. If registration fails:

   - Check your internet connection (required for character generation)
   - Verify your email hasn't been used before
   - Contact administrator if issues persist

2. If login issues occur:
   - Ensure using the correct email address
   - Clear browser cache and cookies
   - Request administrator assistance if needed

## Conclusion

The Cool Kids Network plugin successfully implements most of the core requirements, providing a solid foundation for user management and role-based access control. The pending items primarily relate to the API endpoint implementation and the bonus features around testing and automation.
