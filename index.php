<?php

/**
 * Use native PHP sessions to store auth details
 */
session_start();

/**
 * Composer autload
 */
require 'vendor/autoload.php';

use GuzzleHttp\Message\Request;

/**
 * Create a new Slim PHP app
 */
$app = new \Slim\Slim();

/**
 * Routes
 */

/**
 * About page
 */
$app->get('/about', function()  use ($app)
{
  $app->render('about.php');
});

/**
 * Auth page where user is redirected after logging in with Strava
 */
$app->get('/auth', function()  use ($app)
{
    
    /**
     * Get the auth code form the URL
     */
    $authCode = $app->request()->params('code');
    
    /**
     * Exchange the auth code for an access token 
     */
    $client = new GuzzleHttp\Client();
    
    $response = $client->post(
      'https://www.strava.com/oauth/token',
      [
        'body' => [
          'client_id' => 'YOUR_CLIENT_ID',
          'client_secret' => 'YOUR_CLIENT_SECRET',
          'code' => $authCode,
        ]
      ]
    );
    
    /**
     * Decode the returned JSON object
     */
    $responseBody = json_decode($response->getBody(true));
    
    /**
     * Set the access token as a session variable
     */
    $_SESSION['accessToken'] = $responseBody->access_token;
    
    /**
     * Set the athlete id as a session variable
     */
    $_SESSION['athleteId'] = $responseBody->athlete->id;
    
    /**
     * Redirect to the home page
     */
    $app->redirect('/');
  
});

/**
 * Graph page
 */
$app->get('/graph-of-segment-efforts', function()  use ($app)
{
  
  /**
   * 
   */
  if(!empty($_SESSION['accessToken'])){
    
    /**
     * Get the segment id from the url query string
     */
    $segmentId = $app->request()->params('segmentId');
    
    /**
     * Check there is a segment id
     */
    if(!is_null($segmentId))
    {

      /**
       * Get the segment data
       */
      $client = new GuzzleHttp\Client();
      
      /**
       * Get the segment data
       */
      $segmentResponse = $client->get(
        'https://www.strava.com/api/v3/segments/' . $segmentId,
        [
          'headers' => ['Authorization' => 'Bearer ' . $_SESSION['accessToken']]
        ]
      );
  
      $segmentBody = json_decode($segmentResponse->getBody(true));
      
      $effortsResponse = $client->get(
        'https://www.strava.com/api/v3/segments/' . $segmentId . '/all_efforts?athlete_id=' . $_SESSION['athleteId'],
        [
          'headers' => [
            'Authorization' => 'Bearer ' . $_SESSION['accessToken']
          ],
        ]
      );
  
      $effortsResponseBody = json_decode($effortsResponse->getBody(true));
      
      $effortsArray = [];
      
      foreach($effortsResponseBody as $effort)
      {
        
        $effortTimestamp = strtotime($effort->start_date);
        
        $effortsArray[] = sprintf('[%s,%s]', $effortTimestamp, $effort->elapsed_time);
        
      }
      
      $timesArray = implode(',', $effortsArray);
  
      $app->render(
        'graph-of-segment-efforts.php',
        array(
          'timesArray' => $timesArray,
          'segmentName' => $segmentBody->name,
          'activityType' => $segmentBody->activity_type,
        )
      );
    
    /**
     * EOF check for segment id
     */
    } else {
      $app->render('choose-segment.php');
    }
    
  } else {
    $app->redirect('/');
  }
  
});

/**
 * Home page
 */
$app->get('/', function()  use ($app)
{
  /**
   * Show the different options for interacting with the API
   * if there is an access token in the session
   */
  if(!empty($_SESSION['accessToken'])){
    $app->render('home_access.php');
  } else {
    /**
     * Render the default home page
     */
    $app->render('home.php');
  }
});

/**
 * Run the Slim application
 */
$app->run();
