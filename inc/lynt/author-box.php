<?php
function lynt_author_box($content){

  global $post;

  $html='';

  if (get_post_type() === 'post') {
    $avatar = get_avatar( get_the_author_email(), '60' );
    $name = get_the_author_meta( 'display_name');
    $autor_id = get_the_author_meta( 'ID');
    $text =  nl2br(wp_kses( get_the_author_meta( 'description' ), wp_kses_allowed_html( 'post' ) ));
 
    //TODO: move to the settings or user meta
    if (in_array($autor_id, array(12,13,15))) {
          $html = "<div class=\"author-box\"><div class=\"avatar\">$avatar</div><h5 class=\"author-name\">Èlánek pro vás pøipravila: <strong>$name</strong></h5><p class=\"author-description\">$text</p></div>";
        }
        else  $html = "<div class=\"author-box\"><div class=\"avatar\">$avatar</div><h5 class=\"author-name\">Èlánek pro vás pøipravil: <strong>$name</strong></h5><p class=\"author-description\">$text</p></div>";
  }

  return $content.$html;

}

add_filter('the_content', 'lynt_author_box');

//allow HTML in the user BIO
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter( 'pre_user_description', 'wp_filter_post_kses' );