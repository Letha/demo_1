class ModalElementsView
{
    /* 
     * modalSystemElement can be HTML element with "modal-opener" or "modal-element" function (data-function attribute).
     * modal-opener is intended to open modal-element.
     */
    renderWithModalElement(modalSystemElement, showTime = null, insertText = null)
    {
        let body = document.body,
            modalRelationId = modalSystemElement.getAttribute('data-modal-relation-id'),
            modalOpenerElement = null,
            modalElement = null,
            mismatchIds = null,
            mismatchElements = [];

        let modalSysElementFunctions = this._getElementFunctions(modalSystemElement);
        if (modalSysElementFunctions.includes('modal-element')) {
            modalElement = modalSystemElement;
            mismatchIds = modalElement.getAttribute('data-modal-mismatch-ids');
            modalOpenerElement = body.querySelector(`*[data-function~="modal-opener"][data-modal-relation-id="${modalRelationId}"]`);

        } else if (modalSysElementFunctions.includes('modal-opener')) {
            modalOpenerElement = modalSystemElement;
            modalElement = body.querySelector(`*[data-function~="modal-element"][data-modal-relation-id="${modalRelationId}"]`);
            mismatchIds = modalElement.getAttribute('data-modal-mismatch-ids');
        }

        if (mismatchIds !== null && mismatchIds !== '') {
            if (mismatchIds === '_all') {
                mismatchElements = body.querySelectorAll('*[data-function~="modal-element"], *[data-function~="modal-opener"]');
            } else {

                mismatchIds = mismatchIds.split(' ').filter((element) => element === '' ? false : true);
                mismatchIds.forEach((mismatchId) => {
                    mismatchElements = 
                        mismatchElements.concat(Array.from(body.querySelectorAll(
                            `*[data-function~="modal-element"][data-modal-relation-id~="${mismatchId}"], *[data-function~="modal-opener"][data-modal-relation-id~="${mismatchId}"]`
                        )));
                });
            }
        }
        
        mismatchElements.forEach(mismatchElement => {
            if (mismatchElement.getAttribute('data-state') === 'active') {
                mismatchElement.removeAttribute('data-state');
            }
        });

        if (insertText !== null) {
            modalElement.innerText = insertText;
        }

        if (modalOpenerElement !== null) {
            modalOpenerElement.setAttribute('data-state', 'active');
        }

        modalElement.setAttribute('data-state', 'active');
        modalElement.scrollTop = 0;

        if (showTime !== null) {
            setTimeout(() => {
                this.renderWithoutModalElement(modalSystemElement);
            }, showTime);
        }
    }

    /* 
     * modalSystemElement can be HTML element with "modal-opener" or "modal-element" function (data-function attribute).
     * modal-opener is intended to open modal-element.
     */
    renderWithoutModalElement(modalSystemElement)
    {
        let body = document.body,
            modalRelationId = modalSystemElement.getAttribute('data-modal-relation-id'),
            modalSysElementFunctions = this._getElementFunctions(modalSystemElement),
            modalElement = null,
            modalOpenerElement = null;
        if (modalSysElementFunctions.includes('modal-element')) {
            modalElement = modalSystemElement;
            modalOpenerElement = body.querySelector(`*[data-function~="modal-opener"][data-modal-relation-id="${modalRelationId}"]`);

        } else if (modalSysElementFunctions.includes('modal-opener')) {
            modalOpenerElement = modalSystemElement;
            modalElement = body.querySelector(`*[data-function~="modal-element"][data-modal-relation-id="${modalRelationId}"]`);
        }

        if (modalOpenerElement !== null) {
            modalOpenerElement.removeAttribute('data-state');
        }
        modalElement.removeAttribute('data-state');
    }

    _getElementFunctions(element)
    {
        return element.getAttribute('data-function')
            .split(' ').filter((arrayElement) => arrayElement === '' ? false : true);
    }
}