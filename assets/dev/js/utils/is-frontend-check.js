export function isFrontend() {
	const isWpAdmin = window.location.pathname.indexOf( 'wp-admin' ) !== -1 ||
			document.body.classList.contains( 'wp-admin' ) ||
			document.body.classList.contains( 'admin-bar' ),
		isElementorEditor = document.body.classList.contains( 'elementor-editor-active' ) ||
			document.body.classList.contains( 'elementor-page' ); // Maybe check if this is the Elementor Iframe

	return ! isWpAdmin && ! isElementorEditor;
}
