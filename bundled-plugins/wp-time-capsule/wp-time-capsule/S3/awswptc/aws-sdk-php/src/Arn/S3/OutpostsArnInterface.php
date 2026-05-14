<?php
namespace AwsWPTC\Arn\S3;

use AwsWPTC\Arn\ArnInterface;

/**
 * @internal
 */
interface OutpostsArnInterface extends ArnInterface
{
    public function getOutpostId();
}
