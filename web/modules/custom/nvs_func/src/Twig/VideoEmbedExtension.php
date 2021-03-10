<?php

/**
 * @file
 * Contains \Drupal\nvs_func\Twig\VideoEmbedExtension.
 */

namespace Drupal\nvs_func\Twig;

/**
 * Provides the NodeViewCount debugging function within Twig templates.
 */
class VideoEmbedExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'getThumbVideo';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('getThumbVideo', array($this, 'getThumbVideo'), array(
        'is_safe' => array('html'),
        
      )),
    );
  }

  /**
   * Provides Kint function to Twig templates.
   *
   * Handles 0, 1, or multiple arguments.
   *
   * Code derived from https://github.com/barelon/CgKintBundle.
   *
   * @param Twig_Environment $env
   *   The twig environment instance.
   * @param array $context
   *   An array of parameters passed to the template.
   */
  public function getThumbVideo($nid){
    $n = node_load($nid);
    $build_video = $n->field_video_embed->view('hat_teaser_1');
    $video_thumb = \Drupal::service('renderer')->renderRoot($build_video);  
    $str = strip_tags(render($video_thumb),'<img>');
    $img = trim($str);
    preg_match("<img.*?src=[\"\"'](?<url>.*?)[\"\"'].*?>",$img,$output);
    return $output['url'];
  }
  

}
