# TODO: Modify About Tab to Support 3 Images

- [x] Update `nrna_register_home_meta_fields()`: Remove 'about_image', add 'about_image_1', 'about_image_2', 'about_image_3' as integer fields.
- [x] Update `nrna_render_home_meta_box()` in 'about-us' case: Replace the single image field with three identical image upload fields (about_image_1, about_image_2, about_image_3).
- [x] Update `nrna_save_home_meta_box()`: Remove 'about_image' from $fields, add the three new fields with 'intval' sanitization.
- [x] Update `nrna_prepare_page_rest_response()`: Remove 'about_image' from $single_image_fields, add the three new fields to generate corresponding '_url' in REST response.
