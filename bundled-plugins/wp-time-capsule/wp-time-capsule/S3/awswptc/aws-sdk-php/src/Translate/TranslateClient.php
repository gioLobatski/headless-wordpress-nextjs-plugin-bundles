<?php
namespace AwsWPTC\Translate;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **Amazon Translate** service.
 * @method \AwsWPTC\Result createParallelData(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createParallelDataAsync(array $args = [])
 * @method \AwsWPTC\Result deleteParallelData(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteParallelDataAsync(array $args = [])
 * @method \AwsWPTC\Result deleteTerminology(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteTerminologyAsync(array $args = [])
 * @method \AwsWPTC\Result describeTextTranslationJob(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeTextTranslationJobAsync(array $args = [])
 * @method \AwsWPTC\Result getParallelData(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getParallelDataAsync(array $args = [])
 * @method \AwsWPTC\Result getTerminology(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise getTerminologyAsync(array $args = [])
 * @method \AwsWPTC\Result importTerminology(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise importTerminologyAsync(array $args = [])
 * @method \AwsWPTC\Result listParallelData(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listParallelDataAsync(array $args = [])
 * @method \AwsWPTC\Result listTerminologies(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listTerminologiesAsync(array $args = [])
 * @method \AwsWPTC\Result listTextTranslationJobs(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listTextTranslationJobsAsync(array $args = [])
 * @method \AwsWPTC\Result startTextTranslationJob(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise startTextTranslationJobAsync(array $args = [])
 * @method \AwsWPTC\Result stopTextTranslationJob(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise stopTextTranslationJobAsync(array $args = [])
 * @method \AwsWPTC\Result translateText(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise translateTextAsync(array $args = [])
 * @method \AwsWPTC\Result updateParallelData(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateParallelDataAsync(array $args = [])
 */
class TranslateClient extends AwsClient {}
