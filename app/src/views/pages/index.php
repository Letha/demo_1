<?php
    use const App\APP_DIR;
    include APP_DIR . '/src/views/layouts/headers/theme-1.php';
?>
    
<section class='content content_page_index'>
    <?php if ($viewData['isAuthorized']): ?>
    <form name='exit' class='content__form content__form_id_1 form form_theme_1'>
        <h1 class='form__header'><?=$viewData['login']?></h1>

        <?php if ($viewData['userData']['hasProfilePhoto']): ?>
        <div class='content__form-image content__form-image_id_1' data-function='area-for-background' data-background-id='profile-photo'></div>
        <?php endif; ?>
        <div class='content__form-field form__field'>
            <?=$viewData['texts']['words']['aName']?>&nbsp;&nbsp;:&nbsp;&nbsp;
            <div class='form__output'><?=$viewData['userData']['name']?></div>
        </div>
        <div class='content__form-field form__field'>
            <?=$viewData['texts']['words']['aSurname']?>&nbsp;&nbsp;:&nbsp;&nbsp;
            <div class='form__output'><?=$viewData['userData']['surname']?></div>
        </div>

        <button type='submit' class='content__form-button content__form-button_id_1 form__button form__button_type_submit button button_theme_1 button_size_global-1'>
            <?=$viewData['texts']['words']['toExit']?>
        </button>
    </form>
    <?php endif; ?>
</section>

<?php include APP_DIR . '/src/views/layouts/footers/theme-1.php'; ?>