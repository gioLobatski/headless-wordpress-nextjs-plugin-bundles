<?php
namespace AwsWPTC\IdentityStore;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **AWS SSO Identity Store** service.
 * @method \AwsWPTC\Result describeGroup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeGroupAsync(array $args = [])
 * @method \AwsWPTC\Result describeUser(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeUserAsync(array $args = [])
 * @method \AwsWPTC\Result listGroups(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listGroupsAsync(array $args = [])
 * @method \AwsWPTC\Result listUsers(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listUsersAsync(array $args = [])
 */
class IdentityStoreClient extends AwsClient {}
