const { SelectControl } = wp.components;
const { __ } = wp.i18n;

const systems = [
	{label: '', value: ''},
	{label: 'Pythagorean', value: 'pythagorean'},
	{label: 'Chaldean', value: 'chaldean'}
];

const calculators = {
	chaldean: [
		{ value: '', label: '' },
		{ value: 'birth-number', label: 'Birth Number' },
		{ value: 'life-path-number', label: 'Life Path Number' },
		{ value: 'identity-initial-code-number', label: 'Identity Initial Code Number' },
		{ value: 'whole-name-number', label: 'Whole Name Number' }
	],
	pythagorean: [
		{ value: '', label: '' },
		{ value: 'life-path-number', label: 'Life Path Number' },
		{ value: 'capstone-number', label: 'Cap Stone Number' },
		{ value: 'personality-number', label: 'Personality Number' },
		{ value: 'challenge-number', label: 'Challenge Number' },
		{ value: 'inner-dream-number', label: 'Inner Dream Number' },
		{ value: 'personal-year-number', label: 'Personal Year Number' },
		{ value: 'expression-number', label: 'Expression Number' },
		{ value: 'universal-month-number', label: 'Universal Month Number' },
		{ value: 'personal-month-number', label: 'Personal Month Number' },
		{ value: 'soul-urge-number', label: 'Soul Urge Number' },
		{ value: 'destiny-number', label: 'Destiny Number' },
		{ value: 'attainment-number', label: 'Attainment Number' },
		{ value: 'birth-day-number', label: 'Birth Day Number' },
		{ value: 'universal-day-number', label: 'Universal Day Number' },
		{ value: 'birth-month-number', label: 'Birth Month Number' },
		{ value: 'universal-year-number', label: 'UniversalYearNumber' },
		{ value: 'balance-number', label: 'Balance Number' },
		{ value: 'personal-day-number', label: 'Personal Day Number' },
		{ value: 'cornerstone-number', label: 'Corner Stone Number' },
		{ value: 'subconscious-self-number', label: 'Subconscious Self Number' },
		{ value: 'maturity-number', label: 'Maturity Number' },
		{ value: 'hidden-passion-number', label: 'Hidden Passion Number' },
		{ value: 'rational-thought-number', label: 'Rational Thought Number' },
		{ value: 'pinnacle-number', label: 'Pinnacle Number' },
		{ value: 'karmic-debt-number', label: 'Karmic Debt Number' },
		{ value: 'bridge-number', label: 'Bridge Number' }
	]
};

export default function NumerologyOptions( attributes, setOptions ) {
	/* eslint-disable camelcase */
	const { calculator, system } = attributes.options;

	return (
		<div>
			<SelectControl
				label={ __( 'System' ) }
				value={ system }
				onChange={ system => setOptions({ system, calculator: '' }) }
				options={ systems }
			/>
			<SelectControl
				label={ __( 'Calculator' ) }
				value={ calculator }
				onChange={ calculator => setOptions({ calculator }) }
				options={ calculators[system] }
			/>
		</div>
	);
}
