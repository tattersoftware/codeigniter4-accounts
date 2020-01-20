# Tatter\Accounts
Multi-service account management for CodeIgniter 4

## Quick Start

1. Install with Composer: `> composer require tatter/accounts`
2. Create the config file: **app/Config/Accounts.php**
3. Use the service to access each handler: `$customer = service('accounts')->stripe->find($id);`

## Description

Modern web apps connect to umpteen different platforms, and managing users across all the
services can be a hassle. **Accounts** provides a standard for centralized user management.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
* `> composer require tatter/accounts`

Or, install manually by downloading the source files and adding the directory to
`app/Config/Autoload.php`.

## Configuration

Create a config file in your application directory: **app/Config/Accounts.php**. You may
copy the example file or start from scratch. Make sure to enable the handlers you want to
use.

## Usage

Load the service:

	$accounts = service('accounts');

Then access each handler by its name:

	$user = $accounts->myth->find(3);

**Accounts** returns a standardized set of fields, regardless of the endpoint's format. The
original entity is always available via the `getSource()` method:

	$permissions = $user->getSource()->getPermissions();

## Extending

Use the provided abstract classes and interfaces to write your own handlers, or check back
for new implementations as this library grows.
