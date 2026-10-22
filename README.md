# Cecil theme installer

The [Composer](https://getcomposer.org) installer for [Cecil](https://cecil.app)'s themes.

It installs packages of type `cecil-theme` in the `themes/<theme_name>` directory of a Cecil project, instead of `vendor/`.

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

## Development

```bash
composer install
composer test
```

## License

_Cecil theme installer_ is a free software distributed under the terms of the MIT license.

© Arnaud Ligny
