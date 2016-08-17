# PHPoole-installer

[Composer](https://getcomposer.org) installer for [PHPoole-library](https://github.com/PHPoole/PHPoole-library) themes and plugins.

Package types supported:
* ```phpoole-theme```
* ```phpoole-plugin```
 
## Usage

Add the following to your theme or plugin ```composer.json```:

```json
"type": "phpoole-theme_OR_phpoole-plugin",
```

```json
"require": {
    "narno/phpoole-installer": "^1.1"
}
```

```json
"extra": {
    "name": "theme_or_plugin_name",
}
```
