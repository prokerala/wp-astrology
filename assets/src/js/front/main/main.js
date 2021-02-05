/**
 * Class Main.
 *
 * @since 1.0.0
 */
export class Main {

	/**
	 * Main constructor.
	 *
	 * @since 1.0.0
	 */
	constructor() {
		let inputs = document.querySelectorAll( '.prokerala-location-input' );
		this.register( inputs );
	}

	register( inputs ) {
		[ ...inputs ].map( function( input ) {
			const inputPrefix = input.dataset.location_input_prefix ? input.dataset.location_input_prefix : '';
			new LocationSearch( input, function( data ) {
				const hiddenDiv = document.getElementById( 'form-hidden-fields' );
				const coordinates = document.createElement( 'input' );
				coordinates.name = inputPrefix + 'coordinates';
				coordinates.type = 'hidden';
				coordinates.value = `${data.latitude},${data.longitude}`;
				const timezone = document.createElement( 'input' );
				timezone.name = inputPrefix + 'timezone';
				timezone.type = 'hidden';
				timezone.value = data.timezone;
				hiddenDiv.appendChild( coordinates );
				hiddenDiv.appendChild( timezone );
			}, {clientId: CLIENT_ID, persistKey: inputPrefix});
		});
	}
}
