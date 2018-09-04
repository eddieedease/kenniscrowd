<!DOCTYPE html>
<html ng-app="form" ng-cloak>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="vendor/angular/angular.js"></script>
  <link href="vendor/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="lib/css/angular-busy.min.css">


</head>

<body ng-controller="formcontroller">
  <div class="cover">
    <div class="cover-image"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="well well-lg">

            <h2 class="text-center">Invulformulier
              <br>
              <img src="img/logo.png" class="img-rounded" alt="Crowdlogo">
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <hr>
        </div>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="well">
            <h2>{{eventnaam}}</h2>
            <p class="text-left text-muted">Hier kunt u de selectievakjes aanvinken die bij u van toepassing zijn.
            </p>
          </div>
        </div>
        <div class="col-md-8">
          <div class="well">
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <div class="col-sm-2">
                  <label class="control-label">Naam</label>
                </div>
                <div class="col-sm-10">
                  <input type="text" ng-model="naam" placeholder=". . ." class="form-control">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-2">
                  <label for="inputEmail3" class="control-label">Email</label>
                </div>
                <div class="col-sm-10">
                  <input type="email" ng-model="email" class="form-control" id="inputEmail3" placeholder=". . .">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox" ng-repeat="onderwerp in onderwerpen">
                    <label>
                      <input type="checkbox" ng-model='gekozen[$index]' ng-true-value="'{{onderwerp}}'">{{onderwerp}}</label>
                  </div>
                </div>
              </div>
              <div class="form-group">

                <div class="col-sm-offset-2 col-sm-10">
                  <h3>Upload hier uw/een foto</h3>
                  <h5>Verplicht *, nodig voor visualisatie</h5>
                  <label>

                    <input type="file" ng-capture="'camera'|'other'" ng-file-select="onFileSelect($files)" accept="image/*;capture=camera" capture="camera" id="i_file" name="file" />
                    <div ng-repeat="files in uploadResult">
                      <!--in case of error show error message with file name-->
                      <span class='label label-info'>"Succesvol geupload - "{{files.name}}</span>
                      <br/>
                    </div>
                  </label>

                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" ng-model="vervolg">Als u dit aanvinkt, houden wij u op de hoogte n.a.v. deze bijeenkomst.</label>
                  </div>
                </div>
              </div>
              <hr>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button class="btn btn-default" ng-model='files[0]' ng-click="registeruser()">Verstuur!</button>
                </div>
              </div>
              <div cg-busy="myPromise"></div>
              <div class="alert alert-success" ng-show="succesvol" role="alert">Gebruiker succesvol geregistreerd! Er kan hier nu een nieuwe gebruiker worden geregisteerd!</h4>
              </div>

            </form>
            <br>



          </div>
          <br>
          <br>
          <a href="http://www.kenniscrowd.nl/kenniscrowd?c={{keyy}}" target="_blanc">Klik hier om de bijbehorende crowd te openen.</a>
        </div>
        <br>
        <br>
        <br>


      </div>
    </div>
  </div>
  <hr>

  <script type="text/javascript" src="vendor/angular/angular-route.js"></script>
  <script type="text/javascript" src="vendor/angular/angular-file-upload.js"></script>

  <script type="text/javascript" src="vendor/jquery/jquery.js"></script>
  <script type="text/javascript" src="vendor/bootstrap/bootstrap.js"></script>
  <script type="text/javascript" src="js/form.js"></script>
  <script type="text/javascript" src="js/angular-busy.min.js"></script>
  <script type="text/javascript" src="vendor/angular/ui-bootstrap-0.13.0.min.js"></script>
</body>

</html>
