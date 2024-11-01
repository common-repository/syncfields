<?php
/*
Plugin Name: SyncFields
Plugin URI: https://wordpress.org/plugins/syncfields/
Description: Map and Sync User Custom Meta Fields.
Version: 2.1
Author: PJFC
Author URI: https://profiles.wordpress.org/pjfc/
License: GPL2
*/

// Die if file is called directly)
if (!defined('ABSPATH')) die();

define( 'MSF_PLUGIN_URL', WP_PLUGIN_URL . '/syncfields');

$dir = plugin_dir_path( __FILE__ );

define( 'MSF_PLUGIN_PATH',  $dir);


add_action('admin_menu','msf_admin_menu');

add_action('profile_update', 'msf_action_callback');

add_action( 'wp_ajax_msf_action', 'msf_action_callback' );

add_action('msf_schedule_event', 'msf_action_callback');

add_action('edit_user_profile_update', 'msf_action_callback');

add_action('upme_profile_update', 'msf_action_callback');

add_action('woocommerce_save_account_details', 'msf_action_callback');



function msf_action_callback() {
	global $wpdb; 

	$blogusers = get_users();

	foreach ( $blogusers as $user ) {

		$table = msf_get_fields();

			if ($table){

				foreach ($table as $field){

					$p_field = get_user_meta( $user->ID, $field->p_field, $single=true ); 
	
	         		update_user_meta($user->ID, $field->s_field, $p_field);
	
				}

		}
	}

}
 
 function sync_extra_profile_fields($user_id) {

      $table = msf_get_fields();


		if ($table){

			foreach ($table as $field){

         		update_user_meta($user_id, $field->s_field, $_POST[$field->p_field]);

			}

		}
 }


function msf_admin_menu(){
			//$iconImage = MSF_PLUGIN_URL . '/icons/map-sync.png';
			add_menu_page('SyncFields', 'SyncFields', 'manage_options','syncfields', 'msf_page');
			add_submenu_page('syncfields', 'SyncFields', 'Settings', 'manage_options','syncfields','msf_page');
            
}

