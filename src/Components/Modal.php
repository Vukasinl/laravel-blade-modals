<?php

namespace Vukasinl\Modals\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    
    public function render()
    {
        return view('modal::form-modal');
    }
}
