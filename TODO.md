# TODO: Implement Activities Feature in NRNA Theme

## Steps to Complete

- [x] Update `wp-content/themes/NRNA/inc/custom-post-types.php` to register 'activities' CPT with supports for 'title', 'editor', 'thumbnail', appropriate labels, and menu icon.
- [x] Update `wp-content/themes/NRNA/inc/meta-fields.php` to add meta boxes for photos (multiple image upload) and related notices (multi-select dropdown of notices).
- [x] Create `wp-content/themes/NRNA/template-activities.php` for displaying activities list with title, date, image, content, photo gallery, and related notices links.
- [x] Create `wp-content/themes/NRNA/assets/css/activities.css` for styling the activities display.
- [x] Update `wp-content/themes/NRNA/inc/enqueue-scripts.php` to enqueue the new `activities.css` file.
- [ ] Followup: Create a page in WordPress admin using the new template.
- [ ] Followup: Test by adding sample activities and viewing the page.
- [ ] Followup: Ensure multiple photos display as gallery.
- [ ] Followup: Verify related notices display correctly.
