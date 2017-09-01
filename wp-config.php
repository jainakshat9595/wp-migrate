<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'testpress_dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1234');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'J0_{K]xv;c,}!kc4y%>4*1BvKh)~%@rK,e.>sF-E#C1@agq:P tT1%Q _plm7yAD');
define('SECURE_AUTH_KEY',  ']4E__2KT166d}@r5a/3+2NnMI:n?js>k|q.j,TOUP~TO2Qr6m`Y,2CDTxbOiA`J+');
define('LOGGED_IN_KEY',    'o#Vg?$#@TvkZBZ1RQ*c)(f4P;Cbm(oa=<Q]NyY*;dtyhFY}7VBr6fRga(X1]Jz0t');
define('NONCE_KEY',        'FD,gcHj7]p M4,a2M,x@=^M0a|Jqx}uM81+bg5rhvTKA+i0@D)E~,`Nf_h]^W #}');
define('AUTH_SALT',        'Y2FDP2?SlE${|<dnOAtN[C0%H7Y|`2J6*PkHjzawJR%x8=EZ(laR/UQ5~n$I6P<n');
define('SECURE_AUTH_SALT', 'XQew0d nvjqg l-GupRybPq$nisVLm@vgZEVI}=0sl[TJ],DQb+G]mO|9V&)_+0g');
define('LOGGED_IN_SALT',   '!E|s5D]CvS@[3vEa>!jW&:0].Lf2.S-K>~ }D!^Hi`CMRIl+;Yf/?=Te:xpdaIXz');
define('NONCE_SALT',       'gRy`e]35_M}+oqAU?[+]~LWb6ENZlZSN3KW1Wl`6<r/Q,3]Q1;/2=IL=2LHS!V+I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
