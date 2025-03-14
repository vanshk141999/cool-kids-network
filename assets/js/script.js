document.addEventListener( 'DOMContentLoaded', function () {
	// Helper function to show error messages.
	const showError = ( formId, message ) => {
		const form = document.getElementById( formId );
		let errorDiv = form.querySelector( '.error-message' );
		if ( ! errorDiv ) {
			errorDiv = document.createElement( 'div' );
			errorDiv.className = 'error-message';
			form.appendChild( errorDiv );
		}
		errorDiv.textContent = message;
		errorDiv.style.color = 'red';
		errorDiv.style.marginTop = '10px';
	};

	// Helper function to clear error messages.
	const clearError = ( formId ) => {
		const errorDiv = document
			.getElementById( formId )
			.querySelector( '.error-message' );
		if ( errorDiv ) {
			errorDiv.textContent = '';
		}
	};

	// Helper function to set loading state.
	const setLoading = ( element, isLoading ) => {
		if ( isLoading ) {
			element.disabled = true;
			element.style.opacity = '0.7';
			element.textContent = 'Processing...';
		} else {
			element.disabled = false;
			element.style.opacity = '1';
			element.textContent =
        element.getAttribute( 'data-original-text' ) || element.textContent;
		}
	};

	// Signup Form Submission.
	const signupForm = document.getElementById( 'ckn-signup-form' );
	if ( signupForm ) {
		const submitButton = signupForm.querySelector( 'button[type="submit"]' );
		if ( submitButton ) {
			submitButton.setAttribute( 'data-original-text', submitButton.textContent );
		}

		signupForm.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			clearError( 'ckn-signup-form' );

			const email = document.getElementById( 'ckn-signup-email' ).value.trim();
			if ( ! email || ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( email ) ) {
				showError( 'ckn-signup-form', 'Please enter a valid email address' );
				return;
			}

			setLoading( submitButton, true );

			fetch( cknAjax.ajaxurl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: `action=ckn_signup&security=${ encodeURIComponent(
					cknAjax.security,
				) }&email=${ encodeURIComponent( email ) }`,
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( data.success ) {
						const dashboardUrl = signupForm.getAttribute( 'data-dashboard-url' );
						if ( dashboardUrl ) {
							window.location.href = dashboardUrl;
						} else {
							window.location.reload();
						}
					} else {
						showError(
							'ckn-signup-form',
							data.message || 'Signup failed. Please try again.',
						);
					}
				} )
				.catch( ( error ) => {
					showError( 'ckn-signup-form', 'An error occurred. Please try again.' );
					console.error( 'Signup error:', error );
				} )
				.finally( () => {
					setLoading( submitButton, false );
				} );
		} );
	}

	// Login Form Submission.
	const loginForm = document.getElementById( 'ckn-login-form' );
	if ( loginForm ) {
		const submitButton = loginForm.querySelector( 'button[type="submit"]' );
		if ( submitButton ) {
			submitButton.setAttribute( 'data-original-text', submitButton.textContent );
		}

		loginForm.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			clearError( 'ckn-login-form' );

			const email = document.getElementById( 'ckn-login-email' ).value.trim();
			if ( ! email || ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( email ) ) {
				showError( 'ckn-login-form', 'Please enter a valid email address' );
				return;
			}

			setLoading( submitButton, true );

			fetch( cknAjax.ajaxurl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: `action=ckn_login&security=${ encodeURIComponent(
					cknAjax.security,
				) }&email=${ encodeURIComponent( email ) }`,
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( data.success ) {
						// reload the page to show the logged in state.
						// get data-dashboard-url from form and redirect to that URL.
						const dashboardUrl = loginForm.getAttribute( 'data-dashboard-url' );
						if ( dashboardUrl ) {
							window.location.href = dashboardUrl;
						} else {
							window.location.reload();
						}
					} else {
						showError(
							'ckn-login-form',
							data.message || 'Login failed. Please try again.',
						);
					}
				} )
				.catch( ( error ) => {
					showError( 'ckn-login-form', 'An error occurred. Please try again.' );
					console.error( 'Login error:', error );
				} )
				.finally( () => {
					setLoading( submitButton, false );
				} );
		} );
	}

	// Logout Button Click.
	const logoutButton = document.getElementById( 'ckn-logout' );
	if ( logoutButton ) {
		logoutButton.setAttribute( 'data-original-text', logoutButton.textContent );

		logoutButton.addEventListener( 'click', function () {
			setLoading( logoutButton, true );

			fetch( cknAjax.ajaxurl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: `action=ckn_logout&security=${ encodeURIComponent(
					cknAjax.security,
				) }`,
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( data.success ) {
						window.location.href = data.redirect || '/';
					} else {
						console.error( 'Logout failed:', data.message );
					}
				} )
				.catch( ( error ) => {
					console.error( 'Logout error:', error );
				} )
				.finally( () => {
					setLoading( logoutButton, false );
				} );
		} );
	}
} );
