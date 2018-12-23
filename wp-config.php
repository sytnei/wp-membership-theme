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
define('DB_NAME', 'wp_membership');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'k6VdE`Q8VR{#]<DpR`{1+Z/}x7.(_*,bG:ImF-*e;MzREuXe0^db9bgzrmNCeH4d');
define('SECURE_AUTH_KEY',  ']Plr(80[>k%>|ZNHJvd4_A(LgG,7Bg}hnG7}Ql{_W<?$tw^Hni`^98bHRk_$H1No');
define('LOGGED_IN_KEY',    '?B43,mu ?s5w@ZH }~6,3{Fn%a;!}<XxO2fub#62CJ;#_II}(])Zm1 TeocDP_P:');
define('NONCE_KEY',        'c:aD-`Unf/eyrBu|Qi`OqEjRs0BeR3admty@ea:qG0F$o`o=ano4lij_bKgQ@_Uv');
define('AUTH_SALT',        'j.U@2W0NH+sW9If1P1-5Y_ v]o8QC;b>Rg*O1)`>wvOLMX6M]dapzV9(rr%c,:<H');
define('SECURE_AUTH_SALT', '8  [Z,Ma*n4wWtE&8[ciGr!L>vb()HMWWENZEhjK&W` XS/:y-xYpED<ER<%}]Lw');
define('LOGGED_IN_SALT',   'Mv~QN2DH5]L/^@0>7-XQ^WvV,`o^a1bfBa#r=w]_7vWdvVMJyhIRwGUrAEfd49fW');
define('NONCE_SALT',       'zK#r<]7!ob8:YLY:J4wOt}u# jg#M|hpX>(>2iiFf]/A|/_QL1eQ0Yq7?ppGbdsD');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpmb_';

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
