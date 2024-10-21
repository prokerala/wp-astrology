# Astrology Calculators for WordPress

## Description

Add astrology charts and calculators powered by [Prokerala Astrology API](https://api.prokerala.com) to your WordPress blog in seconds.

## Available Services

- Daily Horoscope Predictions
- Numerology Calculators
- Daily Panchang Calculators
  - Panchang
  - Auspicious Period
  - Inauspicious Period
  - Choghadiya
- Horoscope Calculators
  - Birth Details
  - Charts
  - Kundli
  - Mangal Dosha
  - Kaalsarp Dosha
  - Papasamyam
  - Planet Position
  - Sade-Sati
- Marriage Matching Calculators
  - Kundli Matching
  - Nakshatra Porutham
  - Thirumana Porutham
  - Porutham
  - Papasamyam Check
- Western Astrology
  - Western Charts
  - Natal Chart
  - Transit Chart
  - Progression Chart
  - Solar Chart
  - Synastry Chart
  - Composite Chart

## Usage

The reports can be enabled on you block using blocks or shortcodes.

### Blocks

The plugin adds a new block name **Astrology Reports** to the block editor.

### Shortcode

If you are unable to use the block editor, then you can also activate the plugin using the short code `astrology`.

```
[astrology report="REPORT_NAME"]
```

Where `REPORT_NAME` must be one of following

- `AuspiciousPeriod`
- `BirthDetails`
- `Chart`
- `Choghadiya`
- `DailyPrediction`
- `InauspiciousPeriod`
- `KaalSarpDosha`
- `Kundli`
- `KundliMatching`
- `MangalDosha`
- `NakshatraPorutham`
- `Numerology`
- `Panchang`
- `Papasamyam`
- `PapasamyamCheck`
- `PlanetPosition`
- `Porutham`
- `SadeSati`
- `ThirumanaPorutham`
- `WesternChart`
- `CompatibilityChart`

#### Shortcode attributes

- **`result_type`**

   In calculators that support `basic` and `advanced` results, the result type can be forced using the `result_type` attribute. Settings this attribute will remove the corresponding input fields from the form.

        [astrology report="Kundli" result_type="advanced"]

   **Available for**
   - Kundli
   - Panchang

#### Report Specific Options

#### Chart

- **`chart_style`**

  You can set the `chart_style` attribute to one of `north-indian`, `south-indian` or `east-indian` to force the result chart style. Setting this attribute will remove the corresponding input fields from the form.

        [astrology report="Kundli" chart_style="south-indian"]

##### Kundli

- **`display_charts`**

  The `display_charts` option allows showing _Rasi_ / _Navamsa_ chart in Kundli result. This will cost two additional API calls. The value of the attribute must be `lagna,navamsa`.

        [astrology report="Kundli" display_charts="lagna,rasi"]

##### DailyPrediction

- **`sign`**

   By default, the DailyPrediction report will display predictions for all zodiac signs. You can use limit the result to a single zodiac sign using the `sign` attribute. This may be used to create separate page for each zodiac sign or to insert advertisement between the result.

- **`date`**

   By default, the `DailyPrediction` report will display predictions for the current date. If required, the default behaviour can be changed by setting the `date` attribute to `yesterday`, `today` or `tomorrow`.

      [astrology report="DailyPrediction" date="tomorrow"]

##### Panchang
- **`coordinate`**

  By default, the `Panchang` report will display panchang for Ujjain, Maharashtra. If required, the default behaviour can be changed by setting attribute `coordinate`.

      [astrology report="Panchang" date="tomorrow" coordinate="23.179300,75.784912"]

- **`tz`**

  By default, the `Panchang` report will set timezone to UTC. If required, the default behaviour can be changed by setting attribute `tz`. The `tz` attribute accepts any valid timezone identifier supported by PHP's DateTimeZone class. Examples include `UTC`, `Europe/London`, `America/Los_Angeles`, etc.

      [astrology report="Panchang" date="tomorrow" coordinate="23.179300,75.784912" tz="Asia/Kolkata"]

##### WesternChart

- **`report_type`**

  By default, the `WesternChart` report displays the natal chart. The default behaviour can be modified by setting the `report_type` attribute. Allowed values are `natal-chart`, `transit-chart`, `progression-chart`, `solar-return-chart`, `synastry-chart`, and `composite-chart`.

      [astrology report="WesternChart" report_type="natal-chart" ]

- **`display_options`**

  By default, the `WesternChart` report displays the chart. The default behaviour can be modified by setting the `display_options` attribute. Allowed values are `chart`, `aspect-chart`, `planet-positions`,  `planet-aspects` and `all`. You can specify multiple types by separating them with comma, or use the special value `all` to display everything.

      [astrology report="WesternChart" report_type="natal-chart" display_options="chart,aspect-chart,planet-positions,planet-aspects"]

      [astrology report="WesternChart" report_type="natal-chart" display_options="all"]

##### CompatibilityChart

- **`report_type`**

  By default, the `CompatibilityChart` report displays the synastry chart. The default behaviour can be modified by setting the `report_type` attribute. Allowed values are `synastry-chart`, and `composite-chart`.

      [astrology report="CompatibilityChart" report_type="synastry-chart" ]

- **`display_options`**

  By default, the `CompatibilityChart` report displays the chart. The default behaviour can be modified by setting the `display_options` attribute. Allowed values are `chart`, `aspect-chart`, and `planet-aspects`. You can specify multiple types by separating them with comma, or use the special value `all` to display everything.

      [astrology report="CompatibilityChart" report_type="synastry-chart" display_options="chart,aspect-chart,planet-aspects"]

      [astrology report="CompatibilityChart" report_type="composite-chart" display_options="chart,aspect-chart,planet-positions,planet-aspects"]

      [astrology report="CompatibilityChart" report_type="synastry-chart" display_options="all"]

##### Localization

You can use the following attributes to localize the form / result.  View available languages for each report from  https://api.prokerala.com/docs#tag/Daily-Panchang

- **`form_language`**

  You can set the `form_language` attribute to one of `en`, `hi`, `ml`, `ta`, or `te` to set localization for forms.
  For western reports you can set the `form_language` attribute to one of `en`, or `de` for

      [astrology report="Kundli" form_language="en"]
    	[astrology report="WesternChart" report_type="natal-chart" form_language="de"]

- **`report_language`**

  You can set the `report_language` attribute to one of `en`, `hi`, `ml`, `ta`, or `te` to add language select fields for forms.
  For western reports you can set the `report_language` attribute to one of `en`, or `de` for

      [astrology report="Kundli" report_language="en"]
    	[astrology report="WesternChart" report_type="natal-chart" form_language="de"]


### Frequently Asked Questions

- __Do I need an account to use this plugin?__

   Yes, you need to signup for an account at https://api.prokerala.com to use this plugin.

- __Do I need a paid subscription?__

   No, you can start using the plugin with our free subscription.

### About Prokerala Astrology

Prokerala.com is an astrology service provider with over 10 years of web presence. Our experienced team of astrologers and programmers have succeeded in creating an absolutely reliable astrology platform which is today trusted by millions of dedicated users including professional astrologers and ardent astrology enthusiasts.

Prokerala Astrology API is in pursuance of our efforts to make astrology services and information accessable for everyone. Our simple API empowers you to get started with your own astrology services. You'll be up and running in a jiffy!


### Acknowledgement

The directory layout for this plugin was inspired by [WPPlugin](https://github.com/wppunk/WPPlugin) by [wppunk](https://github.com/wppunk).

The composer dependency management code is based on [Google Site kit](https://github.com/google/site-kit-wp).
