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

namespace Sylius\Bundle\LocaleBundle\Context;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Sylius\Component\Locale\Context\LocaleNormalizerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Resolves locale based on the current HTTP request.
 *
 * This context delegates locale normalization to LocaleNormalizerInterface,
 * allowing support for:
 *  - implicit default locale
 *  - short locale codes (e.g. "nl")
 *  - full locale codes (e.g. "nl_NL")
 */
final class RequestBasedLocaleContext implements LocaleContextInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private LocaleNormalizerInterface $localeNormalizer,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode(): string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            throw new LocaleNotFoundException('No main request available.');
        }

        /** @var string|null $localeCode */
        $localeCode = $request->attributes->get('_locale');

        return $this->localeNormalizer->normalize($localeCode);
    }
}
