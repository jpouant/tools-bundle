<?php

namespace Neirda24\Bundle\ToolsBundle\Converter;

class ArrayToText
{
    /**
     * @var bool
     */
    protected $displayKey = true;

    /**
     * Set to false to be automatic
     *
     * @var bool|string
     */
    protected $aroundKey = false;

    /**
     * Set to false to be automatic
     *
     * @var bool|string
     */
    protected $aroundValue = false;

    /**
     * @var string
     */
    protected $beginOfLine = '';

    /**
     * @var string
     */
    protected $betweenKeyAndValue = ' => ';

    /**
     * @var string
     */
    protected $endOfLine = ',';

    /**
     * Set DisplayKey
     *
     * @param boolean $displayKey
     *
     * @return $this
     */
    public function setDisplayKey($displayKey)
    {
        $this->displayKey = (bool)$displayKey;

        return $this;
    }

    /**
     * Set AroundKey
     *
     * @param bool|string $aroundKey
     *
     * @return $this
     */
    public function setAroundKey($aroundKey = false)
    {
        $this->aroundKey = $aroundKey;

        return $this;
    }

    /**
     * Set AroundValue
     *
     * @param bool|string $aroundValue
     *
     * @return $this
     */
    public function setAroundValue($aroundValue = false)
    {
        $this->aroundValue = $aroundValue;

        return $this;
    }

    /**
     * Set BeginOfLine
     *
     * @param string $beginOfLine
     *
     * @return $this
     */
    public function setBeginOfLine($beginOfLine = '')
    {
        $this->beginOfLine = $beginOfLine;

        return $this;
    }

    /**
     * Set BetweenKeyAndValue
     *
     * @param string $betweenKeyAndValue
     *
     * @return $this
     */
    public function setBetweenKeyAndValue($betweenKeyAndValue = ' => ')
    {
        $this->betweenKeyAndValue = $betweenKeyAndValue;

        return $this;
    }

    /**
     * Set EndOfLine
     *
     * @param string $endOfLine
     *
     * @return $this
     */
    public function setEndOfLine($endOfLine = ',')
    {
        $this->endOfLine = $endOfLine;

        return $this;
    }

    /**
     * @param array  $array
     * @param int    $level
     *
     * @return string
     */
    public function arrayToString(array $array, $level = 0)
    {
        $result       = $this->beginOfLine;
        $spacesStart  = $this->getIndentForLevel(($level - 1));
        $spacesIndent = $this->getIndentForLevel($level);

        foreach ($array as $key => $value) {
            $key = $this->formatKey($key);
            if (is_array($value)) {
                $stringValue = $this->arrayToString($value, ($level + 1));
            } else {
                $stringValue = $this->formatValue($value);
            }
            $result .= "\n";
            $result .= sprintf('%s%s%s%s%s', $spacesIndent, $key, $this->betweenKeyAndValue, $stringValue, $this->endOfLine);
        }
        $result .= "\n";
        $result .= $spacesStart;

        return $result;
    }

    /**
     * @param string|int $value
     *
     * @return string|int
     */
    protected function formatValue($value)
    {
        if (false === $this->aroundValue) {
            if (!is_int($value)) {
                $value = '"' . str_replace('\\', '\\\\', $value) . '"';
            }
        } elseif (is_string($this->aroundValue)) {
            $value = $this->aroundValue . $value . $this->aroundValue;
        }

        return $value;
    }

    /**
     * @param string|int $key
     *
     * @return string|int
     */
    protected function formatKey($key)
    {
        if (false === $this->aroundKey) {
            if (!is_int($key)) {
                $key = '"' . str_replace('\\', '\\\\', $key) . '"';
            }
        } elseif (is_string($this->aroundKey)) {
            $key = $this->aroundKey . $key . $this->aroundKey;
        }

        return $key;
    }

    /**
     * @param int $level
     *
     * @return string
     *
     * @todo: Throw error if not int
     */
    protected function getIndentForLevel($level)
    {
        $spacesIndent = '';

        if (0 < $level) {
            for ($i = 0; $i <= $level; $i++) {
                $spacesIndent .= '    ';
            }
        }

        return $spacesIndent;
    }
}
