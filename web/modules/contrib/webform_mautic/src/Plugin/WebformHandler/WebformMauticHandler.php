<?php

namespace Drupal\webform_mautic\Plugin\WebformHandler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformElement\WebformManagedFileBase;
use Drupal\webform\Plugin\WebformElementManagerInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformMessageManagerInterface;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\WebformTokenManagerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form submission to Mautic handler.
 *
 * @WebformHandler(
 *   id = "mautic",
 *   label = @Translation("Mautic"),
 *   category = @Translation("Mautic"),
 *   description = @Translation("Sends a form submission to a Mautic form."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class WebformMauticHandler extends WebformHandlerBase {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The token manager.
   *
   * @var \Drupal\webform\WebformTokenManagerInterface
   */
  protected $tokenManager;

  /**
   * The webform message manager.
   *
   * @var \Drupal\webform\WebformMessageManagerInterface
   */
  protected $messageManager;

  /**
   * A webform element plugin manager.
   *
   * @var \Drupal\webform\Plugin\WebformElementManagerInterface
   */
  protected $elementManager;

  /**
   * List of unsupported webform submission properties.
   *
   * The below properties will not being included in a remote post.
   *
   * @var array
   */
  protected $unsupportedProperties = [
    'metatag',
  ];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, ModuleHandlerInterface $module_handler, ClientInterface $http_client, WebformTokenManagerInterface $token_manager, WebformMessageManagerInterface $message_manager, WebformElementManagerInterface $element_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->moduleHandler = $module_handler;
    $this->httpClient = $http_client;
    $this->tokenManager = $token_manager;
    $this->messageManager = $message_manager;
    $this->elementManager = $element_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition, $container->get('logger.factory'), $container->get('config.factory'), $container->get('entity_type.manager'), $container->get('webform_submission.conditions_validator'), $container->get('module_handler'), $container->get('http_client'), $container->get('webform.token_manager'), $container->get('webform.message_manager'), $container->get('plugin.manager.webform.element')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $configuration = $this->getConfiguration();
    $settings = $configuration['settings'];
    if ($settings['debug'])
      $debuging_status = '<b class="color-error">Debugging is enabled</b></br>';
    else
      $debuging_status = '';
    return [
      '#theme' => 'markup',
      '#markup' => $debuging_status .
      '<b>Mautic form ID:</b> ' . $settings['mautic_form_id'] .
      '</br><b>Mautic URL:</b> ' . $settings['completed_url'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $field_names = array_keys(\Drupal::service('entity_field.manager')->getBaseFieldDefinitions('webform_submission'));
    $excluded_data = array_combine($field_names, $field_names);
    return [
      'method' => 'POST',
      'type' => 'x-www-form-urlencoded',
      'excluded_data' => $excluded_data,
      'custom_data' => '',
      'debug' => FALSE,
      // States.
      'completed_url' => '',
      'completed_custom_data' => '',
      'mautic_form_id' => '',
      // Custom response messages.
      'message' => '',
      'messages' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $webform = $this->getWebform();
    // States.
    $states = [
      WebformSubmissionInterface::STATE_COMPLETED => [
        'state' => $this->t('completed'),
        'label' => $this->t('Completed'),
        'description' => $this->t('Post data when submission is <b>completed</b>.'),
        'access' => TRUE,
      ],
    ];
    foreach ($states as $state => $state_item) {
      $state_url = $state . '_url';
      $state_custom_data = $state . '_custom_data';
      $t_args = [
        '@state' => $state_item['state'],
        '@title' => $state_item['label'],
        '@url' => 'https://mymautic.mautic.com',
      ];
      $form[$state] = [
        '#type' => 'details',
        '#open' => ($state === WebformSubmissionInterface::STATE_COMPLETED),
        '#title' => 'Mautic Settings',
        '#description' => 'Post data when submission is completed.',
        '#access' => $state_item['access'],
      ];
      $form[$state][$state_url] = [
        '#type' => 'url',
        '#title' => $this->t('Mautic URL'),
        '#required' => ($state === WebformSubmissionInterface::STATE_COMPLETED),
        '#description' => 'The full URL of your Mautic instance. (e.g. https://mymautic.mautic.com). Make sure to include http:// or https://. Do NOT include a trailing slash.',
        '#default_value' => $this->configuration[$state_url],
      ];
      $form[$state]['mautic_form_id'] = [
        '#type' => 'number',
        '#required' => ($state === WebformSubmissionInterface::STATE_COMPLETED),
        '#title' => $this->t('Mautic form ID'),
        '#description' => 'The Mautic form ID that you want to send data to. This is a numeric ID generated by Mautic.',
        '#default_value' => $this->configuration['mautic_form_id'],
      ];
      if ($state === WebformSubmissionInterface::STATE_COMPLETED) {
        $form[$state]['token'] = [
          '#type' => 'webform_message',
          '#message_message' => $this->t('Webform submission data has to correspond to your Mautic form fields. Each Mautic form field name should be entered in the data mapping below. You can choose to map certain fields only as you wish.'),
          '#message_type' => 'info',
        ];
      }
      $elements = $webform->getElementsInitializedFlattenedAndHasValue('view');
      $mautic_submissions = '';
      if ($this->configuration[$state_custom_data] != '') {
        $mautic_submissions = $this->configuration[$state_custom_data];
      } else {
        foreach ($elements as $key => $value) {
          end($elements);
          if ($key === key($elements)) {
            $mautic_submissions = $mautic_submissions . "mauticform[CHANGE_ME]: '[webform_submission:values:$key]'";
          } else {
            $mautic_submissions = $mautic_submissions . "mauticform[CHANGE_ME]: '[webform_submission:values:$key]'\n";
          }
        }
      }
      $form[$state][$state_custom_data] = [
        '#type' => 'webform_codemirror',
        '#mode' => 'yaml',
        '#title' => $this->t('Mautic submission data mapping'),
        '#description' => $this->t('Edit the form data that will be sent to Mautic when a webform submission is @state. Replace <code>CHANGE_ME</code> in <code>mauticform[CHANGE_ME]</code> keys with your Mautic field names. Webform submission tokens are the values mapped to those fields.', $t_args),
        '#states' => ['visible' => [':input[name="settings[' . $state_url . ']"]' => ['filled' => TRUE]]],
        '#default_value' => $mautic_submissions,
      ];
    }

    // Development.
    $form['development'] = [
      '#type' => 'details',
      '#title' => $this->t('Development settings'),
    ];
    $form['development']['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debugging'),
      '#description' => $this->t('If checked, posted submissions will be displayed onscreen to all users.'),
      '#return_value' => TRUE,
      '#default_value' => $this->configuration['debug'],
    ];

    $this->elementTokenValidate($form);
    return $this->setSettingsParents($form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['method'] = 'POST';
    $this->configuration['type'] = 'x-www-form-urlencoded';
    parent::submitConfigurationForm($form, $form_state);
    $this->applyFormStateToConfiguration($form_state);

    // Cast debug.
    $this->configuration['debug'] = (bool) $this->configuration['debug'];
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {

    $fields = $webform_submission->toArray(TRUE);
    $configuration = $this->tokenManager->replace($this->configuration, $webform_submission);


    $state = $webform_submission->getWebform()->getSetting('results_disabled') ? WebformSubmissionInterface::STATE_COMPLETED : $webform_submission->getState();
    $this->remotePost($state, $webform_submission);
  }

  protected function remotePost($state, WebformSubmissionInterface $webform_submission) {
    if (empty($this->configuration[$state . '_url'])) {
      return;
    }

    $this->messageManager->setWebformSubmission($webform_submission);

    $domain_url = $this->configuration[$state . '_url'];
    $request_url = $domain_url . '/form/submit?formId=' . $this->configuration['mautic_form_id'];
    $request_method = 'POST';
    $request_type = NULL;

    // Get request options with tokens replaced.
    $request_options = [];
    $request_options = $this->replaceTokens($request_options, $webform_submission);

    try {
      $method = strtolower($request_method);
      $domain = preg_replace("(^https?://)", "", $domain_url);
      $mautic_referer_id = (isset($_COOKIE['mautic_referer_id'])) ? $_COOKIE['mautic_referer_id'] : "";
      $mautic_session_id = (isset($_COOKIE['mautic_session_id'])) ? $_COOKIE['mautic_session_id'] : "";
      $mautic_device_id = (isset($_COOKIE['mautic_device_id'])) ? $_COOKIE['mautic_device_id'] : "";
      $mtc_id = (isset($_COOKIE['mtc_id'])) ? $_COOKIE['mtc_id'] : "";
      $mtc_sid = (isset($_COOKIE['mtc_sid'])) ? $_COOKIE['mtc_sid'] : "";
      $values = [
        'mautic_referer_id' => $mautic_referer_id,
        'mautic_session_id' => $mautic_session_id,
        'mautic_device_id' => $mautic_device_id,
        'mtc_id' => $mtc_id,
        'mtc_sid' => $mtc_sid,
      ];
      $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($values, $domain);
      $ip_address = $webform_submission->getRemoteAddr();

      $request_options[RequestOptions::COOKIES] = $cookieJar;
      $request_options[RequestOptions::HEADERS]['X-Forwarded-For'] = $ip_address;
      $request_options[($request_type == 'json' ? 'json' : 'form_params')] = $this->getRequestData($state, $webform_submission);
      $request_options['form_params'] = array_merge(
        array(
        'mauticform[formId]' => $this->configuration['mautic_form_id'],
        'mauticform[return]' => \Drupal::request()->getSchemeAndHttpHost(),
        'mauticform[messenger]' => '1'
        ), $request_options['form_params']);

      $response = $this->httpClient->$method($request_url, $request_options);
    } catch (RequestException $request_exception) {
      $response = $request_exception->getResponse();

      // Encode HTML entities to prevent broken markup from breaking the page.
      $message = $request_exception->getMessage();
      $message = nl2br(htmlentities($message));

      $this->handleError($state, $message, $request_url, $request_method, $request_type, $request_options, $response);
      return;
    }

    // Display submission exception if response code is not 2xx.
    $status_code = $response->getStatusCode();
    if ($status_code < 200 || $status_code >= 300) {
      $message = $this->t('Remote post request return @status_code status code.', ['@status_code' => $status_code]);
      $this->handleError($state, $message, $request_url, $request_method, $request_type, $request_options, $response);
      return;
    }

    // If debugging is enabled, display the request and response.
    $this->debug(t('Remote post successful!'), $state, $request_url, $request_method, $request_type, $request_options, $response, 'warning');

    // Replace [webform:handler] tokens in submission data.
    // Data structured for [webform:handler:remote_post:completed:key] tokens.
    $submission_data = $webform_submission->getData();
    $submission_has_token = (strpos(print_r($submission_data, TRUE), '[webform:handler:' . $this->getHandlerId() . ':') !== FALSE) ? TRUE : FALSE;
    if ($submission_has_token) {
      $response_data = $this->getResponseData($response);
      $token_data = ['webform_handler' => [$this->getHandlerId() => [$state => $response_data]]];
      $submission_data = $this->replaceTokens($submission_data, $webform_submission, $token_data);
      $webform_submission->setData($submission_data);
      // Resave changes to the submission data without invoking any hooks
      // or handlers.
      if ($this->isResultsEnabled()) {
        $webform_submission->resave();
      }
    }
  }

  /**
   * Get a webform submission's request data.
   *
   * @param string $state
   *   The state of the webform submission.
   *   Either STATE_NEW, STATE_DRAFT, STATE_COMPLETED, STATE_UPDATED, or
   *   STATE_CONVERTED depending on the last save operation performed.
   * @param \Drupal\webform\WebformSubmissionInterface $webform_submission
   *   The webform submission to be posted.
   *
   * @return array
   *   A webform submission converted to an associative array.
   */
  protected function getRequestData($state, WebformSubmissionInterface $webform_submission) {
    // Get submission and elements data.
    $data = $webform_submission->toArray(TRUE);

    // Remove unsupported properties from data.
    // These are typically added by other module's like metatag.
    $unsupported_properties = array_combine($this->unsupportedProperties, $this->unsupportedProperties);
    $data = array_diff_key($data, $unsupported_properties);

    // Flatten data and prioritize the element data over the
    // webform submission data.
    $element_data = $data['data'];
    unset($data['data']);
    $data = $element_data + $data;

    // Excluded selected submission data.
    $data = array_diff_key($data, $this->configuration['excluded_data']);

    // Append uploaded file name, uri, and base64 data to data.
    $webform = $this->getWebform();
    foreach ($data as $element_key => $element_value) {
      if (empty($element_value)) {
        continue;
      }

      $element = $webform->getElement($element_key);
      if (!$element) {
        continue;
      }

      $element_plugin = $this->elementManager->getElementInstance($element);
      if (!($element_plugin instanceof WebformManagedFileBase)) {
        continue;
      }

      if ($element_plugin->hasMultipleValues($element)) {
        foreach ($element_value as $fid) {
          $data['_' . $element_key][] = $this->getResponseFileData($fid);
        }
      } else {
        $data['_' . $element_key] = $this->getResponseFileData($element_value);
        // @deprecated in Webform 8.x-5.0-rc17. Use new format
        // The code needs to be removed before 8.x-5.0 or 8.x-6.x.
        $data += $this->getResponseFileData($element_value, $element_key . '__');
      }
    }

    // Append custom data.
    if (!empty($this->configuration['custom_data'])) {
      $data = Yaml::decode($this->configuration['custom_data']) + $data;
    }

    // Append state custom data.
    if (!empty($this->configuration[$state . '_custom_data'])) {
      $data = Yaml::decode($this->configuration[$state . '_custom_data']) + $data;
    }

    // Replace tokens.
    $data = $this->replaceTokens($data, $webform_submission);

    return $data;
  }

  /**
   * Get response file data.
   *
   * @param int $fid
   *   A file id.
   * @param string|null $prefix
   *   A prefix to prepended to data.
   *
   * @return array
   *   An associative array containing file data (name, uri, mime, and data).
   */
  protected function getResponseFileData($fid, $prefix = '') {
    /** @var \Drupal\file\FileInterface $file */
    $file = File::load($fid);
    if (!$file) {
      return [];
    }

    $data = [];
    $data[$prefix . 'id'] = (int) $file->id();
    $data[$prefix . 'name'] = $file->getFilename();
    $data[$prefix . 'uri'] = $file->getFileUri();
    $data[$prefix . 'mime'] = $file->getMimeType();
    $data[$prefix . 'uuid'] = $file->uuid();
    $data[$prefix . 'data'] = base64_encode(file_get_contents($file->getFileUri()));
    return $data;
  }

  /**
   * Get response data.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response returned by the remote server.
   *
   * @return array|string
   *   An array of data, parse from JSON, or a string.
   */
  protected function getResponseData(ResponseInterface $response) {
    $body = (string) $response->getBody();
    $data = json_decode($body, TRUE);
    return (json_last_error() === JSON_ERROR_NONE) ? $data : $body;
  }

  /**
   * Get webform handler tokens from response data.
   *
   * @param mixed $data
   *   Response data.
   * @param array $parents
   *   Webform handler token parents.
   *
   * @return array
   *   A list of webform handler tokens.
   */
  protected function getResponseTokens($data, array $parents = []) {
    $tokens = [];
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $tokens = array_merge($tokens, $this->getResponseTokens($value, array_merge($parents, [$key])));
      }
    } else {
      $tokens[] = '[' . implode(':', $parents) . ']';
    }
    return $tokens;
  }

  /**
   * Determine if saving of results is enabled.
   *
   * @return bool
   *   TRUE if saving of results is enabled.
   */
  protected function isResultsEnabled() {
    return ($this->getWebform()->getSetting('results_disabled') === FALSE);
  }

  /**
   * Determine if saving of draft is enabled.
   *
   * @return bool
   *   TRUE if saving of draft is enabled.
   */
  protected function isDraftEnabled() {
    return $this->isResultsEnabled() && ($this->getWebform()->getSetting('draft') != WebformInterface::DRAFT_NONE);
  }

  /**
   * Determine if converting anonymous submissions to authenticated is enabled.
   *
   * @return bool
   *   TRUE if converting anonymous submissions to authenticated is enabled.
   */
  protected function isConvertEnabled() {
    return $this->isDraftEnabled() && ($this->getWebform()->getSetting('form_convert_anonymous') === TRUE);
  }

  /*   * ************************************************************************* */
  // Debug and exception handlers.
  /*   * ************************************************************************* */

  /**
   * Display debugging information.
   *
   * @param string $message
   *   Message to be displayed.
   * @param string $state
   *   The state of the webform submission.
   *   Either STATE_NEW, STATE_DRAFT, STATE_COMPLETED, STATE_UPDATED, or
   *   STATE_CONVERTED depending on the last save operation performed.
   * @param string $request_url
   *   The remote URL the request is being posted to.
   * @param string $request_method
   *   The method of remote post.
   * @param string $request_type
   *   The type of remote post.
   * @param string $request_options
   *   The requests options including the submission data..
   * @param \Psr\Http\Message\ResponseInterface|null $response
   *   The response returned by the remote server.
   * @param string $type
   *   The type of message to be displayed to the end use.
   */
  protected function debug($message, $state, $request_url, $request_method, $request_type, $request_options, ResponseInterface $response = NULL, $type = 'warning') {
    if (empty($this->configuration['debug'])) {
      return;
    }

    $build = [
      '#type' => 'details',
      '#title' => $this->t('Debug: Remote post: @title [@state]', ['@title' => $this->label(), '@state' => $state]),
    ];

    // State.
    $build['state'] = [
      '#type' => 'item',
      '#title' => $this->t('Submission state/operation:'),
      '#markup' => $state,
      '#wrapper_attributes' => ['class' => ['container-inline'], 'style' => 'margin: 0'],
    ];

    // Request.
    $build['request'] = ['#markup' => '<hr />'];
    $build['request_url'] = [
      '#type' => 'item',
      '#title' => $this->t('Request URL'),
      '#markup' => $request_url,
      '#wrapper_attributes' => ['class' => ['container-inline'], 'style' => 'margin: 0'],
    ];
    $build['request_method'] = [
      '#type' => 'item',
      '#title' => $this->t('Request method'),
      '#markup' => $request_method,
      '#wrapper_attributes' => ['class' => ['container-inline'], 'style' => 'margin: 0'],
    ];
    $build['request_type'] = [
      '#type' => 'item',
      '#title' => $this->t('Request type'),
      '#markup' => $request_type,
      '#wrapper_attributes' => ['class' => ['container-inline'], 'style' => 'margin: 0'],
    ];
    $build['request_options'] = [
      '#type' => 'item',
      '#title' => $this->t('Request options'),
      '#wrapper_attributes' => ['style' => 'margin: 0'],
      'data' => [
        '#markup' => htmlspecialchars(Yaml::encode($request_options['form_params'])),
        '#prefix' => '<pre>',
        '#suffix' => '</pre>',
      ],
    ];

    // Response.
    $build['response'] = ['#markup' => '<hr />'];
    if ($response) {
      $build['response_code'] = [
        '#type' => 'item',
        '#title' => $this->t('Response status code'),
        '#markup' => $response->getStatusCode(),
        '#wrapper_attributes' => ['class' => ['container-inline'], 'style' => 'margin: 0'],
      ];
      $build['response_body'] = [
        '#type' => 'item',
        '#wrapper_attributes' => ['style' => 'margin: 0'],
        '#title' => $this->t('Response body:'),
        'data' => [
          '#markup' => htmlspecialchars($response->getBody()),
          '#prefix' => '<pre>',
          '#suffix' => '</pre>',
        ],
      ];
      $response_data = $this->getResponseData($response);
      if ($response_data) {
        $build['response_data'] = [
          '#type' => 'item',
          '#wrapper_attributes' => ['style' => 'margin: 0'],
          '#title' => $this->t('Response data:'),
          'data' => [
            '#markup' => Yaml::encode($response_data),
            '#prefix' => '<pre>',
            '#suffix' => '</pre>',
          ],
        ];
      }
      if ($tokens = $this->getResponseTokens($response_data, ['webform', 'handler', $this->getHandlerId(), $state])) {
        asort($tokens);
        $build['response_tokens'] = [
          '#type' => 'item',
          '#wrapper_attributes' => ['style' => 'margin: 0'],
          '#title' => $this->t('Response tokens:'),
          'description' => ['#markup' => $this->t('Below tokens can ONLY be used to insert response data into value and hidden elements.')],
          'data' => [
            '#markup' => implode(PHP_EOL, $tokens),
            '#prefix' => '<pre>',
            '#suffix' => '</pre>',
          ],
        ];
      }
    } else {
      $build['response_code'] = [
        '#markup' => t('No response. Please see the recent log messages.'),
        '#prefix' => '<p>',
        '#suffix' => '</p>',
      ];
    }

    // Message.
    $build['message'] = ['#markup' => '<hr />'];
    $build['message_message'] = [
      '#type' => 'item',
      '#wrapper_attributes' => ['style' => 'margin: 0'],
      '#title' => $this->t('Message:'),
      '#markup' => $message,
    ];

    $this->messenger()->addMessage(\Drupal::service('renderer')->renderPlain($build), $type);
  }

  /**
   * Handle error by logging and display debugging and/or exception message.
   *
   * @param string $state
   *   The state of the webform submission.
   *   Either STATE_NEW, STATE_DRAFT, STATE_COMPLETED, STATE_UPDATED, or
   *   STATE_CONVERTED depending on the last save operation performed.
   * @param string $message
   *   Message to be displayed.
   * @param string $request_url
   *   The remote URL the request is being posted to.
   * @param string $request_method
   *   The method of remote post.
   * @param string $request_type
   *   The type of remote post.
   * @param string $request_options
   *   The requests options including the submission data..
   * @param \Psr\Http\Message\ResponseInterface|null $response
   *   The response returned by the remote server.
   */
  protected function handleError($state, $message, $request_url, $request_method, $request_type, $request_options, $response) {
    // If debugging is enabled, display the error message on screen.
    $this->debug($message, $state, $request_url, $request_method, $request_type, $request_options, $response, 'error');

    // Log error message.
    $context = [
      '@form' => $this->getWebform()->label(),
      '@state' => $state,
      '@type' => $request_type,
      '@url' => $request_url,
      '@message' => $message,
      'link' => $this->getWebform()
        ->toLink($this->t('Edit'), 'handlers')
        ->toString(),
    ];
    $this->getLogger()
      ->error('@form webform remote @type post (@state) to @url failed. @message', $context);

    // Display custom or default exception message.
    if ($custom_response_message = $this->getCustomResponseMessage($response)) {
      $token_data = [
        'webform_handler' => [
          $this->getHandlerId() => $this->getResponseData($response),
        ],
      ];
      $build_message = [
        '#markup' => $this->replaceTokens($custom_response_message, $this->getWebform(), $token_data),
      ];
      $this->messenger()->addError(\Drupal::service('renderer')->renderPlain($build_message));
    } else {
      $this->messageManager->display(WebformMessageManagerInterface::SUBMISSION_EXCEPTION_MESSAGE, 'error');
    }
  }

  /**
   * Get custom custom response message.
   *
   * @param \Psr\Http\Message\ResponseInterface|null $response
   *   The response returned by the remote server.
   *
   * @return string
   *   A custom custom response message.
   */
  protected function getCustomResponseMessage($response) {
    if ($response instanceof ResponseInterface) {
      $status_code = $response->getStatusCode();
      foreach ($this->configuration['messages'] as $message_item) {
        if ($message_item['code'] == $status_code) {
          return $message_item['message'];
        }
      }
    }
    return (!empty($this->configuration['message'])) ? $this->configuration['message'] : '';
  }

  /**
   * {@inheritdoc}
   */
  protected function buildTokenTreeElement(array $token_types = [], $description = NULL) {
    $description = $description ?: $this->t('Use [webform_submission:values:ELEMENT_KEY:raw] to get plain text values.');
    return parent::buildTokenTreeElement($token_types, $description);
  }

}
