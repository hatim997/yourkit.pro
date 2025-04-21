<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\EmailTemplateRepository;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    // use HelperTrait;
    /**
     * Display a listing of the resource.
     */
    private $emailRepository;

    public function __construct(EmailTemplateRepository $emailRepo)
    {
        $this->emailRepository = $emailRepo;
    }
    public function index(EmailTemplateDataTable $dataTable)
    {
        return $dataTable->render('email_template.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $email = $this->emailRepository->find($id);

        if (empty($email)) {
            Toastr::error('Template not found');

            return redirect(route('admin.email_template.index'));
        }

        return view('admin.email_template.edit')->with('email', $email);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $input = $request->all();
        // echo '<pre>';
        // print_r($input);die;
        try{
            $this->emailRepository->update($id, $input);
            Toastr::success('Template updated successfully ','Success');
            return redirect()->route('admin.email-templates.index');

        } catch(\Exception $e){
            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
