window.addEventListener( 'load', function() {
	var hamburgerButton = document.getElementById( 'hamburger-menu-toggler');

	hamburgerButton.addEventListener( 'click', function() {
		document.body.classList.toggle( 'hamburger-menu-active' );
	} );
} );