<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Routing;

use Sylius\Component\Locale\Context\LocaleDenormalizerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class LocalizedRouter implements RouterInterface, WarmableInterface
{
    public function __construct(
        private RouterInterface $decoratedRouter,
        private LocaleDenormalizerInterface $localeDenormalizer,
    ) {
    }

    public function generate(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if (array_key_exists('_locale', $parameters)) {
            $denormalizedLocale = $this->localeDenormalizer->denormalize(
                $parameters['_locale']
            );

            if (null === $denormalizedLocale) {
                unset($parameters['_locale']);
            } else {
                $parameters['_locale'] = $denormalizedLocale;
            }
        }

        return $this->decoratedRouter->generate($name, $parameters, $referenceType);
    }

    public function setContext(RequestContext $context): void
    {
        $this->decoratedRouter->setContext($context);
    }

    public function getContext(): RequestContext
    {
        return $this->decoratedRouter->getContext();
    }

    public function getRouteCollection(): RouteCollection
    {
        return $this->decoratedRouter->getRouteCollection();
    }

    public function match(string $pathinfo): array
    {
        return $this->decoratedRouter->match($pathinfo);
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        if ($this->decoratedRouter instanceof WarmableInterface) {
            return $this->decoratedRouter->warmUp($cacheDir);
        }

        return [];
    }
}
