#phpspec extension for TeamCity

Makes TeamCity display phpspec execution results in real-time.

[![Build Status](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension.png)](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension)

## Installation

In your composer.json:

```json
{
    "require-dev": {
        "pawel-grzona/teamcity-phpspec-extension": "1.*"
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
