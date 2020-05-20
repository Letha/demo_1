<!DOCTYPE html>
<html lang='<?=$viewData['locale']?>'>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=Edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

        <link rel='stylesheet' href='/front/main.css'>

        <link rel='stylesheet' href='/front/blocks/figure.css'>
        <link rel='stylesheet' href='/front/blocks/button.css'>
        <link rel='stylesheet' href='/front/blocks/menu.css'>
        <link rel='stylesheet' href='/front/blocks/form.css'>

        <link rel='stylesheet' href='/front/blocks/body/body_theme_1.css'>
        <link rel='stylesheet' href='/front/blocks/header/header_theme_1.css'>
        <link rel='stylesheet' href='/front/blocks/content/content_page_index.css'>

        <script>
            let app = {};
            <?php if (isset($viewData['scriptTexts'])): ?>
                app.texts = JSON.parse('<?=json_encode($viewData['scriptTexts']);?>');
            <?php else: ?>
                app.texts = [];
            <?php endif; ?>
        </script>
        <script src='/front/main.js'></script>
    </head>

    <body class='body body_theme_1'>
        <div class='body__temporary-area body__temporary-area_id_1' data-function='modal-element' data-modal-relation-id='body-temporary-1'></div>
        <div class='body__temporary-area body__temporary-area_id_2' data-function='modal-element' data-modal-relation-id='body-temporary-2'></div>
        <header class='header header_theme_1'>
            <a href='/' class='header__figure header__figure_id_1 figure_theme_1'>
                <div></div> <div></div> <div></div> <div></div> <div></div>
            </a>

            <div class='header__menu menu menu_theme_1 menu_interactive'>
                <button class='header__menu-item menu__item' data-function='locale-setter' data-locale='en' <?=$viewData['locale'] === 'en' ? 'data-state="active"' : ''?>>EN</button>
                <button class='header__menu-item menu__item' data-function='locale-setter' data-locale='ru' <?=$viewData['locale'] === 'ru' ? 'data-state="active"' : ''?>>RU</button>
            </div>

            <?php if (!$viewData['isAuthorized']): ?>
            <div class='header__menu header__menu_id_1 menu menu_theme_1 menu_interactive'>
                <button class='header__menu-item menu__item' data-function='modal-opener' data-modal-relation-id='enter' data-modal-switch-mode='click-switcher'>
                    <?=$viewData['texts']['words']['anEntrance']?>
                </button>

                <button class='header__menu-item menu__item' data-function='modal-opener' data-modal-relation-id='reg' data-modal-switch-mode='click-switcher'>
                    <?=$viewData['texts']['words']['aRegistration']?>
                </button>
            </div>
            <?php endif; ?>

            <?php if (!$viewData['isAuthorized']): ?>
            <div class='header__modal-area header__modal-area_type_1' data-function='modal-element' data-modal-relation-id='reg' data-modal-mismatch-ids='enter'>
                <form name='registration' method='post' class='header__form header__form_id_1 form form_theme_1' enctype='multipart/form-data'>
                    <h1 class='form__header'><?=$viewData['texts']['words']['aRegistration']?></h1>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['words']['aLogin']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='login' type='text' class='form__input' pattern='^[a-zA-Z0-9]{1,30}$' maxlength='30' required data-function='modal-opener' data-modal-relation-id='body-temporary-2' data-modal-switch-mode='focus' data-modal-insert-text='<?=$viewData['texts']['phrases']['patternOfLogin']?>'>
                    </label>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['words']['aPassword']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='password' type='password' class='form__input' pattern='^.{10,30}$' maxlength='30' required data-function='modal-opener' data-modal-relation-id='body-temporary-2' data-modal-switch-mode='focus' data-modal-insert-text='<?=$viewData['texts']['phrases']['patternOfPassword']?>'>
                    </label>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['phrases']['passwordRepeat']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='passwordRepeat' type='password' class='form__input' maxlength='30' required>
                    </label>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['words']['aName']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='name' type='text' class='form__input' maxlength='50' required>
                    </label>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['words']['aSurname']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='surname' type='text' class='form__input' maxlength='50' required>
                    </label>

                    <label class='header__form-label form__label form__label_required'>
                        <?=$viewData['texts']['phrases']['invitationCode']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='invitationCode' type='text' class='form__input' maxlength='50' required>
                    </label>

                    <div class='header__form-label header__form-label_id_1 form__field'>
                        <?=$viewData['texts']['phrases']['yourPhoto']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='photoOfOneself' type='file' class='header__file-input form__input' data-function='input-file-replaced'>
                        <button type='button' class='form__button button button_theme_1 button_size_global-2' data-function='input-file-replacement'>
                            <?=$viewData['texts']['words']['toSelect']?>
                        </button>&nbsp;&nbsp;
                        <span>(png / jpg / jpeg / gif, <?=$viewData['texts']['phrases']['notMore']?> 300 KB)&nbsp;</span>
                    </div>

                    <div class='header__form-field header__form-field_id_1 form__field'>
                        <span class='form__mark form__mark_type_required form__mark_theme_1'>*</span>&nbsp;
                        <?=$viewData['texts']['phrases']['meansRequiredFields']?>
                    </div>

                    <button type='submit' class='header__form-button header__form-button_id_1 form__button form__button_type_submit button button_theme_1 button_size_global-1'>
                        <?=$viewData['texts']['words']['toRegister']?>
                    </button>
                </form>
            </div>

            <div class='header__modal-area header__modal-area_type_1' data-function='modal-element' data-modal-relation-id='enter' data-modal-mismatch-ids='reg'>
                <form name='entrance' method='post' class='header__form header__form_id_2 form form_theme_1'>
                    <h1 class='form__header'><?=$viewData['texts']['words']['anEntrance']?></h1>

                    <label class='header__form-label form__label'>
                        <?=$viewData['texts']['words']['aLogin']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='login' type='text' class='form__input' maxlength='30' required>
                    </label>

                    <label class='header__form-label form__label'>
                        <?=$viewData['texts']['words']['aPassword']?>&nbsp;&nbsp;:&nbsp;&nbsp;
                        <input name='password' type='password' class='form__input' maxlength='30' required>
                    </label>

                    <button type='submit' class='form__button form__button_type_submit button button_theme_1 button_size_global-1'>
                        <?=$viewData['texts']['words']['toEnter']?>
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </header>