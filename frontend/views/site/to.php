<?php

/** @var yii\web\View $this */
/** @var string $url */
/** @var int $seconds */

$this->title = 'Reirecting to...' . $url . ' in ' . $seconds . ' seconds';

if (!empty($url)) {
    echo '<meta http-equiv="refresh" content="' . $seconds . ';URL=' . $url . '">';
}

?>
<div class="site-go">
    <h1>Redirecting you to URL <?php echo $url; ?> in <?php echo $seconds; ?> seconds...</h1>
</div>
