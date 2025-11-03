<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'x`Y;F]RH&1_Fy1l^=<MsTY^Kb)g/WiNcvv2rCX!OS(=8,!W/=cv&mA>I1TCz/GL*' );
define( 'SECURE_AUTH_KEY',  'BW/Gv#O*M8>9jTv!nxE!n.s4sw#MeD;H@sQxf%8l^.MBz{D`3c+]5`yvDlziFH8S' );
define( 'LOGGED_IN_KEY',    '!4);R_3_`1e8}wXm6u%{TK_|vZVy~LQsJ0^:<DC9v:^S(OQ)eEKS}5e|#SPIkMc{' );
define( 'NONCE_KEY',        'nh+!.-Urf8=></)Gu4p7Sgg><[Kd1@ca3$R Inayik;H1~W;IF{h5I5&NT-^r_%g' );
define( 'AUTH_SALT',        '&[${-EYJ7B5viu<o.5wje+ -|(RaQ~GD!oY8Y4yudPA>qbP-bs[ c[Dj:1^XOQ_q' );
define( 'SECURE_AUTH_SALT', 'uGXe0F/tAE2?JDFd}g0(PTcIHEL*j3!)X%Ko)-_-z9k0|+C1[jP:s{(6gPCVW!z1' );
define( 'LOGGED_IN_SALT',   'VbJp!ab!uGu=mIDZf^wR%smQ_xxs%Rmo.R:&<y&PzSYou~i0sfG5COuq;9%`fNbL' );
define( 'NONCE_SALT',       'l8Z!~NV]1CqW]vPlIC%T!gJKGkGFWB~FtiYtQas6AN|NbZgDW#T&fPX>X6^2qleG' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
