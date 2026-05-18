<?php
namespace AwsWPTC\Api\Parser;

use AwsWPTC\Api\Service;
use AwsWPTC\Api\StructureShape;
use AwsWPTC\CommandInterface;
use AwsWPTC\ResultInterface;
use PsrWPTC\Http\Message\ResponseInterface;
use PsrWPTC\Http\Message\StreamInterface;

/**
 * @internal
 */
abstract class AbstractParser
{
    /** @var \AwsWPTC\Api\Service Representation of the service API*/
    protected $api;

    /** @var callable */
    protected $parser;

    /**
     * @param Service $api Service description.
     */
    public function __construct(Service $api)
    {
        $this->api = $api;
    }

    /**
     * @param CommandInterface  $command  Command that was executed.
     * @param ResponseInterface $response Response that was received.
     *
     * @return ResultInterface
     */
    abstract public function __invoke(
        CommandInterface $command,
        ResponseInterface $response
    );

    abstract public function parseMemberFromStream(
        StreamInterface $stream,
        StructureShape $member,
        $response
    );
}
