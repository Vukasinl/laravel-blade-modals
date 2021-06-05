import './polyfills/index';
import Modal from './Modal';

window.modals = [];

initializeModals();

function initializeModals() {
    document.querySelectorAll('[data-modal]').forEach(function (el) {
        let modal = new Modal(
            el.getAttribute('data-modal'),
            {
                isAsync: el.getAttribute('data-async') ? true : false,
                isClosable: el.getAttribute('data-closable') ? true : false,
                url: el.getAttribute('data-url')
            }
        );

        modal.registerListeners();

        if (el.getAttribute('data-start-opened')) {
            modal.open();
        }

        window.modals.push(modal);
    });
}

function removeModalListeners() {
    while (window.modals.length) {
        let modal = window.modals.shift();
        modal.removeListeners();
    }
}

// Reinitialize modals on livewire reload
document.addEventListener("livewire:load", function (event) {
    // setTimeout so that modals are not reinitalized on the first livewire load
    setTimeout(() => {
        window.livewire.hook('message.processed', (message, component) => {
            removeModalListeners();
            initializeModals();
        });
    }, 1000);
});
