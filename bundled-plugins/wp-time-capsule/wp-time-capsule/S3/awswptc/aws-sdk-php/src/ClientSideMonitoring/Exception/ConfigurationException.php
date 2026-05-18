<?php
namespace AwsWPTC\ClientSideMonitoring\Exception;

use AwsWPTC\HasMonitoringEventsTrait;
use AwsWPTC\MonitoringEventsInterface;


/**
 * Represents an error interacting with configuration for client-side monitoring.
 */
class ConfigurationException extends \RuntimeException implements
    MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
