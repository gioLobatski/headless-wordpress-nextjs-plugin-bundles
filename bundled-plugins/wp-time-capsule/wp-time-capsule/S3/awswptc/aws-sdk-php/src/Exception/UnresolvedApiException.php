<?php
namespace AwsWPTC\Exception;

use AwsWPTC\HasMonitoringEventsTrait;
use AwsWPTC\MonitoringEventsInterface;

class UnresolvedApiException extends \RuntimeException implements
    MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
