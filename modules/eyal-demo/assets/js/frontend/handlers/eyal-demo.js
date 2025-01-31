import Base from 'elementor-frontend/handlers/base';

export default class EyalDemo extends Base {
	getDefaultSettings() {
		return {
			selectors: {
				title: 'h1',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$title: this.findElement( selectors.title ),
		};
	}

	bindEvents() {
		this.elements.$title.on( 'click', this.onClickTitle.bind( this ) );
	}

	unbindEvents() {
		this.elements.$title.off();
	}

	onInit( ...args ) {
		super.onInit( ...args );

		// Run some code on init.
	}

	onClickTitle() {
		alert( 'Title clicked' );
	}
}
