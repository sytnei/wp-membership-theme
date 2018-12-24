<?php
/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage wp-membership-theme
 * @since wp-membership-theme
 */
 
	get_header();
    $page_id = get_the_ID();
?> 

	
	<div class="container">
		
		<div class="row">
			<div class="col-md-12">
				<h1>Team Members</h2>
			</div>	
		</div>	
		<div class="row">	
		
			<?php 
	            
	            $args = array(
	                "post_type" => "team-member",  
	                "posts_per_page" => -1
	            );
				
				   $teammembers = new WP_Query($args);
			?>
	                                                
	   
	          <?php if ( $teammembers->have_posts()) { ?>
	                            
	                <?php while ( $teammembers->have_posts() ) { $teammembers->the_post(); ?> 
	                	<div class="col-md-4 text-center">
		                	<center>
		                	
		                		<?php the_post_thumbnail('thumbnail'); ?>
		                		
		                		<br /> 
		                		<h3><?php the_title(); ?></h3>
		                		
		                		<?php 
		                			$position = get_post_meta( get_the_ID(), "_position", true); 
									$twitter_url = get_post_meta( get_the_ID(), "_twitter_url", true); 
									$facebook_url = get_post_meta( get_the_ID(), "_facebook_url", true); 
		                		?>	
		                		         
		                		<h4><?php echo $position; ?></h4>
		                	 
		                		<?php if($facebook_url != ""){ ?>
		                			<a href="<?php echo $facebook_url;?>" target="_blank"><i class="fa fa-facebook"></i></a> &nbsp;&nbsp;
		                		<?php } ?>
		                		
		                		<?php if($twitter_url != ""){ ?>
		                			<a href="<?php echo $twitter_url;?>" target="_blank"><i class="fa fa-twitter"></i></a>
		                		<?php } ?>
		                		
		                		<br />	 
		                		
		                		<a href="#" class="btn btn-primary btn-small btn--see-description">Read more</a>
		                		<br />
		                		<div style="display:none">
		                			<?php the_content();?>
		                		</div>
		                		
		                	</center>
	                	</div>
	                	
	                <?php } wp_reset_postdata(); ?>
	           <?php } ?>
	           
           </div>
	 
	</div>
	

<?php

	get_footer();