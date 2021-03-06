# Astrology Calculators for WordPress

## Description

Add astrology charts and calculators powered by [Prokerala Astrology API](https://api.prokerala.com) to your WordPress blog in seconds.

## Available Services

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

#### Shortcode attributes

- **`display_charts`**

   The `display_charts` option allows showing _Rasi_ / _Navamsa_ chart in Kundli result. This will cost two additional API calls. The value of the attribute must be `lagna,navamsa`.

   ```
   [astrology report="Kundli" display_charts="lagna,rasi"]
   ```

- **`result_type`**

   In calculators that support `basic` and `advanced` results, the result type can be forced using the `result_type` attribute. Settings this attribute will remove the corresponding input fields from the form.

   ```
   [astrology report="Kundli" result_type="advanced"]
   ```

- **`chart_style`**

   You can set the `chart_style` attribute to one of `north-indian`, `south-indian` or `east-indian` to force the result chart style. Setting this attribute will remove the corresponding input fields from the form.

   ```
   [astrology report="Kundli" chart_style="south-indian"]
   ```

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
