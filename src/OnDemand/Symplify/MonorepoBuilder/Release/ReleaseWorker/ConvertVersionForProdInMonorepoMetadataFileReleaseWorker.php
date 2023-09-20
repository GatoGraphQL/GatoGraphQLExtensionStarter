<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\ConvertVersionForProdInMonorepoMetadataFileReleaseWorker as UpstreamConvertVersionForProdInMonorepoMetadataFileReleaseWorker;

class ConvertVersionForProdInMonorepoMetadataFileReleaseWorker extends UpstreamConvertVersionForProdInMonorepoMetadataFileReleaseWorker
{
    protected function getMonorepoMetadataFile(): string
    {
        return dirname(__DIR__, 6) . '/src/Monorepo/MonorepoMetadata.php';
    }
}
