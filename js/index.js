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

        // Register open events
        if (document.querySelector('.js-open-' + modal.id)) {
            document.querySelectorAll('.js-open-' + modal.id).forEach((el) => {
                el.addEventListener('click', modal.openListener);
            })
        }

        if (modal.isClosable) {
            // Register close events
            el.querySelectorAll('.js-close-modal').forEach((el) => {
                el.addEventListener('click', modal.closeListener);
            });

            // Register click away close events
            document.querySelector('html').addEventListener('click', modal.clickAwayListener);
            el.querySelector('.modal-default').addEventListener('click', modal.innerClickListener);
        }

        if (modal.isAsync) {
            modal.form.addEventListener('submit', modal.submitListener);
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
