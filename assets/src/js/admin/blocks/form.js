// // const {registerBlockType} = wp.blocks; //Blocks API
// //
// //
// // registerBlockType( 'astrology/block', {
// // 	report: __( 'Report Name' ), // Block title.
// // 	category: __( 'common' ), //category
// // 	//display the edit interface + preview
// // 	edit( attributes, setAttributes ) {
// // 		return createElement( 'div', {}, 'Hello from block edit callback' );
// // 	},
// // 	save() {
// // 		return null;//save has to exist. This all we need
// // 	}
// // });
//
// const {createElement} = wp.element;
// const { SelectControl, InspectorControls } = wp.components;
// const { serverSideRender: ServerSideRender } = wp;
//
// const {__} = wp.i18n;
//
// const blockStyle = {
// 	backgroundColor: '#990000',
// 	color: '#fff',
// 	padding: '20px'
// };
//
// export default {
// 	title: 'Astrology Report',
// 	icon: 'star-filled',
// 	category: 'design',
// 	attributes: {
// 		report: {
// 			default: 'Chart'
// 		}
// 	},
// 	example: {},
// 	edit({ attributes, setAttributes, isSelected, className }) {
// 		const { report } = attributes;
//
// 		return (
// 			<div className={className}>
// 				<InspectorControls>
// 					<SelectControl
// 						label={__( 'Report' )}
// 						value={report}
// 						onChange={report => setAttributes({report})}
// 						options={[
// 							{value: 'Choghadiya', label: 'Choghadiya'},
// 							{value: 'BirthDetails', label: 'Birth Details'},
// 							{value: 'Chart', label: 'Chart'},
// 							{value: 'AuspiciousPeriod', label: 'Auspicious Period'},
// 							{value: 'InauspiciousPeriod', label: 'Inauspicious Period'},
// 							{value: 'KaalSarpDosha', label: 'Kaal Sarp Dosha'},
// 							{value: 'Kundli', label: 'Kundli'},
// 							{value: 'KundliMatching', label: 'KundliMatching'},
// 							{value: 'MangalDosha', label: 'Mangal Dosha'},
// 							{value: 'NakshatraPorutham', label: 'Nakshatra Porutham'},
// 							{value: 'Panchang', label: 'Panchang'},
// 							{value: 'PapasamyamCheck', label: 'Papa Samyam Check'},
// 							{value: 'Papasamyam', label: 'Papa Samyam'},
// 							{value: 'PlanetPosition', label: 'Planet Position'},
// 							{value: 'SadeSati', label: 'Sade Sati'},
// 							{value: 'ThirumanaPorutham', label: 'Thirumana Porutham'}
// 						]}
// 					/>,
// 				</InspectorControls>
// 				<ServerSideRender
// 					block="astrology/report"
// 					attributes={attributes}
// 				/>
// 			</div>
// 		);
// 	},
// 	save() {
//
// 		// return <div style={ blockStyle }>Hello World, step 1 (from the frontend).</div>;
// 		return null;
// 	}
// };
