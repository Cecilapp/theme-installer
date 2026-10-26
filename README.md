# Cecil theme installer

The [Composer](https://getcomposer.org) installer for [Cecil](https://cecil.app)'s themes.

It installs packages of type `cecil-theme` in the `themes/<theme_name>` directory of a Cecil project, instead of `vendor/`.

> [!NOTE]
> This package replaces [`cecil/installer`](https://github.com/Cecilapp/composer-installer) (archived), which is no longer maintained.
> Packages of type `cecil-extension` or `cecil-plugin` must switch to the `cecil-theme` type (see [Plugins and extensions](#plugins-and-extensions)).

## Requirements

- PHP 8.1 or later
- Composer 2

## Usage

### Theme

Add the following lines to the `composer.json` file of your theme:

```json
{
  "name": "vendor/theme-name",
  "type": "cecil-theme",
  "require": {
    "cecil/theme-installer": "^2.1"
  },
  "extra": {
    "cecil": {
      "theme-name": "<theme_name>"
    }
  }
}
```

The theme name is resolved in the following order:

1. `extra.cecil.theme-name`
2. `extra.name` (legacy, still supported)
3. the package name, without the `theme-` prefix or the `-theme` suffix (ie: `vendor/theme-name` → `name`)

The theme name may only contain letters, digits, `.`, `_` and `-`, and must start with a letter or a digit.

### Cecil project

Since Composer 2.2 plugins must be explicitly allowed, in the `composer.json` file of your Cecil project:

```json
{
  "config": {
    "allow-plugins": {
      "cecil/theme-installer": true
    }
  }
}
```

Then require the theme:

```bash
composer require vendor/theme-name
```

The theme is installed in `themes/<theme_name>`, and can be enabled in the Cecil configuration:

```yaml
theme:
  - <theme_name>
```

### Plugins and extensions

A `cecil-theme` package is not limited to templates and assets: it can also ship PHP [extensions](https://cecil.app/documentation/developers/extend/) (pages generators, Twig extensions, output post-processors, build events subscribers), registered by a [plugin](https://cecil.app/documentation/developers/extend/#plugins) declared in the theme `config.yml` (requires Cecil 9.25 or later):

```yaml
extensions:
  plugins:
    MyPlugin: MyPlugin\Plugin
```

Classes live in the `extensions/` directory of the theme, following the namespace (ie: `extensions/MyPlugin/Plugin.php`). Enabling the theme enables the plugin.

This is the way to distribute a Cecil plugin with Composer: see the [plugin example](https://github.com/Cecilapp/plugin-example).

## Development

```bash
composer install
composer test
```

## License

_Cecil theme installer_ is a free software distributed under the terms of the MIT license.

© Arnaud Ligny
