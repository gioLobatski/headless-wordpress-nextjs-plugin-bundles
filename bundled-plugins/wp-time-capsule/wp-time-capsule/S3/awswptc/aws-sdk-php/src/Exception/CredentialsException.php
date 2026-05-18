<?php
namespace AwsWPTC\Exception;

use AwsWPTC\HasMonitoringEventsTrait;
use AwsWPTC\MonitoringEventsInterface;

class CredentialsException extends \RuntimeException implements
    MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
