const { CheckboxControl, SelectControl } = wp.components;
const { __ } = wp.i18n;

const chartStyles = {
	'': '',
	'south-indian': 'South Indian',
	'north-indian': 'North Indian',
	'east-indian': 'East Indian',
};

const chartStyleOptions = Object.entries(chartStyles).map(([value, label]) => {
	return { value, label };
});

export default function KundliOptions(attributes, setOptions) {
	/* eslint-disable camelcase */
	const { display_charts, chart_style } = attributes.options;
	const isChecked = !!display_charts;

	return (
		<div>
			<CheckboxControl
				label="Display charts"
				help="Would you like to include lagna/navamsa charts in the result?"
				checked={isChecked}
				onChange={(val) =>
					setOptions({
						display_charts: val ? 'lagna,navamsa' : '',
					})
				}
			/>
			<SelectControl
				label={__('Chart Style')}
				value={chart_style}
				onChange={(val) => setOptions({ chart_style: val })}
				options={chartStyleOptions}
			/>
		</div>
	);
}
