<div id="overlay" data-modal="{{ $modalId }}" data-async="{{ $async }}" data-url="{{ $url }}"
    data-closable="{{ $isClosable }}" data-start-opened="{{ $startOpened }}">
    <div class="modal__outer">
        <!-- begin modal -->
        <div class="modal__pop js-modal">

            <!-- begin modal popup -->
            <div {{ $attributes->merge(['class' => 'modal-default modal--hidden shadow-lg']) }}>

                <!-- begin modal header -->
                <div class="modal__header">

                    {{-- {{ $heading }} --}}

                    <small class="close-modal js-close-modal"><span class="close-modal__close">x</span></small>
                </div>
                <!-- end modal header -->

                {{ $slot }}

            </div>
            <!-- end modal popup -->

        </div>
        <!-- end modal -->
    </div>
</div>
