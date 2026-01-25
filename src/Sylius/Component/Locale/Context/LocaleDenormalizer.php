<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Locale\Context;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;

/**
 * Default implementation of LocaleDenormalizerInterface.
 */
final class LocaleDenormalizer implements LocaleDenormalizerInterface
{
    public function __construct(
        private LocaleProviderInterface $localeProvider,
        private bool $implicitDefaultLocale = false,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(?string $localCode): ?string
    {
        if (null === $localCode) {
            return null;
        }

        $defaultLocale = $this->localeProvider->getDefaultLocaleCode();

        // Hide default locale in URL
        if ($this->implicitDefaultLocale && $localCode === $defaultLocale) {
            return null;
        }

        // Already short (defensive)
        if (strlen($localCode) === 2) {
            return $localCode;
        }

        // Expected format: ll_CC
        if (str_contains($localCode, '_')) {
            return strtolower(substr($localCode, 0, 2));
        }

        return $localCode;
    }
}
