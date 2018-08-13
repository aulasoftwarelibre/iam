# IAM
> Identity and Access Management

<p align="center">

[![Travis](https://img.shields.io/travis/aulasoftwarelibre/iam.svg?style=for-the-badge)](https://github.com/aulasoftwarelibre/iam) [![GitHub license](https://img.shields.io/github/license/aulasoftwarelibre/iam.svg?style=for-the-badge)](https://github.com/aulasoftwarelibre/gata)

</p>

This project is a (work in progress) **identity and access management** for the [Free Software Club](https://www.uco.es/aulasoftwarelibre) of the [University of Córdoba](https://www.uco.es/).

It is designed using Domain-Driven Design, Event Sourcing and CQRS.

## Installation

### Development

1. Copy .env.dist file in .env and update APP_SECRET and POSTGRES_PASSWORD.
1. Run `docker-compose up` or `docker-compose up -d`
1. Create the stream: `bin/console event-store:event-stream:create`

The docker-compose config file starts several required services:
    - Postgres server


### Testing

This project is not yet functional. Anyway, you can launch the tests:

    vendor/bin/phpspec run
    vendor/bin/behat
 
## Contributing

Any design suggestions are welcome. Feel free to open an issue to discuss anything you want to.
