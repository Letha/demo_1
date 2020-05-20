<!DOCTYPE html>
<html lang='ru'>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=Edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

        <style>
            @font-faсe {
                font-family: 'Noto Sans';
                src: url('/front/fonts/NotoSans-Regular.ttf'); 
            }
            * {
                box-sizing: border-box;
            }
            *:focus {
                outline: none;
                border: none;
            }
            *::-moz-focus-inner {
                outline: none;
                border: none;
            }
            html,
            body {
                margin: 0;
                height: 100%;
            }
            html {
                font-size: 16px;
            }

            h1,
            button,
            input {
                margin: 0;
                padding: 0;
                border: none;
                background: none;
                font-family: inherit;
                font-style: inherit;
                font-weight: inherit;
                font-size: inherit;
                color: inherit;
            }

            .body_theme_1 {
                display: flex;
                flex-direction: column;
                /*background: #000a19;*/
                background: #001719;

                font-family: 'Noto Sans', sans-serif;
                color: aliceblue;
            }

            .figure_theme_1 {
                position: relative;
                display: grid;
                grid-template-rows: auto 17px 17px auto;
                grid-template-columns: auto 17px 17px auto;
                justify-content: center;
                align-content: center;
            }
            .figure_theme_1 > div:nth-child(1),
            .figure_theme_1 > div:nth-child(2),
            .figure_theme_1 > div:nth-child(3),
            .figure_theme_1 > div:nth-child(4) {
                width: 20px;
                height: 20px;
                border: 1px solid #fff;
            }
            .figure_theme_1 > div:nth-child(1) {
                grid-area: 1 / 1 / 4 / 4;
                align-self: end;
                justify-self: end;
            }
            .figure_theme_1 > div:nth-child(2) {
                grid-area: 1 / 3 / 3 / 5;
            }
            .figure_theme_1 > div:nth-child(3) {
                grid-area: 2 / 2 / 5 / 5;
            }
            .figure_theme_1 > div:nth-child(4) {
                grid-area: 3 / 1 / 5 /3;
            }
            .figure_theme_1 > div:nth-child(5) {
                z-index: 10;
                position: absolute;
                top: calc(50% - 4px);
                left: calc(50% - 4px);
                height: 8px;
                width: 8px;
                box-shadow: 0px 0px 10px 4px #17131c;
                border-radius: 50%;
                border: 1px solid #f3fdff;
            }

            .header_theme_1 {
                padding: 0 0 0 10px;
                display: flex;
                border-top: 1px solid #2a4642;
                border-bottom: 1px solid #2a4642;
            }
            .header_theme_1 .header__figure_id_1 {
                margin: 10px 10px 10px 0;
            }
            .header_theme_1 .header__menu .header__menu-item {
                margin: -1px 0;
            }
            .header_theme_1 .header__menu_id_1 {
                margin-left: auto;
            }
            @media (max-width: 639.9px) {
                .header_theme_1 {
                    padding: 20px 0;
                    flex-direction: column;
                    align-items: center;
                }
                .header_theme_1 > * + * {
                    margin-top: 20px;
                }
                .header_theme_1 .header__figure_id_1 {
                    margin: 0;
                }
                .header_theme_1 .header__menu_id_1 {
                    margin-left: 0;
                }
                .header_theme_1 .header__menu_id_1 .header__menu-item {
                    margin: 0;
                }
            }

            .menu_theme_1 {
                display: flex;
            }
            .menu_theme_1 .menu__item {
                cursor: pointer;
                padding: 10px;
            }
            .menu_theme_1 .menu__item:hover {
                transition: background-color 600ms;
                background: #2a4642;
            }
            .menu_theme_1 .menu__item[data-state~='active'] {
                cursor: auto;
                border-top: 1px dashed #91ffef;
                border-bottom: 1px dashed #91ffef;
            }
            .menu_theme_1 .menu__item[data-state~='active']:hover {
                transition: none !important;
                background: none !important;
            }
            @media (min-width: 600px) {
                .menu_theme_1 .menu__item:focus {
                    transition: background-color 600ms;
                    background: #2a4642;
                }
            }

            .content_page_index {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .content_page_index .content__modal-area_id_1,
            .content_page_index .content__modal-area_id_2 {
                display: none;
            }
            .content_page_index .content__modal-area_id_1[data-state~='active'],
            .content_page_index .content__modal-area_id_2[data-state~='active'] {
                overflow: auto;
                position: fixed;
                top: 62px;
                left: 0;
                bottom: 0;
                right: 0;
                display: flex;
                flex-direction: column;
            }

            .content_page_index .content__form_id_1 {
                margin: auto 0;
                padding: 40px 0 40px 100px;
            }
            .content_page_index .content__form_id_1 .content__form-label:nth-of-type(2) {
                margin-left: 15px;
            }
            .content_page_index .content__form_id_1 .content__form-label:nth-of-type(3) {
                margin-left: 30px;
            }
            .content_page_index .content__form_id_1 .content__form-label_id_1 {
                margin-left: 15px;
                grid-area: 5 / 1 / 6 / 3;
            }
            .content_page_index .content__form_id_1 .content__form-field_id_1 {
                grid-area: 6 / 1 / 7 / 3;
            }
            .content_page_index .content__form_id_1 .content__form-button_id_1 {
                grid-area: 7 / 1 / 8 / 3;
            }

            .content_page_index .content__form_id_2 {
                margin: auto 0;
                padding: 40px 0 40px 100px;
                grid-auto-flow: row;
            }
            @media (max-width: 1023.9px) {
                .content_page_index {
                    font-size: 14px;
                }

                .content_page_index .content__modal-area_id_1[data-state~='active'],
                .content_page_index .content__modal-area_id_2[data-state~='active'] {
                    top: 208px;
                }
                .content_page_index .content__form_id_1 {
                    padding-left: 0;
                }
                .content_page_index .content__form_id_1 .content__form-label,
                .content_page_index .content__form_id_1 .content__form-field,
                .content_page_index .content__form_id_2 .content__form-label {
                    margin-left: 24px !important;
                }
                .content_page_index .content__form_id_1 .content__form-label_id_1,
                .content_page_index .content__form_id_1 .content__form-field_id_1,
                .content_page_index .content__form_id_1 .content__form-button_id_1 {
                    grid-area: auto;
                }

                .content_page_index .content__form_id_2 {
                    padding-left: 0;   
                }
            }

            .form_theme_1 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-auto-flow: column;
                column-gap: 30px; 
            }
            .form_theme_1 .form__label {
                cursor: pointer;
                position: relative;
                margin-top: 20px;
                padding-bottom: 10px;
                display: inline-flex;
                flex-wrap: wrap;
                align-items: center;
                border-bottom: 1px solid #2a4642;
                white-space: nowrap;
            }
            .form_theme_1 .form__label_required:before {
                position: absolute;
                top: 0;
                left: -14px;
                content: '*';
                color: #91ffef;
            }
            .form_theme_1 .form__label:hover {
                transition: border-bottom-color 600ms;
                border-bottom-color: #509187;
            }
            .form_theme_1 .form__label:focus-within {
                transition: border-bottom-color 600ms;
                border-bottom-color: #509187;
            }
            .form_theme_1 .form__field {
                margin-top: 20px;
                padding-bottom: 10px;
                display: inline-flex;
                flex-wrap: wrap;
                align-items: center;
                border-bottom: 1px solid #2a4642;
                white-space: nowrap;
            }
            .form_theme_1 .form__input {
                padding: 0 0 0 10px;
                flex-grow: 1;
            }

            .form_theme_1 .form__mark_type_required.form__mark_theme_1 {
                color: #91ffef;
            }
            .form_theme_1 .form__mark_type_inline.form__mark_theme_1 {
                margin-left: 10px;
            }

            .form_theme_1 .form__header {
                margin-bottom: 40px;
                grid-column: span 2;
                justify-self: center;
                font-size: 1.5em;
            }
            .form_theme_1 .form__button_type_submit {
                margin-top: 40px;
                grid-column: span 2;
                justify-self: center;
            }
            .form_theme_1 .form__button_style_inline {
                margin: 0 10px;
            }

            @media (max-width: 1023.9px) {
                .form_theme_1 {
                    grid-auto-flow: row;
                    grid-template-columns: auto;
                }
                .form_theme_1 .form__mark_type_inline.form__mark_theme_1 {
                    display: none;
                }

                .form_theme_1 .form__header {
                    grid-column: span 1;
                }
                .form_theme_1 .form__field {
                    /*display: flex;
                    flex-direction: column;
                    align-items: flex-start;*/
                }
                .form_theme_1 .form__input {
                    padding: 10px 0 0 0;
                    flex-basis: 100%;
                }

                .form_theme_1 .form__button_type_submit {
                    grid-area: span 1;
                }
                .form_theme_1 .form__button_style_inline {
                    /*margin: 0;*/
                }
            }

            .button_theme_1 {
                cursor: pointer;
                border-top: 1px solid #2a4642;
                border-bottom: 1px solid #2a4642;
                border-top-right-radius: 100px 10px;
                border-bottom-left-radius: 100px 10px;
            }
            .button_theme_1:hover {
                transition: border-bottom-color 600ms, border-top-color 600ms;
                border-top-color: #509187;
                border-bottom-color: #509187;
            }
            .button_theme_1[data-state~='wait'] {
                background: no-repeat center / 32px url('/front/img/loaders/demilune.svg');
                color: transparent;
            }

            .button_size_global-1 {
                padding: 10px 0;
                min-width: 300px;
            }
            .button_size_global-2 {
                padding: 10px 20px;
            }
            @media (min-width: 600px) {
                .button_theme_1:focus {
                    transition: border-bottom-color 600ms, border-top-color 600ms;
                    border-top-color: #509187;
                    border-bottom-color: #509187;
                }
            }
        </style>
    </head>
    <body class='body body_theme_1'>
        <header class='header header_theme_1'>
            <div class='header__figure header__figure_id_1 figure_theme_1'>
                <div></div> <div></div> <div></div> <div></div> <div></div>
            </div>
            <div class='header__menu menu menu_theme_1'>
                <button class='header__menu-item menu__item' data-function='language-setter' data-language='en' data-state='active'>EN</button>
                <button class='header__menu-item menu__item' data-function='language-setter' data-language='ru'>RU</button>
            </div>
            <div class='header__menu header__menu_id_1 menu menu_theme_1'>
                <button class='header__menu-item menu__item' data-function='modal-opener' data-modal-opener-id='enter' data-modal-opener-mismatch-id='reg' data-state='active'>entrance</button>
                <button class='header__menu-item menu__item' data-function='modal-opener' data-modal-opener-id='reg' data-modal-opener-mismatch-id='enter'>registrarion</button>
            </div>
        </header>
        <section class='content content_page_index'>
            <div class='content__modal-area content__modal-area_id_1' data-function='modal-element' data-modal-opener-id='reg' data-modal-opener-mismatch-id='enter'>
            <form class='content__form content__form_id_1 form form_theme_1'>
                <h1 class='form__header'>registration</h1>
                <label class='content__form-label form__label form__label_required'>login<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <label class='content__form-label form__label form__label_required'>password<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <label class='content__form-label form__label form__label_required'>password repeat<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <label class='content__form-label form__label form__label_required'>name<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <label class='content__form-label form__label form__label_required'>surname<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <label class='content__form-label form__label form__label_required'>birth date<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='text' class='form__input'></label>
                <div class='content__form-label content__form-label_id_1 form__field'>Your photo<span class='form__mark_type_inline form__mark_theme_1'>:</span><input type='file' class='form__input' style='display:none;'><button type='button' class='form__button form__button_style_inline button button_theme_1 button_size_global-2'>select</button><span>(png / jpg / jpeg / gif, not more 300 KB) </span></div>
                 <script> document.body.querySelector('.button_size_global-2').addEventListener('click', function(event) { let ev = new MouseEvent("click"); document.body.querySelector('input[type="file"]').dispatchEvent(ev); }); document.body.querySelector('input[type="file"]').addEventListener('change', function(event) { document.body.querySelector('.button_size_global-2').innerHTML = '&#10003; | select other'; /*event.currentTarget.innerHTML = this.files[0].name;*/ console.log(this.files[0].name); });</script>
                <div class='content__form-field content__form-field_id_1 form__field'><span class='form__mark form__mark_type_required form__mark_theme_1'>*</span> means required fields</div>
                <button type='submit' class='content__form-button content__form-button_id_1 form__button form__button_type_submit button button_theme_1 button_size_global-1'>register</button>
            </form>
            </div>
            <div class='content__modal-area content__modal-area_id_2' data-function='modal-element' data-modal-opener-id='enter' data-modal-opener-mismatch-id='reg' data-state='active' data-state='active'>
            <form class='content__form content__form_id_2 form form_theme_1'>
                <h1 class='form__header'>entrance</h1>
                <label class='content__form-label form__label'>login :<input type='text' class='form__input'></label>
                <label class='content__form-label form__label'>password :<input type='text' class='form__input'></label>
                <button type='submit' class='form__button form__button_type_submit button button_theme_1 button_size_global-1'>enter</button>
            </form>
            </div>
        </section>
        <script>
            'use strict';

            /* 1 try

            // mixins

            // observers realise update method
            let observableMixin = {
                subscribers: [],
                subscribe: function(subscriber) {
                    this.subscribers.push(subscriber);
                },
                warnSubscribers: function(warning) {
                    this.subscribers.forEach(element => {
                        element.update(warning);
                    });
                },
            };
            let observerMixin = {
                update: function(descriptors) {
                    descriptors.forEach(descriptor => {
                        let functionName = 'update' + descriptor.parameter[0].toUpperCase() +  descriptor.parameter.slice(1),
                            funct = this[functionName];
                        if (funct) {
                            funct.apply(this, [descriptor]);
                        }
                    });
                },
            };

            // domain

            class StateStorage {
                constructor() {
                    this.state = {
                        language: 'ru',
                        activeModalElements: ['entrance'],
                    };
                }
                getState() {
                    return this.state;
                }
            };

            class StateModel {
                constructor(stateStorage) {
                    this.stateStorage = stateStorage;
                    this.state = stateStorage.getState();
                }
            }

            class Controller {
                constructor(model) {
                    this.model = model;
                }
            }

            class LocalizationModel extends StateModel {
                getLanguage() {
                    return this.state.language;
                }
                setLanguage(value) {
                    this.state.language = value;
                    this.warnSubscribers([{parameter: 'language', value: this.state.language}]);
                }
            };
            Object.assign(LocalizationModel.prototype, observableMixin);

            class LocalizationView {
                /*update(descriptors) {
                    descriptors.forEach(descriptor => {
                        if (descriptor.parameter === 'language') {
                            this.updateLanguage(descriptor);
                        }
                    });
                } -/
                updateLanguage(descriptor) {
                    let 
                        body = document.body,
                        languageSetters = body.querySelectorAll('.f-language-setter'),
                        languageSettersRu = body.querySelectorAll('.f-language-setter_set_ru'),
                        languageSettersEn = body.querySelectorAll('.f-language-setter_set_en');

                    languageSetters.forEach(setter => {
                        setter.classList.remove('f-state_active');
                    });

                    switch (descriptor.value) {
                        case 'ru':
                            languageSettersRu.forEach(setter => {
                                setter.classList.add('f-state_active');
                            });
                            break;
                        case 'en':
                            languageSettersEn.forEach(setter => {
                                setter.classList.add('f-state_active');
                            });
                            break;
                    }
                }
            }
            Object.assign(LocalizationView.prototype, observerMixin);

            class LocalizationController extends Controller {
                initialize() {
                    document.documentElement.querySelectorAll('.f-language-setter').forEach(element => {
                        element.addEventListener('click', this.onClickLanguageSetter.bind(this));
                    });
                }
                onClickLanguageSetter(event) {
                    if (event.currentTarget.classList.contains('f-language-setter_set_ru') && this.model.getLanguage() !== 'ru') {
                        this.model.setLanguage('ru');
                    }
                    if (event.currentTarget.classList.contains('f-language-setter_set_en') && this.model.getLanguage() !== 'en') {
                        this.model.setLanguage('en');
                    }
                }
            }

            ///////////////////////////////////////

            class ModalElementsModel extends StateModel {
                setModalElementActiveness(modalElementId, whetherSetActive) {
                    let modalElements = this.state.modalElements;
                    if (this.haveModalElement(modalElementId)) {
                        let modalElement = this.getModalElement(modalElementId);
                        if (!modalElement) {
                            throw new Exception('No such modal element.');
                        }
                        modalElement.active = whetherSetActive;
                        this.warnSubscribers([{parameter: modalElement.modalElementId, active: modalElement.active}]);
                    }
                }

                getModalElement(id) {
                    let modalElements = this.state.modalElements;

                    for (let i = 0; i < modalElements.length; i++) {
                        if (modalElements[i].id === id) {
                            return modalElements[i];
                        }
                    }
                    return null;
                }
                haveModalElement(id) {
                    let modalElements = this.state.modalElements,
                        haveModalElement = false;

                    for (let i = 0; i < modalElements.length; i++) {
                        if (modalElements[i].id === id) {
                            haveModalElement = true;
                            break;
                        }
                    }
                    return haveModalElement;
                }
            };
            Object.assign(ModalElementsModel.prototype, observableMixin);

            class ModalElementsView {
                updateEntrance(descriptor) {
                    let 
                        body = document.body,
                        languageSetters = body.querySelectorAll('.f-language-setter'),
                        languageSettersRu = body.querySelectorAll('.f-language-setter_set_ru'),
                        languageSettersEn = body.querySelectorAll('.f-language-setter_set_en');
                    languageSetters.forEach(setter => {
                        setter.classList.remove('f-state_active');
                    });
                    switch (descriptor.value) {
                        case 'ru':
                            languageSettersRu.forEach(setter => {
                                setter.classList.add('f-state_active');
                            });
                            break;
                        case 'en':
                            languageSettersEn.forEach(setter => {
                                setter.classList.add('f-state_active');
                            });
                            break;
                    }
                }
            }

            class ModalElementsController extends Controller {
                initialize() {
                    document.documentElement.querySelectorAll('.f-language-setter').forEach(element => {
                        element.addEventListener('click', this.onClickLanguageSetter.bind(this));
                    });
                }
                onClickLanguageSetter(event) {
                    if (event.currentTarget.classList.contains('f-language-setter_set_ru') && this.model.getLanguage() !== 'ru') {
                        this.model.setLanguage('ru');
                    }
                    if (event.currentTarget.classList.contains('f-language-setter_set_en') && this.model.getLanguage() !== 'en') {
                        this.model.setLanguage('en');
                    }
                }
            }

            ///////////////////////////////////////

            let stateStorage = new StateStorage();

            let
                localizationModel = new LocalizationModel(stateStorage),
                localizationView = new LocalizationView(),
                localizationController = new LocalizationController(localizationModel);

            localizationModel.subscribe(localizationView);
            localizationController.initialize();*/

            // 2 try

            class AppError extends Error {
                constructor(message) {
                    super(message);
                    this.name = 'AppError';
                }
            }

            class LocalizationController {
                constructor(model, view) {
                    this.model = model;
                    this.view = view;
                    this.existingLanguages = ['en', 'ru'];
                }
                initialize() {
                    let languageSetters = document.body.querySelectorAll('*[data-function~="language-setter"]');
                    languageSetters.forEach(languageSetter => {
                        languageSetter.addEventListener('click', this._onClickLanguageSetter.bind(this));
                    });
                }
                _onClickLanguageSetter(event) {
                    let languageSetter = event.currentTarget;
                    if (!languageSetter.hasAttribute('data-language')) {
                        throw new AppError('Language setter has not data-language attribute.');
                    }

                    let language = languageSetter.getAttribute('data-language');
                    if (!this.existingLanguages.includes(language)) {
                        throw new AppError('Trying to set nonexistent language.');
                    }

                    this.model.setLanguage(language);

                    this.view.renderWithLanguage(language);
                }
            }
            class LocalizationView {
                renderWithLanguage(language) {
                    let body = document.body,
                        currentLanguageSetters = body.querySelectorAll(`*[data-function~="language-setter"][data-language="${language}"]`),
                        previousLanguageSetters = body.querySelectorAll(`*[data-function~="language-setter"]:not([data-language="${language}"])`);

                    this._deactivateElements(previousLanguageSetters);
                    this._activateElements(currentLanguageSetters);
                }

                // htmlElements
                _activateElements(elements) {
                    elements.forEach(element => {
                        if (element.getAttribute('data-state') !== 'active') {
                            element.setAttribute('data-state', 'active');
                        }
                    });
                }
                _deactivateElements(elements) {
                    elements.forEach(element => {
                        if (element.getAttribute('data-state') === 'active') {
                            element.removeAttribute('data-state');
                        }
                    });
                }
            }

            class ModalElementsController {
                constructor(view) {
                    this.view = view;
                }
                initialize() {
                    let modalOpeners = document.body.querySelectorAll('*[data-function~="modal-opener"]');
                    modalOpeners.forEach(modalOpener => {
                        modalOpener.addEventListener('click', this._onClickModalOpener.bind(this));
                    });
                }
                _onClickModalOpener(event) {
                    let modalOpener = event.currentTarget;
                    if (modalOpener.getAttribute('data-state') !== 'active') {
                        if (!modalOpener.hasAttribute('data-modal-opener-id')) {
                            throw new AppError('Modal opener has not data-modal-opener-id attribute.');
                        }
                        this.view.renderWithModalElement(modalOpener);
                    }
                }
            }
            class ModalElementsView {
                // htmlElement
                renderWithModalElement(activationElement) {
                    let body = document.body,
                        modalRelationId = activationElement.getAttribute('data-modal-opener-id'),
                        mismatchId = activationElement.getAttribute('data-modal-opener-mismatch-id'),
                        mismatchElements = body.querySelectorAll(`*[data-function~="modal-element"][data-modal-opener-id~="${mismatchId}"], *[data-function~="modal-opener"][data-modal-opener-id~="${mismatchId}"]`),
                        modalCurrentElement = body.querySelector(`*[data-function~="modal-element"][data-modal-opener-id="${modalRelationId}"]`);

                    mismatchElements.forEach(mismatchElement => {
                        if (mismatchElement.getAttribute('data-state') === 'active') {
                            mismatchElement.removeAttribute('data-state');
                        }
                    });

                    activationElement.setAttribute('data-state', 'active');
                    modalCurrentElement.setAttribute('data-state', 'active');
                    modalCurrentElement.scrollTo(0, 0);
                }
            }

            let localizationView = new LocalizationView(),
                localizationController = new LocalizationController(localizationView),
                
                modalElementsView = new ModalElementsView(),
                modalElementsController = new ModalElementsController(modalElementsView);

            localizationController.initialize();
            modalElementsController.initialize();

        </script>
    </body>
</html>