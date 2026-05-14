<?php
namespace AwsWPTC\Arn\S3;

use AwsWPTC\Arn\ArnInterface;

/**
 * @internal
 */
interface BucketArnInterface extends ArnInterface
{
    public function getBucketName();
}
