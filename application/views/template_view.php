<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Проект</title>
    <link rel="stylesheet" href="/css/normalize.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/css/font-awesome.css" rel="stylesheet">
    <!-- Optional theme -->
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/moment.js"></script>
    <script src="/js/bootstrap-datetimepicker.js"></script>
    <script src="/js/bootstrap-datetimepicker.ru.js"></script>
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <script src="/libs/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="/js/my.js"></script>
    <!-- BootstrapValidator -->
    <link rel="stylesheet"  href="/css/bootstrapValidator.min.css">
    <script src="/js/bootstrapValidator.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Проект "Бросок кобры"</a>
        </div>
        <div class="navbar-collapse collapse">
            <? if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) { ?>
                <ul class="nav navbar-nav navbar-left masthead-nav">
                    <? if ($_SESSION['user_type_id'] == 1) { ?>
                        <li><a href="/users">Пользователи</a></li>
                    <? } else { ?>
                        <li><a href="/">Главная</a></li>
                        <li><a href="/events">События</a></li>
                        <li><a href="/tickets">Билеты</a></li>
                        <li><a href="/config">Цены</a></li>
                    <? } ?>
                    <li><a href="#">О программе</a></li>
                </ul>
                <!-- <form class="navbar-form navbar-right">-->
                <!-- <input type="text" class="form-control" placeholder="Поиск...">-->
                <!-- </form>-->
                <div class="nav navbar-nav navbar-right">
                    <!--  -->
                    <div class="btn-group">
                        <a href="/user/password" class="btn btn-default navbar-btn" title="Сменить пароль">
                            <i class="icon-white glyphicon glyphicon-cog"></i>&nbsp;</a>
                        <a href="/user/logout" title="Выйти" class="btn navbar-btn <? switch($_SESSION["user_type_id"]) {
                            case 1 : ?>btn-warning<? break;
                            case 2 : ?>btn-success<? break;
                            case 3 : ?>btn-primary<? break;
                            default : ?>btn-default<?
                        }?>"><?=$_SESSION['user_login'] ?> <i class="icon-white glyphicon glyphicon-off"></i></a>
                    </div>&nbsp;
                </div>
            <? } ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <? if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) { ?>
            <div class="col-sm-3 col-md-2 sidebar">

                <ul class="nav nav-sidebar">
                    <? if ($_SESSION['user_type_id'] == 1) { ?>
                        <!-- Admin sidebar menu -->
                        <li><a href="/users">Список пользователей</a></li>
                        <li><a href="/users/create">Добавить пользователя</a></li>
                    <? } else { ?>
                        <li><a href="/events/add">Создать мероприятие</a></li>
                        <li><a href="/tickets/add">Analytics</a></li>
                        <li><a href="#">Export</a></li>
                    <? } ?>
                    <li><a href="/user/password">Сменить свой пароль</a></li>
                </ul>

            </div>
        <? } ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?php include 'application/views/'.$content_view; ?>
            <!--            --><?// echo "<pre>" ; print_r($_SESSION); echo "</pre>";?>
        </div>
    </div>
</div>

</body>
</html>