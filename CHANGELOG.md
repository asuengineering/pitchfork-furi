# Change Log

Notable changes to this project will be documented in this file.

CHANGELOG implemented as of Oct 2023 after several released versions of this child theme. See commit history for further details.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

### Version 1.2

Improved mobile experience for the expo page triggered within the template `symposium.php`

- ADD: Filtering of projects by search or keyword.
- REMOVE: Dropped dependencies on Select 2 replacement for native select boxes. Accessibility improvement.
- ADD: Bootstrap 5 offcanvas menu for filters on mobile. Improved styles for participant cards on mobile.

Other issues addressed:

- REMOVE: Project count graph block removed from home page blocks and from child theme. No longer needed.

### Version 1.1

New blocks and new functionality designed to add more details about our faculty mentor profiles were included in this release.

- ADD: A new option was included for faculty mentors to be marked as "ready to mentor" with an additional field in the taxonomy item screen. This new status allows program administrators to highlight new and existing faculty mentors who are looking to enhance their existing profiles (powered by ASU Search) with details about their associated programs as well as their preferred subject matter.
- ADD: A new block called the `mentor-ready-list` block was created to produce the list of mentors who are in "ready to mentor" status.
- ADD: A second new block called the `mentor-list` was added to create profile grids of the faculty members associated with projects within various events. The default setting for the block is to display faculty members associated with the currently "active" expo.
- CHANGE: This release implements build processes associated with the WP Gulp package as opposed to `gulp-wp` to ensure consistency with other ASU Engineering WordPress products. See `README.md` for additional details.

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
