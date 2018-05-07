var app = angular.module('app', ['ngRoute', 'controllers', 'services']);

app.run(function($rootScope, $location){
  $rootScope.sair = function(){
    localStorage.removeItem('usuario');
    $rootScope.logado = false;
    $location.path('/login');
  }
});


app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
  $locationProvider.hashPrefix('');
  $routeProvider
  .when('/login', {
    controller: "LoginController",
    templateUrl: "view/login.html",
  })
  .when('/cadastro', {
    controller: "CadastroController",
    templateUrl: "view/cadastro.html",
  })
  .when('/filmes', {
    controller: "FilmesController",
    templateUrl: "view/filmes.html",
  })
  .when('/favoritos', {
    controller: "FavoritosController",
    templateUrl: "view/favoritos.html",
  })

  //Outro
  .otherwise({
    redirectTo: '/'
  });
}]);
