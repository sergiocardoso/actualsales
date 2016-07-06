angular.module('ActualApp', ['ui.mask'])

.controller('RecordController', [ '$scope', '$http', function($scope, $http){
    
    $scope.user = {};
    $scope.forms = {};
    $scope.forms.form1  = true;
    $scope.forms.form2  = false;
    $scope.forms.sucess = false;

    $scope.regions          = {};
    $scope.regions.nothing  = {'value': 0, 'name': 'selecione a unidade mais próxima', 'citys': []};
    $scope.regions.sul      = {'value': 1, 'name': 'Sul', 'citys': [{'name':'Porto Alegre', 'id': 1}, {'name':'Curitiba', 'id':2}]};
    $scope.regions.sudeste  = {'value': 2, 'name': 'Sudeste', 'citys': [{'name':'São Paulo', 'id': 3}, {'name': 'Rio de Janeiro', 'id': 4}, {'name':'Belo Horizonte', 'id':5}]};
    $scope.regions.centro   = {'value': 3, 'name': 'Centro-Oeste', 'citys':[{'name':'Brasília', 'id': 6}]};
    $scope.regions.nordeste = {'value': 4, 'name': 'Nordeste', 'citys' : [{'name':'Salvador', 'id':7}, {'name':'Recife', 'id':8}]};
    $scope.regions.norte    = {'value': 5, 'name': 'Norte', 'citys' : [{'name':'Não possui disponibilidade', 'id':0}]};
    
    $scope.regions.actual = $scope.regions.nothing;
    $scope.user.regiao = $scope.regions.nothing;

    $scope.nextStep = function(){
        if($scope.step_1.$valid){
            $scope.forms.form1 = !$scope.forms.form1;
            $scope.forms.form2 = !$scope.forms.form2;
        }
    }

    $scope.finishField = function(){

        if($scope.step_2.$valid){
            $scope.forms.form1 = $scope.forms.form2 = false;
            $scope.forms.sucess = true;

            var request = $http({
                method: 'post',
                url: 'send.php',
                data: { user : $scope.user }
            });

            // request.success(function(response){
            //     console.log('veio', response);
            // });
        }
    }

    $scope.changeRegion = function(region){
        console.log(region);
        $scope.regions.actual = region;
    }

}]);
