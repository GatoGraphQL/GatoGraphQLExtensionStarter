<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\SmartFile;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class CopyUpstreamMonorepoFileEntriesProvider
{
    /**
     * @var array<string,string>
     */
    private array $entries = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        $this->entries = $parameterProvider->provideArrayParameter(CustomOption::COPY_UPSTREAM_MONOREPO_FILE_ENTRIES);
    }

    /**
     * @return array<string,mixed>
     */
    public function provideCopyUpstreamMonorepoFileEntries(): array
    {
        /**
         * Validate that all required entries have been provided
         */
        $requiredEntries = [
            'files',
            'toFolder',
        ];
        $entries = [];
        foreach ($this->entries as $entryConfig) {
            $unprovidedEntries = array_diff(
                $requiredEntries,
                array_keys((array) $entryConfig)
            );
            if ($unprovidedEntries !== []) {
                throw new ShouldNotHappenException(sprintf(
                    "The following entries must be provided for copying files from the upstream monorepo: '%s'",
                    implode("', '", $unprovidedEntries)
                ));
            }

            $entries[] = $entryConfig;
        }

        return $entries;
    }
}
