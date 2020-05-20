class ModalElementsController
{
    constructor(modalElementsView)
    {
        this._modalElementsView = modalElementsView;
    }

    initialize()
    {
        let modalClickSwitchers = document.body.querySelectorAll('*[data-function~="modal-opener"][data-modal-switch-mode="click-switcher"]'),
            modalFocusOpeners = document.body.querySelectorAll('*[data-function~="modal-opener"][data-modal-switch-mode="focus"]');
        modalClickSwitchers.forEach(modalOpener => {
            modalOpener.addEventListener('click', this._onUseModalSwitcher.bind(this));
        });
        modalFocusOpeners.forEach(modalOpener => {
            modalOpener.addEventListener('focus', this._onUseModalOpener.bind(this));
            modalOpener.addEventListener('blur', this._onUseModalBreaker.bind(this));
        });
    }

    _onUseModalSwitcher(event)
    {
        if (event.currentTarget.getAttribute('data-state') === 'active') {
            this._onUseModalBreaker(event);
        } else {
            this._onUseModalOpener(event);
        }
    }

    _onUseModalOpener(event)
    {
        if (event.currentTarget.getAttribute('data-state') !== 'active') {
            if (!event.currentTarget.hasAttribute('data-modal-relation-id')) {
                throw new AppError('Modal opener has not data-modal-relation-id attribute.');
            }
            if (event.currentTarget.hasAttribute('data-modal-insert-text')) {
                this._modalElementsView.renderWithModalElement(event.currentTarget, null, event.currentTarget.getAttribute('data-modal-insert-text'));
            } else {
                this._modalElementsView.renderWithModalElement(event.currentTarget);
            }
        }
    }

    _onUseModalBreaker(event)
    {
        if (event.currentTarget.getAttribute('data-state') === 'active') {
            if (!event.currentTarget.hasAttribute('data-modal-relation-id')) {
                throw new AppError('Modal breaker has not data-modal-relation-id attribute.');
            }
            this._modalElementsView.renderWithoutModalElement(event.currentTarget);
        }
    }
}