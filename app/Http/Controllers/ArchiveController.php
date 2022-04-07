<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Account;
use App\Models\Archive;
use Illuminate\Http\Request;
use App\Models\Archives_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Archive::with('user')->withCount('files')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = null;
        if ($archive = Archive::create($request->all())) {
            foreach (json_decode($request->archive_files, true) as $key => $file) {
                $file['archive_id'] = $archive->id;
                $files[] = Archives_file::create($file);
            }
        }
        return [$archive, $files];
    }
    public function upload(Request $request)
    {
        $files = $_FILES;
        $sizeLimit = 40000000;
        // $sizeLimit = 40000;
        // Check for the size
        foreach ($files['archive']['size'] as $key => $value) {
            if ($value > $sizeLimit) {
                $data = ['name' => $files['archive']['name'], 'size' => $this->formatBytes($value), 'main-size' => $value];
                $file_errors[$key] = true;
            } else {
                $file_errors[$key] = false;
            }
        }
        if (in_array(true, $file_errors)) {
            return response($file_errors, 403);
        }
        // Store Files
        $account = Account::find($request->account_id);
        $dir = 'archive/' . $account->id . '-' . $account->name . '/';
        $stored = [];
        foreach ($files['archive']['tmp_name'] as $key => $value) {
            $file = file_get_contents($value);
            // $realName = pathinfo($files['archive']['name'][$key], PATHINFO_FILENAME);
            $extention =  pathinfo($files['archive']['name'][$key], PATHINFO_EXTENSION);
            $newName = time() . $key . '.' . $extention;
            if (Storage::disk('local')->put($dir . $newName, $file)) {
                $stored[$key] = [
                    'path' => $dir . $newName,
                    'origname' => $files['archive']['name'][$key],
                    'newname' => $newName,
                    'mime' => $files['archive']['type'][$key],
                    'caption' => '',
                ];
            } else {
                $stored[$key] = $files['archive']['error'][$key];
            }
        }
        return $stored;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Archive::with('files')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Archive $archive)
    {
        //
    }
    public function removeFile(Request $request)
    {
        return Storage::delete('archive/' . $request->fileName);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Archive  $archive
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $archive = Archive::findOrFail($id);
            $archive->delete();
            DB::commit();
            return response('Succefully Deleted!', 201);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json($e, 400);
        }
    }
    public function mostresent()
    {
        return Account::first();
    }

    public function downloadFile($id, $check = 'c')
    {
        if ($check == 'get') {
            $file = Archives_file::find($id);
            $storage_path = storage_path() . '/' . 'app' . '/' . $file->path;
            if (file_exists($storage_path)) {
                return Response::download($storage_path, $file->origname);
            }
        } else {
            $file = Archives_file::find($id);
            $storage_path = storage_path() . '/' . 'app' . '/' . $file->path;
            if (file_exists($storage_path)) {
                return response(['Ready For Download!'], 200);
            } else {
                return response(['File Not Found!'], 404);
            }
        }
    }
    public function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
