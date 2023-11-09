<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\OnDemand\Symplify\MonorepoBuilder\ModifyProject\CreateExtensionWorker;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\CreateExtensionWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\CreateExtensionInputObjectInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\ModifyProjectInputObjectInterface;
use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\SmartFileSystem\SmartFileInfo;

class UpdateExtensionPluginVSCodeConfigCreateExtensionWorker implements CreateExtensionWorkerInterface
{
    public function __construct(
        protected JsonFileManager $jsonFileManager,
    ) {
    }

    /**
     * @param CreateExtensionInputObjectInterface $inputObject
     */
    public function getDescription(ModifyProjectInputObjectInterface $inputObject): string
    {
        $items = [
            'Added mapping for packages'
        ];
        return sprintf(
            '%s:%s%s',
            'Update the extension plugin\'s VSCode launch.json file',
            PHP_EOL . '- ',
            implode(PHP_EOL . '- ', $items)
        );
    }

    /**
     * Check there's an integration plugin required, otherwise
     * nothing to do.
     *
     * @param CreateExtensionInputObjectInterface $inputObject
     */
    public function work(ModifyProjectInputObjectInterface $inputObject): void
    {
        $vscodeLaunchJSONFile = $this->getVSCodeLaunchJSONFile();
        $this->addMappingForPackagesToVSCodeLaunchJSONFile($vscodeLaunchJSONFile);
    }

    /**
     * @param CreateExtensionInputObjectInterface $inputObject
     */
    protected function addMappingForPackagesToVSCodeLaunchJSONFile(
        string $vscodeLaunchJSONFile,
    ): void {
        $vscodeLaunchJSONFileSmartFileInfo = new SmartFileInfo($vscodeLaunchJSONFile);

        $json = $this->jsonFileManager->loadFromFileInfo($vscodeLaunchJSONFileSmartFileInfo);
        foreach ($json['configurations'] as &$configuration) {
            if (!str_starts_with($configuration['name'], '[Lando webserver]')) {
                continue;
            }
            $configuration['pathMappings'] = array_merge(
                $configuration['pathMappings'] ?? [],
                $this->getVSCodeMappingEntries(),
            );
        }
        
        $this->jsonFileManager->printJsonToFileInfo($json, $vscodeLaunchJSONFileSmartFileInfo);
    }

    protected function getVSCodeLaunchJSONFile(): string
    {
        $rootFolder = dirname(__DIR__, 6);
        return $rootFolder . '/.vscode/launch.json';
    }

    /**
     * @return string[]
     */
    protected function getVSCodeMappingEntries(): array
    {
        $entries = [
            'layers/GatoGraphQLForWP/packages/extension-template-schema',
            'layers/GatoGraphQLForWP/plugins/extension-template',
        ];
        return array_map(
            fn (string $entry) => '${workspaceFolder}/' . $entry,
            $entries 
        );
    }
}
