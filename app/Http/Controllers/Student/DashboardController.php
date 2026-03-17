<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

// The DashboardController class is responsible for handling the display of the student dashboard. 
// It provides a method to return the view for the student dashboard, allowing students to access their personalized dashboard where they can view relevant information and perform actions related to their account and activities within the application. 
// This controller ensures that students have a dedicated space to manage their interactions with the system, enhancing their user experience and providing easy access to the features and information they need.
class DashboardController extends Controller
{
     // Display the Student dashboard.
    public function index(): View
    {
        return view('student.dashboard');
    }
}
