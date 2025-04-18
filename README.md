# Pitchfork Child <br/> A WordPress child theme for Pitchfork

Requires at least: WP 6.0
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

**Contributors**

## Usage Requirements

## Recommended / Required Additional Plugins

- The blocks are constructed using Advanced Custom Fields Pro.
- They were created using the recommended proceedures of ACF Pro's "Block API v1" which pre-dates the 5.10 release of the plugin.

## Development

The plugin uses [Gulp WP](https://github.com/cr0ybot/gulp-wp) to compile SASS and minify assets.

- Run `npx gulp-wp` to trigger the development tools.

An additional gulp script has been added to `gulpfile.js` to extract the SASS variables from the ASU Unity Bootstrap library and incorporate those values into the CSS for the site.

- Run `gulp upboot` to extract the current version of those files.

A small script to lint the codebase is also included via `composer`. It utilizes the rules outlined in the [WP Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards).

- Run `composer install` to setup this process.
- Use `composer check:cs` to lint the plugin files.
- Use `composer fix:cs` to fix the problems that it can address automatically.

## Release Notes

See [CHANGELOG.md](CHANGELOG.md) for the a list of improvements made to the theme.

NOTE: The change log for this project was implemented following the theme release in October 2023. Previous fixes and theme versions can be determined by looking through the commit history of this repository.
