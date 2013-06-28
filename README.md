#phpspec extension for TeamCity

Makes TeamCity report PHP specs execution in real-time.

[![Build Status](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension.png)](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension)

## Installation

In your composer.json:

```json
{
    "require": {
        "pawel-grzona/teamcity-phpspec-extension": "*"
    }
}
```

## Usage

In your phpspec.yml:

```yml
extensions:
  - TeamCityPhpspecExtension
```

## Requirements

PHP 5.3+