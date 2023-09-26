<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Monorepo;

final class MonorepoMetadata
{
    /**
     * Modify this const when bumping the code to a new version.
     *
     * Important: This code is read-only! A ReleaseWorker
     * will search for this pattern using a regex, to update the
     * version when creating a new release
     * (i.e. via `composer release-major|minor|patch`).
     */
    final public const VERSION = '1.1.0-dev';

    final public const GIT_BASE_BRANCH = 'main';
    final public const GIT_USER_NAME = 'extension-git-user-name';
    final public const GIT_USER_EMAIL = 'extension-git-user@email.com';

    final public const GITHUB_REPO_OWNER = 'GatoGraphQL';
    final public const GITHUB_REPO_NAME = 'ExtensionStarter';
}
