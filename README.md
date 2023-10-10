# Gato GraphQL - Extension Starter

GitHub template repository to develop and release your extensions for Gato GraphQL.

![Unit tests](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/unit_tests.yml/badge.svg)
![Downgrade PHP tests](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/downgrade_php_tests.yml/badge.svg)
![Scoping tests](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/scoping_tests.yml/badge.svg)
![Generate plugins](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/generate_plugins.yml/badge.svg)
![PHPStan](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/phpstan.yml/badge.svg)

<!--
@gatographql-project-info

Show a badge for the integration tests against InstaWP

@gatographql-project-action-maybe-required

If these tests are enabled, add the badge code:

![Integration tests](https://github.com/GatoGraphQL/ExtensionStarter/actions/workflows/integration_tests.yml/badge.svg)
-->

## Requirements

- [Lando](https://lando.dev/)
- [Composer](https://getcomposer.org/)

### Recommended to use

- [XDebug](https://xdebug.org/) (integrated out of the box when using [VSCode](https://code.visualstudio.com/))

## What are Gato GraphQL Extensions?

Gato GraphQL extensions add functionality and expand the GraphQL schema provided by the [Gato GraphQL](https://gatographql.com) plugin.

Check [gatographql.com/extensions](https://gatographql.com/extensions/) to browse the list of existing (commercial) extensions, to give you ideas of what you can do via them.

## Install

Follow these steps:

### Create your repo from this template

Create your own repository from the `GatoGraphQL/ExtensionStarter` template:

- Click on "Use this template => Create a new repository"
- Select the GitHub owner, and choose a proper name for your repository (eg: `my-account/GatoGraphQLExtensionsForMyCompany`)
- Choose if to make it Public or Private
- Click on "Create repository"

### Clone your repo locally

Once you have created your repository, clone it in your local drive using the `--recursive` option (needed to clone Git submodule `GatoGraphQL/GatoGraphQL`):

```bash
git clone --recursive https://github.com/my-account/GatoGraphQLExtensionsForMyCompany
```

### Install Composer dependencies

Run:

```bash
$ cd {project folder}
$ cd submodules/GatoGraphQL && composer install && cd ../.. && composer install
```

### Initialize the Project

This step will replace all the generic strings in the extension starter (the PHP namespace `MyCompanyForGatoGraphQL`, company name `My Company`, and others) with the values corresponding to your project.

Input your values in the command below, and then run:

```bash
composer initialize-project -- \
  --php-namespace-owner=MyCompanyName \
  --my-company-name="My Company Name" \
  --my-company-email=email@mycompany.com \
  --my-company-website=https://www.mycompany.com
```

These arguments (and additional ones, see below) are optional. If any of them is not provided, a default value is computed from the configuration in Git and the GitHub repo.

To see the default values, run:

```bash
composer initialize-project -- --dry-run
```

<details>

<summary>All <code>initialize-project</code> command arguments</summary>

To print all the arguments for the `initialize-project` command, run:

```bash
composer initialize-project -- --help
```

This will print:

```
--git-base-branch=GIT-BASE-BRANCH                Base branch of the GitHub repository where this project is hosted. If not provided, this value is retrieved using `git`
--git-user-name=GIT-USER-NAME                    Git user name, to "split" code and push it to a different repo when merging a PR. If not provided, this value is retrieved from the global `git` config
--git-user-email=GIT-USER-EMAIL                  Git user email, to "split" code and push it to a different repo when merging a PR. If not provided, this value is retrieved from the global `git` config
--github-repo-owner=GITHUB-REPO-OWNER            Owner of the GitHub repository where this project is hosted (eg: "GatoGraphQL" in "https://github.com/GatoGraphQL/ExtensionStarter"). If not provided, this value is retrieved using `git`
--github-repo-name=GITHUB-REPO-NAME              Name of the GitHub repository where this project is hosted (eg: "ExtensionStarter" in "https://github.com/GatoGraphQL/ExtensionStarter"). If not provided, this value is retrieved using `git`
--docs-git-base-branch=DOCS-GIT-BASE-BRANCH      Base branch of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `git-base-branch` is used
--docs-github-repo-owner=DOCS-GITHUB-REPO-OWNER  Owner of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `github-repo-owner` is used
--docs-github-repo-name=DOCS-GITHUB-REPO-NAME    Name of the (public) GitHub repository hosting the documentation for the extension, to access the images in PROD. If not provided, the value for option `github-repo-name` is used
--php-namespace-owner=PHP-NAMESPACE-OWNER        PHP namespace owner to use in the codebase (eg: "MyCompanyName"). If not provided, the value from the "github-repo-owner" option is used
--composer-vendor=COMPOSER-VENDOR                Composer vendor to use in the repo. If not provided, it is generated from the "php-namespace-owner" option
--my-company-name=MY-COMPANY-NAME                Name of the person or company owning the extension. If not provided, the value for option `git-user-name` is used
--my-company-email=MY-COMPANY-EMAIL              Email of the person or company owning the extension. If not provided, the value for option `git-user-email` is used
--my-company-website=MY-COMPANY-WEBSITE          Website of the person or company owning the extension. If not provided, the GitHub repo for this project is used
```

</details>

### Commit, Push and Tag the Initial Project

Review the changes applied to the codebase on the step above. If any value is not correct (eg: if the PHP namespace should be a different one), you can undo all changes, and run `composer initialize-project` again providing the right values.

Once all values are right, run:

```bash
git add . && git commit -m "Initialized project" && git push origin && git tag 0.0.0 && git push --tags
```

This will commit the codebase to your GitHub repo, and tag it with version `0.0.0`. (This tag is needed to start incrementing the version automatically from now on.)

## Build the Lando webserver for DEV

This Lando webserver uses:

- The source code on the repo
- PHP 8.1
- XDebug enabled

To build the webserver, run:

```bash
composer build-server
```

After a few minutes, the website will be available under `https://gatographql-{composer-vendor}-extensions.lndo.site`.

The URL is printed on the console under "APPSERVER URLS" (you will need to scroll up):

![Lando webserver URL](assets/img/lando-webserver-url.png)

To print the URL again, run:

```bash
composer server-info
```

### `wp-admin` login credentials

- Username: `admin`
- Password: `admin`

### Using XDebug

XDebug is enabled but inactive.

To activate XDebug for a request, append parameter `?XDEBUG_TRIGGER=1` to the URL (for any page on the Gato GraphQL plugin, including any page in the wp-admin, the GraphiQL or Interactive Schema public clients, or other).

## Start the Lando webserver for DEV

Building the webserver (above) is needed only the first time.

From then on, run:

```bash
composer init-server
```

## Build the Lando webserver for PROD

This Lando webserver uses:

- The generated plugins for PROD
- PHP 7.2
- XDebug not enabled

To build the webserver, run:

```bash
composer build-server-prod
```

After a few minutes, the website will be available under `https://gatographql-{composer-vendor}-extensions-for-prod.lndo.site`.

(The URL is the same one as for DEV above, plus appending `-for-prod` to the domain name.)

To print the URL again, run:

```bash
composer server-info-prod
```

The `wp-admin` login credentials are the same ones as for DEV.

## Start the Lando webserver for PROD

Building the webserver (above) is needed only the first time.

From then on, run:

```bash
composer init-server-prod
```

## Updating the monorepo

After adding a plugin or package to the monorepo, the configuration must be updated.

Run:

```bash
composer update-monorepo-config
```

This command will:

- Update the root `composer.json` with the new packages
- Update the root `phpstan.neon` with the new packages

## Architecture of the Extension Starter

`GatoGraphQL/ExtensionStarter` is a [monorepo](https://css-tricks.com/from-a-single-repo-to-multi-repos-to-monorepo-to-multi-monorepo/#aa-stage-3-monorepo), containing the codebase for not only 1, but multiple extension plugins for Gato GraphQL (and also their packages).

`GatoGraphQL/ExtensionStarter` is also a [multi-monorepo](https://css-tricks.com/from-a-single-repo-to-multi-repos-to-monorepo-to-multi-monorepo/#aa-stage-4-multi-monorepo), containing the source code of the main Gato GraphQL plugin, hosted under the [`GatoGraphQL/GatoGraphQL`](https://github.com/GatoGraphQL/GatoGraphQL) monorepo, as a Git submodule.

The monorepo is managed via the [Monorepo Builder](https://github.com/symplify/monorepo-builder).

## Why a Multi-monorepo

The benefits of using the multi-monorepo approach as a starter project are several.

**Heads up!** All extensions from [gatographql.com/extensions](https://gatographql.com/extensions/) (that is 26 extensions and 4 bundles to date) are hosted on a repo created from `GatoGraphQL/ExtensionStarter`! So you get access to the same tools as the creators of these commercial extensions are themselves using.

### Automation

There are scripts and workflows to automate (as much as possible) the whole process of creating an extension plugin, from developing it, to testing it, to releasing it.

As a consequence, you only need to concentrate on the actual code for your extension; you won't need to worry about tools and boilerplate for the project (saving you no little time and effort).

### Browse the Gato GraphQL source code

The source code of the Gato GraphQL plugin is always readily-available when developing our extensions, and it is kept up to date just by fetching the Git changes from the upstream repo.

This is important as documentation (mostly when we first start developing with Gato GraphQL) and for debugging (XDebug is integrated out of the box, see below).

### Host the codebase for multiple extensions, and all their packages, all together

The repo contains the source code for not only 1, but multiple extension plugins for Gato GraphQL, and also for all their packages.

By hosting all extensions and their packages together, you avoid [dependency hell](https://en.wikipedia.org/wiki/Dependency_hell).

You are also able to do bulk modifications, such as searching and replacing a piece of code across different plugins, in a single action (and push it to the repo using a single commit).

### Continuously access newly-developed code

Once we create a new repository from a GitHub template, the repository and the template are two separate entities. From that moment on, when the template is updated, these changes are not reflected in the repository.

The Gato GraphQL monorepo deals with this issue by providing tools that copy content (code, scripts, workflows, etc) from the Gato GraphQL repo (available as a Git submodule) to the extension project repo. This enables the extension project to be updated when there are changes to the main plugin.

See section "Synchronizing the downstream extension project with the upstream Gato GraphQL repo" to learn more.

### Use the GitHub Actions workflows developed for the Gato GraphQL plugin

The GitHub Actions workflows developed for the Gato GraphQL plugin are readily-available to create and release our extensions.

This includes Continuous Integration workflows to:

- Generate the plugin (when merging a PR, or creating a release from a tag)
- Scope the extension plugin
- Downgrade the code from PHP 8.1 (for DEV), to PHP 7.2 (for PROD)
- Run coding standard checks (via PHPCS), unit tests (via PHPUnit) and static code analysis (via PHPStan)
- Run integration tests via InstaWP (automatically installing the newly-generated extension plugin on the InstaWP instance)

### Downgrade Code - PHP 8.1 (during DEV) is converted to PHP 7.2 (for PROD)

The source code for the main Gato GraphQL plugin, and any of its extensions, is PHP 8.1.

For distribution, though, the plugin and extensions use PHP 7.2, thanks to a "downgrade" process of its code via [Rector](https://github.com/rectorphp/rector/).

Downgrading code provides the best trade-off between availability of PHP features (during development), and the size of the potential userbase (when releasing the plugin):

- Use the strict typing features from PHP 8.1 (typed properties, union types, and others) to develop the plugin, reducing the possibility it will contain bugs
- Increase the potential number of users who can use your plugin, in production, by releasing it with PHP 7.2

**Heads up!** Not all PHP 8.1 features are available, but only those ones that are "downgradeable" via Rector. Check the list of [Supported PHP features in `GatoGraphQL/GatoGraphQL`](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/docs/supported-php-features.md).

### Scope 3rd-party libraries

When the extension uses 3rd-party libraries (loaded via Composer), these must be "scoped" by prepending a custom PHP namespace on their source code.

This is needed to prevent potential conflicts from other plugins installed in the same WordPress site referencing a different version of the same library.

[PHP-Scoper](https://github.com/humbug/php-scoper) is already integrated in this monorepo (ready to be used whenever needed).

### Lando webservers are ready

Lando is already set-up and configured, making 2 webservers available:

1. A webserver to develop the extensions, using PHP 8.1
2. A webserver to test the generated extension plugins, using PHP 7.2

### The source code is mapped to Lando's DEV webserver

The Lando webserver for DEV (on PHP 8.1) overrides the code deployed within the container, mapping the source code from the repo instead.

As such, changes to the source code will be immediately reflected in the webserver.

### XDebug is already configured

XDebug is already integrated (when using VSCode), supporting:

- The extension's source code
- The main Gato GraphQL plugin's source code (accessible via the Git submodule)
- The WordPress core files

This allows you to add breakpoints in the code, and analyze the full stack trace to see how the code and logic works, anywhere.

### Documentation images in the extension are served from the repo

When generating the extension plugin, images to be displayed in the documentation are excluded from the `.zip` file (thus reducing its size), and referenced directly from the GitHub repo (under `raw.githubusercontent.com`).

### Monorepo Split

Even though the code for all plugins and packages is hosted all together in a monorepo, we can optionally also deploy their code to a separate repo of their own.

This is useful for:

- Distributing them via Composer
- Exploring their source code outside of the monorepo

This is achieved via a "monorepo split": When pushing code to the monorepo, the code for each of the modified plugins and packages is copied into their own GitHub repo.

This feature is disabled by default. To enable it, return an empty array in method `getExtensionSkipMonorepoSplitPackagePaths` from class [`MonorepoSplitPackageDataSource`](src/Config/Symplify/MonorepoBuilder/DataSources/MonorepoSplitPackageDataSource.php).

### Distribute PROD code to its own repo

Similar to the monorepo split, when generating the plugin for PROD, we can deploy its code (scoped, downgraded, with the Composer autoload generated, etc) into its own repo.

This is useful for:

- Allowing users to create issues, pinpointing where a problem happens

## Multi-Monorepo Commands

The following commands are available, via `composer`:

## Synchronizing the downstream extension project with the upstream Gato GraphQL repo

@todo

For instance, the Lando webserver for DEV (see below) configures the container to use the source code from the main Gato GraphQL plugin (as to reflect changes on the source code immediately on the webserver), using the mapping from the "upstream" file [`.lando.upstream.yml`](submodules/GatoGraphQL/webservers/gatographql/.lando.upstream.yml).

When the Gato GraphQL plugin incorporates a new package, and a new mapping entry is added to that file, ...

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

To check the coding standards via [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), run:

``` bash
composer check-style
```

To automatically fix issues, run:

``` bash
composer fix-style
```

## Testing

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
```

## Previewing code downgrade

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Report issues

Use the [issue tracker](https://github.com/GatoGraphQL/ExtensionStarter/issues) to report a bug or request a new feature for all packages in the monorepo.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email name@mycompany.com instead of using the issue tracker.
