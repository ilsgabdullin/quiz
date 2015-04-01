<a class="btn btn-default pull-right" href="<?= $this->url('admin/create'); ?>">Создать опрос</a>
<h2>Список опросов</h2>

<?php if (!empty($active)) : ?>
    <h3>Активный опрос</h3>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Название опроса</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr class="active">
            <td><?= $active->id; ?></td>
            <td><?= $this->encode($active->title); ?></td>
            <td>
                <a href="<?= $this->url('admin/view', array('id' => $active->id)); ?>" title="Посмотреть результаты">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>
                <a class="js-action" href="<?= $this->url('admin/close'); ?>" rel="<?= $active->id; ?>"
                   title="Закрыть опрос">
                    <span class="glyphicon glyphicon-ban-circle"></span>
                </a>
                <a class="js-action" href="<?= $this->url('admin/delete'); ?>" rel="<?= $active->id; ?>"
                   title="Удалить опрос">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>

<?php if (!empty($drafts)) : ?>
    <h3>Черновики</h3>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Название опроса</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($drafts as $poll) : ?>
            <tr>
                <td><?= $poll->id; ?></td>
                <td><?= $this->encode($poll->title); ?></td>
                <td>
                    <a href="<?= $this->url('admin/update', array('id' => $poll->id)); ?>" title="Редактировать опрос">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <?php if (empty($active)) : ?>
                        <a class="js-action" href="<?= $this->url('admin/activate'); ?>" rel="<?= $poll->id; ?>"
                           title="Активировать опрос">
                            <span class="glyphicon glyphicon-ok"></span>
                        </a>
                    <?php endif; ?>
                    <a class="js-action" href="<?= $this->url('admin/delete'); ?>" rel="<?= $poll->id; ?>"
                       title="Удалить опрос">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php if (!empty($closed)) : ?>
    <h3>Закрытые опросы</h3>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Название опроса</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($closed as $poll) : ?>
            <tr>
                <td><?= $poll->id; ?></td>
                <td><?= $this->encode($poll->title); ?></td>
                <td>
                    <a href="<?= $this->url('admin/view', array('id' => $poll->id)); ?>" title="Посмотреть результаты">
                        <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                    <?php if (empty($active)) : ?>
                        <a class="js-action" href="<?= $this->url('admin/activate'); ?>" rel="<?= $poll->id; ?>"
                           title="Активировать опрос">
                            <span class="glyphicon glyphicon-ok"></span>
                        </a>
                    <?php endif; ?>
                    <a class="js-action" href="<?= $this->url('admin/delete'); ?>" rel="<?= $poll->id; ?>"
                       title="Удалить опрос">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>