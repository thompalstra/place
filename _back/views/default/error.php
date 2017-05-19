<?php
?>

<h2><?=$exception->getCode()?></h2>
<p><?=(STATE == 'development') ? $exception->getMessage() : 'not found'?></p>
