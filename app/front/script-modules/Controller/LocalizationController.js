class LocalizationController
{
    constructor(localizationModel)
    {
        this._localizationModel = localizationModel;
    }

    initialize()
    {
        let localeSetters = document.body.querySelectorAll('*[data-function~="locale-setter"]');
        localeSetters.forEach(localeSetter => {
            localeSetter.addEventListener('click', this._onClickLocaleSetter.bind(this));
        });
    }

    _onClickLocaleSetter(event)
    {
        let localeSetter = event.currentTarget;
        if (localeSetter.getAttribute('data-state') === 'active') {
            return;
        }
        if (!localeSetter.hasAttribute('data-locale')) {
            throw new AppError('Locale setter has not data-locale attribute.');
        }

        let locale = localeSetter.getAttribute('data-locale');
        this._localizationModel.setLocale(locale);
    }
}