function msf_get_fields() {
    global $wpdb;
	$table_msf	=  $wpdb->prefix . "msf_fileds";
    $msf_fields = $wpdb->get_results("SELECT * FROM {$table_msf} ORDER BY id ASC");
    return $msf_fields;
}
/**Get an specific row from the table wp_msf_software**/
function msf_get_field($id) {
    global $wpdb;
	$table_msf	=  $wpdb->prefix . "msf_fileds";

    $the_field = $wpdb->get_results("SELECT * FROM {$table_msf} WHERE id='".$id."'");
    if(!empty($the_field[0])) {
        return $the_field[0];
    }
    return;
}
function msf_fields_meta_box() {
    global $edit_msf_field;
?>

<p>Primary Field :

<select name="msf_p_field" id="msf_p_field">
				<option value="">
					<?php _e('Choose a Meta Key','upme'); ?>
				</option>
				<optgroup label="-------------">
					<?php
					$current_user = wp_get_current_user();
					if( $all_meta_for_user = get_user_meta( $current_user->ID ) ) {

					    ksort($all_meta_for_user);

					    foreach($all_meta_for_user as $user_meta => $array) {
					        if($user_meta!='_upme_search_cache')
					        {
					        $msf_p_field_select = ($user_meta == $edit_msf_field->p_field) ? 'selected=selected' : '';

					        ?>
					<option value="<?php echo $user_meta; ?>"  <?php echo $msf_p_field_select; ?>>
						<?php echo $user_meta; ?>
					</option>
					<?php
					        }
					    }
					}
					?>
				</optgroup>
		</select>
</p> 
    <p>Mapped Field: <select name="msf_s_field" id="msf_s_field">
				<option value="">
					<?php _e('Choose a Meta Key','upme'); ?>
				</option>
				<optgroup label="-------------">
					<?php
					$current_user = wp_get_current_user();
					if( $all_meta_for_user = get_user_meta( $current_user->ID ) ) {

					    ksort($all_meta_for_user);

					    foreach($all_meta_for_user as $user_meta => $array) {
					        if($user_meta!='_upme_search_cache')
					        {
					  $msf_s_field_select = ($user_meta == $edit_msf_field->s_field) ? 'selected=selected' : '';
					        ?>
					<option value="<?php echo $user_meta; ?>" <?php echo $msf_s_field_select; ?>>
						<?php echo $user_meta; ?>
					</option>
					<?php
					        }
					    }
					}
					?>
				</optgroup>
		</select></p>
  
<?php
}
function msf_fields(){
    global $wpdb;
	$table_msf	=  $wpdb->prefix . "msf_fileds";

    /**Delete the data if the variable "delete" is set**/
    if(isset($_GET['delete'])) {
        $_GET['delete'] = absint($_GET['delete']);
        $wpdb->query("DELETE FROM {$table_msf} WHERE id='" .$_GET['delete']."'");
    }
    /**Process the changes in the custom table**/
    if(isset($_POST['msf_add_field']) and isset($_POST['msf_p_field']) and isset($_POST['msf_s_field'])  ) {   
        /**Add new row in the custom table**/
        $msf_p_field = $_POST['msf_p_field'];
        $msf_s_field = $_POST['msf_s_field']; 
        $id = $_POST['id'];

        if(empty($_POST['id']) or $id == 0) {
            $wpdb->query("INSERT INTO {$table_msf} (p_field,s_field) VALUES('" .$msf_p_field ."','" .$msf_s_field."');");
        } else {
        /**Update the data**/
            $id = $_POST['id'];
            $wpdb->query("UPDATE {$table_msf} SET p_field='" .$msf_p_field ."', s_field='" .$msf_s_field ."'  WHERE id='" .$id ."'");
			$admin_url = get_admin_url() .'admin.php?page=syncfields';
        }
    }  
}
function msf_add_field(){
    $id =0;
    if($_GET['id']) $id = $_GET['id'];
    /**Get an specific row from the table wp_msf_software**/
    global $edit_msf_field;
    if ($id) $edit_msf_field = msf_get_field($id);  
    /**create meta box**/
    add_meta_box('msf-fields-meta', __('msf fields'), 'msf_fields_meta_box', 'bor', 'normal', 'core' );
?>

    <div class="wrap">
      <div id="faq-wrapper">
        <form method="post" action="">
          <h2>
          <?php if( $id == 0 ) {
                $tf_title = __('Add new mapping');
          }else {
                $tf_title = __('Edit mapping');
          }
          echo $tf_title;
          ?>
          </h2>
          <div id="poststuff" class="metabox-holder">
            <?php do_meta_boxes('bor', 'normal','low'); ?>
          </div>
          <input type="hidden" name="id" value="<?php echo $id; ?>" />
          <input type="submit" value="<?php echo $tf_title;?>" name="msf_add_field" id="msf_add_field" class="button-secondary">
        </form>
      </div>
    </div>
<?php
}

