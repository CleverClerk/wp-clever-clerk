<?php
  /*
  Plugin Name: CleverClerk
  Plugin URI: https://platform.cleverclerk.com/wordpress-plugin
  Description: Allows integration with Clever Clerk
  Version: 0.1
  Author: @mrgenixus
  Author URI: cleverclerk.com
  */
  defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

  if ( ! class_exists( 'Teletype' ) ) {
    class CleverClerk {
      public function __construct() {
        add_shortcode( $this->tag, array( &$this, 'shortcode' ) );
        if ( is_admin() ) {
          add_action( 'admin_init', array( &$this, 'settings' ) );
        }

        if ( $options = get_option( $this->tag ) ) {
          $this->options = $options;
        }
      }

      protected function _enqueue() {
        $plugin_path = plugin_dir_url( __FILE__ );

        wp_register_script(
          $this->tag,
          $plugin_path . 'build/cleverclerk.min.js',
          array(),
          $this->version
        );

        if ( !wp_script_is( $this->tag, 'enqueued' ) ) {
          wp_enqueue_script( $this->tag );
        }        
      }

      protected $settings = array(
        'CleverClerkAPIHost' => array(
          'description' => 'The platform host name e.g. platform.cleverclerk.com',
          'placeholder' => 'cleverclerk-staging.herokuapp.com'
        ),

        'marketplaceId' => array(
          'description' => 'the id number of your hotel or marketplace',
          'validator' => 'numeric',
          'placeholder' => '1'
        )
      );

      /**
       * Add the setting fields to the Reading settings page.
       *
       * @access public
       */
      public function settings()
      {
        $section = 'general';
        add_settings_section(
          $this->tag . '_settings_section',
          $this->name . ' Settings',
          function () {
            echo '<p>Configuration options for the ' . esc_html( $this->name ) . ' plugin.</p>';
          },
          $section
        );
        foreach ( $this->settings AS $id => $options ) {
          $options['id'] = $id;
          add_settings_field(
            $this->tag . '_' . $id . '_settings',
            $id,
            array( &$this, 'settings_field' ),
            $section,
            $this->tag . '_settings_section',
            $options
          );
        }

        register_setting(
          $section,
          $this->tag,
          array( &$this, 'settings_validate' )
        );
      }

      public function settings_field( array $options = array() )
      {
        $atts = array(
          'id' => $this->tag . '_' . $options['id'],
          'name' => $this->tag . '[' . $options['id'] . ']',
          'type' => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
          'class' => 'small-text',
          'value' => ( array_key_exists( 'default', $options ) ? $options['default'] : null )
        );
        if ( isset( $this->options[$options['id']] ) ) {
          $atts['value'] = $this->options[$options['id']];
        }
        if ( isset( $options['placeholder'] ) ) {
          $atts['placeholder'] = $options['placeholder'];
          $atts['size'] = strlen($options['placeholder']);
          if ($atts['size'] > 8) {
            $atts['class'] = '';
          }
        }
        if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
          if ( $atts['value'] ) {
            $atts['checked'] = 'checked';
          }
          $atts['value'] = true;
        }
        array_walk( $atts, function( &$item, $key ) {
          $item = esc_attr( $key ) . '="' . esc_attr( $item ) . '"';
        } );
        ?>
        <label>
          <input <?php echo implode( ' ', $atts ); ?> />
          <?php if ( array_key_exists( 'description', $options ) ) : ?>
          <?php esc_html_e( $options['description'] ); ?>
          <?php endif; ?>
        </label>
        <?php
      }

      public function settings_validate( $input )
      {
        $errors = array();
        foreach ( $input AS $key => $value ) {
          if ( $value == '' ) {
            unset( $input[$key] );
          } elseif ( isset( $this->settings[$key]['validator'] ) ) {
            switch ( $this->settings[$key]['validator'] ) {
              case 'numeric':
                if ( is_numeric( $value ) ) {
                  $input[$key] = intval( $value );
                } else {
                  $errors[] = $key . ' must be a numeric value.';
                  unset( $input[$key] );
                }
              break;
            }
          } else {
            $input[$key] = strip_tags( $value );
          }
        }
        if ( count( $errors ) > 0 ) {
          add_settings_error(
            $this->tag,
            $this->tag,
            implode( '<br />', $errors ),
            'error'
          );
        }
        return $input;
      }
      /**
       * Tag identifier used by file includes and selector attributes.
       * @var string
       */
      protected $tag = 'clever_clerk';

      /**
       * User friendly name used to identify the plugin.
       * @var string
       */
      protected $name = 'Clever Clerk';

      /**
       * Current version of the plugin.
       * @var string
       */
      protected $version = '0.1';

      function shortcode( $atts ) {

        // Attributes
        extract( shortcode_atts(
          array(
            'data' => 'tours',
          ), $atts )
        );
        $this->_enqueue();
        $data_attributes = array();
        foreach ($this->options as $key => $attribute) {
          $data_attributes .= ' ' . esc_attr( 'data-' . $key ) . '="' . esc_attr( $attribute ) . '"';
        }
        // Code
        ob_start();
        ?>
          <div class="<?php echo $this->tag; ?>" data-clever-clerk="<?php echo $data; ?>" <?php echo $data_attributes ?>></div>
        <?php
        return ob_get_clean();
      }
    }

    new CleverClerk();
  }