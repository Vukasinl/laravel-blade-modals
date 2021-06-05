export default class Modal {
    constructor(id, options = null) {
        this.id = id;
        this.el = document.querySelector(`[data-modal="${id}"]`);
        this.form = document.querySelector(`#modal-form-${id}`);
        this.submitBtn = document.querySelector(`#submit-modal-form-${id}`);
        this.isAsync = options.isAsync ? true : false;
        this.isClosable = options.isClosable ? true : false;
        this.opened = false;
        this.shouldRemoveClasses = true;
        this.url = options.url ? options.url : '';
        this.message = '';
    }

    setUrl(value) {
        if (!value) {
            return;
        }
        this.url = value;
        this.el.querySelector('.modal__form').setAttribute('action', value);
    }

    setMessage(value) {
        this.message = value;
    }

    open() {
        if (!this.opened) {
            document.querySelector('body').classList.add('modal-is-opened');
            this.el.classList.add('overlay');
            this.el.querySelector('.modal-default').classList.remove("modal--hidden");
            this.el.querySelector('.js-modal').classList.add("modal__pop--index");
            if (this.message) {
                this.el.querySelector('.modal__form-text').innerHTML = this.message;
            }
            this.opened = true;
            this.shouldRemoveClasses = false;

            let openedEvent = new CustomEvent(this.id + '-opened', { detail: event });
            window.dispatchEvent(openedEvent);
        }
    }

    close() {
        document.querySelector('body').classList.remove('modal-is-opened');
        this.el.classList.remove('overlay');
        this.el.querySelector('.modal-default').classList.add("modal--hidden");
        this.el.querySelector('.js-modal').classList.remove("modal__pop--index");
        this.opened = false;
        this.message = '';

        let closedEvent = new CustomEvent(this.id + '-closed');
        window.dispatchEvent(closedEvent);
    }

    openListener(event) {
        event.preventDefault();

        this.setUrl(event.currentTarget.getAttribute("data-url"));
        this.setMessage(event.currentTarget.getAttribute("data-message"));

        this.open();
    }

    closeListener(event) {
        this.close();
    }
     
    escapeCloseListener = (event) => {
        event = event || window.event;
        var isEscape = false;
        if ("key" in event) {
            isEscape = (event.key === "Escape" || event.key === "Esc");
        } else {
            isEscape = (event.keyCode === 27);
        }
        if (isEscape) {
            this.close();
        }
    }

    clickAwayListener(event) {
        if (this.opened && this.shouldRemoveClasses) {
            this.close();
        }

        this.shouldRemoveClasses = true;
    }

    innerClickListener(event) {
        this.shouldRemoveClasses = false;
    }

    submitListener(event) {
        event.preventDefault();

        let startSubmit = new CustomEvent(this.id + '-submit', { detail: event });
        window.dispatchEvent(startSubmit);

        let formData = new FormData(this.form);

        axios.post(this.url, formData)
            .then(response => {
                this.close();

                let successEvent = new CustomEvent(this.id + '-success', { detail: response.data });
                window.dispatchEvent(successEvent);
            }).catch(error => {
                let failEvent = new CustomEvent(this.id + '-error', { detail: error.response.data });
                window.dispatchEvent(failEvent);
            }).finally(() => {
                //
            });
    }
     
    registerListeners() {
        // Register open events
        if(document.querySelector('.js-open-' + this.id)) {
            document.querySelectorAll('.js-open-' + this.id).forEach((el) => {
                el.addEventListener('click', this.openListener);
            })
        }

        if(this.isClosable) {
            // Register close events
            this.el.querySelectorAll('.js-close-modal').forEach((el) => {
                el.addEventListener('click', this.closeListener);
            });

            // Register click away close events
            document.querySelector('html').addEventListener('click', this.clickAwayListener);
            this.el.querySelector('.modal-default').addEventListener('click', this.innerClickListener);

            // Register close on escape events
            document.addEventListener('keydown', this.escapeCloseListener);
        }else{
            this.el.querySelectorAll('.js-close-modal').forEach((el) => {
                el.style.display = "none";
            });
        }

        if(this.isAsync){
            this.form.addEventListener('submit', this.submitListener);
        }
    };

    removeListeners() {
        if (document.querySelector('.js-open-' + this.id)) {
            document.querySelectorAll('.js-open-' + this.id).forEach((el) => {
                el.removeEventListener('click', this.openListener);
            })
        }

        if (this.isClosable) {
            this.el.querySelectorAll('.js-close-modal').forEach((element) => {
                element.removeEventListener('click', this.closeListener);
            });

            document.querySelector('html').removeEventListener('click', this.clickAwayListener);
            this.el.querySelector('.modal-default').removeEventListener('click', this.innerClickListener);

            document.removeEventListener('keydown', this.escapeCloseListener);
        }

        if (this.isAsync) {
            this.form.removeEventListener('submit', this.submitListener);
        }
    }
}
