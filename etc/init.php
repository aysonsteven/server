<?php


/**
 * --------------------- Database Connection -----------------------------
 */
$_sqlite_db = new Database('./var/db/','database.db');
// $_sqlite_db->query("CREATE TABLE user ( id TEXT , idx INTEGER PRIMARY KEY ,  password TEXT(32), email VARCHAR );");
// $_sqlite_db->query("CREATE TABLE questions ( id INTEGER PRIMARY KEY , question TEXT , choice1 VARCHAR ,  choice2 VARCHAR, chocie3 VARCHAR, choice4 VARCHAR, answer INTEGER(1), user_id VARCHAR, created VARCHAR, updated VARCHAR );");
function db() {
    global $_sqlite_db;
    return $_sqlite_db;
}

/**
 * --------------------- User Login --------------------------
 */
$_current_user = []; // This holds logged in user's record. @WARNING: You may need to reload it from DB some time.
if ( in('session_id') ) {
    list( $idx_user, $token ) = explode('-', in('session_id'), 2);
    if ( empty($idx_user) || empty($token) ) json_error( -40093, 'session-id-malformed');
    $_user = user()->get( $idx_user );
    if ( empty($_user) ) json_error( -40091, "user-not-exist-by-that-session-id");
    $_session_id = get_session_id( $_user['id'] );
    if ( $_session_id == in('session_id') ) { // Login OK.
        $_current_user = $_user;
    }
    else { // Login failed.
        json_error(-40097, "wrong-session-id");
    }
}
/**
 * When user is not logged in ( or has no session_id ), the user is using 'anonymous' account.
 */
// else {
//     $_current_user = user()->get( 'anonymous' );
//     if ( empty($_current_user) ) json_error( -40080, "anonymous-does-not-exist");
// }
