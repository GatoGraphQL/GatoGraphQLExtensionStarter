<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Command;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Configuration\InitializeProjectStageResolver;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Configuration\ModifyProjectStageResolverInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\ModifyProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\StageAwareModifyProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Guard\InitializeProjectGuardInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InitializeProjectWorkerProvider;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\InitializeProjectInputObject;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\ModifyProjectInputObjectInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Output\ModifyProjectWorkerReporter;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\ValueObject\Option;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class InitializeProjectCommand extends AbstractModifyProjectCommand
{
    protected ?InitializeProjectInputObject $inputObject = null;
    protected ?string $defaultGitBaseBranch = null;
    protected ?string $defaultGitUserName = null;
    protected ?string $defaultGitUserEmail = null;
    protected ?string $defaultGitHubRepoOwner = null;
    protected ?string $defaultGitHubRepoName = null;

    public function __construct(
        private InitializeProjectWorkerProvider $initializeProjectWorkerProvider,
        private InitializeProjectStageResolver $initializeProjectStageResolver,
        private InitializeProjectGuardInterface $initializeProjectGuard,
        private ProcessRunner $processRunner,
        SourcesPresenceValidator $sourcesPresenceValidator,
        // VersionResolver $versionResolver,
        ModifyProjectWorkerReporter $modifyProjectWorkerReporter
    ) {
        parent::__construct(
            $sourcesPresenceValidator,
            // $versionResolver,
            $modifyProjectWorkerReporter,
        );
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Initialize the project, replacing the extension starter data with your own data.');
        
        $this->addArgument(
            Option::PHP_NAMESPACE_OWNER,
            InputArgument::REQUIRED,
            sprintf(
                'PHP namespace owner to use in the codebase. Eg: "MyCompanyName"',
                Option::PHP_NAMESPACE_OWNER
            )
        );
        $this->addOption(
            Option::COMPOSER_VENDOR,
            null,
            null,
            sprintf(
                'Composer vendor to use in the repo. If not provided, it is generated from the PHP Namespace Owner',
                Option::COMPOSER_VENDOR
            )
        );

        $this->addOption(
            Option::INITIAL_VERSION,
            null,
            InputOption::VALUE_REQUIRED,
            'Initial version to use in the monorepo, in semver format (Major.Minor.Patch)',
            $this->getDefaultInitialVersion()
        );
        $this->addOption(
            Option::GIT_BASE_BRANCH,
            null,
            null,
            'Base branch of the GitHub repository where this project is hosted. If not provided, this value is retrieved using `git`',
            null
        );
        $this->addOption(
            Option::GIT_USER_NAME,
            null,
            null,
            'Git user name, to "split" code and push it to a different repo when merging a PR. If not provided, this value is retrieved from the global `git` config',
            null
        );
        $this->addOption(
            Option::GIT_USER_EMAIL,
            null,
            null,
            'Git user email, to "split" code and push it to a different repo when merging a PR. If not provided, this value is retrieved from the global `git` config',
            null
        );

        $this->addOption(
            Option::GITHUB_REPO_OWNER,
            null,
            null,
            'Owner of the GitHub repository where this project is hosted (eg: "GatoGraphQL" in "https://github.com/GatoGraphQL/ExtensionStarter"). If not provided, this value is retrieved using `git`',
            null //$this->getDefaultGitHubRepoOwner()
        );
        $this->addOption(
            Option::GITHUB_REPO_NAME,
            null,
            null,
            'Name of the GitHub repository where this project is hosted (eg: "ExtensionStarter" in "https://github.com/GatoGraphQL/ExtensionStarter"). If not provided, this value is retrieved using `git`',
            null //$this->getDefaultGitHubRepoName()
        );

        $this->addOption(
            Option::DOCS_GIT_BASE_BRANCH,
            null,
            null,
            sprintf(
                'Base branch of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `%s` is used',
                Option::GIT_BASE_BRANCH
            ),
            null
        );
        $this->addOption(
            Option::DOCS_GITHUB_REPO_OWNER,
            null,
            null,
            sprintf(
                'Owner of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `%s` is used',
                Option::GITHUB_REPO_OWNER
            ),
            null
        );
        $this->addOption(
            Option::DOCS_GITHUB_REPO_NAME,
            null,
            null,
            sprintf(
                'Name of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `%s` is used',
                Option::GITHUB_REPO_NAME
            ),
            null
        );
    }

    /**
     * @return ModifyProjectWorkerInterface[]|StageAwareModifyProjectWorkerInterface[]
     */
    protected function getModifyProjectWorkers(string $stage): array
    {
        return $this->initializeProjectWorkerProvider->provideByStage($stage);
    }

    protected function getModifyProjectInputObject(InputInterface $input, string $stage): ModifyProjectInputObjectInterface
    {
        if ($this->inputObject === null) {
            $initialVersion = (string) $input->getOption(Option::INITIAL_VERSION);
            if ($initialVersion === '') {
                $initialVersion = $this->getDefaultInitialVersion();
            }
            // validation
            $this->initializeProjectGuard->guardVersion($initialVersion);

            $gitBaseBranch = (string) $input->getOption(Option::GIT_BASE_BRANCH);
            if ($gitBaseBranch === '') {
                $gitBaseBranch = $this->getDefaultGitBaseBranch();
            }
            $gitUserName = (string) $input->getOption(Option::GIT_USER_NAME);
            if ($gitUserName === '') {
                $gitUserName = $this->getDefaultGitUserName();
            }
            $gitUserEmail = (string) $input->getOption(Option::GIT_USER_EMAIL);
            if ($gitUserEmail === '') {
                $gitUserEmail = $this->getDefaultGitUserEmail();
            }
            $githubRepoOwner = (string) $input->getOption(Option::GITHUB_REPO_OWNER);
            if ($githubRepoOwner === '') {
                $githubRepoOwner = $this->getDefaultGitHubRepoOwner();
            }
            $githubRepoName = (string) $input->getOption(Option::GITHUB_REPO_NAME);
            if ($githubRepoName === '') {
                $githubRepoName = $this->getDefaultGitHubRepoName();
            }
            $docsGitBaseBranch = (string) $input->getOption(Option::DOCS_GIT_BASE_BRANCH);
            if ($docsGitBaseBranch === '') {
                $docsGitBaseBranch = $gitBaseBranch;
            }
            $docsGithubRepoOwner = (string) $input->getOption(Option::DOCS_GITHUB_REPO_OWNER);
            if ($docsGithubRepoOwner === '') {
                $docsGithubRepoOwner = $githubRepoOwner;
            }
            $docsGithubRepoName = (string) $input->getOption(Option::DOCS_GITHUB_REPO_NAME);
            if ($docsGithubRepoName === '') {
                $docsGithubRepoName = $githubRepoName;
            }
            $phpNamespaceOwner = (string) $input->getArgument(Option::PHP_NAMESPACE_OWNER);
            // validation
            $this->initializeProjectGuard->guardPHPNamespaceOwner($phpNamespaceOwner);
            $composerVendor = (string) $input->getOption(Option::COMPOSER_VENDOR);
            if ($composerVendor === '') {
                $composerVendor = $this->camelToUnderscore($phpNamespaceOwner);
            }
            $this->inputObject = new InitializeProjectInputObject(
                $phpNamespaceOwner,
                $composerVendor,
                $initialVersion,
                $gitBaseBranch,
                $gitUserName,
                $gitUserEmail,
                $githubRepoOwner,
                $githubRepoName,
                $docsGitBaseBranch,
                $docsGithubRepoOwner,
                $docsGithubRepoName,
            );
        }
        return $this->inputObject;
    }

    protected function getDefaultInitialVersion(): string
    {
        return '0.1.0';
    }

    protected function getDefaultGitHubRepoOwner(): string
    {
        if ($this->defaultGitHubRepoOwner === null) {
            $this->defaultGitHubRepoOwner = trim($this->processRunner->run("basename -s .git $(dirname `git config --get remote.origin.url`)"));
        }
        return $this->defaultGitHubRepoOwner;
    }

    protected function getDefaultGitHubRepoName(): string
    {
        if ($this->defaultGitHubRepoName === null) {
            $this->defaultGitHubRepoName = trim($this->processRunner->run("basename -s .git `git config --get remote.origin.url`"));
        }
        return $this->defaultGitHubRepoName;
    }

    protected function getDefaultGitBaseBranch(): string
    {
        if ($this->defaultGitBaseBranch === null) {
            $this->defaultGitBaseBranch = trim($this->processRunner->run("git remote show origin | sed -n '/HEAD branch/s/.*: //p'"));
        }
        return $this->defaultGitBaseBranch;
    }

    protected function getDefaultGitUserName(): string
    {
        if ($this->defaultGitUserName === null) {
            $this->defaultGitUserName = trim($this->processRunner->run("git config user.name"));
        }
        return $this->defaultGitUserName;
    }

    protected function getDefaultGitUserEmail(): string
    {
        if ($this->defaultGitUserEmail === null) {
            $this->defaultGitUserEmail = trim($this->processRunner->run("git config user.email"));
        }
        return $this->defaultGitUserEmail;
    }

    protected function getModifyProjectStageResolver(): ModifyProjectStageResolverInterface
    {
        return $this->initializeProjectStageResolver;
    }

    protected function getSuccessMessage(): string
    {
        return 'The project has been successfully initialized';
    }

    /**
     * @see https://stackoverflow.com/a/40514305/14402031
     */
    protected function camelToUnderscore(string $string, string $us = "-"): string
    {
        return strtolower(
            preg_replace(
                '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/',
                $us,
                $string
            )
        );
    }
}
