# Gato GraphQL - Extension Starter

GitHub template repository to quickstart your extension for Gato GraphQL.

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

- PHP 8.1+ for development
- PHP 7.2+ for production

## About this Extension Starter

`GatoGraphQL/ExtensionStarter` is a GitHub template repository, from which to easily kickstart a GitHub repository to develop and release extensions for [Gato GraphQL](https://gatographql.com).

`GatoGraphQL/ExtensionStarter` is a monorepo, containing the codebase for not only 1, but multiple extension plugins for Gato GraphQL (and also their packages).

`GatoGraphQL/ExtensionStarter` is also a multi-monorepo, containing the source code of the main Gato GraphQL plugin, hosted under the [`GatoGraphQL/GatoGraphQL`](https://github.com/GatoGraphQL/GatoGraphQL) monorepo, as a Git submodule.

- It contains not only 1, but multiple extension plugins for Gato GraphQL (and also their packages)

- It contains not only 1, but multiple extension plugins for Gato GraphQL (and also their packages)

The created repository will contain all the tools 

`GatoGraphQL/ExtensionStarter` is a 

[Gato GraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

## Create your Gato GraphQL extension project

Follow these steps:

### 1. Create a new repository from this template

Create your own repository from the `GatoGraphQL/ExtensionStarter` template:

- Click on "Use this template => Create a new repository"
- Select the GitHub owner, and choose a proper name for your repository (eg: `youraccount/GatoGraphQLExtensionsForMyCompany`)
- Choose if to make it Public or Private
- Click on "Create repository"

### 2. Clone the project locally

Once you have created your repository `youraccount/GatoGraphQLExtensionsForMyCompany`, clone it in your local drive using the `--recursive` option (to also clone Git submodule `GatoGraphQL/GatoGraphQL`):

```bash
git clone --recursive https://github.com/youraccount/GatoGraphQLExtensionsForMyCompany
```

### 

And then install all the dependencies, via Composer

```bash
$ cd {project folder}
$ composer install
$ cd submodules/GatoGraphQL
$ composer install
```

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
