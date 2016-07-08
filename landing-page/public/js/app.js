angular.module('ActualSales', ['ui.mask'])

.controller('ProductController', ['$scope', '$http', function($scope, $http){
    
    $scope.user = {};

    $scope.forms = {
        "form1" : true, 
        "form2" : false,
        "form3" : false,
        "error" : false,
    };

    /* POPULATE REGION SELECT */
    $http({
        method: 'GET',
        url: 'AjaxController/show_regions'
    })
    .success(function(response){
        $scope.regiao = response;
    });

    $scope.unidade = [];

    $scope.gotoNext = function(){
        // if($scope.step_1.$valid)
        {
            $scope.forms.form1 = !$scope.forms.form1;
            $scope.forms.form2 = !$scope.forms.form2;
        }
    }

    $scope.gotoFinish = function(){
        
        if($scope.step_2.$valid)
        {

            console.log('user', $scope.user);

            //send data to server
            $http({
                method: 'POST',
                url: 'AjaxController/data_post',
                data: { user : $scope.user }
            })
            .success(function(response){

                if(response == true){
                    $scope.forms.form2 = !$scope.forms.form2;
                    $scope.forms.form3 = !$scope.forms.form3; 
                }

                else {
                    $scope.forms.error = true;
                    $scope.forms.form1 = !$scope.forms.form1;
                    $scope.forms.form2 = !$scope.forms.form2;
                    $scope.error_message = response;
                }
            });


               
        }
        
    }

    $scope.changeRegion = function(region){
        $http({
            method: 'GET',
            url: 'AjaxController/show_unity/' + region.id
        })
        .success(function(response){
            $scope.unidade = response;
            console.log('unidade', response);
        });
    }

}]);