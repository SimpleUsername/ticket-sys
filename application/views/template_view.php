<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Проект</title>
    <link href="/favicon_<?=$_SERVER['SERVER_ADDR'] == '127.0.0.1'?"debug":"release"?>.ico" rel="shortcut icon" type="image/x-icon" />
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
                    <? if ($_SESSION['user_seller']) { ?>
                        <!-- Seller nav -->
                        <li><a href="/events">События</a></li>
                        <li><a href="#" class="btn-ticket-search">Поиск билета</a></li>
                        <li><a href="#" class="btn-reserve-search">Поиск брони</a></li>
                    <? } ?>
                    <? if ($_SESSION['user_manager']) { ?>
                        <!-- Manager nav -->
                        <?=!$_SESSION['user_seller']?'<li><a href="/events">События</a></li>':''?>
                        <li><a href="/events/archive">Архив событий</a></li>
                        <li><a href="/config">Цены по умолчанию</a></li>
                    <? } ?>
                    <? if ($_SESSION['user_admin']) { ?>
                        <!-- Admin nav -->
                        <li><a href="/users">Пользователи</a></li>
                    <? } ?>
                </ul>
                <div class="nav navbar-nav navbar-right">
                    <div class="btn-group">
                        <div class="btn-group navbar-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span> <i class="icon-white glyphicon glyphicon-cog"></i>&nbsp;</a>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <? if($_SESSION['user_admin']){?>
                                <li><a href="/users/dump"><i class="icon-white glyphicon  glyphicon-download"></i>&nbsp;Дамп базы</a></li>
                                <li><a href="/users/dump/1"><i class="icon-white glyphicon glyphicon-compressed"></i>&nbsp;Дамп базы (gzip)</a></li>
                                <li class="divider"></li>
                                <? } ?>
                                <li><a href="/user/password">Сменить пароль</a></li>
                            </ul>
                        </div>

                        <a href="/user/logout" title="Выйти" class="btn navbar-btn <?
                        if ($_SESSION['user_admin']) {
                            echo "btn-warning";
                        } elseif ($_SESSION['user_manager']) {
                            echo "btn-success";
                        } elseif ($_SESSION['user_seller']) {
                            echo "btn-primary";
                        }?>"><?=$_SESSION['user_login'] ?> <i class="icon-white glyphicon glyphicon-log-out"></i></a>
                    </div>&nbsp;
                </div>
            <? } ?>
        </div>
    </div>
</div>
<div id="dialog-modal"></div>
<div class="modal" id="errorMessageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="errorMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <h4 class="modal-title">Ошибка!</h4>
            </div>
            <div class="modal-body">
                <p><strong>Не удалось получить данные с сервера!</strong></p>
                <p>Перезагрузите страницу</p>
                <p class="modal-error-message"></p>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <? if (isset($_SESSION['authorized']) && $_SESSION['authorized'] == 1) { ?>
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <? if ($_SESSION['user_seller']) { ?>
                        <!-- Seller sidebar menu -->
                        <!--li><a href="/events">Продажа и бронирование</a></li-->
                        <li><a href="#" class="btn-reserve-search">Выкуп брони</a></li>
                        <li><a href="#" class="btn-ticket-search">Проверка места</a></li>
                        <li><hr></li>
                    <? } ?>
                    <? if ($_SESSION['user_manager']) { ?>
                        <!-- Manager sidebar menu -->
                        <li><a href="/events/add">Создать мероприятие</a></li>
                        <li><hr></li>
                    <? } ?>
                    <? if ($_SESSION['user_admin']) { ?>
                        <!-- Admin sidebar menu -->
                        <li><a href="/users">Список пользователей</a></li>
                        <li><a href="/users/create">Добавить пользователя</a></li>
                        <li><hr></li>
                    <? } ?>
                    <li><a href="/user/password">Сменить свой пароль</a></li>
                </ul>
            </div>
        <? } ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?php include 'application/views/'.$content_view; ?>
        </div>
    </div>
</div>
</body>
</html>