<?php
/**
 * @file
 * Contains DdEnvironment class.
 */

namespace Drupal\dd_base;

/**
 * Class DdEnvironment
 *
 * Provides environment detection for Drupal settings.
 */
class DdEnvironment {
  const DD_ENVIRONMENT_LOCAL = 'local';
  const DD_ENVIRONMENT_DEV = 'dev';
  const DD_ENVIRONMENT_QA = 'qa';
  const DD_ENVIRONMENT_PROD = 'prod';
  const DD_ENVIRONMENT_SITE_TYPE_BASE = 'base';
  const DD_ENVIRONMENT_SITE_TYPE_STATE = 'state';
  const DD_ENVIRONMENT_SITE_TYPE_WHITELABEL = 'whitelabel';

  protected $drupalDbHost = 'localhost';
  protected $drupalDbName = '';
  protected $drupalDbUsername = 'drupal';
  protected $drupalDbPassword = 'drupal';

  protected $dddbHost = 'localhost';
  protected $dddbName = 'dddb';
  protected $dddbUsername = 'drupal';
  protected $dddbPassword = 'drupal';

  protected $defaultCommonDbPrefix = 'dd_common';
  protected $commonDbName = '';
  protected $commonDbTables = array();
  protected $commonDbEnabled = FALSE;

  protected $env = '';
  protected $siteType = '';
  protected $state = '';
  protected $whiteLabelId = '';

  protected $solrCollection = '';
  protected $solrCollections = array();
  protected $solrServerIds = array();
  protected $solrServers = array();
  //protected $solrCollectionDefault = 'collection1';
  protected $solrHost = 'dd8.local';
  protected $solrPort = '8983';
  protected $solrUser = '';
  protected $solrPass = '';
  protected $useRemoteDb = FALSE;
  protected static $dddbTables = array(
    'authors',
    'Action',
    'Behests',
    'Bill',
    'BillAlignments',
    'BillAnalysis',
    'BillDiscussion',
    'BillSponsorRolls',
    'BillSponsors',
    'BillTypes',
    'BillVersion',
    'BillVersionCurrent',
    'BillVoteDetail',
    'BillVoteSummary',
    'CombinedAlignmentScores',
    'CombinedLobbyistEmployers',
    'CombinedRepresentations',
    'Committee',
    'CommitteeAuthors',
    'CommitteeHearings',
    'CommitteeHearings_id',
    'CommitteeNames',
    'ConsultantServesOn',
    'Contribution',
    'cosponsors',
    'currentUtterance',
    'currentUtteranceUnsorted',
    'DeprecatedOrganization',
    'DeprecatedPerson',
    'District',
    'FrequentDonors',
    'GeneralPublic',
    'Gift',
    'GiftCombined',
    'Governor',
    'Hearing',
    'HearingAgenda',
    'House',
    'InitialUtterance',
    'join_utrtag',
    'JobSnapshot',
    'KnownClients',
    'LegAnalystOffice',
    'LegAnalystOfficeRepresentation',
    'LegAvgPercentParticipation',
    'LegOfficePersonnel',
    'LegParticipation',
    'LegStaffGifts',
    'LegislativeStaff',
    'LegislativeStaffRepresentation',
    'Legislator',
    'Legislator_copy_copy',
    'LegislatureOffice',
    'LobbyingContracts',
    'LobbyingFirm',
    'LobbyingFirmState',
    'Lobbyist',
    'LobbyistDirectEmployment',
    'LobbyistEmployer',
    'LobbyistEmployment',
    'LobbyistRepresentation',
    'Motion',
    'OrgAlignments',
    'Organizations',
    'OrganizationStateAffiliation',
    'Payors',
    'Person',
    'PersonAffiliations',
    'PersonAffiliationsTable',
    'PersonClassifications',
    'PersonClassificationsTable',
    'PersonStateAffiliation',
    'servesOn',
    'SpeakerParticipation',
    'SpeakerProfileTypes',
    'State',
    'StateAgency',
    'StateAgencyRep',
    'StateAgencyRepRepresentation',
    'StateConstOffice',
    'StateConstOfficeRep',
    'StateConstOfficeRepRepresentation',
    'TT_currentCuts',
    'TT_Cuts',
    'TT_Editor',
    'TT_EditorStates',
    'TT_HostingUrl',
    'TT_ServiceRequests',
    'TT_Task',
    'TT_Videos',
    'Term',
    'Utterance',
    'Video',
    'Video_ttml',
  );

  /**
   * DdEnvironment constructor.
   */
  public function __construct() {
    $this->determineEnvFromPath();
  }

