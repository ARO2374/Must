<?php

namespace App\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Doctrine\ORM\EntityNotFoundException;

class EntityNotFoundExceptionSerializer implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => EntityNotFoundException::class,
                'method' => 'serialize',
            ]
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Exception                $exception
     * @param array                    $type
     * @param Context                  $context
     *
     * @return array
     */
    public function serialize(
        JsonSerializationVisitor $visitor,
        EntityNotFoundException $exception,
        array $type,
        Context $context
    ) {
    	$id = $exception->getMessage();
        $data = [
        	'erreur' => "L'id #$id n'est pas present en base !"
        ];

        return $visitor->visitArray($data, $type, $context);
    }
}