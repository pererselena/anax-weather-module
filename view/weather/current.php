<?php

namespace Anax\View;

?>

<?php if ($weather) : ?>
    <h4>Väderprognos för idag</h4>
    <div class="weather-current">
        <h4>Ort: <?= $weather["city"]; ?></h4>
        <img src="image/weather/<?= $weather["currently"]["icon"]; ?>.png?w=100" alt="<?= $weather["currently"]["icon"]; ?>">
        <p>Väder idag: <?= $weather["currently"]["summary"]; ?></p>
        <p>Temperatur idag: <?= round($weather["currently"]["temperature"]); ?>&ordm;C</p>
        <p>Vindhastighet idag: <?= round($weather["currently"]["windSpeed"]); ?>m/s</p>
        <p>Uv-index idag: <?= $weather["currently"]["uvIndex"]; ?></p>
        <p>Geografisk position: <?= $weather["lat"]; ?>, <?= $weather["long"]; ?></p>
    </div>
<?php endif ?>