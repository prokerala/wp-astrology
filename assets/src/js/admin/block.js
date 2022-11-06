const {registerBlockType} = wp.blocks;
const { createElement } = wp.element;
const { SelectControl } = wp.components;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls } = wp.blockEditor;
import ChartOptions from './blocks/chart';
import KundliOptions from './blocks/kundli';
import NumerologyOptions from './blocks/numerology';

const { __ } = wp.i18n;

const DETAILED_REPORTS = [
	'Panchang', 'Kundli', 'MangalDosha', 'SadeSati',
	'KundliMatching', 'NakshatraPorutham', 'Porutham', 'ThirumanaPorutham'
];

function AddAdvancedOption( attributes, setAttributes ) {
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

	let { report } = props;

	const { options } = attributes;
	const setOption = ( val ) => onChange({options: Object.assign({}, options, val )});

	if ( 'Chart' === report ) {
		return ChartOptions( attributes, setOption );
	}

	if ( 'Kundli' === report ) {
		return [
			KundliOptions( attributes, setOption ),
			AddAdvancedOption( attributes, onChange )
		];
	}

	if ( 'Numerology' === report ) {
		return NumerologyOptions( attributes, setOption );
	}

	const hasDetailed = DETAILED_REPORTS.includes( report );
	if ( hasDetailed ) {
		return AddAdvancedOption( attributes, onChange );
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
					>
						<optgroup label="Daily Panchang">
							<option value="AuspiciousPeriod">Auspicious Period</option>
							<option value="InauspiciousPeriod">Inauspicious Period</option>
							<option value="Choghadiya">Choghadiya</option>
							<option value="Panchang">Panchang</option>
						</optgroup>
						<optgroup label="Horoscope">
							<option value="BirthDetails">Birth Details</option>
							<option value="Chart">Chart</option>
							<option value="KaalSarpDosha">Kaal Sarp Dosha</option>
							<option value="Kundli">Kundli</option>
							<option value="MangalDosha">Mangal Dosha</option>
							<option value="Papasamyam">Papa Samyam</option>
							<option value="PlanetPosition">Planet Position</option>
							<option value="SadeSati">Sade Sati</option>
						</optgroup>
						<optgroup label="Marriage Matching">
							<option value="KundliMatching">Kundli Matching</option>
							<option value="NakshatraPorutham">Nakshatra Porutham</option>
							<option value="PapasamyamCheck">Papa Samyam Check</option>
							<option value="Porutham">Porutham</option>
							<option value="ThirumanaPorutham">Thirumana Porutham</option>
						</optgroup>
						<optgroup label="Numerology">
							<option value="Numerology">Numerology</option>
						</optgroup>
					</SelectControl>

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
