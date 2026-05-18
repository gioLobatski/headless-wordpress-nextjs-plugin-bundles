<?php
namespace AwsWPTC\S3;

use AwsWPTC\CacheInterface;
use AwsWPTC\CommandInterface;
use AwsWPTC\LruArrayCache;
use AwsWPTC\MultiRegionClient as BaseClient;
use AwsWPTC\Exception\AwsException;
use AwsWPTC\S3\Exception\PermanentRedirectException;
use GuzzleHttpWPTC\Promise;

/**
 * **Amazon Simple Storage Service** multi-region client.
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
class S3MultiRegionClient extends BaseClient implements S3ClientInterface
{
    use S3ClientTrait;

    /** @var CacheInterface */
    private $cache;

    public static function getArguments()
    {
        $args = parent::getArguments();
        $regionDef = $args['region'] + ['default' => function (array &$args) {
            $availableRegions = array_keys($args['partition']['regions']);
            return end($availableRegions);
        }];
        unset($args['region']);

        return $args + [
            'bucket_region_cache' => [
                'type' => 'config',
                'valid' => [CacheInterface::class],
                'doc' => 'Cache of regions in which given buckets are located.',
                'default' => function () { return new LruArrayCache; },
            ],
            'region' => $regionDef,
        ];
    }

    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->cache = $this->getConfig('bucket_region_cache');

        $this->getHandlerList()->prependInit(
            $this->determineRegionMiddleware(),
            'determine_region'
        );
    }

    private function determineRegionMiddleware()
    {
        return function (callable $handler) {
            return function (CommandInterface $command) use ($handler) {
                $cacheKey = $this->getCacheKey($command['Bucket']);
                if (
                    empty($command['@region']) &&
                    $region = $this->cache->get($cacheKey)
                ) {
                    $command['@region'] = $region;
                }

                return Promise\coroutine(function () use (
                    $handler,
                    $command,
                    $cacheKey
                ) {
                    try {
                        yield $handler($command);
                    } catch (PermanentRedirectException $e) {
                        if (empty($command['Bucket'])) {
                            throw $e;
                        }
                        $result = $e->getResult();
                        $region = null;
                        if (isset($result['@metadata']['headers']['x-amz-bucket-region'])) {
                            $region = $result['@metadata']['headers']['x-amz-bucket-region'];
                            $this->cache->set($cacheKey, $region);
                        } else {
                            $region = (yield $this->determineBucketRegionAsync(
                                $command['Bucket']
                            ));
                        }

                        $command['@region'] = $region;
                        yield $handler($command);
                    } catch (AwsException $e) {
                        if ($e->getAwsErrorCode() === 'AuthorizationHeaderMalformed') {
                            $region = $this->determineBucketRegionFromExceptionBody(
                                $e->getResponse()
                            );
                            if (!empty($region)) {
                                $this->cache->set($cacheKey, $region);

                                $command['@region'] = $region;
                                yield $handler($command);
                            } else {
                                throw $e;
                            }
                        } else {
                            throw $e;
                        }
                    }
                });
            };
        };
    }

    public function createPresignedRequest(CommandInterface $command, $expires, array $options = [])
    {
        if (empty($command['Bucket'])) {
            throw new \InvalidArgumentException('The S3\\MultiRegionClient'
                . ' cannot create presigned requests for commands without a'
                . ' specified bucket.');
        }

        /** @var S3ClientInterface $client */
        $client = $this->getClientFromPool(
            $this->determineBucketRegion($command['Bucket'])
        );
        return $client->createPresignedRequest(
            $client->getCommand($command->getName(), $command->toArray()),
            $expires
        );
    }

    public function getObjectUrl($bucket, $key)
    {
        /** @var S3Client $regionalClient */
        $regionalClient = $this->getClientFromPool(
            $this->determineBucketRegion($bucket)
        );

        return $regionalClient->getObjectUrl($bucket, $key);
    }

    public function determineBucketRegionAsync($bucketName)
    {
        $cacheKey = $this->getCacheKey($bucketName);
        if ($cached = $this->cache->get($cacheKey)) {
            return Promise\promise_for($cached);
        }

        /** @var S3ClientInterface $regionalClient */
        $regionalClient = $this->getClientFromPool();
        return $regionalClient->determineBucketRegionAsync($bucketName)
            ->then(
                function ($region) use ($cacheKey) {
                    $this->cache->set($cacheKey, $region);

                    return $region;
                }
            );
    }

    private function getCacheKey($bucketName)
    {
        return "aws:s3:{$bucketName}:location";
    }
}
