export default class LightboxManager extends elementorModules.ViewModule {
	static getLightbox( hasSlideshow = false ) {
		const lightboxPromise = new Promise( ( resolveLightbox ) => {
				import(
					/* webpackChunkName: 'lightbox' */
					`elementor-frontend/utils/lightbox/lightbox`
				).then( ( { default: LightboxModule } ) => resolveLightbox( new LightboxModule() ) );
			} ),
			dialogPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'dialog' ),
			shareLinkPromise = elementorFrontend.utils.assetsLoader.load( 'script', 'share-link' ),
			swiperStylePromise = elementorFrontend.utils.assetsLoader.load( 'style', 'swiper' ),
			lightboxStylePromise = elementorFrontend.utils.assetsLoader.load( 'style', 'e-lightbox' );

		const lightboxSlideshowStylePromise = hasSlideshow
			? elementorFrontend.utils.assetsLoader.load( 'style', 'e-lightbox-slideshow' )
			: Promise.resolve( true );

		return Promise.all( [
			lightboxPromise,
			dialogPromise,
			shareLinkPromise,
			swiperStylePromise,
			lightboxStylePromise,
			lightboxSlideshowStylePromise,
		] ).then( () => lightboxPromise );
	}

	getDefaultSettings() {
		return {
			selectors: {
				links: 'a, [data-elementor-lightbox]',
				lightboxSlideshow: '[data-elementor-lightbox-slideshow]',
			},
		};
	}

	getDefaultElements() {
		return {
			$links: jQuery( this.getSettings( 'selectors.links' ) ),
			$lightboxSlideshows: jQuery( this.getSettings( 'selectors.lightboxSlideshow' ) ),
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

		const lightbox = await LightboxManager.getLightbox( this.isLightboxSlideshow() );

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

		this.maybeActivateLightboxOnLink();
	}

	maybeActivateLightboxOnLink() {
		// Detecting lightbox links on init will reduce the time of waiting to the lightbox to be display on slow connections.
		this.elements.$links.each( ( index, element ) => {
			if ( this.isLightboxLink( element ) ) {
				LightboxManager.getLightbox( this.isLightboxSlideshow() );

				// Breaking the iteration when the library loading has already been triggered.
				return false;
			}
		} );
	}

	isLightboxSlideshow() {
		return 0 !== this.elements.$lightboxSlideshows.length;
	}
}
