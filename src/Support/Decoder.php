<?php

namespace Pyro\Platform\Support;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer as AN;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Decoder
{
    /**
     * How many loops of circular reference to allow while normalizing.
     *
     * The default value of 1 means that when we encounter the same object a
     * second time, we consider that a circular reference.
     *
     * You can raise this value for special cases, e.g. in combination with the
     * max depth setting of the object normalizer.
     */
    public const CIRCULAR_REFERENCE_LIMIT = AN::CIRCULAR_REFERENCE_LIMIT;

    /**
     * Instead of creating a new instance of an object, update the specified object.
     *
     * If you have a nested structure, child objects will be overwritten with
     * new instances unless you set DEEP_OBJECT_TO_POPULATE to true.
     */
    public const OBJECT_TO_POPULATE = AN::OBJECT_TO_POPULATE;

    /**
     * Only (de)normalize attributes that are in the specified groups.
     */
    public const GROUPS = AN::GROUPS;

    /**
     * Limit (de)normalize to the specified names.
     *
     * For nested structures, this list needs to reflect the object tree.
     */
    public const ATTRIBUTES = AN::ATTRIBUTES;

    /**
     * If ATTRIBUTES are specified, and the source has fields that are not part of that list,
     * either ignore those attributes (true) or throw an ExtraAttributesException (false).
     */
    public const ALLOW_EXTRA_ATTRIBUTES = AN::ALLOW_EXTRA_ATTRIBUTES;

    /**
     * Hashmap of default values for constructor arguments.
     *
     * The names need to match the parameter names in the constructor arguments.
     */
    public const DEFAULT_CONSTRUCTOR_ARGUMENTS = AN::DEFAULT_CONSTRUCTOR_ARGUMENTS;

    /**
     * Hashmap of field name => callable to normalize this field.
     *
     * The callable is called if the field is encountered with the arguments:
     *
     * - mixed  $attributeValue value of this field
     * - object $object         the whole object being normalized
     * - string $attributeName  name of the attribute being normalized
     * - string $format         the requested format
     * - array  $context        the serialization context
     */
    public const CALLBACKS = AN::CALLBACKS;

    /**
     * Handler to call when a circular reference has been detected.
     *
     * If you specify no handler, a CircularReferenceException is thrown.
     *
     * The method will be called with ($object, $format, $context) and its
     * return value is returned as the result of the normalize call.
     */
    public const CIRCULAR_REFERENCE_HANDLER = AN::CIRCULAR_REFERENCE_HANDLER;

    /**
     * Skip the specified attributes when normalizing an object tree.
     *
     * This list is applied to each element of nested structures.
     *
     * Note: The behaviour for nested structures is different from ATTRIBUTES
     * for historical reason. Aligning the behaviour would be a BC break.
     */
    public const IGNORED_ATTRIBUTES = AN::IGNORED_ATTRIBUTES;


    public static function normalize($object, $context = [])
    {
        $result = static::getSerializer()->normalize($object, null, $context);
        return $result;
    }

    /** @var Serializer */
    protected static $serializer;

    protected static function getSerializer()
    {
        if (static::$serializer === null) {
            $encoders           = [ new XmlEncoder(), new JsonEncoder() ];
            $normalizers        = [ $normalizer = new ObjectNormalizer() ];
            static::$serializer = new Serializer($normalizers, $encoders);
        }
        return static::$serializer;
    }


    protected $object;

    protected $context = [];

    public function set($key, $value = null)
    {
        if ( is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->context[ $key ] = $value;
        }
        return $this;
    }

    public static function ignore(array $attributes)
    {
        $decoder = new static();
        data_set(
            $decoder->context,
            self::IGNORED_ATTRIBUTES,
            array_replace(
                data_get($decoder->context, self::IGNORED_ATTRIBUTES, []),
                $attributes
            )
        );
        return $decoder;
    }

    public static function only(array $attributes, bool $strict = false)
    {
        $decoder = new static();
        data_set(
            $decoder->context,
            self::ATTRIBUTES,
            array_replace(
                data_get($decoder->context, self::ATTRIBUTES, []),
                $attributes
            )
        );
        $decoder->set(self::ALLOW_EXTRA_ATTRIBUTES, !$strict);
        return $decoder;
    }

    public function decode($object)
    {
        return static::getSerializer()->normalize($object, null, $this->context);
    }

    public static function mapper($mode, $attributes = [], $callbacks = [])
    {
        return function ($item) use ($mode, $attributes, $callbacks) {
            $decoder = $mode === 'only' ? Decoder::only($attributes) : Decoder::ignore($attributes);
            $decoder->set(Decoder::CALLBACKS, $callbacks);
            return $decoder->decode($item);
        };
    }
}
