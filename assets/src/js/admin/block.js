const { registerBlockType } = wp.blocks;
const { SelectControl } = wp.components;
const { serverSideRender: ServerSideRender } = wp;
const { InspectorControls } = wp.blockEditor;
import ChartOptions from './blocks/chart';
import KundliOptions from './blocks/kundli';
import NumerologyOptions from './blocks/numerology';
import DailyPredictionOptions from './blocks/horoscope';

const { __ } = wp.i18n;

const DETAILED_REPORTS = [
	'Panchang',
	'Kundli',
	'MangalDosha',
	'SadeSati',
	'KundliMatching',
	'NakshatraPorutham',
	'Porutham',
	'ThirumanaPorutham',
];

function AddAdvancedOption( attributes, setAttributes ) {
	const { resultType } = attributes;

	return (
		<SelectControl
			label={ __( 'Result Type' ) }
			value={ resultType }
			onChange={ ( val ) => setAttributes( { resultType: val } ) }
			options={ [
				{ value: '', label: '' },
				{ value: 'basic', label: 'Basic' },
				{ value: 'advanced', label: 'Advanced' },
			] }
		/>
	);
}

function ReportOptions( { attributes, onChange, report } ) {
	const { options } = attributes;

	const setOption = ( val ) =>
		onChange( { options: { ...options, ...val } } );

	const reportHandlers = {
		Chart: () => ChartOptions( attributes, setOption ),
		Kundli: () => [
			KundliOptions( attributes, setOption ),
			AddAdvancedOption( attributes, onChange ),
		],
		DailyPrediction: () => DailyPredictionOptions( attributes, setOption ),
		Numerology: () => NumerologyOptions( attributes, setOption ),
	};

	if ( reportHandlers[ report ] ) {
		return reportHandlers[ report ]();
	}

	if ( DETAILED_REPORTS.includes( report ) ) {
		return AddAdvancedOption( attributes, onChange );
	}

	return null;
}

registerBlockType( 'astrology/report', {
	title: 'Astrology Report',
	icon: 'star-filled',
	category: 'design',
	attributes: {
		report: {
			type: 'string',
			default: 'Chart',
		},
		resultType: {
			type: 'string',
			default: '',
		},
		options: {
			type: 'object',
			default: {},
		},
	},
	example: {},
	// eslint-disable-next-line max-lines-per-function
	edit( { attributes, setAttributes, className } ) {
		const { report } = attributes;

		return (
			<div className={ className }>
				<InspectorControls>
					<SelectControl
						label={ __( 'Report' ) }
						value={
							report === 'WesternChart' &&
							attributes.options?.report_type
								? `WesternChart:${ attributes.options.report_type }`
								: report
						}
						onChange={ ( val ) => {
							if (
								val.startsWith( 'WesternChart:' ) ||
								val.startsWith( 'CompatibilityChart:' )
							) {
								const [ controller, reportType ] =
									val.split( ':' );
								setAttributes( {
									report: controller,
									options: {
										...attributes.options,
										report_type: reportType,
									},
								} );
							} else {
								setAttributes( { report: val } );
							}
						} }
					>
						<optgroup label="Daily Panchang">
							<option value="AuspiciousPeriod">
								Auspicious Period
							</option>
							<option value="InauspiciousPeriod">
								Inauspicious Period
							</option>
							<option value="Choghadiya">Choghadiya</option>
							<option value="Panchang">Panchang</option>
						</optgroup>
						<optgroup label="Horoscope (Vedic)">
							<option value="BirthDetails">Birth Details</option>
							<option value="Chart">Chart</option>
							<option value="KaalSarpDosha">
								Kaal Sarp Dosha
							</option>
							<option value="Kundli">Kundli</option>
							<option value="MangalDosha">Mangal Dosha</option>
							<option value="Papasamyam">Papa Samyam</option>
							<option value="PlanetPosition">
								Planet Position
							</option>
							<option value="SadeSati">Sade Sati</option>
						</optgroup>
						<optgroup label="Horoscope (Western)">
							<option value="WesternChart:natal-chart">
								Natal Chart
							</option>
							<option value="WesternChart:transit-chart">
								Transit Chart
							</option>
							<option value="WesternChart:progression-chart">
								Progression Chart
							</option>
							<option value="WesternChart:solar-return-chart">
								Solar Return Chart
							</option>
							<option value="CompatibilityChart:synastry-chart">
								Synastry Chart
							</option>
							<option value="CompatibilityChart:composite-chart">
								Composite Chart
							</option>
						</optgroup>
						<optgroup label="Marriage Matching">
							<option value="KundliMatching">
								Kundli Matching
							</option>
							<option value="NakshatraPorutham">
								Nakshatra Porutham
							</option>
							<option value="PapasamyamCheck">
								Papa Samyam Check
							</option>
							<option value="Porutham">Porutham</option>
							<option value="ThirumanaPorutham">
								Thirumana Porutham
							</option>
						</optgroup>
						<optgroup label="Predictions">
							<option value="DailyPrediction">
								Daily Horoscope
							</option>
						</optgroup>
						<optgroup label="Numerology">
							<option value="Numerology">Numerology</option>
						</optgroup>
					</SelectControl>

					<ReportOptions
						report={ report }
						attributes={ attributes }
						onChange={ setAttributes }
					/>
				</InspectorControls>

				<ServerSideRender
					block="astrology/report"
					attributes={ attributes }
				/>
			</div>
		);
	},
	save() {
		return null;
	},
} );
