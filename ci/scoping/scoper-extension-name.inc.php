<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

/**
 * @see submodules/GatoGraphQL/ci/scoping/scoper-extension-name.inc.php
 */
function convertRelativeToFullPath(string $relativePath): string
{
    $monorepoDir = dirname(__DIR__, 2);
    $pluginDir = $monorepoDir . '/layers/GatoGraphQLForWP/plugins';
    return $pluginDir . '/' . $relativePath;
}

return [
    'prefix' => 'PrefixedExtensionVendor',
    'finders' => [
        // Scope packages under vendor/, excluding local WordPress packages
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/.*\\.md|.*\\.dist|composer\\.lock/')
            ->exclude([
                'tests',
            ])
            ->notPath([
                // Exclude all composer.json from own libraries (they get broken!)
                '#[extension\-vendor]/*/composer.json#',
                // // Exclude all libraries for WordPress: Packages ending in "-wp"
                // '#[extension\-vendor]/[a-zA-Z0-9_-]*-wp/#',
                // ...
                // Exclude libraries
                // ...
            ])
            ->in([
                convertRelativeToFullPath('extension-name/vendor'),
            ]),
    ],
    'exclude-namespaces' => [
        // Own namespaces
        'ExtensionVendor',
    ],
];