  /**
   * Get list of dddb tables.
   *
   * @return array
   *   Array of DDDB Tables.
   */
  public static function getDddbTables() {
    return self::$dddbTables;
  }

  /**
   * Get valid environments.
   *
   * @return array
   *   Array of valid environments.
   */
  public static function getValidEnvs() {
    return array(
      self::DD_ENVIRONMENT_LOCAL,
      self::DD_ENVIRONMENT_DEV,
      self::DD_ENVIRONMENT_QA,
      self::DD_ENVIRONMENT_PROD,
    );
  }

  /**
   * Get current environment.
   *
   * @return string
   *   Current environment (local/dev/qa/prod).
   */
  public function getEnv() {
    return $this->env;
  }

  /**
   * Check if is prod environment.
   *
   * @return bool
   *   TRUE, if is prod environment, FALSE otherwise.
   */
  public function getIsProdEnv() {
    return $this->env == self::DD_ENVIRONMENT_PROD;
  }

  /**
   * Get White Label Machine ID.
   *
   * @return string
   *   White Label machine ID from path.
   */
  public function getWhiteLabelId() {
    return $this->whiteLabelId;
  }

  /**
   * Set White Label Machine ID.
   *
   * @param string $id
   *   White label machine id.
   */
  public function setWhiteLabelId($id) {
    $this->whiteLabelId = $id;
  }

  /**
   * Get Solr Host.
   *
   * @return string
   *   Solr Host
   */
  public function getSolrHost() {
    return $this->solrHost;
  }
/**
   * Get Solr User.
   *
   * @return string
   *   Solr User
   */
  public function getSolrUser() {
    return $this->solrUser;
  }
/**
   * Get Solr Pass.
   *
   * @return string
   *   Solr pass
   */
  public function getSolrPass() {
    return $this->solrPass;
  }
 /**
   * Get Solr Servers.
   *
   * @return array()
   *   Solr Servers
   */
  public function getSolrServers() {
    return $this->solrServers;
  }
/**
   * add Solr Server.
   *
   * @param string $server
   *   Solr Server.
   */
  public function addSolrServer($server) {
    array_push($this->solrServers, $server);
  }

  /**
   * Set Solr Host.
   *
   * @param string $host
   *   Solr host.
   */
  public function setSolrHost($host) {
    $this->solrHost = $host;
  }
 /**
   * Set Solr User.
   *
   * @param string $user
   *   Solr user.
   */
  public function setSolrUser($user) {
    $this->solrUser = $user;
  }

 /**
   * Set Solr Password.
   *
   * @param string $pass
   *   Solr pass.
   */
  public function setSolrPass($pass) {
    $this->solrPass = $pass;
  }

/**
   * Set solr extension.
   *
   * @param string $server
   * @param string $extension
   *   Solr serverId.
   */
  public function setSolrExtension($server, $extension) {
    $this->solrServerIds[$server] = $extension;
  }

  /**
   * Get Solr Collection.
   * 
   * @param string $server
   * @return string
   *   Solr Collection
   */
  public function getSolrCollection($server) {
    if (!array_key_exists($server, $this->solrCollections)) {
	$this->solrCollections[$server] = '';
    } 
    if (!array_key_exists($server, $this->solrServerIds)) {
	$this->solrServerIds[$server] = $server;
    }
    if ($this->solrCollections[$server] != '') {
      return $this->solrCollections[$server];
    }
    else {
      if ($this->solrServerIds[$server] == NULL) {
		$this->solrServerIds[$server] = '';
		// just so we don't break
	}
      // Force default collection for local.
      if ($this->getEnv() == self::DD_ENVIRONMENT_LOCAL) {
        return $this->solrCollectionDefault;
      }

      if ($this->getSiteType() == self::DD_ENVIRONMENT_SITE_TYPE_BASE) {
        // For base site, use CA collection since we don't really need solr.
        if ($this->getEnv() == self::DD_ENVIRONMENT_PROD) {
          $collection = 'collection-ca-prod';
        }
        else {
          $collection = 'collection-ca-dev';
        }

        return $collection .  $this->solrServerIds[$server];
      }
      elseif ($this->getSiteType() == self::DD_ENVIRONMENT_SITE_TYPE_STATE) {
        // Using only 2 collections, collection-xx-prod and collection-xx-dev.
        if ($this->getEnv() == self::DD_ENVIRONMENT_PROD) {
          $collection = 'collection-' . strtolower($this->getState()) . '-prod';
        }
        else {
          $collection = 'collection-' . strtolower($this->getState()) . '-dev';
        }

        return $collection  . $this->solrServerIds[$server];
      }
      elseif ($this->getSiteType() == self::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
        // @todo adjust later for whitelabel.
        // Patterns:
        // collection-whitelabel_id-state
        // collection-state
        $collection = 'collection';
        //if ($this->getWhiteLabelId() != '') {
          //$collection .= '-' . $this->getWhiteLabelId();
        //}
        $collection .= '-' . strtolower($this->getState());
	if ($this->getEnv() == self::DD_ENVIRONMENT_PROD) {
          $collection = 'collection-' . strtolower($this->getState()) . '-prod';
        }
        else {
          $collection = 'collection-' . strtolower($this->getState()) . '-dev';
        }

        return $collection  . $this->solrServerIds[$server];
      }
    }
  }

