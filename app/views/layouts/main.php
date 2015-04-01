<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Quiz</title>
    <link href="/web/css/bootstrap.min.css" rel="stylesheet">
    <link href="/web/css/style.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right">
            <li<?= $this->getController() == 'main' ? ' class="active"' : ''; ?>><a
                    href="<?= $this->url('main/index'); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li<?= $this->getController() == 'poll' ? ' class="active"' : ''; ?>><a
                    href="<?= $this->url('poll/start'); ?>">Пройти опрос</a></li>
            <li<?= $this->getController() == 'admin' ? ' class="active"' : ''; ?>><a
                    href="<?= $this->url('admin/index'); ?>">Администрирование</a></li>
        </ul>
        <h3 class="text-muted">Quiz</h3>
    </div>

    <?= $content; ?>

    <div class="footer">
        <p>&copy; Ильсур Габдуллин, <?= date('Y'); ?></p>
    </div>

</div>
<form id="form" method="POST"><input type="hidden" name="id" id="data-id"></form>
<script src="/web/js/jquery.min.js"></script>
<script src="/web/js/bootstrap.min.js"></script>
<?php if ($this->getController() == 'admin') : ?>
    <script src="/web/js/admin.js"></script>
<?php endif; ?>
</body>
</html>