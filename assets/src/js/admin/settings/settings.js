/**
 * @since 1.0.0
 */
export class Settings {
	/**
	 * @since 1.0.0
	 */
	constructor() {
		const form = document.querySelector( '#astrology-settings-form' );
		form.querySelectorAll( 'input,select' ).forEach( ( el ) =>
			el.addEventListener( 'blur', function ( e ) {
				if ( e.target.matches( 'input,select' ) ) {
					e.target.classList.add( 'modified' );
				}
			} )
		);
	}
}
