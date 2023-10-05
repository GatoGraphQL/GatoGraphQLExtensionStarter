<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\OnDemand\Symplify\MonorepoBuilder\ModifyProject\ModifyProjectWorker;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\FilesContainingStringFinder;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\InitializeProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\InitializeProjectInputObjectInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\ModifyProjectInputObjectInterface;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractSearchAndReplaceTextInCodebaseInitializeProjectWorker implements InitializeProjectWorkerInterface
{
    public function __construct(
        private FilesContainingStringFinder $filesContainingStringFinder,
        protected FileContentReplacerSystem $fileContentReplacerSystem,
    ) {
    }

    public function work(ModifyProjectInputObjectInterface $inputObject): void
    {
        foreach ($this->getReplacements($inputObject) as $search => $replace) {
            $files = $this->getFilesContainingStringSmartFileInfos($search);
            $this->fileContentReplacerSystem->replaceContentInFiles(
                $files,
                [
                    $search => $replace,
                ],
            );
        }
    }

    /**
     * @return array<string,string> Key: string to search, Value: string to replace with
     */
    abstract protected function getReplacements(InitializeProjectInputObjectInterface $inputObject): array;

    /**
     * @return SmartFileInfo[]
     */
    protected function getFilesContainingStringSmartFileInfos(string $search): array
    {
        $folders = $this->getFolders();
        return $this->filesContainingStringFinder->findFilesContainingString($folders, $search);
    }

    /**
     * By default, directly search within the root folder
     * @return string[]
     */
    protected function getFolders(): array
    {
        return [
            $this->getRootFolder(),
        ];
    }

    protected function getRootFolder(): string
    {
        return dirname(__DIR__, 6);
    }
}