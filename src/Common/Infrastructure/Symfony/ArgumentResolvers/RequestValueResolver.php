<?php declare(strict_types=1);

namespace olml89\PlayaMedia\Common\Infrastructure\Symfony\ArgumentResolvers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * https://github.com/symfony/symfony/blob/6.2/src/Symfony/Component/HttpKernel/Controller/ArgumentResolver/RequestValueResolver.php
 */
final class RequestValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Request::class === $argument->getType() || is_subclass_of($argument->getType(), Request::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($this->supports($request, $argument)) {
            /** @var class-string<Request> $requestClass */
            $requestClass = $argument->getType();

            $request = $requestClass::createFromGlobals();
        }

        yield $request;
    }
}
