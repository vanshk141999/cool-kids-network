document.addEventListener( 'DOMContentLoaded', function () {
	// Helper function to show error messages.
	const showError = ( formId, message ) => {
		// Get the form element by ID.
		const form = document.getElementById( formId );
		let errorDiv = form.querySelector( '.error-message' );

		// If no error div exists, create a new one.
		if ( ! errorDiv ) {
			errorDiv = document.createElement( 'div' );
			errorDiv.className = 'error-message';
			form.appendChild( errorDiv );
		}

		// Set the error message, style it, and display it.
		errorDiv.textContent = message;
		errorDiv.style.color = 'red';
		errorDiv.style.marginTop = '10px';
	};

	// Helper function to clear error messages.
	const clearError = ( formId ) => {
		// Get the error div and clear its content.
		const errorDiv = document
			.getElementById( formId )
			.querySelector( '.error-message' );
		if ( errorDiv ) {
			errorDiv.textContent = '';
		}
	};

	// Helper function to toggle loading state on buttons.
	const setLoading = ( element, isLoading ) => {
		// If loading, disable the button and change its appearance.
		if ( isLoading ) {
			element.disabled = true;
			element.style.opacity = '0.7';
			element.textContent = 'Processing...';
		} else {
			// If not loading, enable the button and revert its appearance.
			element.disabled = false;
			element.style.opacity = '1';
			element.textContent =
        element.getAttribute( 'data-original-text' ) || element.textContent;
		}
	};

	// General function to handle form submission (signup, login).
	const handleFormSubmission = (
		formId,
		action,
		emailId,
		buttonSelector,
		successRedirectKey
	) => {
		// Get the form and submit button elements.
		const form = document.getElementById( formId );
		const submitButton = form.querySelector( buttonSelector );

		// Save the original text of the button (so it can be restored later).
		if ( submitButton ) {
			submitButton.setAttribute( 'data-original-text', submitButton.textContent );
		}

		// Add an event listener for form submission.
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault(); // Prevent default form submission behavior.
			clearError( formId ); // Clear any existing error messages.

			// Get the email input value and trim spaces.
			const email = document.getElementById( emailId ).value.trim();

			// Check if the email is valid.
			if ( ! email || ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( email ) ) {
				// If invalid, show an error message and return.
				showError( formId, 'Please enter a valid email address' );
				return;
			}

			// Set the button to the loading state.
			setLoading( submitButton, true );

			// Make an AJAX request to the server to process the form.
			fetch( cknAjax.ajaxurl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `action=${ action }&security=${ encodeURIComponent(
					cknAjax.security
				) }&email=${ encodeURIComponent( email ) }`,
			} )
				.then( ( response ) => response.json() ) // Parse the JSON response.
				.then( ( data ) => {
					// If the action was successful, handle the redirect.
					if ( data.success ) {
						const redirectUrl = form.getAttribute( successRedirectKey );
						if ( redirectUrl ) {
							// Redirect to the specified URL if provided.
							window.location.href = redirectUrl;
						} else {
							// Otherwise, reload the page to reflect the new state.
							window.location.reload();
						}
					} else {
						// If the action failed, show an error message.
						showError(
							formId,
							data.message || 'Action failed. Please try again.'
						);
					}
				} )
				.catch( ( error ) => {
					// If there was an error with the AJAX request, show an error message.
					showError( formId, 'An error occurred. Please try again.' );
					console.error( `${ action } error:`, error );
				} )
				.finally( () => {
					// Always revert the loading state when the request is complete.
					setLoading( submitButton, false );
				} );
		} );
	};

	// Handle Signup Form Submission
	if ( document.getElementById( 'ckn-signup-form' ) ) {
		// Use the generalized function for signup form.
		handleFormSubmission(
			'ckn-signup-form',
			'ckn_signup',
			'ckn-signup-email',
			'button[type="submit"]',
			'data-dashboard-url'
		);
	}

	// Handle Login Form Submission
	if ( document.getElementById( 'ckn-login-form' ) ) {
		// Use the generalized function for login form.
		handleFormSubmission(
			'ckn-login-form',
			'ckn_login',
			'ckn-login-email',
			'button[type="submit"]',
			'data-dashboard-url'
		);
	}

	// Logout Button Click.
	const logoutButton = document.getElementById( 'ckn-logout' );
	if ( logoutButton ) {
		// Save the original text of the logout button.
		logoutButton.setAttribute( 'data-original-text', logoutButton.textContent );

		// Add an event listener for the logout button click.
		logoutButton.addEventListener( 'click', function () {
			// Set the button to the loading state.
			setLoading( logoutButton, true );

			// Make an AJAX request to log the user out.
			fetch( cknAjax.ajaxurl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `action=ckn_logout&security=${ encodeURIComponent(
					cknAjax.security
				) }`,
			} )
				.then( ( response ) => response.json() ) // Parse the JSON response.
				.then( ( data ) => {
					// If the logout was successful, redirect to the specified URL or the homepage.
					if ( data.success ) {
						window.location.href = data.redirect || '/';
					} else {
						// If logout failed, log the error message.
						console.error( 'Logout failed:', data.message );
					}
				} )
				.catch( ( error ) => {
					// If there was an error with the AJAX request, log the error.
					console.error( 'Logout error:', error );
				} )
				.finally( () => {
					// Always revert the loading state when the request is complete.
					setLoading( logoutButton, false );
				} );
		} );
	}
} );
