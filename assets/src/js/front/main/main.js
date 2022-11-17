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
		const inputs = document.querySelectorAll('.prokerala-location-input');
		this.register(inputs);
	}

	register(inputs) {
		[...inputs].forEach(function (input) {
			const inputPrefix = input.dataset.location_input_prefix
				? input.dataset.location_input_prefix + '_'
				: '';
			new LocationSearch( // eslint-disable-line no-undef
				input,
				function (data) {
					const hiddenDiv =
						document.getElementById('form-hidden-fields');
					const coordinates = document.createElement('input');
					coordinates.name = inputPrefix + 'coordinates';
					coordinates.type = 'hidden';
					coordinates.value = `${data.latitude},${data.longitude}`;
					const timezone = document.createElement('input');
					timezone.name = inputPrefix + 'timezone';
					timezone.type = 'hidden';
					timezone.value = data.timezone;
					hiddenDiv.appendChild(coordinates);
					hiddenDiv.appendChild(timezone);
				},
				{ clientId: CLIENT_ID, persistKey: `${inputPrefix}loc` } // eslint-disable-line no-undef
			);
		});
	}
}
