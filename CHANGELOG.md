# Change Log

Notable changes to this project will be documented in this file.

CHANGELOG implemented as of Oct 2023 after several released versions of this child theme. See commit history for further details.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

### Version 1.0.2

- FIX: This minor release removes two page archives for `furiproject` and `participant` which are not really helpful for navigating the contents of the site.

### Version 1.0.1

This minor release adds styles to various blocks and page templates for better mobile display. It also includes one additional bug fix.

- FIX: Address compatability issue with select boxes within `/expo` page template and Bootstrap 5.3.2. Updated [Bootstrap Select](https://developer.snapappointments.com/bootstrap-select/) package to current beta version for best results.

### Version 1.0

- FIX: Address PHP warning for missing index in `snapshot-footer.php`
- FIX: Refactor `acf/featured-carousel` block due to changes in expected markup in parent theme.
- CHANGE: Replace featured symposium card display in card grid with degree-card format now available in the Unity project.
- FIX: Allow child theme to load ACF fields from both parent and child theme.
