<?php

declare(strict_types=1);

namespace ExtensionVendor\ExtensionName;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;

class ExtensionStaticHelpers
{
    public static function getGitHubRepoDocsRootPathURL(): string
    {
        $extensionPluginVersion = PluginApp::getExtension(GatoGraphQLExtension::class)->getPluginVersion();
        $tag = PluginVersionHelpers::isDevelopmentVersion($extensionPluginVersion)
            ? 'master'
            : $extensionPluginVersion;
        return 'https://raw.githubusercontent.com/GatoGraphQL/ExtensionStarter/' . $tag . '/layers/GatoGraphQLForWP/plugins/extension-name/';
    }
}
