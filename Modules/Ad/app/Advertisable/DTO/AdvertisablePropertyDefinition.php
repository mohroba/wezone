<?php

namespace Modules\Ad\Advertisable\DTO;

use JsonSerializable;

final class AdvertisablePropertyDefinition implements JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $label,
        public readonly ?string $description = null,
        public readonly bool $required = false,
        public readonly ?array $rules = null,
        public readonly mixed $default = null,
        public readonly ?array $options = null,
    ) {
    }

    /**
     * @return array{name: string, type: string, label: string, description: string|null, required: bool, rules: array<int, string>|null, default: mixed, options: array<int, mixed>|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label,
            'description' => $this->description,
            'required' => $this->required,
            'rules' => $this->rules,
            'default' => $this->default,
            'options' => $this->options,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
