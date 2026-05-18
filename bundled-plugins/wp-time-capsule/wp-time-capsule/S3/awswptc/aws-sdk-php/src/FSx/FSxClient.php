<?php
namespace AwsWPTC\FSx;

use AwsWPTC\AwsClient;

/**
 * This client is used to interact with the **Amazon FSx** service.
 * @method \AwsWPTC\Result associateFileSystemAliases(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise associateFileSystemAliasesAsync(array $args = [])
 * @method \AwsWPTC\Result cancelDataRepositoryTask(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise cancelDataRepositoryTaskAsync(array $args = [])
 * @method \AwsWPTC\Result copyBackup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise copyBackupAsync(array $args = [])
 * @method \AwsWPTC\Result createBackup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createBackupAsync(array $args = [])
 * @method \AwsWPTC\Result createDataRepositoryTask(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createDataRepositoryTaskAsync(array $args = [])
 * @method \AwsWPTC\Result createFileSystem(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createFileSystemAsync(array $args = [])
 * @method \AwsWPTC\Result createFileSystemFromBackup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise createFileSystemFromBackupAsync(array $args = [])
 * @method \AwsWPTC\Result deleteBackup(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \AwsWPTC\Result deleteFileSystem(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise deleteFileSystemAsync(array $args = [])
 * @method \AwsWPTC\Result describeBackups(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \AwsWPTC\Result describeDataRepositoryTasks(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeDataRepositoryTasksAsync(array $args = [])
 * @method \AwsWPTC\Result describeFileSystemAliases(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeFileSystemAliasesAsync(array $args = [])
 * @method \AwsWPTC\Result describeFileSystems(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise describeFileSystemsAsync(array $args = [])
 * @method \AwsWPTC\Result disassociateFileSystemAliases(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise disassociateFileSystemAliasesAsync(array $args = [])
 * @method \AwsWPTC\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \AwsWPTC\Result tagResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise tagResourceAsync(array $args = [])
 * @method \AwsWPTC\Result untagResource(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise untagResourceAsync(array $args = [])
 * @method \AwsWPTC\Result updateFileSystem(array $args = [])
 * @method \GuzzleHttpWPTC\Promise\Promise updateFileSystemAsync(array $args = [])
 */
class FSxClient extends AwsClient {}
