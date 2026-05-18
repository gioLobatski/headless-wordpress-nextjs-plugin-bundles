<?php
namespace AwsWPTC\S3Outposts;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **Amazon S3 on Outposts** service.
 * @method \AwsWPTC\Result createEndpoint(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createEndpointAsync(array $args = [])
 * @method \AwsWPTC\Result deleteEndpoint(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteEndpointAsync(array $args = [])
 * @method \AwsWPTC\Result listEndpoints(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listEndpointsAsync(array $args = [])
 */
class S3OutpostsClient extends AwsClient {}
