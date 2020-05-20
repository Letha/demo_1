class InitiationController
{
    constructor(initiationView)
    {
        this._initiationView = initiationView;
    }

    initialize()
    {
        this._initializeInputFileReplacements();
        this._initiationView.initialize();
    }

    _initializeInputFileReplacements()
    {
        let inputReplacement = document.body.querySelector('*[data-function="input-file-replacement"]');
        if (inputReplacement !== null) {
            inputReplacement.addEventListener('click', function(event) { 
                let clickEvent = new MouseEvent("click"); 
                document.body.querySelector('*[data-function="input-file-replaced"]').dispatchEvent(clickEvent); 
            }); 
        }

        let inputReplaced = document.body.querySelector('*[data-function="input-file-replaced"]');
        if (inputReplaced !== null) {
            inputReplaced.addEventListener('change', function(event) {
                let checkMark = '&#10003;';
                document.body.querySelector('*[data-function="input-file-replacement"]').innerHTML = `${checkMark} | ${app.texts.phrases.selectOther}`; 
            });
        }
    }
}