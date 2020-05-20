app.includeScript = (src, async = false, defer = false) => {
    let scriptElement = document.createElement('script');
    scriptElement.src = src;
    scriptElement.async = async;
    scriptElement.defer = defer;
    document.head.prepend(scriptElement);
};
app.includeModuleScript = function(modulePath, async = false, defer = false) {
    let scriptPath = '/front/script-modules/' + modulePath + '.js';
    this.includeScript(scriptPath, async, defer);
};

[
    'GlobalModule/Error/AppError',

    'Model/AuthorizationModel',
    'Model/LocalizationModel',

    'View/ModalElementsView',
    'View/InitiationView',

    'Controller/LocalizationController',
    'Controller/ModalElementsController',
    'Controller/AuthorizationController',
    'Controller/InitiationController',

].forEach(modulePath => {
    app.includeModuleScript(modulePath);
});

app.includeScript('/front/initialize.js');