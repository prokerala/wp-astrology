const {registerBlockType} = wp.blocks;
const { createElement } = wp.element;
const { SelectControl } = wp.components;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls } = wp.blockEditor;

const { __ } = wp.i18n;

const REPORTS = [
	{value: 'Choghadiya', name: 'Choghadiya', advanced: false},
	{value: 'BirthDetails', name: 'Birth Details', advanced: false},
	{value: 'AuspiciousPeriod', name: 'Auspicious Period', advanced: false},
	{value: 'InauspiciousPeriod', name: 'Inauspicious Period', advanced: false},
	{value: 'Panchang', name: 'Panchang', advanced: true},
	{value: 'Chart', name: 'Chart', advanced: false},
	{value: 'KaalSarpDosha', name: 'Kaal Sarp Dosha', advanced: false},
	{value: 'Kundli', name: 'Kundli', advanced: true},
	{value: 'MangalDosha', name: 'Mangal Dosha', advanced: true},
	{value: 'Papasamyam', name: 'Papa Samyam', advanced: false},
	{value: 'PlanetPosition', name: 'Planet Position', advanced: false},
	{value: 'SadeSati', name: 'Sade Sati', advanced: true},
	{value: 'KundliMatching', name: 'Kundli Matching', advanced: true},
	{value: 'NakshatraPorutham', name: 'Nakshatra Porutham', advanced: true},
	{value: 'PapasamyamCheck', name: 'Papa Samyam Check', advanced: false},
	{value: 'Porutham', name: 'Porutham', advanced: true},
	{value: 'ThirumanaPorutham', name: 'Thirumana Porutham', advanced: true}
];

const labelOptions = REPORTS.map( ({value, name}) => {
    return {value, label: name};
});

function AdvancedOption( props ) {

	let report = findReport( props.report );
	if ( ! report.advanced ) {
		return null;
	}

	const { resultType } = props;
	const { onChange } = props;

	return (
		<SelectControl
			label={__( 'Result Type' )}
			value={resultType}
			onChange={resultType => onChange({resultType})}
			options={[
				{value: '', label: ''},
				{value: 'basic', label: 'Basic'},
				{value: 'advanced', label: 'Advanced'}
			]}
		/>
	);
}

function findReport( name ) {
	for ( let report of REPORTS ) {
		if ( name === report.value ) {
			return report;
		}
	}

	throw new Error( 'Invalid report type' );
}

registerBlockType( 'astrology/report', {
	title: 'Astrology Report',
	icon: 'star-filled',
	category: 'design',
	attributes: {
		report: {
			type: 'string',
			default: 'Chart'
		},
		resultType: {
			type: 'string',
			default: ''
		}
	},
	example: {},
	edit({ attributes, setAttributes, className }) {
		const { report } = attributes;
		const { resultType } = attributes;

		return (
			<div className={className}>
				<InspectorControls>
					<SelectControl
						label={__( 'Report' )}
						value={report}
						onChange={report => setAttributes({report})}
						options={labelOptions}
					/>

					<AdvancedOption
						report={report}
						value={resultType}
						onChange={setAttributes}
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
