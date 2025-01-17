# Laravel Project - Package Overview

This document provides an overview of the third-party **PHP** and **JavaScript** packages used in this Laravel project. It includes details about the package names, their purposes, installation instructions, and configuration steps.

## Table of Contents
- [Installed Packages](#installed-packages)
  - [Required PHP Packages](#required-php-packages)
  - [Development PHP Packages](#development-php-packages)
  - [Required JavaScript Packages](#required-javascript-packages)
  - [Development JavaScript Packages](#development-javascript-packages)
- [Package Descriptions](#package-descriptions)

## Installed Packages

The following third-party packages are installed in this Laravel project:

### Required PHP Packages
- `spatie/laravel-activitylog`
- `spatie/laravel-permission`
- `beyondcode/laravel-websockets`
- `pusher/pusher-php-server`
- `laravel-notification-channels/webpush`

### Development PHP Packages
- `barryvdh/laravel-debugbar`

### Required JavaScript Packages
- `jquery`
- `@popperjs/core`
- `bootstrap`
- `@fortawesome/fontawesome-free`

### Development JavaScript Packages
- `sass`

## Package Descriptions

### Required PHP Packages

#### `spatie/laravel-activitylog`
- **Purpose**: The spatie/laravel-activitylog package provides easy to use functions to log the activities of the users of your app. It can also automatically log model events. All activity will be stored in the activity_log table.
- **Version**: `^4.9`
- **Installation**:
  ```bash
  composer require spatie/laravel-activitylog:^4.9
  ```

#### `spatie/laravel-permission`
- **Purpose**: This package allows you to manage user permissions and roles in a database.
- **Version**: `^6.10`
- **Installation**:
  ```bash
  composer require spatie/laravel-permission:^6.10
  ```

#### `beyondcode/laravel-websockets`
- **Purpose**: Laravel WebSockets is a package for Laravel 5.7 and up that will get your application started with WebSockets in no-time! It has a drop-in Pusher API replacement, has a debug dashboard, realtime statistics and even allows you to create custom WebSocket controllers.
- **Version**: `^1.14`
- **Installation**:
  ```bash
  composer require beyondcode/laravel-websockets:^1.14 -W
  ```

#### `pusher/pusher-php-server`
- **Purpose**: PHP library for interacting with the Pusher Channels HTTP API.
- **Version**: `^7.2`
- **Installation**:
  ```bash
  composer require pusher/pusher-php-server:^7.2
  ```

#### `laravel-notification-channels/webpush`
- **Purpose**: This package makes it easy to send web push notifications with Laravel.
- **Version**: `^9.0`
- **Installation**:
  ```bash
  composer require laravel-notification-channels/webpush:^9.0
  ```

### Development PHP Packages

#### `barryvdh/laravel-debugbar`
- **Purpose**: This is a package to integrate PHP Debug Bar with Laravel. It includes a ServiceProvider to register the debugbar and attach it to the output.
- **Version**: `^3.14`
- **Installation**:
  ```bash
  composer require barryvdh/laravel-debugbar --dev
  ```

### Required JavaScript Packages

#### `jquery`
- **Purpose**: jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers.
- **Version**: `^3.7.1`
- **Installation**:
  ```bash
  npm install jquery@^3.7.1
  ```

#### `@popperjs/core`
- **Purpose**: Tooltip & Popover Positioning Engine.
- **Version**: `^2.11.8`
- **Installation**:
  ```bash
  npm install @popperjs/core@^2.11.8
  ```

#### `bootstrap`
- **Purpose**: Bootstrap is a powerful, feature-packed frontend toolkit.
- **Version**: `^5.3.3`
- **Installation**:
  ```bash
  npm install bootstrap@^5.3.3
  ```

#### `@fortawesome/fontawesome-free`
- **Purpose**: Font Awesome is the Internet's icon library and toolkit.
- **Version**: `^6.7.2`
- **Installation**:
  ```bash
  npm install @fortawesome/fontawesome-free@^6.7.2
  ```

### Development JavaScript Packages

#### `sass`
- **Purpose**: A pure JavaScript implementation of Sass. Sass makes CSS fun again.
- **Version**: `^1.83.4`
- **Installation**:
  ```bash
  npm install sass@^1.83.4 --save-dev
  ```
