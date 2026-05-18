<?php
namespace AwsWPTC\Exception;

use AwsWPTC\HasMonitoringEventsTrait;
use AwsWPTC\MonitoringEventsInterface;

class InvalidJsonException extends \RuntimeException implements
    MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
