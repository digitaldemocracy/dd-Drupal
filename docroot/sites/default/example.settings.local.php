<?php

// Uncomment to disable css/jss aggregation, enable errors.
/*
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$config['system.logging']['error_level'] = 'verbose';
$settings['skip_permissions_hardening'] = TRUE;
*/

// Uncomment to enable development features and disable some caches.
/*
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['menu'] = 'cache.backend.null';
*/

// Uncomment to enable use of common database.
//$_dd_env->setCommonDbEnabled(TRUE);

// Overriding DB Information - Do not use $databases, use the following:
/*
$_dd_env->setDrupalDbHost('localhost');
$_dd_env->setDrupalDbName('drupal');
$_dd_env->setDrupalDbUsername('drupal');
$_dd_env->setDrupalDbPassword('drupal');
$_dd_env->setDddbHost('localhost');
$_dd_env->setDddbName('dddb');
$_dd_env->setDddbUsername('drupal');
$_dd_env->setDddbPassword('drupal');
 */

// To use a remote DB not on the same server, enable this line:
// $_dd_env->useRemoteDb(TRUE);

// To override environment settings
/*
$_dd_env->setSiteType(\Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL);
$_dd_env->setWhiteLabelId('sierraclub_ca');

$_dd_env->setSiteType(\Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_STATE);
$_dd_env->setState('ny');

$_dd_env->setSiteType(\Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_BASE);
*/

// To override Solr server information.
/*
$_dd_env->setSolrHost('localhost');
$_dd_env->setSolrPort('8983');
$_dd_env->setSolrCollection('collection1');
*/

// To set Amazon S3 credentials
/*
$config['s3fs.settings']['access_key'] = 'ACCESSKEY';
$config['s3fs.settings']['secret_key'] = 'SECRETKEY';
$config['s3fs.settings']['bucket'] = 'videostorage-us-west';
$config['s3fs.settings']['region'] = 'us-west-2';
*/

// To enable base site to redirect to last state selected by cookie
/*
$settings['last_state_redirect'] = TRUE;
*/
