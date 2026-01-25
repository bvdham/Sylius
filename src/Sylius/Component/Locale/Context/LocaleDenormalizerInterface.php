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
 * Responsible for denormalizing a locale code coming from the request
 * (e.g. route parameter) into a valid Sylius locale.
 *
 * Examples:
 *  - "en_US"    → "en"
 *  - "nl_NL"    → "nl"
 *  - null    → default locale (if implicit default locale is enabled)
 *
 * This interface does not depend on the HTTP layer and can be reused
 * by different locale contexts or listeners.
 */
interface LocaleDenormalizerInterface
{
    /**
     * Denormalizes a locale from full locale code to URL format.
     *
     * Examples:
     *  - "en_US"    → "en"
     *  - "nl_NL"    → "nl"
     */
    public function denormalize(?string $localCode): ?string;
}
