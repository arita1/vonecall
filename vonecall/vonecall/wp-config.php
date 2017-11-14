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

define('FS_METHOD', 'direct');
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vonecall_db');

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
define('AUTH_KEY', 'xtYW7{q#TTlDfnp1g.o2oMkK}1U-?3![2h*tcP|RAMU;QW8t%iTh|!]oP>S8m=XN');
define('SECURE_AUTH_KEY', '<I]@)*,=abr}e-Q8aHsyf`.<&wb)!`hPQe4_JI<>D?Lf =1?h52<EZ{hj1_fr;v]');
define('LOGGED_IN_KEY', '=&v2KAk7[5{QyeBQCjSsY}i.[Q,c8F $Nqq:UpQq4i}o7@yG(sK:MkG2v[yOv&?V');
define('NONCE_KEY', '2D[IY6?4iC4e)VxFE?&Q}Pl,3[3e{B2=<:YnmuE@_<=Oy+mg#w096vfns)kA0dQf');
define('AUTH_SALT', '++4B]GY>B]u-T;TAv/2&mNkY*A5op%-w6bg-oBaPue}uy9T7.`oo*)]uk$u2CcBQ');
define('SECURE_AUTH_SALT', '!!$J2|*JLr*o8~)6`5{|YM6QqMeQ>E-:xu[x$ SbeTaKw0BtoVEi@(5Rwh7>(O[G');
define('LOGGED_IN_SALT', '+J*4cRssD_L2}dd|V^jl`<:g8=joO_[+w*q+(!XDc-=t`li,f{{|k~D0o*Y1G7Lc');
define('NONCE_SALT', '_BRiU+G/Q/UEW}:G?xT@%Y`SN!|z#J0}@HJ!6Y`H}-X_t#oCj^d3y7B}OWkT|[Q0');

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_SITEURL', 'http://localhost/vonecall_backup/vonecall/');
define('pinlessMode', 'live');
define('pinlessUsername', 'vonecall');
define('pinlessPassword', 'vc@@!!557Ee1');
define('OLDER_DB', 'POST3');
/***************pinless customer ************/
define('customerID', '34'); // Vonecall Pinless Customer 
define('product', '27'); //Vonecall Pinless Product    
define('callingCardCustomerID', '45'); // Vonecall Calling card customer ID



define('smtp_host' , 'smtp.gmail.com');
define('smtp_user' , 'rydtechnologies45@gmail.com');
define('smtp_pass' , 'ryd@gm#0912');
define('smtp_port' , 25);
define('smtp_from' , 'rydtechnologies45@gmail.com');
define('site_email' , 'info@vonecall.com');
define('site_name' ,'Vonecall');

define('textUsername' ,'rydadmin');// textmode username
define('textPassword' ,'rydadmin6');// textmode password

/**********USER PROFILE IMAGE UPLOAD PATH ***********/
define('WP_PROFILE_UPLOAD' , '../wp-content/uploads/user_profile');