<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Endpoints;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\ClassMetadata;
use olml89\PlayaMedia\Common\Domain\Criteria\Order\OrderType;
use olml89\PlayaMedia\Common\Domain\ValueObjects\DateTimeRangeValueObject;
use olml89\PlayaMedia\Common\Domain\ValueObjects\NullableBoolValueObject;
use olml89\PlayaMedia\Common\Infrastructure\Http\TypeCastsQueryString;
use olml89\PlayaMedia\User\Application\Search\Filters;
use olml89\PlayaMedia\User\Application\Search\Order;
use olml89\PlayaMedia\User\Application\Search\SearchData;
use olml89\PlayaMedia\User\Domain\UserType;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchRequest extends Request
{
    use TypeCastsQueryString;

    private function configureOptions(OptionsResolver $resolver, ClassMetadata $userMetadata): void
    {
        $resolver
            ->setDefined('filters')
            ->setAllowedTypes('filters', 'array')
            ->setDefault('filters', function(OptionsResolver $filtersResolver): void {
                $filtersResolver
                    ->setDefined('is_active')
                    ->setAllowedTypes('is_active', ['null', 'bool'])
                    ->setNormalizer('is_active', function (Options $options, ?bool $value): NullableBoolValueObject {
                        return new NullableBoolValueObject($value);
                    });
                $filtersResolver
                    ->setDefined('is_member')
                    ->setAllowedTypes('is_member', 'bool');
                $filtersResolver
                    ->setDefined('user_type')
                    ->setAllowedTypes('user_type', 'int')
                    ->setAllowedValues('user_type', [
                        UserType::Type1->value,
                        UserType::Type2->value,
                        UserType::Type3->value,
                    ])
                    ->setNormalizer('user_type', function (Options $options, int $value): UserType {
                        return UserType::from($value);
                    });
                $filtersResolver
                    ->setDefined('last_login_at')
                    ->setAllowedTypes('last_login_at', ['null', 'array'])
                    ->setDefault('last_login_at', function (OptionsResolver $lastLoginAtResolver): void {
                        $lastLoginAtResolver
                            ->setDefined('start')
                            ->setAllowedTypes('start', ['null', 'string'])
                            ->setAllowedValues('start', function (?string $value): bool {
                                return is_null($value)
                                    || DateTimeImmutable::createFromFormat('Y-m-d', $value) !== false;
                            })
                            ->setNormalizer('start', function (Options $options, ?string $value): ?DateTimeImmutable {
                                return is_null($value)
                                    ? null
                                    : DateTimeImmutable::createFromFormat('Y-m-d', $value);
                            });
                        $lastLoginAtResolver
                            ->setDefined('end')
                            ->setAllowedTypes('end', ['null', 'string'])
                            ->setAllowedValues('end', function (?string $value): bool {
                                return is_null($value)
                                    || DateTimeImmutable::createFromFormat('Y-m-d', $value) !== false;
                            })
                            ->setNormalizer('end', function (Options $options, ?string $value): ?DateTimeImmutable {
                                return is_null($value)
                                    ? null
                                    : DateTimeImmutable::createFromFormat('Y-m-d', $value);
                            });
                    })
                    ->setNormalizer('last_login_at', function (Options $options, array $value): ?DateTimeRangeValueObject {
                        return count($value) === 0
                            ? null
                            : new DateTimeRangeValueObject($value['start'] ?? null, $value['end'] ?? null);
                    });
            })
            ->setNormalizer('filters', function (Options $options, array $value): ParameterBag {
                return new ParameterBag($value);
            });

        $resolver
            ->setDefined('order')
            ->setAllowedTypes('order', 'array')
            ->setDefault('order', function (OptionsResolver $optionsResolver) use($userMetadata): void {
                $optionsResolver
                    ->setDefined('by')
                    ->setAllowedTypes('by', 'string')
                    ->setAllowedValues('by', array_keys($userMetadata->fieldNames));
                $optionsResolver
                    ->setDefined('type')
                    ->setAllowedTypes('type', 'string')
                    ->setAllowedValues('type', [
                        OrderType::ASC->value,
                        OrderType::DESC->value,
                    ]);
            })
            ->setNormalizer('order', function (Options $options, array $value): ParameterBag {
                return new ParameterBag($value);
            });

        $resolver
            ->setDefined('offset')
            ->setAllowedTypes('offset', 'int')
            ->setAllowedValues('offset', function (int $value): bool {
                return $value > 0;
            });

        $resolver
            ->setDefined('limit')
            ->setAllowedTypes('limit', 'int')
            ->setAllowedValues('limit', function (int $value): bool {
                return $value > 0;
            });
    }

    /**
     * The ParameterBag, in contrast to InputBag which the original $this->query is implemented on, allows us
     * to have non-scalar parameters.
     */
    private function validateQueryString(OptionsResolver $resolver): ParameterBag
    {
        $castedQueryStringParameters = $this->castParameters($this->query->all());
        $resolvedParameters = $resolver->resolve($castedQueryStringParameters);

        return new ParameterBag($resolvedParameters);
    }

    public function validate(ClassMetadata $userMetadata): SearchData
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver, $userMetadata);

        try {
            $validatedQueryString = $this->validateQueryString($resolver);

            return new SearchData(
                filters: new Filters(
                    is_active: $validatedQueryString->get('filters')->get('is_active'),
                    is_member: $validatedQueryString->get('filters')->get('is_member'),
                    user_type: $validatedQueryString->get('filters')->get('user_type'),
                    last_login_at: $validatedQueryString->get('filters')->get('last_login_at'),
                ),
                order: new Order(
                    order_by: $validatedQueryString->get('order')->get('by'),
                    order_type: $validatedQueryString->get('order')->get('type'),
                ),
                offset: $validatedQueryString->get('offset'),
                limit: $validatedQueryString->get('limit'),
            );
        }
        catch (InvalidArgumentException $e) {
            throw new BadRequestException(
                message: $e->getMessage(),
                previous: $e,
            );
        }
    }
}
