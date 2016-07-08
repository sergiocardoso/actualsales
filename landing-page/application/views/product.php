<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compre já</title>

    <!--//css-->
    <link rel="stylesheet" href="<?=$public_view;?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$public_view;?>/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=$public_view;?>/css/app.css">

    <!--//js-->
    <script src="<?=$public_view;?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?=$public_view;?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=$public_view;?>/bower_components/angular/angular.min.js"></script>
    <script src="<?=$public_view;?>/bower_components/angular-ui-mask/dist/mask.min.js"></script>
    <script src="<?=$public_view;?>/js/app.js"></script>

</head>
<body ng-app="ActualSales" ng-controller="ProductController">

    <!--//border-->
    <div class="product_image">
        <div class="highlight">
            <div class="pattern"></div>
        </div>
    </div>

    <div class="body">
        
        <div class="info">
            <div class="title">Nome do Produto</div>
            <div class="description">Aqui vai uma efetiva descrição com informações relevantes deste produto.</div>

            <div class="baseOther">

                <div class="otherInfos">
                    <div class="title">Outra informação</div>
                    <div class="description">maiores detalhes sobre o produto</div>
                </div>
            </div>

        </div>

        <div class="base">

            <form name="step_1" ng-show="forms.form1">
                <div class="form">
                    <div class="section">Preencha seus dados para receber o contato</div>

                    <div class="field">
                        <label>Nome</label>
                        <input 
                            type="text" 
                            id="cNome" 
                            name="nome"
                            placeholder="insira o seu nome" 
                            ng-model="user.nome" 
                            type="text" 
                            ng-pattern="/^([a-zA-zÀ-úà-ú]+\s[a-zA-zÀ-úà-ú]+)*$/" 
                            required
                        >

                        <!--//error block -->
                        <div ng-show="step_1.nome.$touched && step_1.nome.$error.pattern" class="alert alert-danger errorBlock">Nome inválido [ é necessário no mínimo duas palavras ]</div>
                        <div ng-show="step_1.nome.$touched && step_1.nome.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>

                    </div>

                    <div class="field">
                        <label>Data de Nascimento</label>
                        <input 
                            type="text" 
                            id="cNascimento" 
                            name="data_nascimento"
                            type="text" 
                            ng-model="user.data_nascimento" 
                            ui-mask="99/99/9999"
                            ng-pattern="/^(0[1-9]|[12]\d|3[01])[\/]+(0?[1-9]|1[012])[\/]+(19|20)\d{2}$/"
                            required>

                        <!--//error block -->
                        <div ng-show="step_1.data_nascimento.$touched && step_1.data_nascimento.$error.pattern" class="alert alert-danger errorBlock">Data de nascimento inválida (PADRAO: dd/mm/yyyy)</div>
                        <div ng-show="step_1.data_nascimento.$touched && step_1.data_nascimento.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>
                    </div>

                    <div class="field">
                        <label>E-Mail</label>
                        <input 
                            type="text" 
                            id="cEmail"
                            name="email"
                            class="form-control" 
                            type="text" 
                            ng-model="user.email"
                            ng-pattern="/^\S+@\S+\.\S+$/"
                            required>
                            
                        <!--//error block -->
                        <div ng-show="step_1.email.$touched && step_1.email.$error.pattern" class="alert alert-danger errorBlock">E-mail inválido!</div>
                        <div ng-show="step_1.email.$touched && step_1.email.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>
                    </div>

                    <div class="field">
                        <label>Telefone</label>
                        <input 
                            type="text" 
                            id="cTelefone" 
                            name="telefone"
                            type="text" 
                            ng-model="user.telefone" 
                            ui-mask="(99) 99999-9999"
                            ng-pattern="/^\([0-9]{2}\)+\s[9][0-9]{4}\-[0-9]{4}$/"
                            required>

                        <!--//error block -->
                        <div ng-show="step_1.telefone.$touched && step_1.telefone.$error.pattern" class="alert alert-danger errorBlock">Telefone inválido!</div>
                        <div ng-show="step_1.telefone.$touched && step_1.telefone.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>
                    </div>

                    <button id="nextForm" ng-click="gotoNext();">Próximo Passo</button>
                </div>
            </form>

            <form name="step_2" ng-show="forms.form2">
                <div class="form">
                    <div class="section">Preencha seus dados para receber o contato</div>

                    <div class="field">
                        <label>Região</label>
                        <select 
                            name="regiao" 
                            id="cRegiao" 
                            ng-model="user.regiao"
                            ng-change="changeRegion(user.regiao)"
                            ng-options="options.nome_regiao for options in regiao track by options.id">
                                
                        </select>

                        <!--//error block -->
                        <div ng-show="step_2.regiao.$touched && step_2.regiao.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>

                    </div>

                    <div class="field">
                        <label>Unidade</label>
                        <select 
                            name="unidade" 
                            id="cUnidade" 
                            ng-model="user.unidade"
                            ng-options="options.nome_unidade for options in unidade track by options.id">
                        </select>

                        <!--//error block -->
                        <div ng-show="step_2.unidade.$touched && step_2.unidade.$error.required" class="alert alert-danger errorBlock">Este campo é obrigatório</div>
                    </div>

                    <button id="nextForm" ng-click="gotoFinish();">Próximo Passo</button>
                </div>
            </form>

            <div class="errorData" ng-show="forms.error">
                {{error_message}}
            </div>

            <div class="step3" ng-show="forms.form3">
                <img src="<?=$public_view?>/img/comment.gif" alt="">
                <h1>Informações enviadas com sucesso</h1>
                <div class="text">Em breve você receberá um contato de nossa equipe.</div>
            </div>

            <ul class="steps">
                <li ng-class="forms.form1 ? 'active' : 'normal'">1</li>
                <li class="dots"></li>
                <li ng-class="forms.form2 ? 'active' : 'normal'">2</li>
                <li class="dots"></li>
                <li ng-class="forms.form3 ? 'active' : 'normal'">3</li>
            </ul>

        </div>

        <div class="clear"></div>

    </div>
    
</body>
</html>