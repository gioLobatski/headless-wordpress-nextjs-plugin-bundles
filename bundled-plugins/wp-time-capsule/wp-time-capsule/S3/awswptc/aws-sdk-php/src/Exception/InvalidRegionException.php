<?php
namespace AwsWPTC\Exception;

use AwsWPTC\HasMonitoringEventsTrait;
use AwsWPTC\MonitoringEventsInterface;

class InvalidRegionException extends \RuntimeException implements
    MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
