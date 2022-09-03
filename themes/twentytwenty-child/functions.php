<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {

wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );

}


function prefix_create_custom_post_type() {
    /*
     * The $labels describes how the post type appears.
     */
    $labels = array(
        'name'          => 'Cars', // Plural name
        'singular_name' => 'Car'   // Singular name
    );

    /*
     * The $supports parameter describes what the post type supports
     */
    $supports = array(
        'title',        // Post title
        'custom-fields' // Supports by custom fields
    );

    /*
     * The $args parameter holds important parameters for the custom post type
     */
    $args = array(
        'labels'              => $labels,
        'description'         => 'Post type post car', // Description
        'supports'            => $supports,
        'taxonomies'          => array(  ), // Allowed taxonomies
        'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
        'public'              => true,  // Makes the post type public
        'show_ui'             => true,  // Displays an interface for this post type
        'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
        'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
        'show_in_admin_bar'   => true,  // Displays in the black admin bar
        'menu_position'       => 2,     // The position number in the left menu
        'menu_icon'           => true,  // The URL for the icon used for this post type
        'can_export'          => true,  // Allows content export using Tools -> Export
        'has_archive'         => true,  // Enables post type archive (by month, date, or year)
        'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
        'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
        'capability_type'     => 'post' // Allows read, edit, delete like “Post”
    );

    register_post_type('car', $args); //Create a post type with the slug is ‘product’ and arguments in $args.
}
add_action('init', 'prefix_create_custom_post_type');

function shortcode_cars_post_type($attr){  
    $args = array(
        'post_type'      => 'car',
        'publish_status' => 'published',
    );  
    $query = new WP_Query($args);
	
	$result .= '<h3>Cars list</h3>';
	
	if($attr['showfilters']==1){
		$results_fuel = array();
		$results_manufacturer = array();
		$results_color[] = array();
		if($query->have_posts()) :  
			while($query->have_posts()) :  
				$query->the_post() ;			
				$results_fuel[]= get_field( "fuel" );  
				$results_manufacturer[]= get_field( "manufacturer" );
				$results_color[]= get_field( "color" );
			endwhile;
			wp_reset_postdata();
		endif; 
		$results_fuel= array_unique($results_fuel);
		$results_manufacturer= array_unique($results_manufacturer);
		$results_color= array_unique($results_color);
		
		$result .= '<form method="GET" action="">';
		$result .= '<div class="form-inline" style="display:inline-block;">';
		$result .= '<label>Fuel:</label>';
		$result .= '<select class="form-control" name="fuel" placeholder="Select a fuel:"><option value="" disabled selected>Select a fuel:</option>';
		foreach ($results_fuel as &$value) {
			$result .= '<option value="'.$value.'">'.$value.'</option>';
		}
		$result .= '</select>';
		$result .= '<label>Manufacturer:</label>';
		$result .= '<select class="form-control" name="manufacturer" placeholder="Select a manufacturer:"><option value="" disabled selected>Select a manufacturer:</option>';
		foreach ($results_manufacturer as &$value) {
			$result .= '<option value="'.$value.'">'.$value.'</option>';
		}
		$result .= '</select>';
		$result .= '<label>Color:</label>';
		$result .= '<select class="form-control" name="color" placeholder="Select a color:"><option value="" disabled selected>Select a color:</option>';
		foreach ($results_color as &$value) {
			$result .= '<option value="'.$value.'">'.$value.'</option>';
		}
		$result .= '</select>';
		$result .= '<button class="btn btn-primary" name="filter">Filter</button>';
		$result .= '<button class="btn btn-success" name="reset">Reset</button></div></form>';     
	}
    if($query->have_posts()) :  
        while($query->have_posts()) :  
			$query->the_post() ;
			if($attr['showfilters']==1 && $_GET['fuel']===null && $_GET['manufacturer']===null && $_GET['color']===null){
				$result .= '<div class="car-item" style="background-color: white;">';
				$result .= '<div class="car-name" style="text-align:center;font-weight:bold;">' . get_the_title() . '</div>'; 
				$result .= '<ul class="car-specs" style="background-color: darkgrey;margin:0;">';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "fuel" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width: 27%;margin: 0 1rem;background-color: lightgray;">' . get_field( "manufacturer" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "color" ) . '</li>';
				$result .= '</ul></div>';
			}elseif($attr['showfilters']==1 && $_GET['fuel']===get_field( "fuel" ) || $_GET['manufacturer']===get_field( "manufacturer" ) || $_GET['color']===get_field( "color" )){
				$result .= '<div class="car-item" style="background-color: white;">';
				$result .= '<div class="car-name" style="text-align:center;font-weight:bold;">' . get_the_title() . '</div>'; 
				$result .= '<ul class="car-specs" style="background-color: darkgrey;margin:0;">';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "fuel" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width: 27%;margin: 0 1rem;background-color: lightgray;">' . get_field( "manufacturer" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "color" ) . '</li>';
				$result .= '</ul></div>';
			}elseif($attr['showfilters']==0 && $attr['fuel'] == "" && $attr['manufacturer'] == "" && $attr['color'] == ""){
				$result .= '<div class="car-item" style="background-color: white;">';
				$result .= '<div class="car-name" style="text-align:center;font-weight:bold;">' . get_the_title() . '</div>'; 
				$result .= '<ul class="car-specs" style="background-color: darkgrey;margin:0;">';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "fuel" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width: 27%;margin: 0 1rem;background-color: lightgray;">' . get_field( "manufacturer" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "color" ) . '</li>';
				$result .= '</ul></div>';
			}elseif($attr['fuel'] == get_field( "fuel" ) || $attr['manufacturer'] == get_field( "manufacturer" ) || $attr['color'] == get_field( "color" )){
				$result .= '<div class="car-item" style="background-color: white;">';
				$result .= '<div class="car-name" style="text-align:center;font-weight:bold;">' . get_the_title() . '</div>'; 
				$result .= '<ul class="car-specs" style="background-color: darkgrey;margin:0;">';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "fuel" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width: 27%;margin: 0 1rem;background-color: lightgray;">' . get_field( "manufacturer" ) . '</li>';
				$result .= '<li class="car-fuel" style="text-align:center;font-weight:italic; display: inline-block;width:31%;margin: 0 1rem;background-color: lightgray;">' . get_field( "color" ) . '</li>';
				$result .= '</ul></div>';
			}
        endwhile;
        wp_reset_postdata();
    endif;    
    return $result;            
}
add_shortcode( 'cars_list', 'shortcode_cars_post_type' );
?>