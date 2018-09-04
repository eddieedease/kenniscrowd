var app = angular.module('crowdapp', ['ngRoute', 'ui.bootstrap'])
        .config(moduleConfig);

moduleConfig.$inject = ['$routeProvider'];

function moduleConfig($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: 'views/home.html',
        controller: 'homeCtrl',
        controllerAs: 'homeCtrl'
    }).when('/nieuw', {
        templateUrl: 'views/nieuw.html',
        controller: 'newCtrl',
        controllerAs: 'newCtrl'
    }).when('/home', {
        templateUrl: 'views/home.html',
        controller: 'homeCtrl',
        controllerAs: 'homeCtrl'
    }).when('/wijzig', {
        templateUrl: 'views/wijzig.html',
        controller: 'setCtrl',
        controllerAs: 'setCtrl'
    }).when('/links', {
        templateUrl: 'views/links.html',
        controller: 'linkCtrl',
        controllerAs: 'linkCtrl'
    }).when('/export', {
        templateUrl: 'views/export.html',
        controller: 'csvCtrl',
        controllerAs: 'csvCtrl'
    })
            .otherwise({
                redirectTo: '/'
            });

}
;

app.controller('homeCtrl',
        function ($scope) {
            $scope.hello = "Welkom hier";
        }
);

app.controller('newCtrl',
        function ($scope, $http) {
            $scope.eventnaam = "";
            $scope.succesvol = false;
            $scope.onderwerpen = [];
            $scope.fillings = [];
            $scope.aantal = 16;
            for (i = 0; i < $scope.aantal; i++) {
                $scope.fillings.push("Onderwerp " + (i + 1));
            }
            $scope.addevent = function () {
                if ($scope.eventnaam !== "") {
                    $scope.onderwerpen = $scope.onderwerpen.filter(function (n) {
                        return n !== undefined;
                    });
                    var request = $http({
                        method: "post",
                        url: "./ajax/craddevent.php",
                        data: {
                            eventnaam: $scope.eventnaam,
                            onderwerpen: $scope.onderwerpen
                        },
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    });
                    /* Check whether the HTTP Request is Successfull or not. */
                    request.success(function (data) {
                        $scope.message = data;
                        $scope.succesvol = true;
                        $scope.onderwerpen = [];
                        $scope.fillings = [];
                    });
                } else {
                    $scope.problemm = "Niet voldoende ingevuld.. of naam ontbreekt of niet minstens 10 onderwerpen";
                }
            };
        }
);

app.controller('setCtrl',
        function ($scope, $http, $route) {

            $scope.formData = {};

            $scope.myDropDown = '';
            $scope.currentID = 0;
            $scope.succesvol = false;
            $scope.message = "Woehoe";

            $scope.selectAction = function () {
                console.log(this.myDropDown);
                $scope.currentID = this.myDropDown;
                $scope.succesvol = false;
            };

            $http.get('./ajax/api.php', {
                params: {
                    'woobar': new Date().getTime()
                }
            }).
                    success(function (data) {
                        // here the data from the api is assigned to a variable named events
                        $scope.events = data;
                    });
            $http.get('./ajax/api2.php', {
                params: {
                    'zoobar': new Date().getTime()
                }
            }).
                    success(function (data) {
                        // here the data from the api is assigned to a variable named users
                        $scope.users = data;
                    });
            $scope.isActive = function (user) {
                return user.elink === $scope.currentID.toString();
            };
            $scope.showsucces = function () {
                var request = $http({
                    cache: false,
                    method: "post",
                    url: "./ajax/crdeleteuser.php",
                    data: {
                        formdata: $scope.formData,
                        aantal: $scope.filtered.length
                    },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
                /* Check whether the HTTP Request is Successfull or not. */
                request.success(function (data) {
                    $scope.message = JSON.stringify(data);
                    //
                    $http.get('./ajax/api2.php', {
                        params: {
                            'foobar': new Date().getTime()
                        }
                    }).
                            success(function (data) {
                                // here the data from the api is assigned to a variable named users
                                $scope.users = data;
                                $scope.succesvol = true;
                            });
                });
            };
            //




        }
);

app.controller('linkCtrl',
        function ($scope, $http) {
            $http.get('./ajax/api.php', {
                params: {
                    'eoobar': new Date().getTime()
                }
            }).
                    success(function (data) {
                        // here the data from the api is assigned to a variable named users
                        $scope.events = data;
                        $scope.events.reverse();
                    });
        }
);

app.controller('csvCtrl',
        function ($scope, $http) {
            $scope.links = new Array("Link 1", "Link 2", "Link 3");
            $scope.thisDown = '';
            $scope.currentID = 44;
            $scope.debug = "";
            //
            $http.get('./ajax/api.php').
                    success(function (data) {
                        // here the data from the api is assigned to a variable named users
                        $scope.events = data;
                    });
            //
            $scope.selectAction = function () {
                $scope.currentID = this.thisDown;
                $scope.debug = $scope.currentID;
            };

            $scope.exportnow = function () {
                $http({method: 'GET', url: './ajax/export.php?elink=' + $scope.currentID}).
                        success(function (data, status, headers, config) {
                            var anchor = angular.element('<a/>');
                            anchor.attr({
                                href: 'data:attachment/csv;charset=utf-8,' + encodeURI(data),
                                target: '_blank',
                                download: 'filename.csv'
                            })[0].click();

                        }).
                        error(function (data, status, headers, config) {
                            // if there's an error you should see it here
                        });
            };
        }
);

app.controller('loginCtrl',
        function ($scope) {
            $scope.username = "crowdadmin";
            $scope.ww = "Cr0wd4d1n";
            $scope.inlogger = true;
            $scope.backend = false;
            $scope.authlogin = function () {
                if ($scope.username === $scope.user && $scope.ww === $scope.pass) {
                    $scope.inlogger = false;
                    $scope.backend = true;

                } else {
                    $scope.messager = "Niet juist...";
                }
            }
        }
);

