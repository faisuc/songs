<?php
/*
Plugin Name: Songs
Plugin URI: http://www.pluginuri.com
Description: Mardell
Version: 1.0
Author: Neil Carlo Sucuangco
Author URI: http://www.authoruri.com
*/

if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

add_action( 'init', 'wp_register_song_table', 1 );
add_action( 'switch_blog', 'wp_register_song_table' );
 
function wp_register_song_table() {
    global $wpdb;
    $wpdb->wp_songs = "{$wpdb->prefix}Songs";
}

function wp_create_tables() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;
	global $charset_collate;

	wp_register_song_table();

	$sql_create_table = "CREATE TABLE {$wpdb->wp_songs} (
          SongID bigint(20) unsigned NOT NULL auto_increment,
          user_id bigint(20) unsigned NOT NULL default '0',
          SongPosition bigint(20) unsigned NOT NULL default '0',
          SongTitle varchar(255) NOT NULL,
          SongArtist varchar(255) NOT NULL,
          SongAlbum varchar(255) NOT NULL,
          SongGenre varchar(255) NOT NULL,
          SongLyrics text NOT NULL,
          SongUrl varchar(255) NOT NULL,
          PRIMARY KEY  (SongID),
          KEY user_id (user_id)
     ) $charset_collate; ";
 
	dbDelta( $sql_create_table );
}

register_activation_hook( __FILE__, 'wp_create_tables' );


if ( is_admin() )
{

  add_action('admin_menu', 'menu_song_pages');
  function menu_song_pages(){
      add_menu_page('Songs', 'Songs', 'manage_options', 'song-menu', 'my_song_output' , '' , 6 );
      add_submenu_page('song-menu', 'Import from CSV', 'Import from CSV', 'manage_options', '' , 'importcsvview' );
  }

  function my_song_output()
  {
    global $wpdb;

    $results = $wpdb->get_results("SELECT * FROM `" . $wpdb->wp_songs . "`" , OBJECT);

    $table = isset($_GET['table']) ? $_GET['table'] : 'SongID';
    $sort  = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';

    $query             = "SELECT * FROM `" . $wpdb->wp_songs  . "`";
    $total_query     = "SELECT COUNT(1) FROM (${query}) AS combined_table";
    $total             = $wpdb->get_var( $total_query );
    $items_per_page = 50;
    $page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset         = ( $page * $items_per_page ) - $items_per_page;
    $results         = $wpdb->get_results( $query . " ORDER BY $table $sort LIMIT ${offset}, ${items_per_page}" );
    $totalPage         = ceil($total / $items_per_page);

    $url = $_SERVER['REQUEST_URI'];

    if ( isset($_GET['table']) && isset($_GET['sort']) )
    {
      $col1Sorting = $_GET['table'] == "SongID" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col2Sorting = $_GET['table'] == "SongPosition" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col3Sorting = $_GET['table'] == "SongTitle" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col4Sorting = $_GET['table'] == "SongArtist" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col5Sorting = $_GET['table'] == "SongAlbum" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col6Sorting = $_GET['table'] == "SongGenre" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col7Sorting = $_GET['table'] == "SongLyrics" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
      $col8Sorting = $_GET['table'] == "SongUrl" && $_GET['sort'] == "ASC" ? "DESC" : "ASC";
    }

    require_once dirname( __FILE__ ) . '/admin/admin.php';
  }

  function importcsvview()
  {
    require_once dirname( __FILE__ ) . '/admin/import-csv.php';
  }

  add_action( 'init' , 'importcsv' );

  function importcsv()
  {
    ini_set('auto_detect_line_endings', true);
    if ( isset($_POST['importcsv']) )
    {

      $filename = $_FILES['csv']['name'];
      $lists = explode("." , $filename);
      $ext = $lists[1];

      if(isset($_FILES['csv']['name']) && !empty($_FILES['csv']['name']) && $ext == "csv")
      {

        global $wpdb;

        $DIR = dirname( __FILE__ ) . "/csv/";

        $newFileName = time() . "_" . $_FILES['csv']['name'];
        if (move_uploaded_file($_FILES['csv']['tmp_name'], $DIR . $newFileName))
        {

          $row = 1;
          if (($handle = fopen($DIR . $newFileName, "r")) !== FALSE) {
              while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                  
                  $songPosition = $data[1];
                  $songTitle    = $data[2];
                  $songArtist   = $data[3];
                  $songAlbum    = $data[4];
                  $songGenre    = $data[5];
                  $songLyrics   = $data[6];
                  $songUrl      = $data[0];

                  $wpdb->insert(
                    $wpdb->wp_songs ,
                    array(
                      'SongPosition'  => $songPosition ,
                      'SongTitle'     => $songTitle ,
                      'SongArtist'    => $songArtist ,
                      'SongAlbum'     => $songAlbum ,
                      'SongGenre'     => $songGenre ,
                      'SongLyrics'    => $songLyrics ,
                      'SongUrl'       => $songUrl
                    ) ,
                    array(
                      '%s' ,
                      '%s' ,
                      '%s' ,
                      '%s' ,
                      '%s' ,
                      '%s' ,
                      '%s'
                    )
                  );


              }
              fclose($handle);
          }

        }

      }
      else
      {
        $_POST['message'] = "Please upload a csv file";
      }
    }
  }

}