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
        
        <p><strong><?php echo $segmentName; ?></strong> - <?php echo $activityType; ?></p>
        
        <form action="/strava/graph-of-segment-efforts" method="get">
          
          <label for="segmentId">Segment ID</label>
          <input type="text" name="segmentId" id="segmentId" />
          
          <input type="submit" name="submit" value="submit" />
          
        </form>
        
        <div>
          <div class="row">
            <div class="col-md-1 key averageEffort"></div>
            <div class="col-md-11"><p>Average time</p></div>
          </div>
          <div class="row">
            <div class="col-md-1 key efforts"></div>
            <div class="col-md-11"><p>Efforts</p></div>
          </div>
        </div>
        
        <svg id="visualisation" width="1000" height="600"></svg>
        
      </div>

    </div><!-- /.container -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/strava/assets/js/bootstrap.min.js"></script>
        
        <script src="/strava/assets/js/d3.min.js"></script>
        
    
        <script>
        
            /**
             * Function for sorting times
             */
            function sortArrayByTimestamp(a, b){ return (a[0]-b[0]); }
            
            /**
             * Data array in form [timestamp, elapsed time in seconds]
             */
            var data = [
              <?php echo $timesArray; ?>
            ];
            
            /**
             * Sort the data by the timestamps
             */
            data.sort(sortArrayByTimestamp);
              
            /**
             * Get the min and max values for the x and y axis
             */
            var xMin = d3.min(data, function(d){
              return d[0];
            });
            
            var xMax = d3.max(data, function(d){
              return d[0];
            });
            
            var yMin = d3.min(data, function(d){
              return d[1];
            });
            
            var yMax = d3.max(data, function(d){
              return d[1];
            });
            
            /**
             * Average time in seconds
             */
            var averageTime = d3.mean(data, function(d){
              return d[1];
            });
            
            /**
             * Create date objects for the x time, full date, and y time, minutes and seconds
             */
            var minDate = new Date,
                maxDate = new Date,
                minTime = new Date,
                maxTime = new Date;
    
            /**
             * Set the values for the date objects
             */
            minDate.setTime(xMin * 1000);
            maxDate.setTime(xMax * 1000);
            minTime.setTime(yMin * 1000);
            maxTime.setTime(yMax * 1000);
              
              /**
               * Variables for a reference to the svg
               * and width, height and margins
               * 
               */
              var vis = d3.select('#visualisation'),
                        WIDTH = 1000,
                        HEIGHT = 500,
                        MARGINS = {
                          top: 20,
                          right: 20,
                          bottom: 20,
                          left: 50
                        };
              /**
               * Create the x range with the margins for the range
               * And the min and max x values from the data
               */
              var xRange = d3.time.scale()
                            .range([MARGINS.left, WIDTH - MARGINS.right])
                            .domain([minDate, maxDate]);
        
              /**
               * Create the x range with the margins for the range
               * And the min and max x values from the data
               */
              var yRange = d3.time.scale()
                            .range([HEIGHT - MARGINS.top, MARGINS.bottom])
                            .domain([minTime, maxTime]);
                          
              /**
               * 
               */
              var xAxis = d3.svg.axis()
                            .scale(xRange)
                            .tickSize(5)
                            .tickSubdivide(true)
                            .tickFormat(d3.time.format("%a %x"));
                            
              /**
               * 
               */
              var yAxis = d3.svg.axis()
                            .scale(yRange)
                            .tickSize(5)
                            .orient('left')
                            .tickSubdivide(true)
                            .tickFormat(d3.time.format("%M:%S"));
                            
              /**
               * 
               */
              vis.append('svg:g')
                .attr('class', 'x axis')
                .attr('transform', 'translate(0,' + (HEIGHT - MARGINS.bottom) + ')')
                .attr("class", "xaxis")
                .call(xAxis);
                
              /**
               * 
               */
              vis.append('svg:g')
                .attr('class', 'y axis')
                .attr('transform', 'translate(' + (MARGINS.left) + ',0)')
                .call(yAxis);
                
              /**
               * 
               */
              var averageTimeArray = [averageTime];
              
              var xRangeAverage = d3.scale.ordinal()
                                    .rangeRoundBands([MARGINS.left, WIDTH - MARGINS.right], 0.1)
                                    //.domain(averageTimeArray.map(function(d) {
                                      //return d.x;
                                    .domain(averageTimeArray.map(function(d) {
                                      return d;
                                    }));
              
              var yRangeAverage = d3.scale.linear()
                                    .range([HEIGHT - MARGINS.top, MARGINS.bottom])
                                    .domain([0, d3.max(averageTimeArray, function(d) {
                                      return d;
                                    })]);
                                    
              /**
               * 
               */
              vis.selectAll('rect')
                .data(averageTimeArray)
                .enter()
                .append('rect')
                .attr('x', function(d) { // sets the x position of the bar
                  return 50;
                  //return xRange(d.x);
                })
                .attr('y', function(d) { // sets the y position of the bar
                  return 480 - d;
                  //return yRange(d.y);
                })
                //.attr('width', xRange.rangeBand()) // sets the width of bar
                .attr('width', 930) // sets the width of bar
                .attr('height', function(d) {      // sets the height of bar
                  return d;
                  return 20;
                  //return ((HEIGHT - MARGINS.bottom) - yRange(d.y));
                })
                .attr('fill', 'grey');   // fills the bar with grey color
                
              /**
               * Create a line generator function
               * This returns the x and y co-ordinates which are date objects
               * The interpolate tells the graph the type of line to draw, e.g. straight, curved
               */  
              var lineFunc = d3.svg.line()
                              .x(function(d) {
                                var tempDate = new Date;
                                tempDate.setTime(d[0] * 1000);
                                return xRange(tempDate);
                              })
                              .y(function(d) {
                                var tempTime = new Date;
                                tempTime.setTime(d[1] * 1000);
                                return yRange(tempTime);
                              })
                              .interpolate('linear');
                                
                /**
                 * 
                 */
                vis.append('svg:path')
                  .attr('d', lineFunc(data))
                  .attr('stroke', 'blue')
                  .attr('stroke-width', 2)
                  .attr('fill', 'none');
                  
              vis.selectAll(".xaxis text")  // select all the text elements for the xaxis
                .attr("transform", function(d) {
                  return "translate(" + this.getBBox().height*-2 + "," + (this.getBBox().height + 20) + ")rotate(-45)";
                });
    
        </script>
        
    </body>
</html>
