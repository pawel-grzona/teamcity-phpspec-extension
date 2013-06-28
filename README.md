#phpspec extension for TeamCity

Makes TeamCity report PHP specs execution in real-time.

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