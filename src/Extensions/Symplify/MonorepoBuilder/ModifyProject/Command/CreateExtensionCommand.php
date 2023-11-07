<?php

declare(strict_types=1);

namespace PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Command;

use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Configuration\CreateExtensionStageResolver;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Configuration\ModifyProjectStageResolverInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\ModifyProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Contract\ModifyProjectWorker\StageAwareModifyProjectWorkerInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Guard\CreateExtensionGuardInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\CreateExtensionWorkerProvider;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\CreateExtensionInputObject;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\InputObject\ModifyProjectInputObjectInterface;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\Output\ModifyProjectWorkerReporter;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\ModifyProject\ValueObject\Option;
use PoP\ExtensionStarter\Extensions\Symplify\MonorepoBuilder\Utils\StringUtils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class CreateExtensionCommand extends AbstractModifyProjectCommand
{
    protected ?CreateExtensionInputObject $inputObject = null;
    // @todo Review Options for the CreateExtension command
    // protected ?string $defaultGitBaseBranch = null;
    // protected ?string $defaultGitUserName = null;
    // protected ?string $defaultGitUserEmail = null;
    // protected ?string $defaultGitHubRepoOwner = null;
    // protected ?string $defaultGitHubRepoName = null;

    public function __construct(
        private CreateExtensionWorkerProvider $createExtensionWorkerProvider,
        private CreateExtensionStageResolver $createExtensionStageResolver,
        private CreateExtensionGuardInterface $createExtensionGuard,
        private ProcessRunner $processRunner,
        private StringUtils $stringUtils,
        SourcesPresenceValidator $sourcesPresenceValidator,
        ModifyProjectWorkerReporter $modifyProjectWorkerReporter
    ) {
        parent::__construct(
            $sourcesPresenceValidator,
            $modifyProjectWorkerReporter,
        );
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Create the scaffolding for an extension plugin, hosted in this monorepo.');

        // @todo Review Options for the CreateExtension command
        $this->addOption(
            Option::EXTENSION_NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Extension plugin name',
        );
        $this->addOption(
            Option::EXTENSION_SLUG,
            null,
            InputOption::VALUE_REQUIRED,
            sprintf(
                'Slug of the extension plugin. If not provided, it is generated from the "%s" option',
                Option::EXTENSION_NAME
            )
        );
        $this->addOption(
            Option::EXTENSION_CLASSNAME,
            null,
            InputOption::VALUE_REQUIRED,
            sprintf(
                'PHP classname to append to classes in the extension plugin. If not provided, it is generated from the "%s" option',
                Option::EXTENSION_SLUG
            )
        );
    }

    /**
     * @return ModifyProjectWorkerInterface[]|StageAwareModifyProjectWorkerInterface[]
     */
    protected function getModifyProjectWorkers(string $stage): array
    {
        return $this->createExtensionWorkerProvider->provideByStage($stage);
    }

    protected function getModifyProjectInputObject(InputInterface $input, string $stage): ModifyProjectInputObjectInterface
    {
        if ($this->inputObject === null) {
            // @todo Review Options for the CreateExtension command
            $extensionName = (string) $input->getOption(Option::EXTENSION_NAME);
            // validation
            $this->createExtensionGuard->guardExtensionName($extensionName);

            $extensionSlug = (string) $input->getOption(Option::EXTENSION_SLUG);
            if ($extensionSlug === '') {
                $extensionSlug = $this->stringUtils->slugify($extensionName);
            }
            // validation
            $this->createExtensionGuard->guardExtensionSlug($extensionSlug);

            $extensionClassname = (string) $input->getOption(Option::EXTENSION_CLASSNAME);
            if ($extensionClassname === '') {
                $extensionClassname = $this->stringUtils->dashesToCamelCase($extensionSlug);
            }
            // validation
            $this->createExtensionGuard->guardExtensionClassname($extensionClassname);

            $this->inputObject = new CreateExtensionInputObject(
                // @todo Review Options for the CreateExtension command
                $extensionName,
                $extensionSlug,
                $extensionClassname,
            );
        }
        return $this->inputObject;
    }

    // @todo Review Options for the CreateExtension command
    // protected function getDefaultGitHubRepoOwner(): string
    // {
    //     if ($this->defaultGitHubRepoOwner === null) {
    //         $this->defaultGitHubRepoOwner = trim($this->processRunner->run("basename -s .git $(dirname `git config --get remote.origin.url`)"));
    //     }
    //     return $this->defaultGitHubRepoOwner;
    // }

    // protected function getDefaultGitHubRepoName(): string
    // {
    //     if ($this->defaultGitHubRepoName === null) {
    //         $this->defaultGitHubRepoName = trim($this->processRunner->run("basename -s .git `git config --get remote.origin.url`"));
    //     }
    //     return $this->defaultGitHubRepoName;
    // }

    // protected function getDefaultGitBaseBranch(): string
    // {
    //     if ($this->defaultGitBaseBranch === null) {
    //         $this->defaultGitBaseBranch = trim($this->processRunner->run("git remote show origin | sed -n '/HEAD branch/s/.*: //p'"));
    //     }
    //     return $this->defaultGitBaseBranch;
    // }

    // protected function getDefaultGitUserName(): string
    // {
    //     if ($this->defaultGitUserName === null) {
    //         $this->defaultGitUserName = trim($this->processRunner->run("git config user.name"));
    //     }
    //     return $this->defaultGitUserName;
    // }

    // protected function getDefaultGitUserEmail(): string
    // {
    //     if ($this->defaultGitUserEmail === null) {
    //         $this->defaultGitUserEmail = trim($this->processRunner->run("git config user.email"));
    //     }
    //     return $this->defaultGitUserEmail;
    // }

    protected function getModifyProjectStageResolver(): ModifyProjectStageResolverInterface
    {
        return $this->createExtensionStageResolver;
    }

    protected function getSuccessMessage(): string
    {
        return 'The extension has been successfully created';
    }
}