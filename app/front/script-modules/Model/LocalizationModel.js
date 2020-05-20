class LocalizationModel
{
    constructor()
    {
        this._existingLocales = ['en', 'ru'];
    }

    async setLocale(locale)
    {
        if (!this._existingLocales.includes(locale)) {
            throw new AppError('Trying to set nonexistent language.');
        }
                    
        let fetchResult = await fetch(`/api/config/localization/language/${locale}`);
        if (fetchResult.status === 200) {
            document.location.reload(true);
        }
    }
}