<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */


/*** FTP login settings ***/
define("FTP_HOST", "localhost");
define("FTP_USER", "root");
define("FTP_PASS", "Rikaki1492");
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
define('DB_NAME', 'mangacom_10manga');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Rikaki1492');

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
define('AUTH_KEY',         'SC%Eg[Zi0U];IYIyymT%U]v>.K;=?Qrm!gRKAVdJ6)I4*gAB%^W#PLv?=7g/14la');
define('SECURE_AUTH_KEY',  '$}V&d$=_d%Iu?)8_~pe -{@x2`Yp+J4P^vSMq;x%BFRTs!8=726f2Gox6-*Fb7eZ');
define('LOGGED_IN_KEY',    'VemC:_A5_BLT<b&]n],cSdbjg}3A6q-G0mZ:q:Or^?[gl%tJ&Asa)i-KkfW#Fl=M');
define('NONCE_KEY',        'I]X)UZivU,<)?3]f5u }aus4(iEnJQyPaXD.>25>kUa*6?,<fl}t $hp1A$VAa^e');
define('AUTH_SALT',        'iRf8[PUhN&lqx.:}_TbnE6Vdjq+[utpbW+~ne3%1<F{tpR5:]XwWLEqrf5n{D-Q.');
define('SECURE_AUTH_SALT', 'Jx4oXHY,Ud4Z$=b0Py01}zcj.>rhRJp<bd:erZlvdiT1a){db~: cWYDRK.]+TxN');
define('LOGGED_IN_SALT',   'ZT#U2N75uL{}|?%Ci(f,#hP:6-KQ2970r1H;!~&%;lLkdeU1]5f2wAG^]c`6dGvg');
define('NONCE_SALT',       '$DgLe9h)0(wlaKE;$,`Dzvkj@)ox(-9F?xm:8B/4uxU,(v5t42xt)Qo_Cw+V;*sS');

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
define('WP_CACHE_KEY_SALT', 'example.com');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
