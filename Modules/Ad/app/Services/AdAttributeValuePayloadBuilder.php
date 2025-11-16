<?php

namespace Modules\Ad\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use JsonException;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeValue;

class AdAttributeValuePayloadBuilder
{
    /**
     * @param array<string, mixed> $payload
     */
    public function build(
        AdAttributeDefinition $definition,
        array $payload,
        bool $includeMissing,
        ?AdAttributeValue $currentValue = null
    ): array {
        $columns = [
            'value_string',
            'value_integer',
            'value_decimal',
            'value_boolean',
            'value_date',
            'value_json',
        ];

        $result = [
            'definition_id' => $definition->getKey(),
        ];

        if (array_key_exists('ad_id', $payload)) {
            $result['ad_id'] = $payload['ad_id'];
        } elseif ($currentValue) {
            $result['ad_id'] = $currentValue->ad_id;
        }

        foreach ($columns as $column) {
            if (array_key_exists($column, $payload)) {
                $result[$column] = $column === 'value_date' && $payload[$column]
                    ? Carbon::parse($payload[$column])->toDateString()
                    : $payload[$column];
            } elseif ($includeMissing) {
                $result[$column] = null;
            }
        }

        $valueColumn = $this->valueColumnForType($definition->data_type);
        $shouldUpdateNormalized = $valueColumn && ($includeMissing || array_key_exists($valueColumn, $payload));

        if ($shouldUpdateNormalized && $valueColumn) {
            $result['normalized_value'] = $this->normalizedValue($definition, $payload, $currentValue);
        }

        return $result;
    }

    private function valueColumnForType(string $type): ?string
    {
        return match ($type) {
            'string', 'enum' => 'value_string',
            'integer' => 'value_integer',
            'decimal' => 'value_decimal',
            'boolean' => 'value_boolean',
            'date' => 'value_date',
            'json' => 'value_json',
            default => null,
        };
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function normalizedValue(
        AdAttributeDefinition $definition,
        array $payload,
        ?AdAttributeValue $currentValue = null
    ): ?string {
        $valueColumn = $this->valueColumnForType($definition->data_type);

        if (! $valueColumn) {
            return null;
        }

        $rawValue = array_key_exists($valueColumn, $payload)
            ? $payload[$valueColumn]
            : ($currentValue?->{$valueColumn});

        if ($rawValue === null) {
            return null;
        }

        return match ($definition->data_type) {
            'string', 'enum' => $this->normalizeStringValue($rawValue),
            'integer' => (string) (int) $rawValue,
            'decimal' => $this->normalizeDecimalValue($rawValue),
            'boolean' => $rawValue ? '1' : '0',
            'date' => $this->normalizeDateValue($rawValue),
            'json' => $this->normalizeJsonValue($rawValue),
            default => null,
        };
    }

    private function normalizeStringValue(mixed $value): string
    {
        return Str::of((string) $value)->lower()->trim()->value();
    }

    private function normalizeDecimalValue(mixed $value): string
    {
        $number = is_string($value) ? (float) $value : (float) ($value ?? 0);

        return rtrim(rtrim(number_format($number, 4, '.', ''), '0'), '.') ?: '0';
    }

    private function normalizeDateValue(mixed $value): string
    {
        return Carbon::parse($value)->toDateString();
    }

    private function normalizeJsonValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }
    }
}
