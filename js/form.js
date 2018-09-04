var app = angular.module('form', ['angularFileUpload', 'ngRoute', 'cgBusy']);

app.controller('formcontroller', ['$scope', '$location', '$timeout', '$upload', '$http',
  function($scope, $location, $timeout, $upload, $http) {

    $scope.verstuurd = false;
    $scope.naam = "";
    $scope.elink = "";
    $scope.email = "";
    $scope.catstring = "";
    $scope.eventnaam = "Evenementnaam";
    $scope.dirlink = "";
    $scope.currentevent = "0";
    $scope.ondercatch = "";
    $scope.uploadResult = [];
    //$scope.onderwerpen = {};
    $scope.gekozen = [];
    $scope.gekozen2 = [];
    //$scope.uploadResult = [];
    $scope.events = {};
    //getting the url parameter, stored in yy
    $scope.keyyy = $location.url();
    $scope.keyy = $scope.keyyy.replace('/', '');

    $http.get('./ajax/api.php').
    success(function(data) {
      // here the data from the api is assigned to a variable named events
      $scope.events = data;
      // Catch all the things!
      for (var b = 0; b < $scope.events.length; b++) {
        if ($scope.events[b].sleutel === $scope.keyy) {
          //console.log($scope.events[b].sleutel);
          $scope.currentevent = b;
          $scope.ondercatch = $scope.events[b].cats;
          $scope.elink = $scope.events[b].id;
          $scope.eventnaam = $scope.events[b].eventname;
          $scope.onderwerpen = $scope.ondercatch.split(',');
        }
      }
    });


    $scope.registeruser = function($files) {
      console.log("jasdasd");
      if ($scope.verstuurd === false) {
        $scope.verstuurd = true;
        for (i = 0; i < $scope.gekozen.length; i++) {
          if ($scope.gekozen[i]) {
            $scope.gekozen2.push($scope.gekozen[i]);
          }
        }
        $scope.catstring = $scope.gekozen2.join();
        $scope.doFileNow();
      }
    };

    $scope.onFileSelect = function($files) {
      console.log("checkcheckcheck");
      $scope.$files = $files;
    };

    $scope.reset = function() {
      console.log('resettingForm...');
      $scope.$files = null;
      $scope.naam = "";
      $scope.email = "";
      $scope.catstring = "";
      $scope.dirlink = "";
      $scope.ondercatch = "";
      $scope.uploadResult = [];
      //$scope.onderwerpen = {};
      $scope.gekozen = [];
      $scope.gekozen2 = [];
      $scope.problemo = "";

      $scope.vervolg = false;
      $scope.succesvol = true;
    };
    // TODO TODO TODO this function isn't called yet! But is => onFileSelect($files);
    // TODO better yet.... make this the first function, so if image uploads fails-> world ends
    $scope.doFileNow = function() {
      console.log("upload user");
      if ($scope.$files !== "undefined" && $scope.naam !== "" && $scope.email !== "") {
        //console.log($files.length);
        //$files: an array of files selected, each file has name, size, and type.
        for (var i = 0; i < $scope.$files.length; i++) {
          var $file = $scope.$files[i];
          $scope.myPromise = $upload.upload({
            url: './ajax/upload.php',
            file: $file,
            progress: function(e) {}
          }).then(function(response) {
            // file is uploaded successfully
            $timeout(function() {
              $scope.uploadResult.push(response.data);
              console.log($scope.uploadResult);
              $scope.dirlink = $scope.uploadResult.toString();
              $scope.dirlink = $scope.dirlink.substr(0, $scope.dirlink.length - 1);
              $scope.dirlink = $scope.dirlink.substr(14);

              // Final call, push everything to the db;
              //And here the final Call
              var request = $http({
                method: "post",
                url: "./ajax/cr_register.php",
                data: {
                  naam: $scope.naam,
                  email: $scope.email,
                  imglink: $scope.dirlink,
                  elink: $scope.elink,
                  catstring: $scope.catstring,
                  vervolg: $scope.vervolg
                },
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                }
              });
              /* Check whether the HTTP Request is Successfull or not. */
              request.success(function(data) {
                $scope.verstuurd = false;
                $scope.reset();

              });

            });

          });
        }
      } else {
        console.log("Heeey mislukt!");
        $scope.verstuurd = false;
        $scope.succesvol = false;
        $scope.problemo = "Niet gelukt - Controleer of alle gegevens ingevuld zijn. Ook is een foto vereist.";
      }
    };
  }
]);
