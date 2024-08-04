module.exports = elementorModules.ViewModuleFrontend.extend( {
	eElement : null,

	editorListeners : null,

	onElementChange : null,

	onEditSettingsChange : null,

	onPageSettingsChange : null,

	isEdit : null,

	isJqueryRequired : null,

	__construct( settings ) {
		if ( ! this.isActive( settings ) ) {
			return;
		}

		this.eElement = settings.eElement;

		this.isEdit = this.eElement?.classList?.contains( 'elementor-element-edit-mode' );

		if ( this.isEdit ) {
			this.addEditorListeners();
		}
	},

	isActive() {
		return true;
	},

	isElementInTheCurrentDocument() {
		if ( ! elementorFrontend.isEditMode() ) {
			return false;
		}

		return elementor.documents.currentDocument.id.toString() === this.eElement?.closest( '.elementor' )?.dataset?.elementorId;
	},

	findElement( selector ) {
		const mainElement = this.eElement,
			rawElements = mainElement?.querySelectorAll( selector );

		if ( ! rawElements ) {
			return;
		}

		return Array.from( rawElements ).filter( ( element ) => {
			// Start `closest` from parent since self can be `.elementor-element`.
			const closestElement = element.parentNode?.closest( '.elementor-element' );
			return closestElement === mainElement;
		} );
	},

	getUniqueHandlerID( cid, eElement ) {
		if ( ! cid ) {
			cid = this.getModelCID();
		}

		if ( ! eElement ) {
			eElement = !! this.eElement ? this.eElement : this.getSettings( 'eElement' );
		}

		const elementType = !! window.jQuery && !! eElement.jquery
			? eElement.attr( 'data-element_type' )
			: eElement.getAttribute( 'data-element_type' );

		return cid + elementType + this.getConstructorID();
	},

	initEditorListeners() {
		var self = this;

		self.editorListeners = [
			{
				event: 'element:destroy',
				to: elementor.channels.data,
				callback( removedModel ) {
					if ( removedModel.cid !== self.getModelCID() ) {
						return;
					}

					self.onDestroy();
				},
			},
		];

		if ( self.onElementChange ) {
			const elementType = self.getWidgetType() || self.getElementType();

			let eventName = 'change';

			if ( 'global' !== elementType ) {
				eventName += ':' + elementType;
			}

			self.editorListeners.push( {
				event: eventName,
				to: elementor.channels.editor,
				callback( controlView, elementView ) {
					var elementViewHandlerID = self.getUniqueHandlerID( elementView.model.cid, elementView.$el );

					if ( elementViewHandlerID !== self.getUniqueHandlerID() ) {
						return;
					}

					if ( typeof self.onElementChange !== 'function' ) {
						return;
					}

					self.onElementChange( controlView.model.get( 'name' ), controlView, elementView );
				},
			} );
		}

		if ( self.onEditSettingsChange ) {
			self.editorListeners.push( {
				event: 'change:editSettings',
				to: elementor.channels.editor,
				callback( changedModel, view ) {
					if ( view.model.cid !== self.getModelCID() ) {
						return;
					}

					const propName = Object.keys( changedModel.changed )[ 0 ];

					self.onEditSettingsChange( propName, changedModel.changed[ propName ] );
				},
			} );
		}

		[ 'page' ].forEach( function( settingsType ) {
			var listenerMethodName = 'on' + settingsType[ 0 ].toUpperCase() + settingsType.slice( 1 ) + 'SettingsChange';

			if ( self[ listenerMethodName ] ) {
				self.editorListeners.push( {
					event: 'change',
					to: elementor.settings[ settingsType ].model,
					callback( model ) {
						self[ listenerMethodName ]( model.changed );
					},
				} );
			}
		} );
	},

	getEditorListeners() {
		if ( ! this.editorListeners ) {
			this.initEditorListeners();
		}

		return this.editorListeners;
	},

	addEditorListeners() {
		var uniqueHandlerID = this.getUniqueHandlerID();

		this.getEditorListeners().forEach( function( listener ) {
			elementorFrontend.addListenerOnce( uniqueHandlerID, listener.event, listener.callback, listener.to );
		} );
	},

	removeEditorListeners() {
		var uniqueHandlerID = this.getUniqueHandlerID();

		this.getEditorListeners().forEach( function( listener ) {
			elementorFrontend.removeListeners( uniqueHandlerID, listener.event, null, listener.to );
		} );
	},

	getElementType() {
		return this.eElement?.dataset?.element_type;
	},

	getWidgetType() {
		const widgetType = this.eElement?.dataset?.widget_type;

		if ( ! widgetType ) {
			return;
		}

		return widgetType.split( '.' )[ 0 ];
	},

	getID() {
		return this.eElement?.dataset?.id;
	},

	getModelCID() {
		if ( !! this.eElement ) {
			return this.eElement.dataset?.modelCid;
		}

		return this.$element.data( 'model-cid' );
	},

	getElementSettings( setting ) {
		let elementSettings = {};

		const modelCID = this.getModelCID();

		if ( this.isEdit && modelCID ) {
			const settings = elementorFrontend.config.elements.data[ modelCID ],
				attributes = settings.attributes;

			let type = attributes.widgetType || attributes.elType;

			if ( attributes.isInner ) {
				type = 'inner-' + type;
			}

			let settingsKeys = elementorFrontend.config.elements.keys[ type ];

			if ( ! settingsKeys ) {
				settingsKeys = elementorFrontend.config.elements.keys[ type ] = [];

				Object.entries( settings.controls ).forEach( ( [ name, control ] ) => {
					if ( control.frontend_available || control.editor_available ) {
						settingsKeys.push( name );
					}
				} );
			}

			Object.keys( settings.getActiveControls() )?.forEach( ( controlKey ) => {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					let value = attributes[ controlKey ];

					if ( value.toJSON ) {
						value = value.toJSON();
					}

					elementSettings[ controlKey ] = value;
				}
			} );
		} else {
			const rawSettings = this.eElement?.dataset?.settings;
			elementSettings = !! rawSettings
				? JSON.parse( rawSettings )
				: {};
		}

		return this.getItems( elementSettings, setting );
	},

	getEditSettings( setting ) {
		let attributes = {};

		if ( this.isEdit ) {
			attributes = elementorFrontend.config.elements.editSettings[ this.getModelCID() ].attributes;
		}

		return this.getItems( attributes, setting );
	},

	getCurrentDeviceSetting( settingKey ) {
		return elementorFrontend.getCurrentDeviceSetting( this.getElementSettings(), settingKey );
	},

	onInit() {
		if ( null === this.isJqueryRequired ) {
			this.isJqueryRequired = true;
		}

		if ( ! this.eElement ) {
			this.eElement = this.getSettings( 'eElement' );
		}

		if ( !! window.jQuery ) {
			this.$element = jQuery( this.eElement );
		}

		if ( this.isActive( this.getSettings() ) ) {
			elementorModules.ViewModuleFrontend.prototype.onInit.apply( this, arguments );
		}
	},

	onDestroy() {
		if ( this.isEdit ) {
			this.removeEditorListeners();
		}

		if ( this.unbindEvents ) {
			this.unbindEvents();
		}
	},
} );
