const { SelectControl } = wp.components;
const { __ } = wp.i18n;

const chartStyles = {
	'': '',
	'south-indian': 'South Indian',
	'north-indian': 'North Indian',
	'east-indian': 'East Indian'
};

const chartTypes = [
	'', 'Rasi', 'Navamsa', 'Lagna', 'Trimsamsa', 'Drekkana', 'Chaturthamsa', 'Dasamsa', 'Ashtamsa',
	'Dwadasamsa', 'Shodasamsa', 'Hora', 'Akshavedamsa', 'Shashtyamsa', 'Panchamsa', 'Khavedamsa',
	'Saptavimsamsa', 'Shashtamsa', 'Chaturvimsamsa', 'Saptamsa', 'Vimsamsa'
];

const chartTypeOptions = chartTypes.map( label => {
	return {value: label.toLowerCase(), label};
});

const chartStyleOptions = Object.entries( chartStyles ).map( ([ value, label ]) => {
	return {value, label};
});

export default function ChartOptions( attributes, setOptions ) {
	/* eslint-disable camelcase */
	const { chart_type, chart_style } = attributes.options;

	return (
		<div>
			<SelectControl
				label={__( 'Chart Type' )}
				value={chart_type}
				onChange={chart_type => setOptions({ chart_type })}
				options={chartTypeOptions}
			/>
			<SelectControl
				label={__( 'Chart Style' )}
				value={chart_style}
				onChange={chart_style => setOptions({ chart_style })}
				options={chartStyleOptions}
			/>
		</div>
	);
}
