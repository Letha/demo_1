class AuthorizationController
{
    constructor(authorizationModel, modalElementsView)
    {
        this._authorizationModel = authorizationModel;
        this._modalElementsView = modalElementsView;
    }

    initialize()
    {
        let entranceForm = document.body.querySelector('form[name="entrance"]'),
            registrationForm = document.body.querySelector('form[name="registration"]'),
            exitForm = document.body.querySelector('form[name="exit"]');

        if (entranceForm !== null) {
            entranceForm.addEventListener('submit', this._enter.bind(this));
        }
        if (registrationForm !== null) {
            registrationForm.addEventListener('submit', this._register.bind(this));
        }
        if (exitForm !== null) {
            exitForm.addEventListener('submit', this._exit.bind(this));
        }
    }

    async _enter(event)
    {
        event.preventDefault();

        let submitButton = this._getSubmitButton(event.currentTarget);
        submitButton.setAttribute('data-state', 'wait');
        
        let formDataObject = new FormData(event.currentTarget),
            fetchResult = await this._authorizationModel.enter(formDataObject);
        this._processCommonAnswer(fetchResult.status);
        submitButton.removeAttribute('data-state');
    }

    async _register(event)
    {
        event.preventDefault();
        
        let submitButton = this._getSubmitButton(event.currentTarget);
        submitButton.setAttribute('data-state', 'wait');
        
        let formDataObject = new FormData(event.currentTarget),
            fileObjectOfPhotoOfOneself = formDataObject.get('photoOfOneself'),
            topModalElement = document.querySelector('.body__temporary-area_id_1'),
            showModalElementTime = 4000;

        // verify password repeat
        if (event.currentTarget.elements['password'].value !== event.currentTarget.elements['passwordRepeat'].value) {
            this._modalElementsView.renderWithModalElement(topModalElement, showModalElementTime, app.texts.phrases.passwordRepeatIsIncorrect);
            submitButton.removeAttribute('data-state');
            return;
        }

        // if there is a file in the form
        if (fileObjectOfPhotoOfOneself !== '' && typeof fileObjectOfPhotoOfOneself.name !== 'undefined') {
            // verify form image file
            if (!this._whetherRegistrationImageFileUsesAllowedExtention(fileObjectOfPhotoOfOneself)) {
                this._modalElementsView.renderWithModalElement(topModalElement, showModalElementTime, app.texts.phrases.unallowedImageFileExtension);
                submitButton.removeAttribute('data-state');
                return;
            }
            if (!this._isRegistrationImageFileSizeNotMoreThanAllowed(fileObjectOfPhotoOfOneself)) {
                this._modalElementsView.renderWithModalElement(topModalElement, showModalElementTime, app.texts.phrases.imageFileSizeMoreAllowed);
                submitButton.removeAttribute('data-state');
                return;
            }

            // rename form image file to 'f.{extention}'
            formDataObject.set('photoOfOneself', fileObjectOfPhotoOfOneself, fileObjectOfPhotoOfOneself.name.replace(/^.*(\..+)$/, 'f$1'));
        }

        // try to register and show result
        let registrationFetchResult = await this._authorizationModel.register(formDataObject);            
        this._processCommonAnswer(registrationFetchResult.status);
        switch (registrationFetchResult.status) {
            case 403:
                this._modalElementsView.renderWithModalElement(topModalElement, showModalElementTime, app.texts.phrases.textOfError403ForRegistration);
                break;
            case 409:
                this._modalElementsView.renderWithModalElement(topModalElement, showModalElementTime, app.texts.phrases.textOfError409ForRegistration);
                break;
        }
        submitButton.removeAttribute('data-state');
    }

    async _exit(event)
    {
        event.preventDefault();
        
        let submitButton = this._getSubmitButton(event.currentTarget);
        submitButton.setAttribute('data-state', 'wait');

        let fetchResult = await this._authorizationModel.exit();
        this._processCommonAnswer(fetchResult.status);
        submitButton.removeAttribute('data-state');
    }

    _getSubmitButton(formElement)
    {
        return formElement.querySelector('[type="submit"]');
    }

    _processCommonAnswer(responseCode)
    {
        let modalElement = document.querySelector('.body__temporary-area_id_1'),
            showModalElementTime = 4000;

        switch (responseCode) {
            case 200:
                document.location.reload(true);
                break;
            case 400:
                this._modalElementsView.renderWithModalElement(modalElement, showModalElementTime, app.texts.phrases.textOfError400);
                break;
            case 500:
                this._modalElementsView.renderWithModalElement(modalElement, showModalElementTime, app.texts.phrases.textOfError500);
                break;
        } 
    }

    _whetherRegistrationImageFileUsesAllowedExtention(fileObject)
    {
        let allowedImageExtentions = [
            'jpg', 'jpeg', 'png', 'gif',
        ];
        for (let allowedExtention of allowedImageExtentions) {
            if (fileObject.name.match(new RegExp(`\.${allowedExtention}$`)) !== null) {
                return true;
            }
        }
        return false;
    }
    
    _isRegistrationImageFileSizeNotMoreThanAllowed(fileObject)
    {
        // 300 KB
        let allowedFileSize = 307200;
        if (fileObject.size > allowedFileSize) {
            return false;
        }
        return true;
    }
}