  /**
   * Set Solr Collection for a given server.
   *
   * @param string $server
   * @param string $collection
   *   Solr collection.
   */
  public function setSolrCollection($server, $collection){
    $this->solrCollections[$server] = $collection;
  }

  /**
   * Get Solr Port.
   *
   * @return string
   *   Solr Port
   */
  public function getSolrPort() {
    return $this->solrPort;
  }

  /**
   * Set Solr Port.
   *
   * @param string $port
   *   Solr port.
   */
  public function setSolrPort($port) {
    $this->solrPort = $port;
  }

  /**
   * Set Current Environment.
   *
   * @param string $env
   *   Environment (local/dev/qa/prod)
   *
   * @throws \Exception
   *   If an invalid environment is passed in, throws exception.
   */
  public function setEnv($env) {
    // Validate env.
    switch ($env) {
      case self::DD_ENVIRONMENT_LOCAL:
      case self::DD_ENVIRONMENT_DEV:
      case self::DD_ENVIRONMENT_QA:
      case self::DD_ENVIRONMENT_PROD:
        $this->env = $env;
        break;

      default:
        throw new \Exception(t("DdEnvironment::setEnv - Invalid environment '@env'", array('@env' => $env)));
    }
  }

  /**
   * Get current state.
   *
   * @return string
   *   Upper case state
   */
  public function getState() {
    return $this->state;
  }

  /**
   * Set current state.
   *
   * @param string $state
   *   2-letter state, transforms to upper case
   */
  public function setState($state) {
    $this->state = strtoupper($state);
  }

  /**
   * Get Site Type.
   *
   * @return string
   *   Site type (base, state, whitelabel)
   */
  public function getSiteType() {
    return $this->siteType;
  }

  /**
   * Set Site Type.
   *
   * @param string $type
   *   Site type, like DD_ENVIRONMENT_SITE_TYPE_STATE.
   */
  public function setSiteType($type) {
    $this->siteType = $type;
  }

  /**
   * Determine Environment From Path.
   *
   * @throws \Exception
   *   If invalid state or env determined, throws exception.
   */
  public function determineEnvFromPath() {
    // Base path assumes /somedir/docroot/sites/default to get /somedir.
    $base_path = self::dirnameWithLevels(DRUPAL_ROOT, 1);
    $path_dirs = explode("/", $base_path);
    $base_dir_name = $path_dirs[count($path_dirs) - 1];

    if ($base_dir_name != 'dd-Drupal8' && preg_match("/^(..)-([a-zA-Z_0-9]*)/", $base_dir_name, $matches)) {
      // State/base site patterns.
      // /var/www/d8-prod
      // /var/www/d8-dev
      // /var/www/d8-qa
      // /var/www/ny-prod
      // /var/www/ny-qa
      // /var/www/ny-dev
      $state_or_base = strtoupper($matches[1]);
      $this->setEnv($matches[2]);

      // /var/www/d8* are considered the base site.
      if (strtolower($state_or_base) == 'd8') {
        $this->setSiteType(self::DD_ENVIRONMENT_SITE_TYPE_BASE);
      }
      else {
        $this->setSiteType(self::DD_ENVIRONMENT_SITE_TYPE_STATE);
        $this->setState($state_or_base);
      }

      if ($state_or_base == '' || $this->getEnv() == '' || !in_array($this->getEnv(), self::getValidEnvs())) {
        throw new \Exception(t("DdEnvironment::determineEnvFromPath - Invalid state @state or env @env determined from path @path", array(
          '@state' => $state_or_base,
          '@env' => $this->getEnv(),
          '@path' => __DIR__,
        )));
      }

    }
    elseif (preg_match("/^whitelabel-([a-zA-Z0-9]*)_([a-zA-Z][a-zA-Z])-([a-z]*)/", $base_dir_name, $matches)) {
      // State/base site patterns.
      // /var/www/whitelabel-sierraclub_ca-dev
      // /var/www/whitelabel-sierraclub_ca-prod)
      $this->setEnv($matches[3]);

      $this->setSiteType(self::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL);
      $this->setWhiteLabelId($matches[1]);
      $this->setState($matches[2]);

      if ($this->getWhiteLabelId() == '' || $this->getEnv() == '' || !in_array($this->getEnv(), self::getValidEnvs())) {
        throw new \Exception(t("DdEnvironment::determineEnvFromPath - Invalid white label ID @id or env @env determined from path @path", array(
          '@id' => $this->getWhiteLabelId(),
          '@env' => $this->getEnv(),
          '@path' => __DIR__,
        )));
      }
    }
    elseif (strpos($_SERVER['HTTP_HOST'], 'digitaldemocracy.org') === FALSE && !isset($_SERVER['AWS_PATH'])) {
      // Setup default local environment to use CA state.
      // Override in settings.local.php if necessary.
      $this->setEnv(self::DD_ENVIRONMENT_LOCAL);
      $this->setSiteType(self::DD_ENVIRONMENT_SITE_TYPE_STATE);
      $this->setState('CA');
    }
    else {
      throw new \Exception(t("DdEnvironment::determineEnvFromPath - Could not determine environment / site type from path @path", array(
        '@path' => __DIR__,
      )));
    }
  }

