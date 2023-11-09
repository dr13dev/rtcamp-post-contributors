<?php
  class ShowPostContributorsMetabox {

    private $screens = array("post");
    public function __construct() {
        add_filter( 'the_content', array( $this, 'append_contributors_to_post' ) );
    }

    public function append_contributors_to_post( $content ) {
      global $post;
      if( is_single() && in_array( $post->post_type, $this->screens ) ) {

        $contributors = get_post_meta($post->ID, "dr13dev_contributors", true);
        $contributorsHTML = '';
		
		if( !empty($contributors) && is_array($contributors) ){
			$contributorsHTML .= '<div class="has-global-padding wp-block-group is-layout-flex wp-container-8 wp-block-group-;is-layout-flex">';
			$contributorsHTML .= '<h4>Contributors:</h4>';

			foreach( $contributors as $contrib) {
			  $contribMeta = get_userdata($contrib);

			  $contributorsHTML .= '<div class="wp-block-post-author">';
			  $contributorsHTML .= '<div class="wp-block-post-author__content">';  
			  $contributorsHTML .= '<div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow"><div class="wp-block-avatar">'. get_avatar($contrib, 32) .'</div>';
			  $contributorsHTML .='<p class="wp-block-post-author__name"><a href="'. get_author_posts_url($contrib) .'">'. $contribMeta->first_name .' '. $contribMeta->last_name .'</a></p></div>';
			  $contributorsHTML .= '</div></div>';
			}
			$contributorsHTML .= '</div>';
		}
        return $content . $contributorsHTML;

      } else {
        return $content;
      }
    }

  }

  if (class_exists('ShowPostContributorsMetabox')) {
    new ShowPostContributorsMetabox;
  };
?>