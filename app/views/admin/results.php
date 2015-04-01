<a class="btn btn-default pull-right" href="<?= $this->url('admin/view', ['id' => $model->id]); ?>">Назад</a>
<h2>Результаты опроса «<?= $this->encode($model->title); ?>»</h2>

<?php if ($explanation) : ?>
    <strong>Фильтр</strong>
    <p><?= $explanation; ?></p>
<?php endif; ?>

<?php foreach ($model->getQuestions() as $question) : ?>
    <h3><?= $this->encode($question->title); ?></h3>
    <?php foreach ($question->getAnswersWithResults($query) as $answer) : ?>
        <div class="row">
            <div class="col-md-4"><?= $this->encode($answer['title']); ?></div>
            <div class="col-md-4">
                <div class="progress">
                    <div class="progress-bar"
                         style="width: <?= ceil(((int)$answer['count'] / (int)$answer['total']) * 100); ?>%;"></div>
                </div>
            </div>
            <div class="col-md-4"><?= (int)$answer['count']; ?> из <?= (int)$answer['total']; ?></div>
        </div>
    <?php endforeach; ?>

<?php endforeach; ?>