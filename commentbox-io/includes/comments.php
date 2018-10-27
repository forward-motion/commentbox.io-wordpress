<?php

$className = get_option('commentbox-io-setting-class-name');

if (!$className) {
    $className = 'commentbox';
}

echo '<div class="'. esc_attr( $className ) .'"></div>';