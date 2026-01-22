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
 * Default implementation of LocaleNormalizerInterface.
 *
 * It resolves locale codes coming from the request into valid Sylius locales.
 *
 * Supported scenarios:
 *  - Full locale codes: "en_US", "nl_NL"
 *  - Short locale codes: "en", "nl" (matched against available locales)
 *  - Implicit default locale when no locale is provided
 */
final class LocaleNormalizer implements LocaleNormalizerInterface
{
    public function __construct(
        private LocaleProviderInterface $localeProvider,
        private bool $implicitDefaultLocale = false,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(?string $localeCode): string
    {
        // No locale provided in the request
        if (null === $localeCode) {
            if ($this->implicitDefaultLocale) {
                return $this->localeProvider->getDefaultLocaleCode();
            }

            throw new LocaleNotFoundException('No locale code was provided.');
        }

        $availableLocales = $this->localeProvider->getAvailableLocalesCodes();

        // Exact match (e.g. "en_US")
        if (in_array($localeCode, $availableLocales, true)) {
            return $localeCode;
        }

        // Short locale match (e.g. "en" → "en_US")
        foreach ($availableLocales as $availableLocale) {
            if (str_starts_with($availableLocale, $localeCode . '_')) {
                return $availableLocale;
            }
        }

        throw LocaleNotFoundException::notAvailable($localeCode, $availableLocales);
    }
}
