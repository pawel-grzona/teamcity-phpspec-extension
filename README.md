#phpspec extension for TeamCity

Makes TeamCity display phpspec execution results in real-time.

[![Build Status](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension.png)](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension)

This version is rebuilt to work with phpspec/phpspec
rather than phpspec/phpspec2 and as such is not backward compatible.

## Installation

In your composer.json:

```json
{
    "require-dev": {
        "pawel-grzona/teamcity-phpspec-extension": "2.*"
    }
}
```

## Usage

In your phpspec.yml:

```yml
extensions:
    - PhpSpec\TeamCity\Extension
```

## Requirements

PHP 5.3+
