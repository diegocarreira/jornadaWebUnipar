var app = angular.module('services', []);

app.service('Requisicao', function($http){
  return{
    get: function(metodo, callback){
      var headers = {};

      $http.get(
        metodo,
        headers
      )
      .then(function successCallback(response) {

        callback(response.data);
      }, function errorCallback(response) {
        alert('Falha na requisicao de '+metodo);
      });


    },
    post: function(metodo, parametros, callback){

      // var formData = new FormData();
      //
      // for (var key in parametros) {
      //     formData.append(key, parametros[key]);
      // }

      var token = (JSON.parse(localStorage.getItem('usuario'))) ? JSON.parse(localStorage.getItem('usuario')).token : null;

      $http.post(
        metodo,
        parametros,
        {
          headers: {
            "Content-Type": undefined,
            "token": token
          }
        }
      )
      .then(function successCallback(response) {

        if(response.data.erro.cd > 0){
          alert(response.data.erro.msg);
        }else{
          callback(response.data);
        }

      }, function errorCallback(response) {
        alert('Falha na requisicao de '+metodo);
      });


    }
  };
});
