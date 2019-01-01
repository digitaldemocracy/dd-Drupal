<?php

namespace Drupal\dd_fax_service;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\key\KeyRepositoryInterface;
use Drupal\dd_fax_service\Phaxio;

class DdFaxService {

  /** @var Drupal\dd_fax_service\Phaxio\Phaxio object */
  protected $phaxio;

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $config;

  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface*/
  protected $logger;

  /** @var \Drupal\key\KeyRepositoryInterface */
  protected $key;


  /**
   * constructor
   *
   * @param ConfigFactoryInterface $config_factory
   * @param LoggerChannelInterface $logger 
   * @param KeyRepositoryInterface $key 
   *
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                     LoggerChannelInterface $logger, KeyRepositoryInterface $key) {
    $this->config = $config_factory->get('dd_fax_service.settings');
    $this->logger = $logger;
    $this->key = $key;
    $apiKey = $this->getApiKey();
    $apiSecret = $this->getApiSecret();
    $apiHost = $this->getApiHost();

    $this->phaxio = new Phaxio\Phaxio($apiKey, $apiSecret, $apiHost);
  }

  /**
   * function to send html dormat string via fax 
   *
   * @param string $toNumber
   *   fax number with prefix +1
   *
   * @param string $htmlData
   *   html formatted string 
   *
   * @return PhaxioOperaitonResult object
   */
  public function sendHtmlStringData($toNumber, $htmlData) {
    return $this->phaxio->sendFax(
      $toNumber, array(), 
      array('string_data' => $htmlData, 'string_data_type' => 'html'));
  }

  /**
   * function to send string via fax 
   *
   * @param string $toNumber
   *   fax number with prefix +1
   *
   * @param string $textData
   *   plain string 
   *
   * @return PhaxioOperaitonResult object
   */
  public function sendTextStringData($toNumber, $textData) {
    return $this->phaxio->sendFax(
      $toNumber, array(),
      array('string_data' => $textData, 'string_data_type' => 'text'));
  }

  /**
   * function to get the mode of operation
   *
   * @return string
   *   live or test
   */
  public function getMode() {
    $mode = $this->config->get('mode');

    if (!$mode) {
      $mode = 'test';
    }

    return $mode;
  }

  /**
   * function to get api key
   *
   * @return string
   *   api key
   */
  public function getApiKey() {
    $config_key = $this->getMode() . '_api_key';
    $key_id = $this->config->get($config_key);
    $key_obj = $this->key->getKey($key_id);

    return $key_obj ? $key_obj->getKeyValue() : '';
  }

  /**
   * function to get api secret 
   *
   * @return string
   *   api secret 
   */
  public function getApiSecret() {
    $config_key = $this->getMode() . '_api_secret';
    $key_id = $this->config->get($config_key);
    $key_obj = $this->key->getKey($key_id);

    return $key_obj ? $key_obj->getKeyValue() : '';
  }

  /**
   * function to get api host url 
   *
   * @return string
   *   api host url 
   */
  public function getApiHost() {
    return $this->config->get('api_host');
  }
}
