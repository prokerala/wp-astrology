const {registerBlockType} = wp.blocks;
const { createElement } = wp.element;
const { SelectControl } = wp.components;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls } = wp.blockEditor;

const { __ } = wp.i18n;

registerBlockType( 'astrology/report', {
	title: 'Astrology Report',
	icon: 'star-filled',
	category: 'design',
	attributes: {
		report: {
			type: 'string',
			default: 'Chart'
		}
	},
	example: {},
	edit({ attributes, setAttributes, className }) {
		const { report } = attributes;

		return (
			<div className={className}>
				<InspectorControls>
					<SelectControl
						label={__( 'Report' )}
						value={report}
						onChange={report => setAttributes({report})}
						options={[
							{value: 'Choghadiya', label: 'Choghadiya'},
							{value: 'BirthDetails', label: 'Birth Details'},
							{value: 'AuspiciousPeriod', label: 'Auspicious Period'},
							{value: 'InauspiciousPeriod', label: 'Inauspicious Period'},
							{value: 'Panchang', label: 'Panchang'},
							{value: 'Chart', label: 'Chart'},
							{value: 'KaalSarpDosha', label: 'Kaal Sarp Dosha'},
							{value: 'Kundli', label: 'Kundli'},
							{value: 'MangalDosha', label: 'Mangal Dosha'},
							{value: 'Papasamyam', label: 'Papa Samyam'},
							{value: 'PlanetPosition', label: 'Planet Position'},
							{value: 'SadeSati', label: 'Sade Sati'},
							{value: 'KundliMatching', label: 'KundliMatching'},
							{value: 'NakshatraPorutham', label: 'Nakshatra Porutham'},
							{value: 'PapasamyamCheck', label: 'Papa Samyam Check'},
							{value: 'Porutham', label: 'Porutham'},
							{value: 'ThirumanaPorutham', label: 'Thirumana Porutham'}
						]}
					/>
				</InspectorControls>
				<ServerSideRender
					block="astrology/report"
					attributes={attributes}
				/>
			</div>
		);
	},
	save() {
		return null;
	}
});
