<?php

namespace Bobbyshaw\WatsonVisualRecognition;

interface ClientInterface
{

    /**
     * Define client parameters, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     */
    public function getDefaultParameters();

    /**
     * Initialize client with parameters
     *
     * @param array $parameters
     */
    public function initialize(array $parameters = array());

    /**
     * Get all client parameters
     *
     * @return array
     */
    public function getParameters();
}