  /**
   * Get Drupal DB Suffix.
   *
   * @return string
   *   DB Suffix based on environment
   */
  public function getDrupalDbSuffix() {
    $suffix = '';
    if ($this->getSiteType() == self::DD_ENVIRONMENT_SITE_TYPE_STATE && $this->getEnv() != self::DD_ENVIRONMENT_LOCAL) {
      $suffix = '_' . strtolower($this->getState());
    }
    switch ($this->env) {
      case self::DD_ENVIRONMENT_DEV:
        return $suffix . '_dev';

      case self::DD_ENVIRONMENT_QA:
        return $suffix . '_qa';

      case self::DD_ENVIRONMENT_PROD:
        return $suffix . '_prod';

      case self::DD_ENVIRONMENT_LOCAL:
        return $suffix;

      default:
        die("DdEnvironment::getDrupalDatabaseSuffix - No valid environment set");
    }
  }

  /**
   * Get Drupal DB Prefix.
   *
   * @return string
   *   DB Prefix based on environment
   */
  public function getDrupalDbPrefix() {
    if ($this->getSiteType() == self::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      $prefix = 'dd_' . $this->getWhiteLabelId();
    }
    else {
      $prefix = 'dd_drupal8';
    }
    return $prefix;
  }

  /**
   * Get DB Host.
   *
   * @return string
   *   Default DB Host
   */
  public function getDrupalDbHost() {
    return $this->drupalDbHost;
  }

  /**
   * Set Drupal DB Host.
   *
   * @param string $host
   *   DB Host
   */
  public function setDrupalDbHost($host) {
    $this->drupalDbHost = $host;
  }

  /**
   * Get Drupal DB Name.
   *
   * @return string
   *   Overridden db name, or based on environment if not set.
   */
  public function getDrupalDbName() {
    return ($this->drupalDbName == '') ? ($this->getDrupalDbPrefix() . $this->getDrupalDbSuffix()) : $this->drupalDbName;
  }

  /**
   * Set Drupal DB Name.
   *
   * @param string $name
   *   DB Name
   */
  public function setDrupalDbName($name) {
    $this->drupalDbName = $name;
  }

  /**
   * Get Drupal DB Username.
   *
   * @return string
   *   Default Drupal DB Username.
   */
  public function getDrupalDbUsername() {
    return $this->drupalDbUsername;
  }

  /**
   * Set Drupal DB Username.
   *
   * @param string $username
   *   DB Username
   */
  public function setDrupalDbUsername($username) {
    $this->drupalDbUsername = $username;
  }

  /**
   * Get Drupal DB Password.
   *
   * @return string
   *   Default Drupal DB Password
   */
  public function getDrupalDbPassword() {
    return $this->drupalDbPassword;
  }

  /**
   * Set Drupal DB Password.
   *
   * @param string $password
   *   DB Password
   */
  public function setDrupalDbPassword($password) {
    $this->drupalDbPassword = $password;
  }

  /**
   * Get DDDB Name.
   *
   * @return string
   *   Default DDDB Name
   */
  public function getDddbName() {
    return $this->dddbName;
  }

  /**
   * Set DDDB Name.
   *
   * @param string $dddb_name
   *   DDDB Name
   */
  public function setDddbName($dddb_name) {
    $this->dddbName = $dddb_name;
  }

