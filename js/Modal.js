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
        this.url = value;
        $(this.el).find('.modal__form').attr('action', value);
    }

    setMessage(value) {
        this.message = value;
    }

    open() {
        if (!this.opened) {
            $('body').addClass('modal-is-opened');
            $(this.el).addClass('overlay');
            $(this.el).find('.modal-default').removeClass("modal--hidden");
            $(this.el).find('.js-modal').addClass("modal__pop--index");
            if (this.message) {
                $('.modal__form-text').text(this.message);
            }
            this.opened = true;
            this.shouldRemoveClasses = false;
        }
    }

    close() {
        $('body').removeClass('modal-is-opened');
        $(this.el).removeClass('overlay');
        $(this.el).find('.modal-default').addClass("modal--hidden");
        $(this.el).find('.js-modal').removeClass("modal__pop--index");
        this.opened = false;
        this.message = '';

    }

    openListener(event) {
        event.preventDefault();

        this.setUrl(event.currentTarget.getAttribute("data-url"));
        this.setMessage(event.currentTarget.getAttribute("data-message"));

        this.open();

        let openedEvent = new CustomEvent(this.id + '-opened', { detail: event });
        window.dispatchEvent(openedEvent);
    }

    closeListener(event) {
        this.close();
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
        }

        if (this.isAsync) {
            this.form.removeEventListener('submit', this.submitListener);
        }
    }
}
