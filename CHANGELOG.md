# Release Notes

## v3.1.0 (2020-07-04)

### Added 

- Added `port` option to the database constructor.

### Fixed 

- Fixed bug related to fast queries conditions.

### Changed

- Removed default database encoding.
## v3.0.2 (2020-05-13)

### Fixed

- Fix bug related to general routing.

## v3.0.1 (2020-05-08)

### Fixed

- Add multibyte string support for most methods of the `Wolff\Utils\Str` class. 

## v3.0.0 (2020-05-01)

### Added

- Add support for routes that call a controller's method directly.

- Add view inheritance support to the template engine.

- Add `setFile`, `isEnabled` and `setStatus` method to the `Core\Maintenance` class.

- Add `view` method to the `Core\Route` class.

- Allow dot notation in the `select` method of the `Core\DB` class.

- Add support for csrf protection.

- Add support for environment files.

- Add `setDateFormat` and `setFolder` methods to the `Core\Log` class.

- Add `removeQuotes` method to the `Utilities\Str` class.

- Add `arrayRemove`, `bytesToString`, `path` and `validateCsrf` functions to the standard library.

### Changed

- Rename the `Core` namespace to `Wolff\Core`.

- Rename the `Utilities` namespace to `Wolff\Utils`.

- Improve internal routing.

- Improve configuration system.

- Improve exception handling.

- Make the standard library optional.

- Update the `for` tag in the template engine.

- Rename the `@load` tag to `@include` in the template engine.

- Remake the `Core\Request` and `Core\Response` classes.

- Remake the `Core\Middleware` class.

- Remake the `Core\Maintenance` class.

- Rename `selectAll`, `countAll` and `deleteAll` methods to `select`, `count` and `delete` in the `Core\DB` class.

- Rename `toJson` method to `getJson` in the `Core\Query` class.

- Rename `run` method to `query` in the `Core\DB` class.

- Rename `methodExists` and `call` methods to `hasMethod` and `get` in the `Core\Controller` class.

- Rename `getContent` method to `get` in the `Core\Cache` class.

- Treat the `Core\DB` class functionality as non-static.

- Rename the `Core\Request` and `Core\Response` classes to `Core\Http\Request` and `Core\Http\Response`.

- Rename `add` method to `any` in the `Core\Route` class.

- Replace the `system/definitions/Middlewares.php`, `system/definitions/Routes.php` and `system/definitions/Templates.php` files with `system/web.php`.

### Fixed

- Fix bug related to the `title` function of the template engine. 

### Removed

- Remove `Core\Cookie` class.

- Remove `Utilities\Upload` class.

- Remove conditional tags of the template engine.

- Remove `getTableSchema` method of the `Core\DB` class.

- Remove `getStartTime` and `getLiveTime` methods of the `Core\Session` class.

- Remove `getPath` method of the `Core\Controller` class.

- Remove `createFile` and `isClientAllowed` methods of the `Core\Maintenance` class.

- Remove `expired`, `getFilename` and `getPath` methods of the `Core\Cache` class.

- Remove `setType`, `getData` method of the `Utilities\Validation` class.

- Remove `unshift`, `pathToNamespace` and `namespaceToPath` methods of the `Utilities\Str` class.

- Remove `mkdir` and `folderExists` methods of the `Core\Middleware` class.

- Remove `inCli`, `arrayToCsv`, `getUserAgent`, `getServerRoot`, `deleteFilesInDir`, `dumpAll`, `getServer`, `getDB`, `getDBMS`, `getDbUser`, `getDbPass`, `getLanguage`, `getDir`, `getProjectDir`, `getSystemDir`, `getAppDir`, `getCacheDir` and `getPageTitle`, `getMainPage` functions of the standard library.
