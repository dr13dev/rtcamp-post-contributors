<?php
    class PostContributorsMetabox {

      private $screens = array('post');

      private $roles = array("administrator", "editor", "contributor");

      private $authors;
        public function __construct() {
          add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
          add_action( 'save_post', array( $this, 'save_fields' ) );
        }

        public function add_meta_boxes() {
          foreach ( $this->screens as $s ) {
            $curr_user = wp_get_current_user();
            $capable = array_intersect($this->roles, (array) $curr_user->roles );
            if ( is_array($capable) && !empty($capable) ) {
              add_meta_box(
                'Contributors',
                __( 'Contributors', 'post-contributors' ),
                array( $this, 'meta_box_callback' ),
                $s,
                'normal',
                'core'
              );
            }
          }
        }

        public function meta_box_callback( $post ) {
          wp_nonce_field( 'Contributors_data', 'Contributors_nonce' ); 
          echo "List of authors contributed in the post:";
          $this->generator_checkbox( $post );
        }

        public function generator_checkbox( $post ) {
          
          $user_args  = array(
            'role' => 'author',
            'orderby' => 'display_name'
          );
          // Create the WP_User_Query object
          $wp_user_query = new WP_User_Query($user_args);
          $this->authors = $wp_user_query->get_results();
          if (empty($this->authors)) {
            echo "<h3>Contributors are not exists!!!</h3>";exit;
          }

          // List contributors as checkbox with mark
          $meta_value = get_post_meta( $post->ID, "dr13dev_contributors", true );
          if ( empty( $meta_value ) ) {
            if ( isset( $user['default'] ) ) {
              $meta_value = $user['default'];
            }
          }
          
          $output = '';
          foreach ( $this->authors as $user ) { // echo "<pre>"; print_r($user);exit;
            $label = '<label for="' . $user->user_nicename. "_" . $user->id . '">' . $user->first_name ." ". $user->last_name ."( ". $user->user_nicename ." )";
            $input = sprintf(
                '<input %s id="%s" name="contributors[]" type="checkbox" value="%s">',
                !empty( $meta_value ) && in_array($user->id, $meta_value) ? 'checked' : '',
                $user->user_nicename. "_" .$user->id,
                $user->id
                );
            $output .= $this->format_rows( $label, $input );
          }
          echo $output;
        }

        public function format_rows( $label, $input ) {
          return '<div style="margin-top: 10px;">'.$input.' <strong>'.$label.'</strong></div>';
        }

        public function save_fields( $post_id ) {

        //   echo "<pre>"; print_r($_POST);exit;
          if ( !isset( $_POST['Contributors_nonce'] ) ) {
            return $post_id;
          }
          $nonce = $_POST['Contributors_nonce'];
          if ( !wp_verify_nonce( $nonce, 'Contributors_data' ) ) {
            return $post_id;
          }
          if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
          }

          if ( isset( $_POST[ 'contributors' ] ) && is_array( $_POST[ 'contributors' ] ) ) {   
            update_post_meta( $post_id, "dr13dev_contributors", $_POST[ 'contributors' ] );
          }
        }

      }

    if (class_exists('PostContributorsMetabox')) {
      new PostContributorsMetabox;
    };
?>