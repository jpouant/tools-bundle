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
     * @return self
     */
    public function setDisplayKey(bool $displayKey): self
    {
        $this->displayKey = $displayKey;

        return $this;
    }

    /**
     * Set AroundKey
     *
     * @param bool|string $aroundKey
     *
     * @return self
     */
    public function setAroundKey(bool $aroundKey = false): self
    {
        $this->aroundKey = $aroundKey;

        return $this;
    }

    /**
     * Set AroundValue
     *
     * @param bool|string $aroundValue
     *
     * @return self
     */
    public function setAroundValue(bool $aroundValue = false): self
    {
        $this->aroundValue = $aroundValue;

        return $this;
    }

    /**
     * Set BeginOfLine
     *
     * @param string $beginOfLine
     *
     * @return self
     */
    public function setBeginOfLine(string $beginOfLine = ''): self
    {
        $this->beginOfLine = $beginOfLine;

        return $this;
    }

    /**
     * Set BetweenKeyAndValue
     *
     * @param string $betweenKeyAndValue
     *
     * @return self
     */
    public function setBetweenKeyAndValue(string $betweenKeyAndValue = ' => '): self
    {
        $this->betweenKeyAndValue = $betweenKeyAndValue;

        return $this;
    }

    /**
     * Set EndOfLine
     *
     * @param string $endOfLine
     *
     * @return self
     */
    public function setEndOfLine(string $endOfLine = ','): self
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
    public function arrayToString(array $array, int $level = 0): string
    {
        $result       = '';
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
            $result .= sprintf('%s%s%s%s%s%s', $spacesIndent, $this->beginOfLine, $key, $this->betweenKeyAndValue, $stringValue, $this->endOfLine);
        }
        if ($level > 0) {
            $result .= "\n";
        }
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
     */
    protected function getIndentForLevel(int $level)
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
