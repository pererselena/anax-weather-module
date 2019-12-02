<?php

namespace Anax\View;

?>

<h2><?= $title ?></h2>
<h3>Validera genom html svar</h3>
<form class="ip_verify" method="post">
    <input type="text" name="ipAddress" placeholder="Ip adress" value="<?= $ip ?>" />
    <br>
    <p>
        <input class="buttons_input" type="submit" name="ipVerify" value="Validera" />
    </p>
</form>
<h3>Validera genom API svar</h3>
<form class="ip_verify" method="get" action="json_ip">
    <input type="text" name="ip" placeholder="Ip adress" />
    <br>
    <p>
        <input class="buttons_input" type="submit" value="Validera" />
    </p>
</form>
<a href="json_geo/?ip=8.8.8.8">Test API rätt svar</a>
<a href="json_geo/?ip=8.8.8">Test API fel svar</a>
<p>För att använda APIt använd följande URL: http://www.student.bth.se/~elpr18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/json_geo/?ip=8.8.8.8 där det efter ip?= byts ut mot den IP address man vill validera.</p>
<p>Använd metod GET och svaret kan se ut på det här sättet:</p>
<pre>
    {
        "geoInfo": {
            "ip": "8.8.8.8",
            "protocol": "IPV4",
            "isValid": true,
            "domain": "dns.google",
            "long": -122.07540893554688,
            "lat": 37.419158935546875,
            "country": "United States",
            "city": "Mountain View",
            "map": "https://www.openstreetmap.org/#map=15/37.419158935547/-122.07540893555",
            "flag": "http://assets.ipstack.com/flags/us.svg",
            "embed": "-122.06089893555%2C37.432538935547%2C-122.08991893555%2C37.405778935547"
        },
        "ip": "8.8.8.8"
    }
</pre>
<?php if ($geoInfo) : ?>
    <div>
        <a href="json_geo/?ip=<?= $geoInfo["ip"]; ?>">API svar</a>
        <p>Ip: <?= $geoInfo["ip"]; ?></p>
        <p>Giltig ip adress: <?= $geoInfo["isValid"] ? "Godkänd" : "Ej godkänd"; ?></p>
        <p>Protokoll: <?= $geoInfo["protocol"]; ?></p>
        <p>Domän: <?= $geoInfo["domain"]; ?></p>
        <p>Geografisk position: <?= $geoInfo["lat"]; ?>, <?= $geoInfo["long"]; ?></p>
        <p>Ort: <?= $geoInfo["city"]; ?></p>
        <p>Land: <?= $geoInfo["country"]; ?></p>
        <p>Flagga: <img src="<?= $geoInfo["flag"]; ?>" width="40" height="30" alt="Flagga" /> </p>
        <div>
            <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=<?= $geoInfo["embed"]; ?>&amp;layer=mapnik&amp;marker=<?= $geoInfo["lat"]; ?>%2C<?= $geoInfo["long"]; ?>" style="border: 1px solid black"></iframe><br /><small><a href="https://www.openstreetmap.org/?mlat=<?= $geoInfo["lat"]; ?>&amp;mlon=<?= $geoInfo["long"]; ?>#map=15/<?= $geoInfo["lat"]; ?>/<?= $geoInfo["long"]; ?>">Visa större karta</a></small>
        </div>
    </div>
<?php endif ?>