<?php
namespace AwsWPTC\S3;

use AwsWPTC\Api\ApiProvider;
use AwsWPTC\Api\DocModel;
use AwsWPTC\Api\Service;
use AwsWPTC\AwsClient;
use AwsWPTC\CacheInterface;
use AwsWPTC\ClientResolver;
use AwsWPTC\Command;
use AwsWPTC\Exception\AwsException;
use AwsWPTC\HandlerList;
use AwsWPTC\InputValidationMiddleware;
use AwsWPTC\Middleware;
use AwsWPTC\Retry\QuotaManager;
use AwsWPTC\RetryMiddleware;
use AwsWPTC\ResultInterface;
use AwsWPTC\CommandInterface;
use AwsWPTC\RetryMiddlewareV2;
use AwsWPTC\S3\UseArnRegion\Configuration;
use AwsWPTC\S3\UseArnRegion\ConfigurationInterface;
use AwsWPTC\S3\UseArnRegion\ConfigurationProvider as UseArnRegionConfigurationProvider;
use AwsWPTC\S3\RegionalEndpoint\ConfigurationProvider;
use GuzzleHttpWPTC\Exception\RequestException;
use GuzzleHttpWPTC\Promise\Promise;
use GuzzleHttpWPTC\Promise\PromiseInterface;
use PsrWPTC\Http\Message\RequestInterface;

