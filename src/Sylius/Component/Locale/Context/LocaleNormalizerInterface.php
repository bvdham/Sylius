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

/**
 * Responsible for normalizing a locale code coming from the request
 * (e.g. route parameter) into a valid Sylius locale.
 *
 * Examples:
 *  - "en"    → "en_US"
 *  - "nl"    → "nl_NL"
 *  - "en_US" → "en_US"
 *  - null    → default locale (if implicit default locale is enabled)
 *
 * This interface does not depend on the HTTP layer and can be reused
 * by different locale contexts or listeners.
 */
interface LocaleNormalizerInterface
{
    /**
     * Normalizes a locale code into a valid Sylius locale.
     *
     * @param string|null $localeCode Locale code from the request (e.g. "en", "nl_NL") or null
     *
     * @throws LocaleNotFoundException If the locale cannot be resolved or is not available
     */
    public function normalize(?string $localeCode): string;
}
