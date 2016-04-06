# IBM Watson Visual Recognition API

This PHP library provides integration with the 
[IBM Watson Visual Recognition](http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/visual-recognition.html) 
service.

See [API documentation](https://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/visual-recognition/api/v2/).

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/bobbyshaw/watson-visual-recognition-php/build-status/develop)


## Usage

The structure of this library was inspired by the [Omnipay](https://github.com/thephpleague/omnipay) suite of packages.

One of the things that this means is the library doesn't hide away that fact that API requests are being made.  For each API request, the request should be created with parameters passed in, sent and then response reviewed.

All requests are available via the Client.

	use Bobbyshaw\WatsonVisualRecognition\Client;
	use Bobbyshaw\WatsonVisualRecognition\Classifier;
	
    $client = new Client();
    
The client should then be initialized with parameters, e.g. username and password (your IBM Watson Service credentials).

    $client->initialize(['username' => 'abcdef', 'password' => '12356])

The following can be used find default parameters

	$client->getDefaultParameters();
	
Each method matches an API request and returns a request object ready to be sent.  Most of the time you'll want to send the request straight away.

	$request = $client->getClassifiers();
	$response = $request->send();

Each response has a class which helps to manage the response, e.g with the Classifier and Image classes. 

	/** @var Classifier[] $classifiers */
	$classifiers = $response->getClassifiers();


## Commands

The library also comes with a set of commands to use on the command line

### Get Classifiers

    php app/console classifiers:get [-d|--version-date="..."] username password
    
### Classify Image(s)

    php app/console classifiers:classify [-c|--classifiers="..."] [-d|--version-date="..."] username password images
    
### Get Classifier Info
    
    php app/console classifier:get [-d|--version-date="..."] username password classifier_id
    
### Create/Train Classifier
    
    php app/console classifier:create [-d|--version-date="..."] username password positive_examples negative_examples name
    
    
### Delete Classifier 
    
    php app/console classifier:delete [-d|--version-date="..."] username password classifier_id


## Testing

Run phpunit tests with:

    vendor/bin/phpunit
    
This is also using grumphp to automatically check for PSR style formatting as well. 
    
Test images are provided by [Pixabay](https://pixabay.com/).
    
    
## Documentation

PHPDocumentor is being used for creating library documentation.  So make sure to add function comments 
    
    vendor/bin/
    
    
You may need to install graphViz on your machine if you don't already have it.  This can be done on mac with homebrew

    brew install graphviz
