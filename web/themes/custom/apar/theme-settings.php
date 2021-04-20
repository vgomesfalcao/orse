<?php
use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\ThemeSettingsForm;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

function apar_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface &$form_state) {
  $form['settings'] = array(
      '#type' => 'details',
      '#title' => t('Theme settings'),
      '#open' => TRUE,
      '#attached' => array(
        'library' =>  array(
          'apar/admin-lib'
        ),
      ),
  );
  //Border Page
  $form['settings']['switch_setting'] = array(
      '#type' => 'details',
      '#title' => t('Switch Setting'),
      '#open' => FALSE,
  );
  $form['settings']['switch_setting']['display_switch'] = array(
      '#title' => t('Display Switch Setting'),
      '#type' => 'checkbox',
      '#default_value' => theme_get_setting('display_switch', 'apar'),
  );
  $form['settings']['switch_setting']['layout_page'] = array(
      '#title' => t('Page Layout'),
      '#type' => 'select',
      '#options' => array(
          'wide' => t('Wide'),
          'boxed' => t('Boxed'),
      ),
      '#default_value' => theme_get_setting('layout_page', 'apar'),
  );
  $form['settings']['switch_setting']['background_page'] = array(
    '#type' => 'details',
    '#title' => t('Background Page'),
    '#open' => FALSE,
  );
  $form['settings']['switch_setting']['background_page']['background_styles'] = array(
      '#title' => t('Background Styles'),
      '#type' => 'select',
      '#options' => array(
          'solid_color' => t('Solid Color'),
          'image' => t('Image'),
      ),
      '#default_value' => theme_get_setting('background_styles', 'apar'),
  );
  $form['settings']['switch_setting']['background_page']['background_solid_color'] = array(
    '#type' => 'details',
    '#title' => t('Background Styles Solid Color Setting'),
    '#open' => FALSE,
  );
  //Background Color
  $form['settings']['switch_setting']['background_page']['background_solid_color']['background_color'] = array(
    '#type' => 'textfield',
    '#title' => t('Background Color'),
    '#default_value' => theme_get_setting('background_color', 'apar'),
    '#description'  => t('<strong>Example: </strong><b><em>"red"</b></em> or <b><em>"#fff"</b></em>')
  );
  $form['settings']['switch_setting']['background_page']['background_page_image'] = array(
      '#type' => 'details',
      '#title' => t('Background Styles Image Setting'),
      '#default_value' => theme_get_setting('background_page_image', 'apar'),
  );
  //Page Image URL
  $form['settings']['switch_setting']['background_page']['background_page_image']['page_image_bg'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the page image background'),
    '#default_value' => theme_get_setting('page_image_bg'),
    '#description' => t('Enter a URL page image background'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );

  //Page Image Upload
  $form['settings']['switch_setting']['background_page']['background_page_image']['page_image_bg_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload page image background'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your background image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('page_image_bg_validate')
  );
  //Background
  $form['settings']['switch_setting']['background_page']['background_page_image']['page_bg_css'] = array(
    '#type' => 'textfield',
    '#title' => t('Background css'),
    '#default_value' => theme_get_setting('page_bg_css', 'apar'),
    '#description'  => t('<strong>Example: </strong> "<b><em>repeat</em></b> or <b><em>#ffffff repeat center top / cover fixed</em></b>"')
  );
  $form['settings']['switch_setting']['primary_color'] = array(
      '#type' => 'details',
      '#title' => t('Primary Color'),
      '#open' => FALSE,
  );
  $form['settings']['switch_setting']['primary_color']['built_in_skins_pr'] = array(
      '#type' => 'radios',
      '#title' => t('Predefined skins'),
      '#options' => array(
          'default-pr'       => t('Default'),
          'orange-pr'        => t('Orange'),
          'lightgreen-pr'    => t('Lightgreen'),
          'blue-pr'          => t('Blue'),
          'green-pr'         => t('Green'),
          'red-pr'           => t('Red'),
          'cyan-pr'          => t('Cyan'),
          'purple-pr'        => t('Purple'),
          'pink-pr'          => t('Pink'),
          'brown-pr'         => t('Brown'),
          'yellow-pr'        => t('Yellow'),
          'purple-2-pr'      => t('Purple 2'),

      ),
      '#required' => true,
      '#default_value' => theme_get_setting('built_in_skins_pr','apar') ? theme_get_setting('built_in_skins_pr','apar') : 'default',
  );
  $form['settings']['switch_setting']['secondary_color'] = array(
      '#type' => 'details',
      '#title' => t('Secondary Color'),
      '#open' => FALSE,
  );
  $form['settings']['switch_setting']['secondary_color']['built_in_skins_sc'] = array(
      '#type' => 'radios',
      '#title' => t('Predefined skins'),
      '#options' => array(
          'default-sc'       => t('Default'),
          'orange-sc'        => t('Orange'),
          'lightgreen-sc'    => t('Lightgreen'),
          'blue-sc'          => t('Blue'),
          'green-sc'         => t('Green'),
          'red-sc'           => t('Red'),
          'cyan-sc'          => t('Cyan'),
          'purple-sc'        => t('Purple'),
          'pink-sc'          => t('Pink'),
          'brown-sc'         => t('Brown'),
          'yellow-sc'        => t('Yellow'),
          'purple-2-sc'      => t('Purple 2'),

      ),
      '#required' => true,
      '#default_value' => theme_get_setting('built_in_skins_sc','apar') ? theme_get_setting('built_in_skins_sc','apar') : 'default',
  );
  //Preloader
  $form['settings']['preloader'] = array(
      '#type' => 'details',
      '#title' => t('Preloader'),
      '#open' => FALSE,
  );
  $form['settings']['preloader']['display_preloader'] = array(
      '#title' => t('Display Preloader'),
      '#type' => 'checkbox',
      '#default_value' => theme_get_setting('display_preloader', 'apar'),
  );
  $form['settings']['preloader']['preloader_style'] = array(
      '#title' => t('Preloader Style'),
      '#type' => 'select',
      '#options' => array(
          'style1' => t('Loading Style 1'),
          'style2' => t('Loading Style 2'),
          'style3' => t('Loading Style 3'),
          'style4' => t('Loading Style 4'),
          'style5' => t('Loading Style 5'),
          'style6' => t('Loading Style 6'),
          'style7' => t('Loading Style 7'),
          'style8' => t('Loading Style 8'),
          'style9' => t('Loading Style 9'),
      ),
      '#default_value' => theme_get_setting('preloader_style', 'apar'),
  );

  //Header
  $form['settings']['header'] = array(
      '#type' => 'details',
      '#title' => t('Header settings'),
      '#open' => FALSE,
  );
  //Header style
  $form['settings']['header']['header_style'] = array(
      '#title' => t('HEADER STYLE'),
      '#type' => 'select',
      '#options' => array(
          'style1' => t('Header Light'),
          'style2' => t('Header Dark'),
          'style3' => t('Header Transparent'),
          'style4' => t('Header White'),
      ),
      '#default_value' => theme_get_setting('header_style', 'apar'),
  );
  //Logo Default
  $form['settings']['header']['logo1'] = array(
      '#type' => 'details',
      '#title' => t('Logo Default'),
      '#open' => FALSE,
  );

  $form['settings']['header']['logo1']['link_config'] = array(
    '#markup' => t('<em>Change logo <a id="apar-change-logo" href="#edit-theme-settings">here</a></em>'),
  );

  //Header Transparent
  $form['settings']['header']['header_transparent'] = array(
      '#type' => 'details',
      '#title' => t('Header Transparent Logo Setting'),
      '#default_value' => theme_get_setting('header_transparent', 'apar'),
  );
  //Header Transparent Logo URL
  $form['settings']['header']['header_transparent']['header_transparent_logo_url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the header transparent logo'),
    '#default_value' => theme_get_setting('header_transparent_logo_url'),
    '#description' => t('Enter a URL header transparent logo'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );
  //Header Transparent Logo upload
  $form['settings']['header']['header_transparent']['header_transparent_logo_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload header transparent logo'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your header image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('header_transparent_logo_validate')
  );
  //Header Dark
  $form['settings']['header']['header_dark'] = array(
      '#type' => 'details',
      '#title' => t('Header Dark Logo Setting'),
      '#default_value' => theme_get_setting('header_dark', 'apar'),
  );
  //Header Dark Logo URL
  $form['settings']['header']['header_dark']['header_dark_logo_url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the header dark logo'),
    '#default_value' => theme_get_setting('header_dark_logo_url'),
    '#description' => t('Enter a URL header dark logo'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );
  //Header Dark Logo upload
  $form['settings']['header']['header_dark']['header_dark_logo_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload header dark logo'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your header image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('header_dark_logo_validate')
  );
  //Page Title
  $form['settings']['page_title'] = array(
      '#type' => 'details',
      '#title' => t('Page Title Setting'),
      '#open' => FALSE,
  );
  //Page Title Image
  $form['settings']['page_title']['page_title_image'] = array(
      '#type' => 'details',
      '#title' => t('Page Title Image Setting'),
      '#default_value' => theme_get_setting('page_title_image', 'apar'),
  );
  //Page Title Image URL
  $form['settings']['page_title']['page_title_image']['page_title_image_url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the page title image'),
    '#default_value' => theme_get_setting('page_title_image_url'),
    '#description' => t('Enter a URL page title image.'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );
  //Page Title Image upload
  $form['settings']['page_title']['page_title_image']['page_title_image_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload page title image'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your header image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('page_title_image_validate')
  );
  // Blog settings
  $form['settings']['blog'] = array(
    '#type' => 'details',
    '#title' => t('Blog settings'),
    '#open' => FALSE,
  );
  $form['settings']['blog']['blog_listing'] = array(
    '#type' => 'details',
    '#title' => t('Blog listing'),
    '#open' => FALSE,
  );
  $form['settings']['blog']['blog_listing']['blog_layout'] = array(
    '#type' => 'select',
    '#title' => t('Blog Layout'),
    '#options' => array(
        'default' => t('Default'),
        'classic' => t('Classic'),
        'thumb' => t('Thumbnail'),
        '1column' => t('One Column'),
        '2column' => t('Two Column'),
        '3column' => t('Three Column'),
        '4column' => t('Four Column'),
        '5column' => t('Five Column'),
        '6column' => t('Six Column'),
      ),
    '#default_value' => theme_get_setting('blog_layout', 'apar'),
  );
  $form['settings']['blog']['blog_listing']['blog_sidebar'] = array(
    '#type' => 'select',
    '#title' => t('Blog sidebar'),
    '#options' => array(
        'none' => t('Full Width'),
        'right' => t('Right'),
        'left' => t('Left'),
      ),
    '#description' => t('Only for types "Default", "Classic", "Thumbnail"'),
    '#default_value' => theme_get_setting('blog_sidebar', 'apar'),
  );

  //Product settings
  $form['settings']['product'] = array(
    '#type' => 'details',
    '#title' => t('Product settings'),
    '#open' => FALSE,
  );
  $form['settings']['product']['product_layout'] = array(
    '#type' => 'select',
    '#title' => t('Product Layout'),
    '#options' => array(
        'default' => t('Default'),
        '2column' => t('Two Column'),
        '3column' => t('Three Column'),
        '4column' => t('Four Column'),
        '5column' => t('Five Column'),
        '6column' => t('Six Column'),
      ),
    '#default_value' => theme_get_setting('product_layout', 'apar'),
  );
  $form['settings']['product']['product_sidebar'] = array(
    '#type' => 'select',
    '#title' => t('Product sidebar'),
    '#options' => array(
        'none' => t('None'),
        'right' => t('Right'),
        'left' => t('Left'),
      ),
    '#default_value' => theme_get_setting('product_sidebar', 'apar'),
  );
  $form['settings']['product']['product_taxanomy'] = array(
    '#type' => 'details',
    '#title' => t('Product Taxanomy settings'),
    '#open' => FALSE,
  );
  $form['settings']['product']['product_taxanomy']['product_page_taxonomy_items_per_page'] = array(
    '#type' => 'number',
    '#attributes' => array('min' => 1 ),
    '#title' => t('Product Taxanomy settings'),
    '#default_value' => theme_get_setting('product_page_taxonomy_items_per_page', 'apar'),
  );
  //Contact
  $form['settings']['contact'] = array(
    '#type' => 'details',
    '#title' => t('Contact Setting'),
    '#open' => FALSE,
  );
  $form['settings']['contact']['contact_layout'] = array(
      '#title' => t('Contact Layout'),
      '#type' => 'select',
      '#options' => array(
          'style1' => t('Contact Classic'),
          'style2' => t('Contact Left Sidebar'),
          'style3' => t('Contact Right Sidebar'),
          'style4' => t('Contact Full Width Map'),
          'style5' => t('Contact Prallax'),
      ),
      '#default_value' => theme_get_setting('contact_layout', 'apar'),
  );
  //Contact Bg Form
  $form['settings']['contact']['contact_image'] = array(
    '#type' => 'details',
    '#title' => t('Contact Background Image Form Setting'),
    '#default_value' => theme_get_setting('contact_image', 'apar'),
  );
  //Contact Bg URL
  $form['settings']['contact']['contact_image']['contact_image_url'] = array(
  '#type' => 'textfield',
  '#title' => t('URL of the Contact Background Image Form'),
  '#default_value' => theme_get_setting('contact_image_url'),
  '#description' => t('Enter a URL Contact Background Image Form.'),
  '#size' => 40,
  '#maxlength' => 512,
  '#attributes' => array('disabled' => 'disabled'),
  );
  ///Contact Bg upload
  $form['settings']['contact']['contact_image']['contact_image_upload'] = array(
  '#type' => 'file',
  '#title' => t('Upload Contact Background Image'),
  '#size' => 40,
  '#attributes' => array('enctype' => 'multipart/form-data'),
  '#description' => t('If you don\'t jave direct access to the server, use this field to upload your header image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
  '#element_validate' => array('contact_image_validate')
  );
  //title Form
  $form['settings']['contact']['title_form'] = array(
    '#type' => 'textfield',
    '#title' => t('Title Form'),
    '#default_value' => theme_get_setting('title_form', 'apar'),
  );
  //title Contact
  $form['settings']['contact']['title_contact'] = array(
    '#type' => 'textfield',
    '#title' => t('Title Contact'),
    '#default_value' => theme_get_setting('title_contact', 'apar'),
  );
  //contact address
  $form['settings']['contact']['contact_address'] = array(
    '#type' => 'textfield',
    '#title' => t('Address'),
    '#default_value' => theme_get_setting('contact_address', 'apar'),
  );
  //contact email
  $form['settings']['contact']['contact_email'] = array(
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#default_value' => theme_get_setting('contact_email', 'apar'),
  );
  //contact phone
  $form['settings']['contact']['contact_phone'] = array(
      '#type' => 'textfield',
      '#title' => t('Phone'),
      '#default_value' => theme_get_setting('contact_phone', 'apar'),
  );
  //contact fax
  $form['settings']['contact']['contact_fax'] = array(
      '#type' => 'textfield',
      '#title' => t('Fax'),
      '#default_value' => theme_get_setting('contact_fax', 'apar'),
  );
  //contact website
  $form['settings']['contact']['contact_website'] = array(
      '#type' => 'textfield',
      '#title' => t('WebSite'),
      '#default_value' => theme_get_setting('contact_website', 'apar'),
  );
  //Maintenance page
  $form['settings']['maintenance_page'] = array(
      '#type' => 'details',
      '#title' => t('Maintenance page'),
      '#open' => FALSE,
  );
  $form['settings']['maintenance_page']['maintenance_page_header_bg'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the header background image'),
    '#default_value' => theme_get_setting('maintenance_page_header_bg'),
    '#description' => t('Enter a URL background image.'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );
  $form['settings']['maintenance_page']['maintenance_page_header_bg_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload header background image'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your background image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('maintenance_page_header_bg_validate'),
  );
  //custom css
  $form['settings']['custom_css'] = array(
    '#type' => 'details',
    '#title' => t('Custom CSS'),
    '#open' => FALSE,
  );
  //custom css
  $form['settings']['custom_css']['custom_css'] = array(
    '#type' => 'textarea',
    '#title' => t('Custom CSS'),
    '#default_value' => theme_get_setting('custom_css', 'apar'),
    '#description'  => t('<strong>Example:</strong><br/>h1 { font-family: \'Metrophobic\', Arial, serif; font-weight: 400; }')
  );
  //general setting
  $form['settings']['general_setting'] = array(
        '#type' => 'details',
        '#title' => t('General Settings'),
        '#open' => FALSE,
  );
  $form['settings']['general_setting']['general_setting_tracking_code'] = array(
      '#type' => 'textarea',
      '#title' => t('Tracking Code'),
      '#default_value' => theme_get_setting('general_setting_tracking_code', 'apar'),
  );
  //Footer
  $form['settings']['footer'] = array(
      '#type' => 'details',
      '#title' => t('Footer settings'),
      '#open' => FALSE,
  );
  //Footer style
  $form['settings']['footer']['footer_style'] = array(
      '#title' => t('FOOTER STYLE'),
      '#type' => 'select',
      '#options' => array(
          'style1' => t('Footer Dark'),
          'style2' => t('Footer White'),
          'style3' => t('Footer Image'),
          'none' => t('None'),
      ),
      '#default_value' => theme_get_setting('footer_style', 'apar'),
  );
  //Copyright text
  $form['settings']['footer']['copyright_text'] = array(
    '#type' => 'textarea',
    '#title' => t('Copyright text'),
    '#default_value' => theme_get_setting('copyright_text', 'apar'),
  );
  //Social Network
  $form['settings']['footer']['social_network_footer'] = array(
    '#type' => 'textarea',
    '#title' => t('Social Network'),
    '#default_value' => theme_get_setting('social_network_footer', 'apar'),
  );
  //Footer Background Image
  $form['settings']['footer']['footer_image'] = array(
      '#type' => 'details',
      '#title' => t('Footer Background Image Setting'),
      '#description' => t('Only for type "Footer Image"'),
      '#default_value' => theme_get_setting('footer_image', 'apar'),
  );
  //Footer Background Image URL
  $form['settings']['footer']['footer_image']['footer_image_url'] = array(
    '#type' => 'textfield',
    '#title' => t('URL of the footer background image.'),
    '#default_value' => theme_get_setting('footer_image_url'),
    '#description' => t('Enter a URL footer background image.'),
    '#size' => 40,
    '#maxlength' => 512,
    '#attributes' => array('disabled' => 'disabled'),
  );

  //Footer Background Image Upload
  $form['settings']['footer']['footer_image']['footer_image_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload footer background image'),
    '#size' => 40,
    '#attributes' => array('enctype' => 'multipart/form-data'),
    '#description' => t('If you don\'t jave direct access to the server, use this field to upload your header image. Uploads limited to .png .gif .jpg .jpeg .apng .svg extensions'),
    '#element_validate' => array('footer_image_validate')
  );
}

function page_title_image_validate($element, FormStateInterface $form_state){
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('page_title_image_upload', $validators, "public://page_title_image", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('page_title_image_url', $file_url);
    }
 }
}

function maintenance_page_header_bg_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('maintenance_page_header_bg_upload', $validators, "public://maintenance_page", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('maintenance_page_header_bg', $file_url);
    }
 }
}

