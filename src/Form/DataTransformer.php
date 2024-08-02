<?php
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use DateTime;

class StringToDateTimeTransformer implements DataTransformerInterface
{
    public function transform(mixed $dateTime): mixed
    {
        // Transform the DateTime object to string (optional, if needed)
        return $dateTime instanceof DateTime ? $dateTime->format('Y-m-d') : '';
    }

    public function reverseTransform(mixed $dateString): mixed
    {
        if (!$dateString) {
            return null;
        }

        try {
            $dateTime = new DateTime($dateString);
            $dateTime->setTime(0, 0); // Set time to 00:00:00
            return $dateTime;
        } catch (\Exception $e) {
            throw new TransformationFailedException('Invalid date format');
        }
    }
}