function msf_manage_fields(){
?>
<script type="text/javascript">

jQuery(document).ready(function($) {
	$("#sync_all_fields").click(function() {
	
		    var plug_url = "<?php echo MSF_PLUGIN_URL; ?>";
			
			$('.saved').html('<img src="'+plug_url+'/images/loading1.gif" title="loading" style="padding-left: 15px;">');
			var data = {
				'action': 'msf_action',
			};
		 $.post(ajaxurl, data, function(response) {
			$('.saved').html('');
			});
	
	});
});
</script>

<div class="wrap">
  <div class="icon32" id="icon-edit"><br></div>
  <form method="post" action="" id="msf_form_action">


<p>
  	Mapped fields will run on every update of a field.<br /><br />
	You can additionally enforce a manual sync via the button "Manual sync all mappings" below.<br /><br />
</p>

<hr>
<h1><small>Field mappings</small></h1>
	Click 'add new mapping' to create a mapping which will sync two fields with each other.<br />

  <p>	<select name="msf_action">
            <option value="actions"><?php _e('Select action')?></option>
            <option value="delete"><?php _e('Delete')?></option>
      </select>
      <input type="submit" name="msf_form_action_changes" class="button-secondary" value="<?php _e('Apply')?>" />
        <input type="button" class="button-secondary" value="<?php _e('Add New Mapping')?>" onclick="window.location='?page=syncfields&amp;edit=true'" />
        <input type="button" id="sync_all_fields" class="button-secondary" value="<?php _e('Manual Sync All Mappings')?>" />
		<span class="saved"></span>
    </p>
    <table class="widefat page fixed" cellpadding="0">
      <thead>
        <tr>
        <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
          <input type="checkbox"/>
        </th>
          <th class="manage-column"><?php _e('Primary Field')?></th>
          <th class="manage-column"><?php _e('Synced Field')?></th>
        </tr>
      </thead>
      <tfoot>
        <tr>
        <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
          <input type="checkbox"/>
        </th>
          <th class="manage-column"><?php _e('Primary Field')?></th>
          <th class="manage-column"><?php _e('Synced Field')?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php
          $table = msf_get_fields();
          if($table){
           $i=0;
           foreach($table as $field) {
               $i++;
        ?>
      <tr class="<?php echo (ceil($i/2) == ($i/2)) ? "" : "alternate"; ?>">
        <th class="check-column" scope="row">
          <input type="checkbox" value="<?php echo $field->id; ?>" name="id[]" />
        </th>
          <td>
          <strong><?php echo $field->p_field; ?></strong>
          <div class="row-actions-visible">
          <span class="edit"><a href="?page=syncfields&amp;id=<?php echo $field->id; ?>&amp;edit=true">Edit</a> | </span>
          <span class="delete"><a href="?page=syncfields&amp;delete=<?php echo $field->id; ?>" onclick="return confirm('Are you sure you want to delete this mapping?');">Delete</a></span>
          </div>
          </td>
          <td><?php echo $field->s_field; ?></td>
        </tr>
        <?php
           }
        }
        else{  
      ?>
        <tr><td colspan="4"><?php _e('There is no data.')?></td></tr>  
        <?php
      }
        ?>  
      </tbody>
    </table>
    <p>
        <select name="msf_action-2">
            <option value="actions"><?php _e('Select action')?></option>
            <option value="delete"><?php _e('Delete')?></option>
        </select>
        <input type="submit" name="msf_form_action_changes-2" class="button-secondary" value="<?php _e('Apply')?>" />
    </p>
<?php 

$map_cron_schedule = get_option('map_cron_schedule'); 
 $cron_schedule0 = ($map_cron_schedule == 'none') ? 'selected=selected' : '';
 $cron_schedule1 = ($map_cron_schedule == 'hourly') ? 'selected=selected' : '';
 $cron_schedule2 = ($map_cron_schedule == 'twicedaily') ? 'selected=selected' : '';
 $cron_schedule3 = ($map_cron_schedule == 'daily') ? 'selected=selected' : '';


?>
<hr>
<h1><small>Cron Scheduling (optional)</small></h1>
	If automatic Sync fails in your install, try to set a cron schedule below. (It should normally not be required, because mapped fields are synced upon every update).<br />
<p>
	<select name="cron_shedule">
            <option value="none" <?php echo $cron_schedule0; ?>><?php _e('None')?></option>
            <option value="hourly" <?php echo $cron_schedule1; ?>><?php _e('Hourly')?></option>
            <option value="twicedaily" <?php echo $cron_schedule2; ?>><?php _e('Twice Daily')?></option>
            <option value="daily" <?php echo $cron_schedule3; ?>><?php _e('Daily')?></option>
      </select>
      <input type="submit" name="cron_changes" class="button-secondary" value="<?php _e('Apply')?>" />
</p>

  </form>
</div>
<?php
}


function msf_page () { 

?>
		
<form action="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=syncfields" method="post" id="msf_url_shortner">
<input type="hidden" name="action" value="msf_url_shortner" />
<?php
		echo '
<div class="container-fluid">

      <div class="well well-1">
        <div class="page-header page-header-2">
          <h1> <small>SyncFields</small> 
          </h1>
        </div>';

	$schedule =  $_POST['cron_shedule']; 
		if(isset($schedule )){

			if ($schedule == 'none'){
				wp_clear_scheduled_hook('msf_schedule_event');
				update_option('map_cron_schedule', $schedule);
			}else{
				wp_clear_scheduled_hook('msf_schedule_event');
				wp_schedule_event(time(), $schedule, 'msf_schedule_event');
				update_option('map_cron_schedule', $schedule);
			}
		}
  	echo '<div class="row-fluid">';
				 msf_fields();
		    if (empty($_GET['edit'])) {
		      /**Display the data into the Dashboard**/
		        msf_manage_fields();
		    } else {
		      /**Display a form to add or update the data**/
		        msf_add_field();  
		    }
		echo '</div></div></div>';
}

function msf_activate() {

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
global $wpdb;

$table_msf	=  $wpdb->prefix . "msf_fileds";


$table_msf="CREATE TABLE IF NOT EXISTS " . $table_msf . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `p_field` text NOT NULL,
  `s_field` text NOT NULL,
  PRIMARY KEY (`id`)
);";


dbDelta($table_msf);

    // Activation code here...
}
register_activation_hook( __FILE__, 'msf_activate' );