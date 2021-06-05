<?php

namespace Vukasinl\Modals\Components;

use Illuminate\View\Component;

class FormModal extends Component
{
    public $modalId;
    public $method;
    public $url;
    public $async;
    public $isClosable;
    public $startOpened;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($modalId, $options = [])
    {

        $this->modalId = $modalId;
        $this->method = $options['method'] ?? 'post';
        $this->url = $options['url'] ?? '';
        $this->async = $options['isAsync'] ?? false;
        $this->isClosable = $options['isClosable'] ?? true;
        $this->startOpened = $options['startOpened'] ?? false;
    }
    
    public function render()
    {
        return view('modals::form-modal');
    }
}
