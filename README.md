#PhpSpec extension for TeamCity

Formats PhpSpec output to make TeamCity display spec execution results
in real-time.

[![Build Status](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension.png)](https://travis-ci.org/pawel-grzona/teamcity-phpspec-extension)

The 2.* version has been rebuilt from scratch to work with phpspec/phpspec
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

## Configuration

In your phpspec.yml:

```yml
extensions:
    - PhpSpec\TeamCity\Extension
```

## Usage

```bash
./phpspec -f teamcity
```

## Requirements

PHP 5.3+
