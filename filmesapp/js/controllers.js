var app = angular.module('controllers', []);

app.controller('CadastroController', function($scope, $location, $http, Requisicao) {

// ##### Carregar o nome e email do localstorage (como se fosse uma edicao)
  var usuario = localStorage.getItem('usuario');
  if(usuario){
    var usuarioJson = JSON.parse(usuario);
    $scope.email = usuarioJson.email;
  }

  $scope.salvarCadastro = function(){
    console.log($scope.nome);
    console.log($scope.email);
    console.log($scope.senha);

    if(!$scope.nome){
      alert('Informe o campo nome');
      return false;
    }
    if(!$scope.email){
      alert('Informe o campo email');
      return false;
    }
    if(!$scope.senha){
      alert('Informe o campo senha');
      return false;
    }

    var usuario = new FormData();

    usuario.append('nome',  $scope.nome);
    usuario.append('email', $scope.email);
    usuario.append('senha', $scope.senha);

// ######## 1
  $http.post(
    'http://jornada.interate.com.br/api/v1/usuarios',
    usuario,
    {
      headers: {
        "Content-Type": undefined
      }
    }
  )
  .then(function successCallback(response) {
      alert('Usuário cadastrado. Efetue o login!');
      $location.path('/login');
  }, function errorCallback(response) {
    alert('Falha na requisicao');
  });


// ######## 2
    // Requisicao.post('http://jornada.interate.com.br/api/v1/usuarios', usuario, function(retorno){
    //   console.log('retorno');
    //   console.log(retorno);
    //   console.log('retorno.dados');
    //   console.log(retorno.dados);
    //   alert('Usuário cadastrado. Efetue o login!');
    //   $location.path('/login');
    //
    // });




  }

});

app.controller('LoginController', function($scope, $location, $http, Requisicao) {

  $scope.login = function(){
    // console.log($scope.email);
    // console.log($scope.senha);
    if(!$scope.email){
      alert('Informe o campo email');
      return false;
    }
    if(!$scope.senha){
      alert('Informe o campo senha');
      return false;
    }

    var dados = new FormData();
    dados.append('email', $scope.email);
    dados.append('senha', $scope.senha);

// ######## 1
    $http.post(
      'http://jornada.interate.com.br/api/v1/token',
      dados,
      {
        headers: {
          "Content-Type": undefined
        }
      }
    )
    .then(function successCallback(response) {
      var retorno = response.data;
        if(retorno.dados.token){
          localStorage.setItem('usuario', JSON.stringify({ 'email': $scope.email, 'senha': $scope.senha, 'token': retorno.dados.token  }));
          $location.path('/filmes');
        }else{
          alert(retorno.erro.msg);
        }
    }, function errorCallback(response) {
      alert('Falha na requisicao');
    });

// ######## 2
    // Requisicao.post('http://jornada.interate.com.br/api/v1/token', dados, function(retorno){
    //   console.log('retorno');
    //   console.log(retorno);
    //   console.log('retorno.dados');
    //   console.log(retorno.dados);
    //   if(false){
    //     $location.path('/filmes');
    //   }
    // });

  }

});

app.controller('FilmesController', function($scope, $location, Requisicao, $http, $rootScope) {

  var usuario = localStorage.getItem('usuario');
  if(usuario){
    var usuarioJson = JSON.parse(usuario);
    if(!usuarioJson.token){
      // mandar pra pagina de login
      $location.path('/login');
      return false;
    }
  }else{
    // mandar pra pagina de login
    $location.path('/login');
    return false;
  }

  $rootScope.logado = true;
  $scope.filmes = [];

  // ##### 1
  $http.get(
    'http://jornada.interate.com.br/api/v1/filmes',
    {}
  )
  .then(function successCallback(response) {
    var retorno = response.data;
    console.log(retorno.dados);
      if(retorno.dados.length > 0){
        $scope.filmes = retorno.dados;
      }
      console.log($scope.filmes);
  }, function errorCallback(response) {
    alert('Falha na requisicao');
  });



  // ##### 2
  // Requisicao.get('http://jornada.interate.com.br/api/v1/filmes', function(retorno){
  //   console.log(retorno.dados);
  //   if(retorno.dados.length > 0){
  //     $scope.filmes = retorno.dados;
  //   }
  //   console.log($scope.filmes);
  // });




  $scope.favoritar = function(id){
    console.log('favoritar o filme: ',id);

    // enviar requisicao FAVORITAR passando id do filme
    var dados = new FormData();
    dados.append('id_filme', id);

    var token = JSON.parse(localStorage.getItem('usuario')).token;

// ######## 1
    $http.post(
      'http://jornada.interate.com.br/api/v1/favoritos',
      dados,
      {
        headers: {
          "Content-Type": undefined,
          "token": token
        }
      }
    )
    .then(function successCallback(response) {
      var retorno = response.data;

      if(retorno.erro.cd > 0){
        alert(retorno.erro.msg);
      }else{
        alert('Filme favoritado!')
      }

    }, function errorCallback(response) {
      alert('Falha na requisicao');
    });

    // ##### 2
    // Requisicao.post('http://jornada.interate.com.br/api/v1/favoritos', dados, function(retorno){
    //   console.log(retorno.dados);
    //   if(retorno.dados){
    //     alert('Filme favoritado!');
    //   }
    // });

  } // fim da funcao favoritar

});

app.controller('FavoritosController', function($scope, $location, $http, $route, $rootScope) {
  var usuario = localStorage.getItem('usuario');
  if(usuario){
    var usuarioJson = JSON.parse(usuario);
    if(!usuarioJson.token){
      // mandar pra pagina de login
      $location.path('/login');
      return false;
    }
  }else{
    // mandar pra pagina de login
    $location.path('/login');
    return false;
  }

  $rootScope.logado = true;

  // ##### 1
  var token = JSON.parse(localStorage.getItem('usuario')).token;

  $http.get(
    'http://jornada.interate.com.br/api/v1/favoritos',
    {
      headers: {
        'token': token
      }
    }
  )
  .then(function successCallback(response) {
    var retorno = response.data;

    if(retorno.erro.cd > 0){
      alert(retorno.erro.msg);
    }else{
      if(retorno.dados.length > 0){
        $scope.filmes = retorno.dados;
      }
    }

  }, function errorCallback(response) {
    alert('Falha na requisicao');
  });


  $scope.removerFavorito = function(id){
    console.log('id pra remover: ',id);

    // enviar requisicao DELETE passando id do filme
    var dados = new FormData();
    dados.append('id_filme', id);

    var token = JSON.parse(localStorage.getItem('usuario')).token;

// ######## 1
    $http.delete(
      'http://jornada.interate.com.br/api/v1/favoritos',
      {
        params: {'id_filme': id},
        headers: {
          "Content-Type": undefined,
          "token": token
        }
      }
    )
    .then(function successCallback(response) {
      console.log(response);
      if(response.data.erro.cd > 0) {
        alert(response.data.erro.msg);
      }else{
        $route.reload();
      }

    }, function errorCallback(response) {
      alert('Falha na requisicao');
    });

  } // fim da funcao removerFavorito



});