/**
 * Client used to interact with **Amazon Simple Storage Service (Amazon S3)**.
 *
 * @method \AwsWPTC\Result abortMultipartUpload(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise abortMultipartUploadAsync(array $args = [])
 * @method \AwsWPTC\Result completeMultipartUpload(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise completeMultipartUploadAsync(array $args = [])
 * @method \AwsWPTC\Result copyObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise copyObjectAsync(array $args = [])
 * @method \AwsWPTC\Result createBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createBucketAsync(array $args = [])
 * @method \AwsWPTC\Result createMultipartUpload(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createMultipartUploadAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketAnalyticsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketCors(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketCorsAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketEncryption(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketEncryptionAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketIntelligentTieringConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketIntelligentTieringConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketInventoryConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketInventoryConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketLifecycle(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketLifecycleAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketMetricsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketMetricsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketOwnershipControls(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketOwnershipControlsAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketReplication(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketReplicationAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBucketWebsite(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBucketWebsiteAsync(array $args = [])
 * @method \AwsWPTC\Result deleteObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteObjectAsync(array $args = [])
 * @method \AwsWPTC\Result deleteObjectTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteObjectTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result deleteObjects(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteObjectsAsync(array $args = [])
 * @method \AwsWPTC\Result deletePublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deletePublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketAccelerateConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketAccelerateConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketAcl(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketAclAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketAnalyticsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketCors(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketCorsAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketEncryption(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketEncryptionAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketIntelligentTieringConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketIntelligentTieringConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketInventoryConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketInventoryConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketLifecycle(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketLifecycleAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketLifecycleConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketLifecycleConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketLocation(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketLocationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketLogging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketLoggingAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketMetricsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketMetricsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketNotification(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketNotificationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketNotificationConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketNotificationConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketOwnershipControls(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketOwnershipControlsAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketPolicyStatus(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketPolicyStatusAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketReplication(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketReplicationAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketRequestPayment(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketRequestPaymentAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketVersioning(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketVersioningAsync(array $args = [])
 * @method \AwsWPTC\Result getBucketWebsite(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getBucketWebsiteAsync(array $args = [])
 * @method \AwsWPTC\Result getObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectAcl(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectAclAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectLegalHold(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectLegalHoldAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectLockConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectLockConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectRetention(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectRetentionAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result getObjectTorrent(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getObjectTorrentAsync(array $args = [])
 * @method \AwsWPTC\Result getPublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getPublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result headBucket(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise headBucketAsync(array $args = [])
 * @method \AwsWPTC\Result headObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise headObjectAsync(array $args = [])
 * @method \AwsWPTC\Result listBucketAnalyticsConfigurations(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listBucketAnalyticsConfigurationsAsync(array $args = [])
 * @method \AwsWPTC\Result listBucketIntelligentTieringConfigurations(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listBucketIntelligentTieringConfigurationsAsync(array $args = [])
 * @method \AwsWPTC\Result listBucketInventoryConfigurations(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listBucketInventoryConfigurationsAsync(array $args = [])
 * @method \AwsWPTC\Result listBucketMetricsConfigurations(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listBucketMetricsConfigurationsAsync(array $args = [])
 * @method \AwsWPTC\Result listBuckets(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listBucketsAsync(array $args = [])
 * @method \AwsWPTC\Result listMultipartUploads(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listMultipartUploadsAsync(array $args = [])
 * @method \AwsWPTC\Result listObjectVersions(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listObjectVersionsAsync(array $args = [])
 * @method \AwsWPTC\Result listObjects(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listObjectsAsync(array $args = [])
 * @method \AwsWPTC\Result listObjectsV2(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listObjectsV2Async(array $args = [])
 * @method \AwsWPTC\Result listParts(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listPartsAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketAccelerateConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketAccelerateConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketAcl(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketAclAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketAnalyticsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketCors(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketCorsAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketEncryption(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketEncryptionAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketIntelligentTieringConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketIntelligentTieringConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketInventoryConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketInventoryConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketLifecycle(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketLifecycleAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketLifecycleConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketLifecycleConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketLogging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketLoggingAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketMetricsConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketMetricsConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketNotification(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketNotificationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketNotificationConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketNotificationConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketOwnershipControls(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketOwnershipControlsAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketPolicy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketPolicyAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketReplication(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketReplicationAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketRequestPayment(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketRequestPaymentAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketVersioning(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketVersioningAsync(array $args = [])
 * @method \AwsWPTC\Result putBucketWebsite(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putBucketWebsiteAsync(array $args = [])
 * @method \AwsWPTC\Result putObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectAsync(array $args = [])
 * @method \AwsWPTC\Result putObjectAcl(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectAclAsync(array $args = [])
 * @method \AwsWPTC\Result putObjectLegalHold(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectLegalHoldAsync(array $args = [])
 * @method \AwsWPTC\Result putObjectLockConfiguration(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectLockConfigurationAsync(array $args = [])
 * @method \AwsWPTC\Result putObjectRetention(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectRetentionAsync(array $args = [])
 * @method \AwsWPTC\Result putObjectTagging(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putObjectTaggingAsync(array $args = [])
 * @method \AwsWPTC\Result putPublicAccessBlock(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putPublicAccessBlockAsync(array $args = [])
 * @method \AwsWPTC\Result restoreObject(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise restoreObjectAsync(array $args = [])
 * @method \AwsWPTC\Result selectObjectContent(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise selectObjectContentAsync(array $args = [])
 * @method \AwsWPTC\Result uploadPart(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise uploadPartAsync(array $args = [])
 * @method \AwsWPTC\Result uploadPartCopy(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise uploadPartCopyAsync(array $args = [])
 * @method \AwsWPTC\Result writeGetObjectResponse(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise writeGetObjectResponseAsync(array $args = [])
 */
class S3Client extends AwsClient implements S3ClientInterface
{
    use S3ClientTrait;

    /** @var array */
    private static $mandatoryAttributes = ['Bucket', 'Key'];

