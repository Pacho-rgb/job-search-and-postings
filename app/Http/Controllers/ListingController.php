<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\List_;

class ListingController extends Controller
{
    // This is where the main website logic is put

    // Shows/actions on the index page
    public function index()
    {
        // This is what we call the dependenct injection (Injecting the Request class)
        // request(['tag', 'search']) will return an associative array
        // paginate() is ust like get(), but has more advanced features
        $listings = Listing::latest()->filter(request(['tag', 'search']))->paginate(2);
        return view('listings.index', [
            'listings' => $listings
        ]);
    }

    // Shows/actions on the single listing page
    public function show(Listing $listing)
    {
        // This is called Route-model binding where we pass the actual table(model) instance as a parameter in the url
        // All the validation, that is, checking if the actual id is present within the table, is done for us.
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    // Show a create form
    public function create()   
    {
        return view('listings.create');
    }

    // Store data from create form
    public function store(Request $request)
    {
        
        // If the validator finds an error in the inputs, it redirects you automatically to that form page
        $formFiels = $request->validate([
            'company' => ['required', Rule::unique('listings', 'company')], //Rule connects to the database and checks if the user input matches the database specifications
            'title' => 'required',
            'location' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'tags' => 'required',
            'description' => 'required'
        ]);

        // If we want to create a listing, then we'll have to store the user's id also in the listing table
        $id = auth()->user()->id; 
        $formFiels['user_id'] = $id;

        // Now we are dealing with the filesystem. Checking if an image is uploaded
        if ($request->hasFile('logo')){
            // Store the uploaded file on a filesystem disk. Returns a string path
            $formFiels['logo'] = $request->file('logo')->store('logos', 'public'); //storage_path('app/public')
        }

        // If the input validation is successful, then we need to insert the data into the database
        // We just pass the associative array from the validator 
        Listing::create($formFiels);

        // Creating a session(temporary associative array) which will display a flash message upon redirection
        // Redirecting to homepage
        // After the redirection, the session will be displayed, then unset
        return redirect('/')->with('message', 'Listing created successfully');
    }

    // Show the edit form based on the selected listing
    public function edit(Listing $listing)
    {
        // Only the user who posted the listing should be the one to edit/delete
        if (auth()->user()->id !== $listing->user_id){
            abort(403, 'Unauthorized action');
            return redirect('/');
        }

        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    // Update data posted from the edit form
    public function update(Listing $listing, Request $request)
    {
        // Only the user who posted the listing should be the one to edit/delete
        if (auth()->user()->id !== $listing->user_id){
            abort(403, 'Unauthorized action');
        }
        
       // If the validator finds an error in the inputs, it redirects you automatically to that form page
       $formFiels = $request->validate([
        'company' => ['required'], 
        'title' => 'required',
        'location' => 'required',
        'email' => ['required', 'email'],
        'website' => 'required',
        'tags' => 'required',
        'description' => 'required'
        ]);

        // Now we are dealing with the filesystem. Checking if an image is uploaded
        if ($request->hasFile('logo')){
            // Store the uploaded file on a filesystem disk. Returns a string path
            $formFiels['logo'] = $request->file('logo')->store('logos', 'public'); //storage_path('app/public')
        }

        // If the input validation is successful, then we need to insert the data into the database
        // We just pass the associative array from the validator 
        // We use the $listing instance, that is, the row we ant to update
        $listing->update($formFiels);

        // Creating a session(temporary associative array) which will display a flash message upon redirection
        // Redirecting to homepage
        // After the redirection, the session will be displayed, then unset
        return back()->with('message', 'Listing updated successfully'); 
        }

        // Delete a listing based on the id
        public function destroy(Listing $listing)
        {
            // Only the user who posted the listing should be the one to edit/delete
            if (auth()->user()->id !== $listing->user_id){
            abort(403, 'Unauthorized action');
        }
            $listing->delete();
            return redirect('/')->with('message', 'Listing deleted successfully ');
        }

        // Manage your listigs
        public function manage()
        {
            return view('listings.manage', [
                'listings' => auth()->user()->listing()->get()
            ]);
        }

}
  