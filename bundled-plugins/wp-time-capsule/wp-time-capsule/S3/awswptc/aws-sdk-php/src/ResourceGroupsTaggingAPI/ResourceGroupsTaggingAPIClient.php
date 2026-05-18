<?php
namespace AwsWPTC\ResourceGroupsTaggingAPI;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Groups Tagging API** service.
 * @method \AwsWPTC\Result describeReportCreation(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeReportCreationAsync(array $args = [])
 * @method \AwsWPTC\Result getComplianceSummary(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getComplianceSummaryAsync(array $args = [])
 * @method \AwsWPTC\Result getResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result getTagKeys(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getTagKeysAsync(array $args = [])
 * @method \AwsWPTC\Result getTagValues(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getTagValuesAsync(array $args = [])
 * @method \AwsWPTC\Result startReportCreation(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise startReportCreationAsync(array $args = [])
 * @method \AwsWPTC\Result tagResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise tagResourcesAsync(array $args = [])
 * @method \AwsWPTC\Result untagResources(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise untagResourcesAsync(array $args = [])
 */
class ResourceGroupsTaggingAPIClient extends AwsClient {}