    public static function getArguments()
    {
        $args = parent::getArguments();
        $args['retries']['fn'] = [__CLASS__, '_applyRetryConfig'];
        $args['api_provider']['fn'] = [__CLASS__, '_applyApiProvider'];

        return $args + [
            'bucket_endpoint' => [
                'type'    => 'config',
                'valid'   => ['bool'],
                'doc'     => 'Set to true to send requests to a hardcoded '
                    . 'bucket endpoint rather than create an endpoint as a '
                    . 'result of injecting the bucket into the URL. This '
                    . 'option is useful for interacting with CNAME endpoints.',
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
            'use_accelerate_endpoint' => [
                'type' => 'config',
                'valid' => ['bool'],
                'doc' => 'Set to true to send requests to an S3 Accelerate'
                    . ' endpoint by default. Can be enabled or disabled on'
                    . ' individual operations by setting'
                    . ' \'@use_accelerate_endpoint\' to true or false. Note:'
                    . ' you must enable S3 Accelerate on a bucket before it can'
                    . ' be accessed via an Accelerate endpoint.',
                'default' => false,
            ],
            'use_dual_stack_endpoint' => [
                'type' => 'config',
                'valid' => ['bool'],
                'doc' => 'Set to true to send requests to an S3 Dual Stack'
                    . ' endpoint by default, which enables IPv6 Protocol.'
                    . ' Can be enabled or disabled on individual operations by setting'
                    . ' \'@use_dual_stack_endpoint\' to true or false.',
                'default' => false,
            ],
            'use_path_style_endpoint' => [
                'type' => 'config',
                'valid' => ['bool'],
                'doc' => 'Set to true to send requests to an S3 path style'
                    . ' endpoint by default.'
                    . ' Can be enabled or disabled on individual operations by setting'
                    . ' \'@use_path_style_endpoint\' to true or false.',
                'default' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * In addition to the options available to
     * {@see AwsWPTC\AwsClient::__construct}, S3Client accepts the following
     * options:
     *
     * - bucket_endpoint: (bool) Set to true to send requests to a
     *   hardcoded bucket endpoint rather than create an endpoint as a result
     *   of injecting the bucket into the URL. This option is useful for
     *   interacting with CNAME endpoints.
     * - calculate_md5: (bool) Set to false to disable calculating an MD5
     *   for all Amazon S3 signed uploads.
     * - s3_us_east_1_regional_endpoint:
     *   (AwsWPTC\S3\RegionalEndpoint\ConfigurationInterface|AwsWPTC\CacheInterface\|callable|string|array)
     *   Specifies whether to use regional or legacy endpoints for the us-east-1
     *   region. Provide an AwsWPTC\S3\RegionalEndpoint\ConfigurationInterface object, an
     *   instance of AwsWPTC\CacheInterface, a callable configuration provider used
     *   to create endpoint configuration, a string value of `legacy` or
     *   `regional`, or an associative array with the following keys:
     *   endpoint_types: (string)  Set to `legacy` or `regional`, defaults to
     *   `legacy`
     * - use_accelerate_endpoint: (bool) Set to true to send requests to an S3
     *   Accelerate endpoint by default. Can be enabled or disabled on
     *   individual operations by setting '@use_accelerate_endpoint' to true or
     *   false. Note: you must enable S3 Accelerate on a bucket before it can be
     *   accessed via an Accelerate endpoint.
     * - use_arn_region: (AwsWPTC\S3\UseArnRegion\ConfigurationInterface,
     *   AwsWPTC\CacheInterface, bool, callable) Set to true to enable the client
     *   to use the region from a supplied ARN argument instead of the client's
     *   region. Provide an instance of AwsWPTC\S3\UseArnRegion\ConfigurationInterface,
     *   an instance of AwsWPTC\CacheInterface, a callable that provides a promise for
     *   a Configuration object, or a boolean value. Defaults to false (i.e.
     *   the SDK will not follow the ARN region if it conflicts with the client
     *   region and instead throw an error).
     * - use_dual_stack_endpoint: (bool) Set to true to send requests to an S3
     *   Dual Stack endpoint by default, which enables IPv6 Protocol.
     *   Can be enabled or disabled on individual operations by setting
     *   '@use_dual_stack_endpoint\' to true or false. Note:
     *   you cannot use it together with an accelerate endpoint.
     * - use_path_style_endpoint: (bool) Set to true to send requests to an S3
     *   path style endpoint by default.
     *   Can be enabled or disabled on individual operations by setting
     *   '@use_path_style_endpoint\' to true or false. Note:
     *   you cannot use it together with an accelerate endpoint.
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        if (
            !isset($args['s3_us_east_1_regional_endpoint'])
            || $args['s3_us_east_1_regional_endpoint'] instanceof CacheInterface
        ) {
            $args['s3_us_east_1_regional_endpoint'] = ConfigurationProvider::defaultProvider($args);
        }
        parent::__construct($args);
        $stack = $this->getHandlerList();
        $stack->appendInit(SSECMiddleware::wrap($this->getEndpoint()->getScheme()), 's3.ssec');
        $stack->appendBuild(ApplyChecksumMiddleware::wrap($this->getApi()), 's3.checksum');
        $stack->appendBuild(
            Middleware::contentType(['PutObject', 'UploadPart']),
            's3.content_type'
        );

        // Use the bucket style middleware when using a "bucket_endpoint" (for cnames)
        if ($this->getConfig('bucket_endpoint')) {
            $stack->appendBuild(BucketEndpointMiddleware::wrap(), 's3.bucket_endpoint');
        } else {
            $stack->appendBuild(
                S3EndpointMiddleware::wrap(
                    $this->getRegion(),
                    $this->getConfig('endpoint_provider'),
                    [
                        'dual_stack' => $this->getConfig('use_dual_stack_endpoint'),
                        'accelerate' => $this->getConfig('use_accelerate_endpoint'),
                        'path_style' => $this->getConfig('use_path_style_endpoint'),
                    ]
                ),
                's3.endpoint_middleware'
            );
        }

        $stack->appendBuild(
            BucketEndpointArnMiddleware::wrap(
                $this->getApi(),
                $this->getRegion(),
                [
                    'use_arn_region' => $this->getConfig('use_arn_region'),
                    'dual_stack' => $this->getConfig('use_dual_stack_endpoint'),
                    'accelerate' => $this->getConfig('use_accelerate_endpoint'),
                    'path_style' => $this->getConfig('use_path_style_endpoint'),
                    'endpoint' => isset($args['endpoint'])
                        ? $args['endpoint']
                        : null
                ]
            ),
            's3.bucket_endpoint_arn'
        );

        $stack->appendValidate(
            InputValidationMiddleware::wrap($this->getApi(), self::$mandatoryAttributes),
            'input_validation_middleware'
        );
        $stack->appendSign(PutObjectUrlMiddleware::wrap(), 's3.put_object_url');
        $stack->appendSign(PermanentRedirectMiddleware::wrap(), 's3.permanent_redirect');
        $stack->appendInit(Middleware::sourceFile($this->getApi()), 's3.source_file');
        $stack->appendInit($this->getSaveAsParameter(), 's3.save_as');
        $stack->appendInit($this->getLocationConstraintMiddleware(), 's3.location');
        $stack->appendInit($this->getEncodingTypeMiddleware(), 's3.auto_encode');
        $stack->appendInit($this->getHeadObjectMiddleware(), 's3.head_object');
    }

    /**
     * Determine if a string is a valid name for a DNS compatible Amazon S3
     * bucket.
     *
     * DNS compatible bucket names can be used as a subdomain in a URL (e.g.,
     * "<bucket>.s3.amazonaws.com").
     *
     * @param string $bucket Bucket name to check.
     *
     * @return bool
     */
    public static function isBucketDnsCompatible($bucket)
    {
        $bucketLen = strlen($bucket);

        return ($bucketLen >= 3 && $bucketLen <= 63) &&
            // Cannot look like an IP address
            !filter_var($bucket, FILTER_VALIDATE_IP) &&
            preg_match('/^[a-z0-9]([a-z0-9\-\.]*[a-z0-9])?$/', $bucket);
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

    public function createPresignedRequest(CommandInterface $command, $expires, array $options = [])
    {
        $command = clone $command;
        $command->getHandlerList()->remove('signer');

        /** @var \AwsWPTC\Signature\SignatureInterface $signer */
        $signer = call_user_func(
            $this->getSignatureProvider(),
            $this->getConfig('signature_version'),
            $this->getConfig('signing_name'),
            $this->getConfig('signing_region')
        );

        return $signer->presign(
            \AwsWPTC\serialize($command),
            $this->getCredentials()->wait(),
            $expires,
            $options
        );
    }

    /**
     * Returns the URL to an object identified by its bucket and key.
     *
     * The URL returned by this method is not signed nor does it ensure that the
     * bucket and key given to the method exist. If you need a signed URL, then
     * use the {@see \AwsWPTC\S3\S3Client::createPresignedRequest} method and get
     * the URI of the signed request.
     *
     * @param string $bucket  The name of the bucket where the object is located
     * @param string $key     The key of the object
     *
     * @return string The URL to the object
     */
    public function getObjectUrl($bucket, $key)
    {
        $command = $this->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);

        return (string) \AwsWPTC\serialize($command)->getUri();
    }

    /**
     * Raw URL encode a key and allow for '/' characters
     *
     * @param string $key Key to encode
     *
     * @return string Returns the encoded key
     */
    public static function encodeKey($key)
    {
        return str_replace('%2F', '/', rawurlencode($key));
    }

    /**
     * Provides a middleware that removes the need to specify LocationConstraint on CreateBucket.
     *
     * @return \Closure
     */
    private function getLocationConstraintMiddleware()
    {
        $region = $this->getRegion();
        return static function (callable $handler) use ($region) {
            return function (Command $command, $request = null) use ($handler, $region) {
                if ($command->getName() === 'CreateBucket') {
                    $locationConstraint = isset($command['CreateBucketConfiguration']['LocationConstraint'])
                        ? $command['CreateBucketConfiguration']['LocationConstraint']
                        : null;

                    if ($locationConstraint === 'us-east-1') {
                        unset($command['CreateBucketConfiguration']);
                    } elseif ('us-east-1' !== $region && empty($locationConstraint)) {
                        $command['CreateBucketConfiguration'] = ['LocationConstraint' => $region];
                    }
                }

                return $handler($command, $request);
            };
        };
    }

    /**
     * Provides a middleware that supports the `SaveAs` parameter.
     *
     * @return \Closure
     */
    private function getSaveAsParameter()
    {
        return static function (callable $handler) {
            return function (Command $command, $request = null) use ($handler) {
                if ($command->getName() === 'GetObject' && isset($command['SaveAs'])) {
                    $command['@http']['sink'] = $command['SaveAs'];
                    unset($command['SaveAs']);
                }

                return $handler($command, $request);
            };
        };
    }

    /**
     * Provides a middleware that disables content decoding on HeadObject
     * commands.
     *
     * @return \Closure
     */
    private function getHeadObjectMiddleware()
    {
        return static function (callable $handler) {
            return function (
                CommandInterface $command,
                RequestInterface $request = null
            ) use ($handler) {
                if ($command->getName() === 'HeadObject'
                    && !isset($command['@http']['decode_content'])
                ) {
                    $command['@http']['decode_content'] = false;
                }

                return $handler($command, $request);
            };
        };
    }

    /**
     * Provides a middleware that autopopulates the EncodingType parameter on
     * ListObjects commands.
     *
     * @return \Closure
     */
    private function getEncodingTypeMiddleware()
    {
        return static function (callable $handler) {
            return function (Command $command, $request = null) use ($handler) {
                $autoSet = false;
                if ($command->getName() === 'ListObjects'
                    && empty($command['EncodingType'])
                ) {
                    $command['EncodingType'] = 'url';
                    $autoSet = true;
                }

                return $handler($command, $request)
                    ->then(function (ResultInterface $result) use ($autoSet) {
                        if ($result['EncodingType'] === 'url' && $autoSet) {
                            static $topLevel = [
                                'Delimiter',
                                'Marker',
                                'NextMarker',
                                'Prefix',
                            ];
                            static $nested = [
                                ['Contents', 'Key'],
                                ['CommonPrefixes', 'Prefix'],
                            ];

                            foreach ($topLevel as $key) {
                                if (isset($result[$key])) {
                                    $result[$key] = urldecode($result[$key]);
                                }
                            }
                            foreach ($nested as $steps) {
                                if (isset($result[$steps[0]])) {
                                    foreach ($result[$steps[0]] as $key => $part) {
                                        if (isset($part[$steps[1]])) {
                                            $result[$steps[0]][$key][$steps[1]]
                                                = urldecode($part[$steps[1]]);
                                        }
                                    }
                                }
                            }

                        }

                        return $result;
                    });
            };
        };
    }

    /** @internal */
    public static function _applyRetryConfig($value, $args, HandlerList $list)
    {
        if ($value) {
            $config = \AwsWPTC\Retry\ConfigurationProvider::unwrap($value);

            if ($config->getMode() === 'legacy') {
                $maxRetries = $config->getMaxAttempts() - 1;
                $decider = RetryMiddleware::createDefaultDecider($maxRetries);
                $decider = function ($retries, $command, $request, $result, $error) use ($decider, $maxRetries) {
                    $maxRetries = null !== $command['@retries']
                        ? $command['@retries']
                        : $maxRetries;

                    if ($decider($retries, $command, $request, $result, $error)) {
                        return true;
                    }

                    if ($error instanceof AwsException
                        && $retries < $maxRetries
                    ) {
                        if ($error->getResponse()
                            && $error->getResponse()->getStatusCode() >= 400
                        ) {
                            return strpos(
                                    $error->getResponse()->getBody(),
                                    'Your socket connection to the server'
                                ) !== false;
                        }

                        if ($error->getPrevious() instanceof RequestException) {
                            // All commands except CompleteMultipartUpload are
                            // idempotent and may be retried without worry if a
                            // networking error has occurred.
                            return $command->getName() !== 'CompleteMultipartUpload';
                        }
                    }

                    return false;
                };

                $delay = [RetryMiddleware::class, 'exponentialDelay'];
                $list->appendSign(Middleware::retry($decider, $delay), 'retry');
            } else {
                $defaultDecider = RetryMiddlewareV2::createDefaultDecider(
                    new QuotaManager(),
                    $config->getMaxAttempts()
                );

                $list->appendSign(
                    RetryMiddlewareV2::wrap(
                        $config,
                        [
                            'collect_stats' => $args['stats']['retries'],
                            'decider' => function(
                                $attempts,
                                CommandInterface $cmd,
                                $result
                            ) use ($defaultDecider, $config) {
                                $isRetryable = $defaultDecider($attempts, $cmd, $result);
                                if (!$isRetryable
                                    && $result instanceof AwsException
                                    && $attempts < $config->getMaxAttempts()
                                ) {
                                    if (!empty($result->getResponse())
                                        && strpos(
                                            $result->getResponse()->getBody(),
                                            'Your socket connection to the server'
                                        ) !== false
                                    ) {
                                        $isRetryable = false;
                                    }
                                    if ($result->getPrevious() instanceof RequestException
                                        && $cmd->getName() !== 'CompleteMultipartUpload'
                                    ) {
                                        $isRetryable = true;
                                    }
                                }
                                return $isRetryable;
                            }
                        ]
                    ),
                    'retry'
                );
            }
        }
    }

    /** @internal */
    public static function _applyApiProvider($value, array &$args, HandlerList $list)
    {
        ClientResolver::_apply_api_provider($value, $args);
        $args['parser'] = new GetBucketLocationParser(
            new AmbiguousSuccessParser(
                new RetryableMalformedResponseParser(
                    $args['parser'],
                    $args['exception_class']
                ),
                $args['error_parser'],
                $args['exception_class']
            )
        );
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        $b64 = '<div class="alert alert-info">This value will be base64 encoded on your behalf.</div>';
        $opt = '<div class="alert alert-info">This value will be computed for you it is not supplied.</div>';

        // Add a note on the CopyObject docs
         $s3ExceptionRetryMessage = "<p>Additional info on response behavior: if there is"
            . " an internal error in S3 after the request was successfully recieved,"
            . " a 200 response will be returned with an <code>S3Exception</code> embedded"
            . " in it; this will still be caught and retried by"
            . " <code>RetryMiddleware.</code></p>";

        $docs['operations']['CopyObject'] .=  $s3ExceptionRetryMessage;
        $docs['operations']['CompleteMultipartUpload'] .=  $s3ExceptionRetryMessage;
        $docs['operations']['UploadPartCopy'] .=  $s3ExceptionRetryMessage;
        $docs['operations']['UploadPart'] .=  $s3ExceptionRetryMessage;

        // Add note about stream ownership in the putObject call
        $guzzleStreamMessage = "<p>Additional info on behavior of the stream"
            . " parameters: Psr7 takes ownership of streams and will automatically close"
            . " streams when this method is called with a stream as the <code>Body</code>"
            . " parameter.  To prevent this, set the <code>Body</code> using"
            . " <code>GuzzleHttpWPTC\Psr7\stream_for</code> method with a is an instance of"
            . " <code>PsrWPTC\Http\Message\StreamInterface</code>, and it will be returned"
            . " unmodified. This will allow you to keep the stream in scope. </p>";
        $docs['operations']['PutObject'] .=  $guzzleStreamMessage;

        // Add the SourceFile parameter.
        $docs['shapes']['SourceFile']['base'] = 'The path to a file on disk to use instead of the Body parameter.';
        $api['shapes']['SourceFile'] = ['type' => 'string'];
        $api['shapes']['PutObjectRequest']['members']['SourceFile'] = ['shape' => 'SourceFile'];
        $api['shapes']['UploadPartRequest']['members']['SourceFile'] = ['shape' => 'SourceFile'];

        // Add the ContentSHA256 parameter.
        $docs['shapes']['ContentSHA256']['base'] = 'A SHA256 hash of the body content of the request.';
        $api['shapes']['ContentSHA256'] = ['type' => 'string'];
        $api['shapes']['PutObjectRequest']['members']['ContentSHA256'] = ['shape' => 'ContentSHA256'];
        $api['shapes']['UploadPartRequest']['members']['ContentSHA256'] = ['shape' => 'ContentSHA256'];
        unset($api['shapes']['PutObjectRequest']['members']['ContentMD5']);
        unset($api['shapes']['UploadPartRequest']['members']['ContentMD5']);
        $docs['shapes']['ContentSHA256']['append'] = $opt;

        // Add the SaveAs parameter.
        $docs['shapes']['SaveAs']['base'] = 'The path to a file on disk to save the object data.';
        $api['shapes']['SaveAs'] = ['type' => 'string'];
        $api['shapes']['GetObjectRequest']['members']['SaveAs'] = ['shape' => 'SaveAs'];

        // Several SSECustomerKey documentation updates.
        $docs['shapes']['SSECustomerKey']['append'] = $b64;
        $docs['shapes']['CopySourceSSECustomerKey']['append'] = $b64;
        $docs['shapes']['SSECustomerKeyMd5']['append'] = $opt;

        // Add the ObjectURL to various output shapes and documentation.
        $docs['shapes']['ObjectURL']['base'] = 'The URI of the created object.';
        $api['shapes']['ObjectURL'] = ['type' => 'string'];
        $api['shapes']['PutObjectOutput']['members']['ObjectURL'] = ['shape' => 'ObjectURL'];
        $api['shapes']['CopyObjectOutput']['members']['ObjectURL'] = ['shape' => 'ObjectURL'];
        $api['shapes']['CompleteMultipartUploadOutput']['members']['ObjectURL'] = ['shape' => 'ObjectURL'];

        // Fix references to Location Constraint.
        unset($api['shapes']['CreateBucketRequest']['payload']);
        $api['shapes']['BucketLocationConstraint']['enum'] = [
            "ap-northeast-1",
            "ap-southeast-2",
            "ap-southeast-1",
            "cn-north-1",
            "eu-central-1",
            "eu-west-1",
            "us-east-1",
            "us-west-1",
            "us-west-2",
            "sa-east-1",
        ];

        // Add a note that the ContentMD5 is optional.
        $docs['shapes']['ContentMD5']['append'] = '<div class="alert alert-info">The value will be computed on '
            . 'your behalf.</div>';

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function addDocExamples($examples)
    {
        $getObjectExample = [
            'input' => [
                'Bucket' => 'arn:aws:s3:us-east-1:123456789012:accesspoint:myaccesspoint',
                'Key' => 'my-key'
            ],
            'output' => [
                'Body' => 'class GuzzleHttpWPTC\Psr7\Stream#208 (7) {...}',
                'ContentLength' => '11',
                'ContentType' => 'application/octet-stream',
            ],
            'comments' => [
                'input' => '',
                'output' => 'Simplified example output'
            ],
            'description' => 'The following example retrieves an object by referencing the bucket via an S3 accesss point ARN. Result output is simplified for the example.',
            'id' => '',
            'title' => 'To get an object via an S3 access point ARN'
        ];
        if (isset($examples['GetObject'])) {
            $examples['GetObject'] []= $getObjectExample;
        } else {
            $examples['GetObject'] = [$getObjectExample];
        }

        $putObjectExample = [
            'input' => [
                'Bucket' => 'arn:aws:s3:us-east-1:123456789012:accesspoint:myaccesspoint',
                'Key' => 'my-key',
                'Body' => 'my-body',
            ],
            'output' => [
                'ObjectURL' => 'https://my-bucket.s3.us-east-1.amazonaws.com/my-key'
            ],
            'comments' => [
                'input' => '',
                'output' => 'Simplified example output'
            ],
            'description' => 'The following example uploads an object by referencing the bucket via an S3 accesss point ARN. Result output is simplified for the example.',
            'id' => '',
            'title' => 'To upload an object via an S3 access point ARN'
        ];
        if (isset($examples['PutObject'])) {
            $examples['PutObject'] []= $putObjectExample;
        } else {
            $examples['PutObject'] = [$putObjectExample];
        }

        return $examples;
    }
}
