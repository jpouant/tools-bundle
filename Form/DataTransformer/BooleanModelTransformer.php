<?php

namespace Neirda24\Bundle\ToolsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class BooleanModelTransformer implements DataTransformerInterface
{
    /**
     * Transform a boolean into a string (1 or 0)
     *
     * @param bool|null $boolVal
     *
     * @return string
     */
    public function transform($boolVal)
    {
        if (null === $boolVal) {
            return '';
        }

        return true === $boolVal ? '1' : '0';
    }

    /**
     * Transform 1 or 0 in a boolean
     *
     * @param  int|string $textVal
     *
     * @return bool|null
     */
    public function reverseTransform($textVal)
    {
        if ('' === $textVal) {
            return null;
        }

        return '1' == $textVal;
    }
}