function footer_image_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('footer_image_upload', $validators, "public://footer_image", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('footer_image_url', $file_url);
    }
 }
}

function contact_image_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('contact_image_upload', $validators, "public://contact_image", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('contact_image_url', $file_url);
    }
 }
}

function header_transparent_logo_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('header_transparent_logo_upload', $validators, "public://header_transparent_logo", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('header_transparent_logo_url', $file_url);
    }
  }
}

function header_dark_logo_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('header_dark_logo_upload', $validators, "public://header_dark_logo", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('header_dark_logo_url', $file_url);
    }
  }
}

function page_image_bg_validate($element, FormStateInterface $form_state) {
  global $base_url;

  $validators = array('file_validate_extensions' => array('png gif jpg jpeg apng svg'));
  $file = file_save_upload('page_image_bg_upload', $validators, "public://page_image_bg", NULL, FILE_EXISTS_REPLACE);

  if (!empty($file)) {
    // change file's status from temporary to permanent and update file database
    if ((is_object($file[0]) == 1)) {
      $file[0]->status = FILE_STATUS_PERMANENT;
      $file[0]->save();
      $uri = $file[0]->getFileUri();
      $file_url = file_create_url($uri);
      $file_url = str_ireplace($base_url, '', $file_url);
      $form_state->setValue('page_image_bg', $file_url);
    }
 }
}
