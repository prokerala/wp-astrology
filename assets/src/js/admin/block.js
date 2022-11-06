const {registerBlockType} = wp.blocks;
const { createElement } = wp.element;
const { SelectControl } = wp.components;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls } = wp.blockEditor;
import ChartOptions from './blocks/chart';
import KundliOptions from './blocks/kundli';

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

function AddAdvancedOption( report, attributes, setAttributes ) {
	const { resultType } = attributes;

	return (
		<SelectControl
			label={__( 'Result Type' )}
			value={resultType}
			onChange={resultType => setAttributes({resultType})}
			options={[
				{value: '', label: ''},
				{value: 'basic', label: 'Basic'},
				{value: 'advanced', label: 'Advanced'}
			]}
		/>
	);
}

function transformKeys( object ) {
	return Object.fromEntries(
		Object.entries( object ).map(
			([ key, val ]) => [
				key.replace( /[A-Z]/, ( char ) => `_${char.toLowerCase()}` ),
				val
			]
		)
	);
}

function ReportOptions( props ) {
	const { attributes } = props;
	const { onChange } = props;

	let report = findReport( props.report );

	const { options } = attributes;
	const setOptions = ( val ) => onChange({options: Object.assign({}, options, val )});

	if ( 'Chart' === report.value ) {
		return ChartOptions( report, attributes, setOptions );
	}

	if ( 'Kundli' === report.value ) {
		return KundliOptions( report, attributes, setOptions );
	}

	if ( report.advanced ) {
		return AddAdvancedOption( report, attributes, onChange );
	}

	return null;
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
		},
		options: {
			type: 'object',
			default: {
			}
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
						options={labelOptions}
					/>

					<ReportOptions
						report={report}
						attributes={attributes}
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
