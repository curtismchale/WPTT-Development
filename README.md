# WPTT Development

The plan is to bundle up a bunch of development functions in to a singel plugin so we don't have them duplicated all over the place.

### Warning

Currently we have activation errors because of the order of the pluggable wp_mail function. Comment out the call to the file and it will work fine.

This is also current being built on the fly during client work. It needs a clean up (and proper UI for logs) when I have a chance.

Very much a rough go at something I want.

### Future Plans

- log all emails locally and in dev
- define local/dev/live environments so we can do different things depending
- make it easy to define which plugins should be activated in each environment
- make it easy to allow options set based on plugins activated in different environments
