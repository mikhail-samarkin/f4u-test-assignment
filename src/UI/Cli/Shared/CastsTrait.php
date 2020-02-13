<?php

declare(strict_types=1);

namespace App\UI\Cli\Shared;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Trait for casts array<string>|bool|string|null in method getOption
 * Need for PHPStan
 */
trait CastsTrait
{
    public function getStringFromOption(InputInterface $input, string $nameOption): string
    {
        $option = $input->getOption($nameOption);

        if (!is_string($option)) {
            throw new \RuntimeException("Option " .  strtoupper($nameOption) . " is not a string");
        }

        return $option;
    }

    public function getIntFromOption(InputInterface $input, string $nameOption): int
    {
        $option = $input->getOption($nameOption);

        if (is_array($option)) {
            throw new \RuntimeException("Option " .  strtoupper($nameOption) . " can not be array");
        }

        return (int) $option;
    }
}
