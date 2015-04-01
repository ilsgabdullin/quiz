<h2>Результаты опроса «<?= $this->encode($model->title); ?>»</h2>


<?php foreach ($model->getQuestions() as $question) : ?>
    <h3><?= $this->encode($question->title); ?></h3>

    <?php foreach ($question->getAnswersWithResults() as $answer) : ?>
        <div class="row">
            <div class="col-md-4"><?= $this->encode($answer['title']); ?></div>
            <div class="col-md-4">
                <div class="progress">
                    <div class="progress-bar"
                         style="width: <?= ceil(((int)$answer['count'] / (int)$answer['total']) * 100); ?>%;"></div>
                </div>
            </div>
            <div class="col-md-4"><?= $answer['count']; ?> из <?= $answer['total']; ?></div>
        </div>
    <?php endforeach; ?>

<?php endforeach; ?>