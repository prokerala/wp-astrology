const { SelectControl } = wp.components;
const { __ } = wp.i18n;

const days = [
	{ label: 'Yesterday', value: 'yesterday' },
	{ label: 'Today', value: 'today' },
	{ label: 'Tomorrow', value: 'tomorrow' },
];

const signs = [
	{ value: 'all', label: 'All Signs' },
	{ value: 'aries', label: 'Aries' },
	{ value: 'taurus', label: 'Taurus' },
	{ value: 'gemini', label: 'Gemini' },
	{ value: 'cancer', label: 'Cancer' },
	{ value: 'leo', label: 'Leo' },
	{ value: 'virgo', label: 'Virgo' },
	{ value: 'libra', label: 'Libra' },
	{ value: 'scorpio', label: 'Scorpio' },
	{ value: 'sagittarius', label: 'Sagittarius' },
	{ value: 'capricorn', label: 'Capricorn' },
	{ value: 'aquarius', label: 'Aquarius' },
	{ value: 'pisces', label: 'Pisces' },
];

const types = [
	{ value: 'general', label: 'General' },
	{ value: 'career', label: 'Career' },
	{ value: 'health', label: 'Health' },
	{ value: 'love', label: 'Love' },
];

export default function DailyPredictionOptions( attributes, setOptions ) {
	/* eslint-disable camelcase */
	const { day, sign, type } = attributes.options;
	return (
		<div>
			<SelectControl
				label={ __( 'Date' ) }
				value={ day || 'today' }
				onChange={ ( val ) => setOptions( { day: val } ) }
				options={ days }
			/>
			<SelectControl
				label={ __( 'Sign' ) }
				value={ sign }
				onChange={ ( val ) => setOptions( { sign: val } ) }
				options={ signs }
			/>
			<SelectControl
				label={ __( 'Type' ) }
				value={ type }
				onChange={ ( val ) => setOptions( { type: val } ) }
				options={ types }
			/>
		</div>
	);
}