  /**
   * Get DDDB Host.
   *
   * @return string
   *   Default DDDB Host
   */
  public function getDddbHost() {
    return $this->dddbHost;
  }

  /**
   * Set DDDB Host.
   *
   * @param string $host
   *   DDDB Host
   */
  public function setDddbHost($host) {
    $this->dddbHost = $host;
  }

  /**
   * Get DDDB Username.
   *
   * @return string
   *   Default DDDB Username.
   */
  public function getDddbUsername() {
    return $this->dddbUsername;
  }

  /**
   * Set DDDB Username.
   *
   * @param string $username
   *   DDDB Username
   */
  public function setDddbUsername($username) {
    $this->dddbUsername = $username;
  }

  /**
   * Get DDDB Password.
   *
   * @return string
   *   Default DDDB Password
   */
  public function getDddbPassword() {
    return $this->dddbPassword;
  }

  /**
   * Set DDDB Password.
   *
   * @param string $password
   *   DDDB Password
   */
  public function setDddbPassword($password) {
    $this->dddbPassword = $password;
  }

  /**
   * Get Common DB Name.
   *
   * @return string
   *   Overridden db name, or based on environment if not set.
   */
  public function getCommonDbName() {
    return ($this->commonDbName == '') ? ($this->defaultCommonDbPrefix . $this->getDrupalDbSuffix()) : $this->commonDbName;
  }

  /**
   * Set Common DB Name.
   *
   * @param string $name
   *   DB Name
   */
  public function setCommonDbName($name) {
    $this->commonDbName = $name;
  }

  /**
   * Set Common DB Enabled.
   *
   * @param bool $value
   *   TRUE or FALSE
   */
  public function setCommonDbEnabled($value) {
    $this->commonDbEnabled = $value;
  }

  /**
   * Get Common DB Enabled.
   *
   * @return bool
   *   TRUE or FALSE
   */
  public function getCommonDbEnabled() {
    return $this->commonDbEnabled;
  }

  /**
   * Set Common DB Tables.
   *
   * @param array $tables
   *   DB Tables
   */
  public function setCommonDbTables($tables) {
    $this->commonDbTables = $tables;
  }

  /**
   * Get Common DB Tables.
   *
   * @return array
   *   Array of tables
   */
  public function getCommonDbTables() {
    return $this->commonDbTables;
  }

  /**
   * Use Remote Database not on same server.
   *
   * @param string $value
   *   If TRUE or FALSE will set remote usage.
   *
   * @return bool
   *   If value is blank, returns TRUE or FALSE.
   */
  public function useRemoteDb($value = '') {
    if ($value != '') {
      $this->useRemoteDb = $value;
    }
    return $this->useRemoteDb;
  }

  /**
   * Get Environment Info.
   *
   * @return string
   *   Debug information with all variables
   */
  public function getEnvInfo() {
    $common_db_tables = '  ' . implode("\n  ", $this->getCommonDbTables());

    $info = <<<HDOC
Environment: {$this->getEnv()}
Site Type: {$this->getSiteType()}
State: {$this->getState()}
White Label ID: {$this->getWhiteLabelId()}

Drupal DB Host: {$this->getDrupalDbHost()}
Drupal DB Name: {$this->getDrupalDbName()}
Drupal DB User: {$this->getDrupalDbUsername()}
Drupal DB Pass: {$this->getDrupalDbPassword()}

DDDB Host: {$this->getDddbHost()}
DDDB Name: {$this->getDddbName()}
DDDB User: {$this->getDddbUsername()}
DDDB Pass: {$this->getDddbPassword()}

Common DB Enabled: {$this->getCommonDbEnabled()}
Common DB Name: {$this->getCommonDbName()}
Common DB Tables:
{$common_db_tables}

Solr Host: {$this->getSolrHost()}
Solr Port: {$this->getSolrPort()}
Solr Collection: {$this->getSolrCollection()}
HDOC;

    return $info;
  }

  /**
   * Return a dirname of parents, for < PHP7 compatibility.
   *
   * @param string $path
   *   Path to use as source
   * @param int $levels
   *   Number of levels deep to get.
   *
   * @return string
   *   Parent path $levels deep.
   */
  public static function dirnameWithLevels($path, $levels = 1) {
    while ($levels--) {
      $path = dirname($path);
    }
    return $path;
  }
/**
 * Get name of solr core
 * 
 * @param string $coreName
 *
 * @return string
 *  returns name of solr core
*/
  public function getSolrCore($coreName) {
	return 'collection-ca-dev-' . $coreName;
 }

}
