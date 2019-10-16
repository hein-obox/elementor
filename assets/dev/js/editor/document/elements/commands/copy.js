import Base from '../../commands/base';

export class Copy extends Base {
	validateArgs( args ) {
		this.requireContainer( args );
	}

	getHistory( args ) {
		// No history required for the command.
		return false;
	}

	apply( args ) {
		const {
			storageKey = 'clipboard',
			containers = [ args.container ],
			elementsType = containers[ 0 ].model.elType,
		} = args;

		elementorCommon.storage.set( storageKey, {
			type: 'copy',
			elementsType,
			containers: containers.map( ( container ) => container.model.toJSON( { copyHtmlCache: true } ) ),
		} );
	}
}

export default Copy;
