# PHPoole-installer

Composer installer for [PHPoole-library](https://github.com/Narno/PHPoole-library) themes and plugins.

Package type supported:
* ```phpoole-theme```
* ```phpoole-plugin```
 
## Usage

Add the following to your theme or plugin ```composer.json```:

```json
"type": "phpoole-theme_or_phpoole-plugin",
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
