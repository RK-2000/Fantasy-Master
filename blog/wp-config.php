<?php

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL
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
define( 'DB_NAME', 'blog' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'zJ?-d3U!gC*^Hfc#ns=f4x$nL%3s^z' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'H[6SJ~fn7hZ7ht.F)$dx9EO#?bnx:&R:,uR[wH9Kz93H+_nu|{E6GHG[3/K^/o K' );
define( 'SECURE_AUTH_KEY',  '?^nm@}%/2~@(n(2*$ya%7S!6!$.|x|c dp L,R&=#M<{f<o:qOF:eTL[N-RWrU3]' );
define( 'LOGGED_IN_KEY',    '-.6_$#B7wEtqTeq&(vF5 Q|&rB&!#E=@`!4 ?99,AG;f1j/uA@cMg|&C4G Fw6jn' );
define( 'NONCE_KEY',        '7kw]idC!/u18(jIu~dFX^:&|eYIz@g<^i@cqcgtud.^dC^ebynIyIzxoLNhP9%p]' );
define( 'AUTH_SALT',        '9=NQusqm*iSMl&qHq.`/x|ylR @j*(D[{G+<lBE]8KaCP EY IB6V1wSKOj-`Bbi' );
define( 'SECURE_AUTH_SALT', 'W.RLx5|^:jxT`+.UYNNd/-&rG;jJ2V@9joJ V5+n%a^&<f.s27Hfj|!E~dvW0&tE' );
define( 'LOGGED_IN_SALT',   'ZP0>9$krEi)JtsPAb*{B^Y&^_Ytlr?pcgrEFuGd;c+Xr9g|(=qSKIWK-8oJJ>gZ+' );
define( 'NONCE_SALT',       'w_i&r7HX8l[by-Oo<!=)p0^M`9t17@6C0zO/TCc^yBH9YY@iRq4lS}[>GP&z}o28' );

/**#@-*/

define('FS_METHOD', 'direct');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mw_blog_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
