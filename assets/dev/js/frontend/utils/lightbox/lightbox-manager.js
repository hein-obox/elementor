export default class LightboxManager extends elementorModules.ViewModule {
	static getLightbox() {
		const lightboxPromise = new Promise( ( resolveLightbox ) => {
				import(
					/* webpackChunkName: 'lightbox' */
					`elementor-frontend/utils/lightbox/lightbox`
				).then( ( { default: LightboxModule } ) => resolveLightbox( new LightboxModule() ) );
			} ),
			dialogPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'dialog' ),
			shareLinkPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'share-link' ),
			swiperStylePromise = elementorFrontend.utils.assetsLoader.load( 'style', 'swiper' );

		return Promise.all( [ lightboxPromise, dialogPromise, shareLinkPromise, swiperStylePromise ] ).then( () => lightboxPromise );
	}

	getDefaultSettings() {
		return {
			selectors: {
				links: 'a, [data-elementor-lightbox]',
				lightBox: '[data-elementor-lightbox]',
			},
		};
	}

	getDefaultElements() {
		return {
			$links: jQuery( this.getSettings( 'selectors.links' ) ),
			$lightBoxes: jQuery( this.getSettings( 'selectors.lightbox' ) ),
		};
	}

	isLightboxLink( element ) {
		// Check for lowercase `a` to make sure it works also for links inside SVGs.
		if ( ( 'a' === element.tagName.toLowerCase() && ( element.hasAttribute( 'download' ) || ! /^[^?]+\.(png|jpe?g|gif|svg|webp)(\?.*)?$/i.test( element.href ) ) ) && ! element.dataset.elementorLightboxVideo ) {
			return false;
		}

		const generalOpenInLightbox = elementorFrontend.getKitSettings( 'global_image_lightbox' ),
			currentLinkOpenInLightbox = element.dataset.elementorOpenLightbox;

		return 'yes' === currentLinkOpenInLightbox || ( generalOpenInLightbox && 'no' !== currentLinkOpenInLightbox );
	}

	async onLinkClick( event ) {
		const element = event.currentTarget,
			$target = jQuery( event.target ),
			editMode = elementorFrontend.isEditMode(),
			isColorPickingMode = editMode && elementor.$previewContents.find( 'body' ).hasClass( 'elementor-editor__ui-state__color-picker' ),
			isClickInsideElementor = ! ! $target.closest( '.elementor-edit-area' ).length;

		if ( ! this.isLightboxLink( element ) ) {
			if ( editMode && isClickInsideElementor ) {
				event.preventDefault();
			}

			return;
		}

		event.preventDefault();

		if ( editMode && ! elementor.getPreferences( 'lightbox_in_editor' ) ) {
			return;
		}

		// Disable lightbox on color picking mode.
		if ( isColorPickingMode ) {
			return;
		}

		const lightbox = await LightboxManager.getLightbox();

		lightbox.createLightbox( element );
	}

	bindEvents() {
		elementorFrontend.elements.$document.on(
			'click',
			this.getSettings( 'selectors.links' ),
			( event ) => this.onLinkClick( event ),
		);
	}

	onInit( ...args ) {
		super.onInit( ...args );

		if ( elementorFrontend.isEditMode() ) {
			return;
		}

		this.maybeActivateLightbox();
		this.maybeLoadSlideShowStyles();
		this.maybeLoadSwiperJs();
	}

	maybeActivateLightbox() {
		// Detecting lightbox links on init will reduce the time of waiting to the lightbox to be display on slow connections.
		this.elements.$links.each( ( index, element ) => {
			if ( this.isLightboxLink( element ) ) {
				this.maybeInsertLightboxStyle();
				LightboxManager.getLightbox();

				// Breaking the iteration when the library loading has already been triggered.
				return false;
			}
		} );
	}

	maybeInsertLightboxStyle() {
		this.maybeLoadStyle( 'e-lightbox', 'css/conditionals/lightbox.min.css' );
	}

	maybeLoadSlideShowStyles() {
		if ( 0 === this.elements.$lightBoxes.length ) {
			return;
		}

		this.maybeLoadStyle( 'e-lightbox-slideshow', 'css/conditionals/lightbox-slideshow.min.css' );
		// To check:
		// Swiper version 5 and 8??
		// Swiper CSS file path??
		// Swiper JS file??
		// this.maybeLoadStyle( 'e-swiper', 'css/conditionals/swiper.min.css' );
	}

	maybeLoadStyle( styleSheetId, cssFilePath ) {
		const hasStylesheet = !! document.getElementById( styleSheetId );

		if ( hasStylesheet ) {
			return;
		}

		const linkElement = document.createElement( 'link' );

		linkElement.id = styleSheetId;
		linkElement.rel = 'stylesheet';
		linkElement.type = 'text/css';
		linkElement.href = `${ elementorFrontend.config.urls.assets }${ cssFilePath }?ver=${ elementorFrontend.config.version }`;

		document.head.appendChild( linkElement );
	}

	maybeLoadSwiperJs() {
		if ( 0 === this.elements.$lightBoxes.length ) {
			return;
		}
		//
		// const lightboxPromise = new Promise( ( resolveLightbox ) => {
		// 		import(
		// 			/* webpackChunkName: 'lightbox' */
		// 			`elementor-frontend/utils/lightbox/lightbox`
		// 			).then( ( { default: LightboxModule } ) => resolveLightbox( new LightboxModule() ) );
		// 	} ),
		// 	dialogPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'dialog' ),
		// 	shareLinkPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'share-link' );
		//
		// return Promise.all( [ lightboxPromise, dialogPromise, shareLinkPromise ] ).then( () => lightboxPromise );
	}
}
