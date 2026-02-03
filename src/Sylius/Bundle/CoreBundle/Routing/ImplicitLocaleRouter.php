<?php

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

/**
 * Router decorator responsible for handling implicit locale behavior in URLs.
 *
 * It delegates route matching to the decorated router, while allowing
 * customization of locale handling during URL generation.
 */
final class ImplicitLocaleRouter implements RouterInterface, RequestMatcherInterface, WarmableInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * Matches a pathinfo string to a set of routing parameters.
     *
     * @param string $pathinfo The path info to be matched
     *
     * @return array<string, mixed> An array of route parameters
     */
    public function match(string $pathinfo): array
    {
        return $this->router->match($pathinfo);
    }

    /**
     * Matches a Request object to a set of routing parameters.
     *
     * @param Request $request The request to match
     *
     * @return array<string, mixed> An array of route parameters
     */
    public function matchRequest(Request $request): array
    {
        return $this->router->matchRequest($request);
    }

    /**
     * Generates a URL for the given route name and parameters.
     *
     * This method is the primary extension point for modifying how locale
     * parameters are represented in generated URLs.
     *
     * @param string $name The name of the route
     * @param array<string, mixed> $parameters An array of route parameters
     * @param int $referenceType The type of reference to be generated
     *
     * @return string The generated URL
     */
    public function generate(
        string $name,
        array $parameters = [],
        int $referenceType = self::ABSOLUTE_PATH,
    ): string {
        return $this->router->generate($name, $parameters, $referenceType);
    }

    /**
     * Sets the request context.
     *
     * @param RequestContext $context The request context
     */
    public function setContext(RequestContext $context): void
    {
        $this->router->setContext($context);
    }

    /**
     * Gets the current request context.
     */
    public function getContext(): RequestContext
    {
        return $this->router->getContext();
    }

    /**
     * Returns the route collection.
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->router->getRouteCollection();
    }

    /**
     * Warms up the router cache.
     *
     * @param string $cacheDir The cache directory
     * @param string|null $buildDir The build directory (optional)
     *
     * @return array<int, string> A list of cache files
     */
    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        if ($this->router instanceof WarmableInterface) {
            return $this->router->warmUp($cacheDir, $buildDir);
        }

        return [];
    }
}
