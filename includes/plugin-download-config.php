<?php
/**
 * Plugin Download Configuration
 * 
 * This file maps plugin slugs to their GitHub Release download URLs.
 * Update this file when adding new plugins or updating versions.
 * 
 * FORMAT:
 * 'plugin-slug' => array(
 *     'zip_file' => 'plugin-name.zip',
 *     'version' => 'v1.0.0',  // GitHub release tag
 * )
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

return array(
    // E-Commerce
    'woocommerce' => array(
        'zip_file' => 'woocommerce.zip',
        'version' => 'v1.1.0',
    ),
    
    // GraphQL
    'wp-graphql' => array(
        'zip_file' => 'wp-graphql.zip',
        'version' => 'v1.1.0',
    ),
    'wpgraphql-acf' => array(
        'zip_file' => 'wpgraphql-acf.zip',
        'version' => 'v1.1.0',
    ),
    'wpgraphql-ide' => array(
        'zip_file' => 'wpgraphql-ide.zip',
        'version' => 'v1.1.0',
    ),
    'wpgraphql-smart-cache' => array(
        'zip_file' => 'wpgraphql-smart-cache.zip',
        'version' => 'v1.1.0',
    ),
    // 'wp-graphql-woocommerce' => array(  // NOT IN RELEASE YET
    //     'zip_file' => 'wp-graphql-woocommerce.zip',
    //     'version' => 'v1.1.0',
    // ),
    'wp-graphql-tax-query' => array(
        'zip_file' => 'wp-graphql-tax-query-develop.zip',
        'version' => 'v1.1.0',
    ),
    // 'wp-graphql-rank-math' => array(  // NOT IN RELEASE YET
    //     'zip_file' => 'wp-graphql-rank-math.zip',
    //     'version' => 'v1.1.0',
    // ),
    
    // SEO
    'seo-by-rank-math' => array(
        'zip_file' => 'seo-by-rank-math.zip',
        'version' => 'v1.1.0',
    ),
    
    // Security
    'ithemes-security-pro' => array(
        'zip_file' => 'ithemes-security-pro.zip',
        'version' => 'v1.1.0',
    ),
    
    // Backup & Management
    'wp-time-capsule' => array(
        'zip_file' => 'wp-time-capsule.zip',
        'version' => 'v1.1.0',
    ),
    'iwp-client' => array(
        'zip_file' => 'iwp-client.zip',
        'version' => 'v1.1.0',
    ),
    
    // Content & Media
    'advanced-custom-fields-pro' => array(
        'zip_file' => 'advanced-custom-fields-pro.zip',
        'version' => 'v1.1.0',
    ),
    'classic-editor' => array(
        'zip_file' => 'classic-editor.zip',
        'version' => 'v1.1.0',
    ),
    'duplicate-page' => array(
        'zip_file' => 'duplicate-page.zip',
        'version' => 'v1.1.0',
    ),
    'svg-support' => array(
        'zip_file' => 'svg-support.zip',
        'version' => 'v1.1.0',
    ),
    'imagify' => array(
        'zip_file' => 'imagify.zip',
        'version' => 'v1.1.0',
    ),
    
    // Forms
    'gravityforms' => array(
        'zip_file' => 'gravityforms.zip',
        'version' => 'v1.1.0',
    ),
);
