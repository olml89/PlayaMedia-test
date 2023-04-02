<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Infrastructure\Http;

trait TypeCastsQueryString
{
    private function castParameters(array $parameters): array
    {
        $castedParameters = [];

        foreach ($parameters as $parameter => $value) {
            if (is_array($value)) {
                $castedParameters[$parameter] = $this->castParameters($value);
                continue;
            }

            $castedParameters[$parameter] = $this->castParameter($value);
        }

        return $castedParameters;
    }

    private function castParameter(string $value): int|float|bool|null|string
    {
        if (is_numeric($value)) {
            return 0 + $value;
        }

        $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_null($boolValue)) {
            return $boolValue;
        }

        if (strtolower($value) === "null") {
            return null;
        }

        return $value;
    }
}
