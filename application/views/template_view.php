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
            <a class="navbar-brand" href="#">Проект "Бросок кобры"</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left masthead-nav">
                <li><a href="/">Главная</a></li>
                <li><a href="/events">События</a></li>
                <li><a href="/tickets">Билеты</a></li>
                <li><a href="/config">Цены</a></li>
            </ul>
            <!-- <form class="navbar-form navbar-right">-->
            <!-- <input type="text" class="form-control" placeholder="Поиск...">-->
            <!-- </form>-->
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
               <? $uri_path = explode("/",$_SERVER['REQUEST_URI']);
                $sec = (!empty($uri_path[0])) ? $uri_path[0]: 'events';

               ?>
                <li><a href="/<?=$sec?>/add">Создать мероприятие</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="#">Export</a></li>
            </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?php include 'application/views/'.$content_view; ?>
        </div>
    </div>
</div>

</body>
</html>