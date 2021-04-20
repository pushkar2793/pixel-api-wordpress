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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pixelapi' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'iK^@qF:J!7N6R*|ViqKNhJ/0G&evOgjb8w%T8eQ,$h-%H6zoO**bkn_p!*HBPv4@' );
define( 'SECURE_AUTH_KEY',  'eK {7Aw;k;sIw-7C^d57OT*TJ?g{hi/s7DLkjWb4J@cDe`C*~j>syXPkhF%tv{jj' );
define( 'LOGGED_IN_KEY',    'xxI>+mn*m0BNG?,Pc;P>A/k0SF*N!YoiXc|>^UyOz+v*/_19* zQNZO7<82p4:>5' );
define( 'NONCE_KEY',        'K*Dg]k,C%ph.WVq|IF8y0z9qvgNo)pSU:_+vT+6((?$mSl81k]`:SAdU9fbITC|T' );
define( 'AUTH_SALT',        'G,4/W:3FHdZgqSM/1ZPF(k<BJf>s|00lNb=s6q:wD=&%wvkkdOGQ,BjkLY#;[XG*' );
define( 'SECURE_AUTH_SALT', '[6j0{~,%)lP&k=z)EcHNsbgW!FU-fPE>GZlf!4eHg+`z]Ikp6#*uZ!dHaDiib&4a' );
define( 'LOGGED_IN_SALT',   'mB=o4ZHI%iT%$3)f!XoZuc%N)48_/J5xzRD4*Iq*^S&v`.T0|l<BqZP{~*qQt<F0' );
define( 'NONCE_SALT',       '=vk#hceY4#INyUztOWNDpCh=&ltw.3Nr<HfK[{0s6;/m&Uub)DpS&e[b;jX3*2`/' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
