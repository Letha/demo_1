let localizationModel = new LocalizationModel(),
    localizationController = new LocalizationController(localizationModel),
                
    modalElementsView = new ModalElementsView(),
    modalElementsController = new ModalElementsController(modalElementsView),
                
    authorizationModel = new AuthorizationModel(),
    authorizationController = new AuthorizationController(authorizationModel, modalElementsView),
    
    initialionView = new InitiationView(),
    initiationController = new InitiationController(initialionView);

[
    initiationController, 
    localizationController, 
    modalElementsController, 
    authorizationController
].forEach(controller => {
    controller.initialize();
});