<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/npm.js"></script>
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
</head>
<body>
  <div class="row">
    <div class="col-lg-3 col-lg-offset-4">
      <iframe height="300px" width="500px" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vRnDf68Fo9XxPsdJNlT29ycal1tHWcZ2oxae89HGy9ol0_t3xjFNXcJM_4hFPr0FMUBq6X7BffkfEaS/pubhtml?gid=0&amp;single=true&amp;widget=true&amp;headers=false"></iframe>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-lg-offset-4">
      <div>
        <form action="home.php" method="post">
          <ul style="list-style: none;">
            <li>First Name <input type="text" name="first_name"></li>
            <li>Last Name <input type="text" name="last_name"></li>
            <li>Delete <input type="text" name="course_id"></li>
            <input type="hidden" name="update">
            <li><input onclick="index.php" type="submit"></li>
          </ul>
        </form>
        <div class="col-lg-3 col-lg-offset-4">
          <?php
            //checking if the four inputs are set
            if (isset($_POST['first_name'])   &&
                isset($_POST['last_name']))   
            {
              echo $_POST["first_name"]; 
              echo $_POST["last_name"];
            }
          ?> 
        </div>
      </div>
    </div>
  </div>
</body>
</html>