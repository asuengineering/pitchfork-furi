# Pitchfork Child <br/> A WordPress child theme for Pitchfork ##

Requires at least: WP 6.0  
Requires PHP: 7.4
Stable tag: 1.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

**Contributors**

## Usage Requirements

## Recommended / Required Additional Plugins ## 

- The blocks are constructed using Advanced Custom Fields Pro.
- They were created using the recommended proceedures of ACF Pro's "Block API v1" which pre-dates the 5.10 release of the plugin.

## Development

The plugin uses [WP-Gulp](https://github.com/ahmadawais/WPGulp) to compile SASS and minify assets. 
- Run `npm install` and `npm start` to trigger the development tools.
- `gulpfile.babel.js` contains the configuration files for BrowserSync, the watched file paths, etc.

An additional gulp script has been added to `gulpfile.babel.js` to extract a copy of the ASU Unity Bootstrap 4 SASS files from the included library. This allows for the easy linking of the `_variables` file from the design tokens distribution. 
- Run `gulp upboot-tokens` to extract the current version of those files.

A small script to lint the codebase is also included via `composer`. It utilizes the rules outlined in the [WP Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards).
- Run `composer install` to setup this process.
- Use `composer check:cs` to lint the plugin files.
- Use `composer fix:cs` to fix the problems that it can address automatically.

## Block Settings

<hr>

## Release Notes

### Version 0.1

- First stable release of the plugin. 
