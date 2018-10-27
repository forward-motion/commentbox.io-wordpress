<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('commentbox-io-setting-project-id');
delete_option('commentbox-io-setting-class-name');
delete_option('commentbox-io-setting-box-id');
delete_option('commentbox-io-setting-comment-link-param');
delete_option('commentbox-io-setting-background-color');
delete_option('commentbox-io-setting-text-color');
delete_option('commentbox-io-setting-subtext-color');
delete_option('commentbox-io-setting-comment-count-selector');