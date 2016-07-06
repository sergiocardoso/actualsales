angular.module('ActualApp', ['ui.mask'])

.controller('RecordController', [ '$scope', function($scope){
    
    $scope.user = {};
    $scope.forms = {};
    $scope.forms.form1  = true;
    $scope.forms.form2  = false;
    $scope.forms.sucess = false;

    $scope.regions          = {};
    $scope.regions.nothing  = {'value': 0, 'name': 'selecione a unidade mais próxima', 'citys': []};
    $scope.regions.sul      = {'value': 1, 'name': 'Sul', 'citys': ['Porto Alegre', 'Curitiba']};
    $scope.regions.sudeste  = {'value': 2, 'name': 'Sudeste', 'citys': ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte']};
    $scope.regions.centro   = {'value': 3, 'name': 'Centro-Oeste', 'citys':['Brasília']};
    $scope.regions.nordeste = {'value': 4, 'name': 'Nordeste', 'citys' : ['Salvador', 'Recife']};
    $scope.regions.norte    = {'value': 5, 'name': 'Norte', 'citys' : ['Não possui disponibilidade']};
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
        }
    }

    $scope.changeRegion = function(region){
        $scope.regions.actual = region;
    }

}]);
