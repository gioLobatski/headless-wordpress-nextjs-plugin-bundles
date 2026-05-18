<?php
namespace AwsWPTC\Support;

use AwsWPTC\AwsClient;

/**
 * AWS Support client.
 *
 * @method \AwsWPTC\Result addAttachmentsToSet(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise addAttachmentsToSetAsync(array $args = [])
 * @method \AwsWPTC\Result addCommunicationToCase(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise addCommunicationToCaseAsync(array $args = [])
 * @method \AwsWPTC\Result createCase(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createCaseAsync(array $args = [])
 * @method \AwsWPTC\Result describeAttachment(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeAttachmentAsync(array $args = [])
 * @method \AwsWPTC\Result describeCases(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeCasesAsync(array $args = [])
 * @method \AwsWPTC\Result describeCommunications(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeCommunicationsAsync(array $args = [])
 * @method \AwsWPTC\Result describeServices(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeServicesAsync(array $args = [])
 * @method \AwsWPTC\Result describeSeverityLevels(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeSeverityLevelsAsync(array $args = [])
 * @method \AwsWPTC\Result describeTrustedAdvisorCheckRefreshStatuses(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeTrustedAdvisorCheckRefreshStatusesAsync(array $args = [])
 * @method \AwsWPTC\Result describeTrustedAdvisorCheckResult(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeTrustedAdvisorCheckResultAsync(array $args = [])
 * @method \AwsWPTC\Result describeTrustedAdvisorCheckSummaries(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeTrustedAdvisorCheckSummariesAsync(array $args = [])
 * @method \AwsWPTC\Result describeTrustedAdvisorChecks(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeTrustedAdvisorChecksAsync(array $args = [])
 * @method \AwsWPTC\Result refreshTrustedAdvisorCheck(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise refreshTrustedAdvisorCheckAsync(array $args = [])
 * @method \AwsWPTC\Result resolveCase(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise resolveCaseAsync(array $args = [])
 */
class SupportClient extends AwsClient {}
