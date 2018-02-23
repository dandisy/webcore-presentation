<?php

namespace Webcore\Presentation\Controllers;

use Webcore\Presentation\DataTables\PresentationDataTable;
use App\Http\Requests;
use Webcore\Presentation\Requests\CreatePresentationRequest;
use Webcore\Presentation\Requests\UpdatePresentationRequest;
use Webcore\Presentation\Repositories\PresentationRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Support\Facades\Auth; // add by dandisy
use Illuminate\Support\Facades\Storage; // add by dandisy

class PresentationController extends AppBaseController
{
    /** @var  PresentationRepository */
    private $presentationRepository;

    public function __construct(PresentationRepository $presentationRepo)
    {
        $this->middleware('auth');
        $this->presentationRepository = $presentationRepo;
    }

    /**
     * Display a listing of the Presentation.
     *
     * @param PresentationDataTable $presentationDataTable
     * @return Response
     */
    public function index(PresentationDataTable $presentationDataTable)
    {
        return $presentationDataTable->render('presentation::presentations.index');
    }

    /**
     * Show the form for creating a new Presentation.
     *
     * @return Response
     */
    public function create()
    {
        // add by dandisy
        $presentation = \Webcore\Presentation\Models\Presentation::pluck('page_id')->toArray();
        $page = \Webcore\FrontPage\Models\Page::whereNotIn('id', $presentation)->get();
        $component = \Webcore\Component\Models\Component::all();
        
        $themes = array_map(function ($file) {
            $fileName = explode('.', $file);
            if(count($fileName) > 0) {
                return $fileName[0];
            }
        }, Storage::disk('theme')->allFiles());

        $themes = array_combine($themes, $themes);

        // edit by dandisy
        //return view('presentations.create');
        return view('presentation::presentations.create')
            ->with('page', $page)
            ->with('component', $component)
            ->with('themes', $themes);
    }

    /**
     * Store a newly created Presentation in storage.
     *
     * @param CreatePresentationRequest $request
     *
     * @return Response
     */
    public function store(CreatePresentationRequest $request)
    {
        $input = $request->all();

        $input['created_by'] = Auth::user()->id;

        $presentation = $this->presentationRepository->create($input);

        Flash::success('Presentation saved successfully.');

        return redirect(route('presentations.index'));
    }

    /**
     * Display the specified Presentation.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $presentation = $this->presentationRepository->findWithoutFail($id);

        if (empty($presentation)) {
            Flash::error('Presentation not found');

            return redirect(route('presentations.index'));
        }

        return view('presentation::presentations.show')->with('presentation', $presentation);
    }

    /**
     * Show the form for editing the specified Presentation.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        // add by dandisy
        $page = \Webcore\FrontPage\Models\Page::all();
        $component = \Webcore\Component\Models\Component::all();
        
        $themes = array_map(function ($file) {
            $fileName = explode('.', $file);
            if(count($fileName) > 0) {
                return $fileName[0];
            }
        }, Storage::disk('theme')->allFiles());

        $themes = array_combine($themes, $themes);

        $presentation = $this->presentationRepository->findWithoutFail($id);

        if (empty($presentation)) {
            Flash::error('Presentation not found');

            return redirect(route('presentations.index'));
        }

        // edit by dandisy
        //return view('presentations.edit')->with('presentation', $presentation);
        return view('presentation::presentations.edit')
            ->with('presentation', $presentation)
            ->with('page', $page)
            ->with('component', $component)
            ->with('themes', $themes);
    }

    /**
     * Update the specified Presentation in storage.
     *
     * @param  int              $id
     * @param UpdatePresentationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePresentationRequest $request)
    {
        $input = $request->all();

        $input['updated_by'] = Auth::user()->id;

        $presentation = $this->presentationRepository->findWithoutFail($id);

        if (empty($presentation)) {
            Flash::error('Presentation not found');

            return redirect(route('presentations.index'));
        }

        $presentation = $this->presentationRepository->update($input, $id);

        Flash::success('Presentation updated successfully.');

        return redirect(route('presentations.index'));
    }

    /**
     * Remove the specified Presentation from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $presentation = $this->presentationRepository->findWithoutFail($id);

        if (empty($presentation)) {
            Flash::error('Presentation not found');

            return redirect(route('presentations.index'));
        }

        $this->presentationRepository->delete($id);

        Flash::success('Presentation deleted successfully.');

        return redirect(route('presentations.index'));
    }
}
