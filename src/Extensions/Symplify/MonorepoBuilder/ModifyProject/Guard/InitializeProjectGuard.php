<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Guard;

use PharIo\Version\InvalidVersionException;
use PharIo\Version\Version;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\InitializeProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\ModifyProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Exception\ConfigurationException;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class InitializeProjectGuard extends AbstractModifyProjectGuard
{
    /**
     * @param InitializeProjectWorkerInterface[] $initializeProjectWorkers
     */
    public function __construct(
        ParameterProvider $parameterProvider,
        private array $initializeProjectWorkers
    ) {
        parent::__construct($parameterProvider);
    }

    /**
     * @return ModifyProjectWorkerInterface[]
     */
    protected function getModifyProjectWorkers(): array
    {
        return $this->initializeProjectWorkers;
    }

    /**
     * Make sure the INITIAL_VERSION input follows semver
     */
    public function guardVersion(string $initialVersion): void
    {
        try {
            new Version($initialVersion);
        } catch (InvalidVersionException $e) {
            throw new ConfigurationException(sprintf(
                'Version "%s" does not follow semver',
                $initialVersion
            ));
        }
    }
}
