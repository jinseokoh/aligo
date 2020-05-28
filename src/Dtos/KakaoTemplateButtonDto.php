<?php


namespace JinseokOh\Aligo\Dtos;

use JsonSerializable;

class KakaoTemplateButtonDto implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $linkType;
    /**
     * @var string
     */
    private $linkTypeName;
    /**
     * @var string
     */
    private $linkMo;
    /**
     * @var string
     */
    private $linkPc;
    /**
     * @var string
     */
    private $linkIos;
    /**
     * @var string
     */
    private $linkAnd;

    public function __construct(
        string $name,
        string $linkType,
        string $linkTypeName,
        string $linkMo,
        string $linkPc,
        string $linkIos,
        string $linkAnd
    )
    {
        $this->name = $name;
        $this->linkType = $linkType;
        $this->linkTypeName = $linkTypeName;
        $this->linkMo = $linkMo;
        $this->linkPc = $linkPc;
        $this->linkIos = $linkIos;
        $this->linkAnd = $linkAnd;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLinkType(): string
    {
        return $this->linkType;
    }

    /**
     * @return string
     */
    public function getLinkTypeName(): string
    {
        return $this->linkTypeName;
    }

    /**
     * @return string
     */
    public function getLinkMo(): string
    {
        return $this->linkMo;
    }

    /**
     * @return string
     */
    public function getLinkPc(): string
    {
        return $this->linkPc;
    }

    /**
     * @return string
     */
    public function getLinkIos(): string
    {
        return $this->linkIos;
    }

    /**
     * @return string
     */
    public function getLinkAnd(): string
    {
        return $this->linkAnd;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'linkType' => $this->getLinkType(),
            'linkTypeName' => $this->getLinkTypeName(),
            'linkMo' => $this->getLinkMo(),
            'linkPc' => $this->getLinkPc(),
            'linkIos' => $this->getLinkIos(),
            'linkAnd' => $this->getLinkAnd(),
        ];
    }
}
