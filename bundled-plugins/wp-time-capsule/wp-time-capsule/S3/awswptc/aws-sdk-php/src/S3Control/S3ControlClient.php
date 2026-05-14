<?php
namespace AwsWPTC\S3Control;

use AwsWPTC\AwsClient;
use AwsWPTC\CacheInterface;
use AwsWPTC\HandlerList;
use AwsWPTC\S3\UseArnRegion\Configuration;
use AwsWPTC\S3\UseArnRegion\ConfigurationInterface;
use AwsWPTC\S3\UseArnRegion\ConfigurationProvider as UseArnRegionConfigurationProvider;
use GuzzleHttpWPTC\Promise\PromiseInterface;

/**
 * This client is used to interact with the **AWS S3 Control** service.
 * @method \AwsWPTC\Result createAccessPoint(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createAccessPointAsync(array $args = [])
 * @method \AwsWPTC\Result createAccessPointForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createAccessPointForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result createBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createBucketAsync(array $args = [])
 * @method \AwsWPTC\Result createJob(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createJobAsync(array $args = [])
 * @method \AwsWPTC\Result deleteAccessPoint(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteAccessPointAsync(array $args = [])
 * @method \AwsWPTC\Result deleteAccessPointForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteAccessPointForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result deleteAccessPointPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteAccessPointPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result deleteAccessPointPolicyForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteAccessPointPolicyForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketLifecycleConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketLifecycleConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result deleteJobTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteJobTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result deletePublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deletePublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result deleteStorageLensConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteStorageLensConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteStorageLensConfigurationTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteStorageLensConfigurationTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result describeJob(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeJobAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPoint(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointConfigurationForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointConfigurationForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointPolicyForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointPolicyForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointPolicyStatus(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointPolicyStatusAsync(array $args = [])
 * @method \AwsWPTC\Result getAccessPointPolicyStatusForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAccessPointPolicyStatusForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result getBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketLifecycleConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketLifecycleConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result getJobTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getJobTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result getPublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getPublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result getStorageLensConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getStorageLensConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getStorageLensConfigurationTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getStorageLensConfigurationTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result listAccessPoints(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listAccessPointsAsync(array $args = [])
 * @method \AwsWPTC\Result listAccessPointsForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listAccessPointsForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result listJobs(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listJobsAsync(array $args = [])
 * @method \AwsWPTC\Result listRegionalBuckets(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listRegionalBucketsAsync(array $args = [])
 * @method \AwsWPTC\Result listStorageLensConfigurations(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listStorageLensConfigurationsAsync(array $args = [])
 * @method \AwsWPTC\Result putAccessPointConfigurationForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putAccessPointConfigurationForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result putAccessPointPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putAccessPointPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result putAccessPointPolicyForObjectLambda(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putAccessPointPolicyForObjectLambdaAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketLifecycleConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketLifecycleConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result putJobTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putJobTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result putPublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putPublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result putStorageLensConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putStorageLensConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putStorageLensConfigurationTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putStorageLensConfigurationTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result updateJobPriority(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateJobPriorityAsync(array $args = [])
 * @method \AwsWPTC\Result updateJobStatus(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateJobStatusAsync(array $args = [])
 */
class S3ControlClient extends AwsClient 
{
    public static function getArguments()
    {
        $args = parent::getArguments();
        return $args + [
            'use_dual_stack_endpoint' => [
                'type' => 'config',
                'valid' => ['bool'],
                'doc' => 'Set to true to send requests to an S3 Control Dual Stack'
                    . ' endpoint by default, which enables IPv6 Protocol.'
                    . ' Can be enabled or disabled on individual operations by setting'
                    . ' \'@use_dual_stack_endpoint\' to true or false.',
                'default' => false,
            ],
            'use_arn_region' => [
                'type'    => 'config',
                'valid'   => [
                    'bool',
                    Configuration::class,
                    CacheInterface::class,
                    'callable'
                ],
                'doc'     => 'Set to true to allow passed in ARNs to override'
                    . ' client region. Accepts...',
                'fn' => [__CLASS__, '_apply_use_arn_region'],
                'default' => [UseArnRegionConfigurationProvider::class, 'defaultProvider'],
            ],
        ];
    }

    public static function _apply_use_arn_region($value, array &$args, HandlerList $list)
    {
        if ($value instanceof CacheInterface) {
            $value = UseArnRegionConfigurationProvider::defaultProvider($args);
        }
        if (is_callable($value)) {
            $value = $value();
        }
        if ($value instanceof PromiseInterface) {
            $value = $value->wait();
        }
        if ($value instanceof ConfigurationInterface) {
            $args['use_arn_region'] = $value;
        } else {
            // The Configuration class itself will validate other inputs
            $args['use_arn_region'] = new Configuration($value);
        }
    }

    /**
     * {@inheritdoc}
     *
     * In addition to the options available to
     * {@see AwsWPTC\AwsClient::__construct}, S3ControlClient accepts the following
     * option:
     *
     * - use_dual_stack_endpoint: (bool) Set to true to send requests to an S3
     *   Control Dual Stack endpoint by default, which enables IPv6 Protocol.
     *   Can be enabled or disabled on individual operations by setting
     *   '@use_dual_stack_endpoint\' to true or false. Note:
     *   you cannot use it together with an accelerate endpoint.
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        parent::__construct($args);
        $stack = $this->getHandlerList();
        $stack->appendBuild(
            S3ControlEndpointMiddleware::wrap(
                $this->getRegion(),
                [
                    'dual_stack' => $this->getConfig('use_dual_stack_endpoint'),
                ]
            ),
            's3control.endpoint_middleware'
        );
        $stack->appendBuild(
            EndpointArnMiddleware::wrap(
                $this->getApi(),
                $this->getRegion(),
                [
                    'use_arn_region' => $this->getConfig('use_arn_region'),
                    'dual_stack' => $this->getConfig('use_dual_stack_endpoint'),
                    'endpoint' => isset($args['endpoint'])
                        ? $args['endpoint']
                        : null
                ]
            ),
            's3control.endpoint_arn_middleware'
        );
    }
}
