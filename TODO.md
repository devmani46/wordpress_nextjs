# TODO: Customize WordPress Admin Menus

## Overview
- Remove the "Themes" submenu from the "Appearance" menu in wp-admin/menu.php.
- Add a new "Team" custom post type with fields: name (title), description (content), category (taxonomy), and type (employee/staff as taxonomy).
- Register the custom post type and taxonomies in the active theme's functions.php (twentytwentyfour/functions.php).

## Steps
- [x] Edit wp-admin/menu.php to remove the "Themes" submenu.
- [x] Edit wp-content/themes/twentytwentyfour/functions.php to register the 'team' custom post type.
- [x] Add taxonomy 'team_category' for categories.
- [x] Add taxonomy 'team_type' with terms 'employee' and 'staff'.
- [x] Test the changes by accessing wp-admin and verifying the menu updates.
