<?php
    use const App\APP_DIR;
    include APP_DIR . '/src/views/layouts/headers/theme-1.php';
?>

<div style='margin:10px;'><?=$viewData['texts']['phrases']['textOfError404']?></div>

<?php include APP_DIR . '/src/views/layouts/footers/theme-1.php'; ?>