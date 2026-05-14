<?php
namespace AwsWPTC\Arn\S3;

use AwsWPTC\Arn\AccessPointArn as BaseAccessPointArn;
use AwsWPTC\Arn\AccessPointArnInterface;
use AwsWPTC\Arn\ArnInterface;
use AwsWPTC\Arn\Exception\InvalidArnException;

/**
 * @internal
 */
class AccessPointArn extends BaseAccessPointArn implements AccessPointArnInterface
{
    /**
     * Validation specific to AccessPointArn
     *
     * @param array $data
     */
    public static function validate(array $data)
    {
        parent::validate($data);
        if ($data['service'] !== 's3') {
            throw new InvalidArnException("The 3rd component of an S3 access"
                . " point ARN represents the region and must be 's3'.");
        }
    }
}