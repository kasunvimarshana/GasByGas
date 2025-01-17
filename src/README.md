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

### Development JavaScript Packages

## Package Descriptions

### Required PHP Packages

#### `spatie/laravel-activitylog`
- **Purpose**: The spatie/laravel-activitylog package provides easy to use functions to log the activities of the users of your app. It can also automatically log model events. All activity will be stored in the activity_log table.
- **Version**: `^4.9`
- **Installation**:
  ```bash
  composer require spatie/laravel-activitylog
  ```

#### `spatie/laravel-permission`
- **Purpose**: This package allows you to manage user permissions and roles in a database.
- **Version**: `^6.10`
- **Installation**:
  ```bash
  composer require spatie/laravel-permission
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
  composer require pusher/pusher-php-server
  ```

#### `laravel-notification-channels/webpush`
- **Purpose**: This package makes it easy to send web push notifications with Laravel.
- **Version**: `^9.0`
- **Installation**:
  ```bash
  composer require laravel-notification-channels/webpush
  ```

### Development PHP Packages

#### `barryvdh/laravel-debugbar`
- **Purpose**: This is a package to integrate PHP Debug Bar with Laravel. It includes a ServiceProvider to register the debugbar and attach it to the output.
- **Version**: `^3.14`
- **Installation**:
  ```bash
  composer require barryvdh/laravel-debugbar --dev
  ```
