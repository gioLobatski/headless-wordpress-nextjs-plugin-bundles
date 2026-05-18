<?php
namespace AwsWPTC\ResourceGroups;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Groups** service.
 * @method \AwsWPTC\Result createGroup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createGroupAsync(array $args = [])
 * @method \AwsWPTC\Result deleteGroup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteGroupAsync(array $args = [])
 * @method \AwsWPTC\Result getGroup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getGroupAsync(array $args = [])
 * @method \AwsWPTC\Result getGroupConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getGroupConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getGroupQuery(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getGroupQueryAsync(array $args = [])
 * @method \AwsWPTC\Result getTags(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getTagsAsync(array $args = [])
 * @method \AwsWPTC\Result groupResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise groupResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result listGroupResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listGroupResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result listGroups(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listGroupsAsync(array $args = [])
 * @method \AwsWPTC\Result putGroupConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putGroupConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result searchResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise searchResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result tag(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise tagAsync(array $args = [])
 * @method \AwsWPTC\Result ungroupResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise ungroupResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result untag(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise untagAsync(array $args = [])
 * @method \AwsWPTC\Result updateGroup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateGroupAsync(array $args = [])
 * @method \AwsWPTC\Result updateGroupQuery(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateGroupQueryAsync(array $args = [])
 */
class ResourceGroupsClient extends AwsClient {}
