# WPTT Development

The plan is to bundle up a bunch of development functions in to a singel plugin so we don't have them duplicated all over the place.

### Warning

This being built on the fly during client work. It needs a clean up (and proper UI for logs) when I have a chance.

Very much a rough go at something I want.

### Future Plans

- make it easy to define which plugins should be activated in each environment
- make it easy to allow options set based on plugins activated in different environments

### Setup

Define your environments in wp-config.php.

##### Environments

define( 'WPTT_LOCAL', 'http://local.domain.com' );
define( 'WPTT_DEV', 'http://dev.domain.com' );
define( 'WPTT_LIVE', 'http://domain.com' );
