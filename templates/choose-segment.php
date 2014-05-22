<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Graph - My Experiments With The Strava API</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->
        
        <link rel="stylesheet" href="/strava/assets/css/bootstrap.min.css">
        <!--<link rel="stylesheet" href="css/normalize.css">-->
        <link rel="stylesheet" href="/strava/assets/css/main.css">
        <!--<script src="js/vendor/modernizr-2.8.0.min.js"></script>-->
    </head>
    <body>
      
      <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/strava">Strava API Experiments</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="/strava">Home</a></li>
              <li><a href="/strava/about">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>

    <div class="container">

      <div class="starter-template">
        
        <h1>My Experiments With The Strava API</h1>
        
        <form action="/strava/graph-of-segment-efforts" method="get">
          
          <label for="segmentId">Segment ID</label>
          <input type="text" name="segmentId" id="segmentId" />
          
          <input type="submit" name="submit" value="submit" />
          
        </form>
        
        <svg id="visualisation" width="1000" height="600"></svg>
        
      </div>

    </div><!-- /.container -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/strava/assets/js/bootstrap.min.js"></script>
        
    </body>
</html>
