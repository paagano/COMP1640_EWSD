<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

// The AppLayout component represents the main application layout for authenticated users in the University of Glasgow's Annual Magazine System. 
// It extends the base Component class provided by Laravel and defines a render method that returns the view for the app layout. 
// This layout is typically used for pages that require user authentication and provides a consistent structure and design across the application, including elements such as navigation bars, footers, and other common UI components. 
// By using this component, developers can ensure that all authenticated pages maintain a cohesive look and feel, while also allowing for easy updates to the layout by modifying a single view
class AppLayout extends Component
{

    // Get the view / contents that represents the component.
    public function render(): View
    {
        return view('layouts.app');
    }
}
