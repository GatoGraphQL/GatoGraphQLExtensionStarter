<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Config\Rector\Configurators;

trait ContainerConfigurationServiceTrait
{
    /**
     * @return string[]
     */
    protected function getDownstreamProjectPaths(): array
    {
        return [
            $this->rootDirectory . '/layers/GatoGraphQLForWP/packages/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/packages/*/tests/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/plugins/*/src/*',
            $this->rootDirectory . '/layers/GatoGraphQLForWP/plugins/*/tests/*',
        ];
    }

    /**
     * Retrieve all the PHP stubs from under stubs/
     *
     * @return string[]
     */
    protected function getDownstreamBootstrapFiles(): array
    {
        $stubFiles = array_values(array_filter(
            scandir($this->rootDirectory . '/stubs'),
            fn(string $file) => str_ends_with($file, '.php')
        ));
        return $stubFiles;
        // return [
        //     $this->rootDirectory . '/stubs/wpackagist-plugin/hello-dolly/stubs.php',
        // ];
    }
}
