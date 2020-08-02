<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (DateTimeInterface) to a string.
     *
     * @param  DateTimeInterface|null $datetime
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }
        
        return $datetime->date_create_from_format ->format('d/m/Y H:i:s');
        // return date_create_from_format('d/m/Y H:i', $datetime, new \DateTimeZone('Europe/Paris'));
    }

    /**
     * Transforms a string to an object (DateTimeInterface).
     *
     * @param  string $datetime
     * @return DateTime|null
     */
    public function reverseTransform($datetime)
    {
        var_dump($datetime->format('Y-m-d H:i:s') );
        die;
        // datetime optional
        if (!$datetime) {
            return;
        }

        return date_create_from_format('d/m/Y H:i', $datetime, new \DateTimeInterface());
    }
}