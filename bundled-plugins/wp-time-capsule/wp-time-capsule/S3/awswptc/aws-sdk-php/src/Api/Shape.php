<?php
namespace AwsWPTC\Api;

/**
 * Base class representing a modeled shape.
 */
class Shape extends AbstractModel
{
    /**
     * Get a concrete shape for the given definition.
     *
     * @param array    $definition
     * @param ShapeMap $shapeMap
     *
     * @return mixed
     * @throws \RuntimeException if the type is invalid
     */
    public static function create(array $definition, ShapeMap $shapeMap)
    {
        static $map = [
            'structure' => 'AwsWPTC\Api\StructureShape',
            'map'       => 'AwsWPTC\Api\MapShape',
            'list'      => 'AwsWPTC\Api\ListShape',
            'timestamp' => 'AwsWPTC\Api\TimestampShape',
            'integer'   => 'AwsWPTC\Api\Shape',
            'double'    => 'AwsWPTC\Api\Shape',
            'float'     => 'AwsWPTC\Api\Shape',
            'long'      => 'AwsWPTC\Api\Shape',
            'string'    => 'AwsWPTC\Api\Shape',
            'byte'      => 'AwsWPTC\Api\Shape',
            'character' => 'AwsWPTC\Api\Shape',
            'blob'      => 'AwsWPTC\Api\Shape',
            'boolean'   => 'AwsWPTC\Api\Shape'
        ];

        if (isset($definition['shape'])) {
            return $shapeMap->resolve($definition);
        }

        if (!isset($map[$definition['type']])) {
            throw new \RuntimeException('Invalid type: '
                . print_r($definition, true));
        }

        $type = $map[$definition['type']];

        return new $type($definition, $shapeMap);
    }

    /**
     * Get the type of the shape
     *
     * @return string
     */
    public function getType()
    {
        return $this->definition['type'];
    }

    /**
     * Get the name of the shape
     *
     * @return string
     */
    public function getName()
    {
        return $this->definition['name'];
    }
}
