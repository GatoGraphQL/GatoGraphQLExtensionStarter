<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\OnDemand\Symplify\MonorepoBuilder\ModifyProject\ModifyProjectWorker;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\InitializeProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\ModifyProjectInputObjectInterface;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;

final class UpdateCurrentBranchAliasModifyProjectWorker implements InitializeProjectWorkerInterface
{
    public function __construct(
        private DevMasterAliasUpdater $devMasterAliasUpdater,
        private ComposerJsonProvider $composerJsonProvider,
        // private VersionUtils $versionUtils
    ) {
    }

    public function work(ModifyProjectInputObjectInterface $inputObject): void
    {
        // @todo Fix ModifyProject
        // $nextAlias = $this->versionUtils->getCurrentAliasFormat($version);
        $nextAlias = '1.0.0';

        $this->devMasterAliasUpdater->updateFileInfosWithAlias(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $nextAlias
        );
    }

    public function getDescription(ModifyProjectInputObjectInterface $inputObject): string
    {
        // @todo Fix ModifyProject
        // $nextAlias = $this->versionUtils->getCurrentAliasFormat($version);
        $nextAlias = '1.0.0';

        return sprintf('Set branch alias "%s" to all packages', $nextAlias);
    }
}
