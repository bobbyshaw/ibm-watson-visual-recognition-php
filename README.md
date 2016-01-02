# IBM Watson Visual Recognition API

This PHP library provides integration with the 
[IBM Watson Visual Recognition](http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/visual-recognition.html) 
service.

See [API documentation](https://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/visual-recognition/api/v2/).


## Commands

The library also comes with a set of commands to use on the command line

    php app/console classifiers:get [-a|--major-api-version="..."] [-d|--version-date="..."] username password


## Development


## Testing

Run phpunit tests with

    vendor/bin/phpunit
    
    
## Documentation

PHPDocumentor is being used for creating library documentation.  So make sure to add function comments 
    
    vendor/bin/
    
    
You may need to install graphViz on your machine if you don't already have it.  This can be done on mac with homebrew

    brew install graphviz