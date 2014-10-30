<?php
/*
Plugin Name: Admium Release Checklist
Plugin URI: www.admium.nl
Description: Checks a list of Wordpress settings
Author: Admium
Version: 1.0
Author URI: www.admium.nl
License: GPLv2 or later
GitHub Plugin URI: AdmiumNL/adm-releasechecklist
*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function adm_releasechecklist_menu() {
	add_menu_page( 'Admium Checklist', 'Admium Checklist', 'manage_options', 'adm-releasechecklist', 'adm_releasechecklist_options' );
}
add_action( 'admin_menu', 'adm_releasechecklist_menu' );

function adm_releasechecklist_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
		
	?>

	<style>
    	.valid
    	{
            color:green;
    	}
    	.error
    	{
        	color:red;
    	}
    </style>

    <div class="wrap">
    
        <h2>Admium Checklist</h2>
    
        <div>
        	<h4><?php _e("What does this plugin do?",'adm-releasechecklist'); ?></h4>
    		<p><?php _e("This plugin checks a predefined list of Wordpress settings for correctness. If one of these settings is set incorrectly the site should not be released to the client.", 'adm-releasechecklist'); ?></p>
        </div>
        
        <br style="clear:both" />
        
        <h2>Settings</h2>
    
    	<table class="form-table">
    		<tr valign="middle">
        		<th scope="row">Google Analytics code</th>
        		<td>
        		    <?php 
                        if ( ! class_exists( 'Yoast_GA_Options' ) ) {
                        	echo "<span class='error'>Plugin niet geinstalleerd</span>";
                        } else {
                            
                            global $yoast;
                            $yoast = new Yoast_GA_Options;
                            $ua_code = $yoast->get_tracking_code();
                            if ($ua_code == "UA-000000-0") {
                                echo "<span class='error'>Incorrecte code (". $ua_code .")</span>";
                            } else {
                                echo "<span class='valid'>Correcte code (". $ua_code .")</span>";
                            }
                            
                        }
        		    ?>
        		</td>
    		</tr>		
    		
    		<tr valign="middle">
        		<th scope="row">Salt & peper</th>
        		<td>
        		    <?php 
                    
                        if (AUTH_KEY == "put your unique phrase here"){
                            echo "<span class='error'>Voer een unieke salt & peper in wp-config.php</span>";
                        } else {
                            echo "<span class='valid'>Willekeurig ingesteld (pas aan als site een kloon is)</span>";
                        }

        		    ?>
        		</td>
    		</tr>		

    		<tr valign="middle">
        		<th scope="row">Website naam</th>
        		<td>
        		    <?php 
                    
                        $website_name = get_bloginfo('name');
                        if ($website_name == '[ADMIUM_SITE_TITLE]'){
                            echo "<span class='error'>Voer de juiste naam in voor de website titel, is nu: ". $website_name ."</span>";
                        } else {
                            echo "<span class='valid'>Naam van de website is: " . $website_name . "</span>";
                        }

        		    ?>
        		</td>
    		</tr>		

    		<tr valign="middle">
        		<th scope="row">Website beschrijving</th>
        		<td>
        		    <?php 
                    
                        $website_description = get_bloginfo('description');
                        if ($website_description == '[ADMIUM_SITE_DESCRIPTION]'){
                            echo "<span class='error'>Voer de juiste beschrijving in voor de website, is nu: ". $website_description ."</span>";
                        } else {
                            echo "<span class='valid'>Beschrijving van de website is: " . $website_description . "</span>";
                        }

        		    ?>
        		</td>
    		</tr>		

    		
    	</table>
    				
        <hr/>
    
    </div>
    
<?php
}