=== Astrology ===
Contributors: Prokerala
Tags: astrology, prokerala
Requires at least: 5.5
Tested up to: 5.8.1
Stable tag: 1.0.5
Requires PHP: 7.2.0
License: GPLV2+

Turn your Wordpress blog into a full astrology site, powered by Prokerala's astrology API.

== Description ==

Turn your Wordpress blog into a full astrology site, powered by Prokerala's astrology API.

= Features =
* Free
* Easy to setup

== Available Services ==

* Daily Panchang Calculators
 * Panchang
 * Auspicious Period
 * Inauspicious Period
 * Choghadiya
* Horoscope Calculators
 * Birth Details
 * Charts
 * Kundli
 * Mangal Dosha
 * Kaalsarp Dosha
 * Papasamyam
 * Planet Position
 * Sade-Sati
* Marriage Matching Calculators
 * Kundli Matching
 * Nakshatra Porutham
 * Thirumana Porutham
 * Porutham
 * Papasamyam Check

== Installation ==

1. Upload `astrology` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==

- Install and activate the plugin on your WordPress dashboard.
- Enter you Prokerala API client id and client secret in the plugin settings page.
- Create a blog post or a page and add the report form.

The reports form can be added to a page using the block editor or using shortcode.

= Blocks =

The plugin adds a new block name **Astrology Reports** to the block editor.

= Shortcode =

If you are unable to use the block editor, then you can also activate the plugin using the short code `astrology`.

<code>[astrology report="REPORT_NAME"]</code>

Where `REPORT_NAME` must be one of following

- `AuspiciousPeriod`
- `BirthDetails`
- `Chart`
- `Choghadiya`
- `InauspiciousPeriod`
- `KaalSarpDosha`
- `KundliCharts`
- `Kundli`
- `KundliMatching`
- `MangalDosha`
- `NakshatraPorutham`
- `Panchang`
- `PapasamyamCheck`
- `Papasamyam`
- `PlanetPosition`
- `Porutham`
- `SadeSati`
- `ThirumanaPorutham`

*Shortcode attributes*

- `display_charts`

   The `display_charts` option allows showing _Rasi_ / _Navamsa_ chart in Kundli result. This will cost two additional API calls. The value of the attribute must be `lagna,navamsa`.

   `[astrology report="Kundli" display_charts="lagna,rasi"]`

- `result_type`

   In calculators that support `basic` and `advanced` results, the result type can be forced using the `result_type` attribute. Settings this attribute will remove the corresponding input fields from the form.

   `[astrology report="Kundli" result_type="advanced"]`

- `chart_style`

   You can set the `chart_style` attribute to one of `north-indian`, `south-indian` or `east-indian` to force the result chart style. Setting this attribute will remove the corresponding input fields from the form.

   `[astrology report="Kundli" chart_style="south-indian"]`

== Frequently Asked Questions ==

= Do I need an account to use this plugin? =

Yes, you need to signup for an account at https://api.prokerala.com to use this plugin.

= Do I need a paid subscription? =

No, you can start using the plugin with our free subscription.

== Changelog ==

= 1.0.5 =
* Location parameters where not being passed correctly
* Minor inconsistencies and mistakes in template files
* Fix PHP notices

= 1.0.4 =
* Fix php notices

= 1.0.3 =
* Added new shortcode attribute `form_action` to specify a different page url for result.

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* Initial release

== Screenshots ==

1. Astrology Reports Block
2. Plugin block editor demo
