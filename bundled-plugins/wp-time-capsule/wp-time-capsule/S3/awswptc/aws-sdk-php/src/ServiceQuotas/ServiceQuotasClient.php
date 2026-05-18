<?php
namespace AwsWPTC\ServiceQuotas;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **Service Quotas** service.
 * @method \AwsWPTC\Result associateServiceQuotaTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise associateServiceQuotaTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result deleteServiceQuotaIncreaseRequestFromTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteServiceQuotaIncreaseRequestFromTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result disassociateServiceQuotaTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise disassociateServiceQuotaTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result getAWSDefaultServiceQuota(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAWSDefaultServiceQuotaAsync(array $args = [])
 * @method \AwsWPTC\Result getAssociationForServiceQuotaTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getAssociationForServiceQuotaTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result getRequestedServiceQuotaChange(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getRequestedServiceQuotaChangeAsync(array $args = [])
 * @method \AwsWPTC\Result getServiceQuota(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getServiceQuotaAsync(array $args = [])
 * @method \AwsWPTC\Result getServiceQuotaIncreaseRequestFromTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getServiceQuotaIncreaseRequestFromTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result listAWSDefaultServiceQuotas(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listAWSDefaultServiceQuotasAsync(array $args = [])
 * @method \AwsWPTC\Result listRequestedServiceQuotaChangeHistory(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listRequestedServiceQuotaChangeHistoryAsync(array $args = [])
 * @method \AwsWPTC\Result listRequestedServiceQuotaChangeHistoryByQuota(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listRequestedServiceQuotaChangeHistoryByQuotaAsync(array $args = [])
 * @method \AwsWPTC\Result listServiceQuotaIncreaseRequestsInTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listServiceQuotaIncreaseRequestsInTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result listServiceQuotas(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listServiceQuotasAsync(array $args = [])
 * @method \AwsWPTC\Result listServices(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listServicesAsync(array $args = [])
 * @method \AwsWPTC\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \AwsWPTC\Result putServiceQuotaIncreaseRequestIntoTemplate(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise putServiceQuotaIncreaseRequestIntoTemplateAsync(array $args = [])
 * @method \AwsWPTC\Result requestServiceQuotaIncrease(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise requestServiceQuotaIncreaseAsync(array $args = [])
 * @method \AwsWPTC\Result tagResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise tagResourceAsync(array $args = [])
 * @method \AwsWPTC\Result untagResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise untagResourceAsync(array $args = [])
 */
class ServiceQuotasClient extends AwsClient {}
