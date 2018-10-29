<?php
/**
 *
 * @link              https://commentbox.io
 * @since             1.0.0
 * @package           CommentBox.io
 *
 * @wordpress-plugin
 * Plugin Name:       CommentBox.io
 * Plugin URI:        https://commentbox.io
 * Description:       Hosted commenting that's painless to embed, a pleasure to use, and a breeze to moderate. Happily works with Wordpress!
 * Version:           1.0.0
 * Author:            CommentBox.io
 * Text Domain:       commentbox-io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if (!class_exists( 'CommentBoxIo' )) {

    class CommentBoxIo {

        private $fields = array(
            'project-id' => array(
                'label' => 'Project ID',
                'settings' => array (
                    'placeholder' => 'xxxxxxxxxxxxxxxx-proj',
                    'required' => true
                )
            ),
            'class-name' => array(
                'label' => 'Class Name',
                'settings' => array(
                    'placeholder' => 'commentbox'
                )
            ),
            'box-id' => array(
                'label' => 'Box ID',
                'settings' => array(
                    'placeholder' => 'commentbox'
                )
            ),
            'comment-link-param' => array(
                'label' => 'Comment Link Param',
                'settings' => array(
                    'placeholder' => 'tlc'
                )
            ),
            'background-color' => array(
                'label' => 'Background Color',
                'settings' => array(
                    'placeholder' => 'transparent'
                )
            ),
            'text-color' => array(
                'label' => 'Text Color',
                'settings' => array(
                    'placeholder' => 'black'
                )
            ),
            'subtext-color' => array(
                'label' => 'Subtext Color',
                'settings' => array(
                    'placeholder' => 'grey'
                )
            ),
            'comment-count-selector' => array(
                'label' => 'Comment Count Selector',
                'settings' => array(
                    'placeholder' => 'none'
                )
            ),
        );

        public function __construct() {

            add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
            add_action( 'admin_init', array( $this, 'addAdminSettings' ) );
            add_action('wp_enqueue_scripts',  array( $this, 'addCommentBoxIoScript' ) );
            add_filter( 'comments_template', array( $this, 'addCommentsTemplate' ) );
        }

        public function addAdminMenu() {

            add_menu_page(
                'CommentBox.io '.__( 'Settings', 'commentbox-io' ),
                'CommentBox.io',
                'manage_options',
                'commentbox-io',
                array($this, 'adminLayout'),
                'dashicons-format-chat',
                25
            );
        }

        public function addAdminSettings() {

            add_settings_section(
                'commentbox-io-settings-default-section',
                'CommentBox.io Settings',
                array( $this, 'addSettingsSection' ),
                'commentbox-io'
            );

            foreach ($this->fields as $key => $value) {

                register_setting('commentbox-io', 'commentbox-io-setting-'.$key);

                add_settings_field(
                    'commentbox-io-settings-field-'.$key,
                    __( $value['label'], 'commentbox-io' ),
                    array( $this, 'addSettingsField' ),
                    'commentbox-io',
                    'commentbox-io-settings-default-section',
                    array_merge(array( 'label_for' => 'commentbox-io-setting-'.$key, 'key' => $key ), $value['settings'])
                );
            }
        }

        public function adminLayout() {

            echo '<form method="post" action="options.php">';
            settings_fields( 'commentbox-io' );
            do_settings_sections( 'commentbox-io' );
            submit_button( 'Save' );
            echo '<form />';
        }

        public function addSettingsSection() { ?>

            <p>
                Please be sure you've created a project in the <a target="_blank" href="https://dashboard.commentbox.io">CommentBox.io dashboard</a> first.
            </p>

            <p>
                Need help? Check out the docs <a target="_blank" href="https://commentbox.io/docs/wordpress">here</a>.
            </p>

            <?php
            $projectId = get_option('commentbox-io-setting-project-id');

            if ( isset( $projectId ) ) { ?>

                <p>
                    To moderate your comments, <a target="_blank" href="https://dashboard.commentbox.io/<?php echo rawurlencode($projectId) ?>">click here</a>.
                </p>

                <?php
            }

        }

        public function addSettingsField($args) {

            $setting = get_option('commentbox-io-setting-'.$args['key']);
            ?>
            <input
                    id="commentbox-io-setting-<?php echo $args['key'] ?>"
                    name="commentbox-io-setting-<?php echo $args['key'] ?>"
                    type="text"
                    value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"
                    placeholder="<?php echo $args['placeholder'] ?>"
                <?php echo $args['required'] ? 'required' : '' ?>
            />
            <label>(<?php echo $args['required'] ? 'required' : 'optional' ?>)</label>

            <?php
        }

        public function addCommentBoxIoScript() {

            wp_enqueue_script( 'commentbox-io', 'https://unpkg.com/commentbox.io/dist/commentBox.min.js' );

            $options = array(
                'permalink' => get_permalink()
            );

            foreach ($this->fields as $key => $value) {

                $options[$key] = get_option('commentbox-io-setting-'.$key);
            }

            wp_enqueue_script( 'commentbox-io-init', plugin_dir_url( __FILE__ ) . 'public/js/initCommentBoxIo.js', array('jquery', 'commentbox-io') );
            wp_localize_script( 'commentbox-io-init', 'commentBoxIoOptions', $options );

        }

        public function addCommentsTemplate() {

            return dirname(__FILE__) . '/includes/comments.php';
        }
    }


    new CommentBoxIo();
}