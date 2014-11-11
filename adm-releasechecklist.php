<?php
/*
Plugin Name: Admium Release Checklist
Plugin URI: www.admium.nl
Description: Checks a list of Wordpress settings
Author: Admium
Version: 0.5
Author URI: www.admium.nl
License: GPLv2 or later
GitHub Plugin URI: AdmiumNL/adm-releasechecklist
*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Only show to admin users
if ( is_admin() ){
    
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
        		
        		<tr valign="middle">
            		<th scope="row">Titel homepage</th>
            		<td>
            		    <?php 
                		    
                		    $title = get_the_title(get_option('page_on_front'));
                		    
                		    if (preg_match("/Homepagina/", $title) || preg_match("/Homepage/", $title) || preg_match("/Voorpagina/", $title)){
                                echo "<span class='error'>Titel van homepage is niet correct ingesteld: ". $title ."</span>";
                            } else {
                                echo "<span class='valid'>Titel is herschreven en lijkt correct: " . $title . "</span>";
                            }
    
            		    ?>
            		</td>
        		</tr>
        		
        		<tr valign="middle">
            		<th scope="row">Wachtwoord op website</th>
            		<td>
            		    <?php 
                        
                            $htaccess = explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.htaccess"));
                            $protected = false;
                            foreach($htaccess as $item){
                                if (trim($item) == "AuthType Basic"){
                                    $protected = true;
                                }
                            }
                            
                            if ($protected){
                                echo "<span class='error'>Website is beveiligd met .htaccess gebruiker, verwijder het blok uit de .htaccess file</span>";
                            } else {
                                echo "<span class='valid'>Website is niet beveiligd met .htaccess gebruiker</span>";
                            }
    
            		    ?>
            		</td>
        		</tr>
        		
        		<tr valign="middle">
            		<th scope="row">Redirect domein naar www.</th>
            		<td>
            		    <?php 
                        
                            $homeURL = preg_replace("/https?:\/\//", "", get_option('siteurl'));
                        
                            $htaccess = explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.htaccess"));
                            $correctRedirect = false;
                            foreach($htaccess as $item){
                                if (trim($item) == "RewriteCond %{HTTP_HOST} !^".$homeURL."$ [NC]"){
                                    $correctRedirect = true;
                                }
                            }
                            
                            if (!$correctRedirect){
                                echo "<span class='error'>Website redirect niet correct naar hoofddomein (".$homeURL."), pas aan in .htaccess</span>";
                            } else {
                                echo "<span class='valid'>Website bevat correcte redirect naar hoofddomein</span>";
                            }
    
            		    ?>
            		</td>
        		</tr>		
        		
        		<tr valign="middle">
            		<th scope="row">E-mail voor beheerdoeleinden</th>
            		<td>
            		    <?php 
                        
                            $website_email = get_bloginfo('admin_email');
                            if ($website_email == 'wordpress@admium.nl'){
                                echo "<span class='error'>E-mail gaat naar: wordpress@admium.nl</span>";
                            } else {
                                echo "<span class='valid'>E-mail gaat naar: " . $website_email . "</span>";
                            }
    
    
            		    ?>
            		</td>
        		</tr>		
    	
        		<tr valign="middle">
            		<th scope="row">Ontwikkelomgeving</th>
            		<td>
            		    <?php 
                        
                            $amount = 0;
                        
                            global $wpdb;
                            $result = $wpdb->get_var( "SELECT count(*) as `amount` FROM `wp_posts` WHERE `post_content` LIKE '%admiumdev.nl%'" );
                            $amount += $result;
                            $result = $wpdb->get_var( "SELECT count(*) as `amount` FROM `wp_postmeta` WHERE `meta_value` LIKE '%admiumdev.nl%'" );
                            $amount += $result;
                            $result = $wpdb->get_var( "SELECT count(*) as `amount` FROM `wp_options` WHERE `option_value` LIKE '%admiumdev.nl%'" );
                            $amount += $result;
                            
                            if ($amount > 0){
                                echo "<span class='error'>Website linkt naar admiumdev.nl (draai verhuisscript)</span>";
                            } else {
                                echo "<span class='valid'>Website linkt niet naar admiumdev.nl</span>";
                            }
    
            		    ?>
            		</td>
        		</tr>		
        		
        		<tr valign="middle">
            		<th scope="row">Post eigenaren</th>
            		<td>
            		    <?php 
                        
                            if (count(query_posts('author=1&order=ASC')) > 0){
                                echo "<span class='error'>Er bestaan blogberichten met auteur Admium (pas dit aan naar account van klant)</span>";
                            } else {
                                echo "<span class='valid'>Er bestaan geen blogberichten met auteur Admium</span>";
                            }
                        
            		    ?>
            		</td>
        		</tr>		
        		
        		<tr valign="middle">
            		<th scope="row">Site indexeren door zoekmachines</th>
            		<td>
            		    <?php 
                        
                            if (get_option('blog_public') == 0){
                                echo "<span class='error'>De website wordt niet geindexeerd door zoekmachines!</span>";
                            } else {
                                echo "<span class='valid'>Zoekmachines mogen de website indexeren</span>";
                            }
                        
            		    ?>
            		</td>
        		</tr>		


        	</table>
        				
            <hr/>
        
        </div>
        
    <?php
    }

}